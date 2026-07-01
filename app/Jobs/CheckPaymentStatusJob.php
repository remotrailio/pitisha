<?php

namespace App\Jobs;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Services\MpesaService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Throwable;

class CheckPaymentStatusJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue;

    public int $tries = 1; // Retries are managed manually with increasing delays

    // Seconds to wait before the next check: attempt 1→2, 2→3, 3→4, 4→5
    private const BACKOFF = [30, 60, 120, 300];

    private const MAX_ATTEMPTS = 5;

    public function __construct(
        public readonly int $paymentId,
        public readonly int $attempt = 1,
    ) {}

    public function handle(MpesaService $mpesa): void
    {
        $payment = Payment::with('order')->find($this->paymentId);

        if (! $payment) {
            Log::warning('CheckPaymentStatusJob: payment not found', ['payment_id' => $this->paymentId]);
            return;
        }

        $order = $payment->order;

        // Already resolved — stop the chain
        if (in_array($payment->status, [PaymentStatus::PAID, PaymentStatus::FAILED])) {
            Log::info('CheckPaymentStatusJob: payment already resolved, stopping', [
                'order'   => $order->order_number,
                'payment' => $payment->id,
                'status'  => $payment->status->value,
            ]);
            return;
        }

        $payment->update([
            'status_query_attempts' => $this->attempt,
            'last_status_query_at'  => now(),
        ]);

        Log::info('CheckPaymentStatusJob: querying M-Pesa', [
            'order'   => $order->order_number,
            'payment' => $payment->id,
            'attempt' => $this->attempt . '/' . self::MAX_ATTEMPTS,
        ]);

        $result = $mpesa->queryStatus($payment);

        Log::info('CheckPaymentStatusJob: query result', [
            'order'       => $order->order_number,
            'resolved'    => $result['resolved'],
            'paid'        => $result['paid'],
            'result_code' => $result['result_code'],
            'result_desc' => $result['result_desc'],
        ]);

        // ── Success ────────────────────────────────────────────────────────────
        if ($result['paid']) {
            $receipt = $result['receipt'];

            $order->markPaid(
                paymentReference: $receipt ?? 'RECONCILED-' . $order->order_number,
                mpesaReceipt: $receipt,
                payment: $payment,
            );

            Log::info('CheckPaymentStatusJob: payment confirmed via status query', [
                'order'   => $order->order_number,
                'receipt' => $receipt,
                'attempt' => $this->attempt,
            ]);

            GenerateTicketsJob::dispatch($order->id);
            return;
        }

        // ── Definitive failure ─────────────────────────────────────────────────
        if ($result['resolved'] && ! $result['paid']) {
            $reason = $result['result_desc'] ?? 'Payment declined (code ' . $result['result_code'] . ')';

            $order->markFailed($reason, payment: $payment);

            Log::info('CheckPaymentStatusJob: payment definitively failed', [
                'order'       => $order->order_number,
                'result_code' => $result['result_code'],
                'reason'      => $reason,
            ]);

            return;
        }

        // ── Still pending ──────────────────────────────────────────────────────
        if ($this->attempt < self::MAX_ATTEMPTS) {
            $delay = self::BACKOFF[$this->attempt - 1] ?? 300;

            Log::info('CheckPaymentStatusJob: still pending — retrying', [
                'order'      => $order->order_number,
                'attempt'    => $this->attempt,
                'next_delay' => $delay . 's',
            ]);

            self::dispatch($this->paymentId, $this->attempt + 1)
                ->delay(now()->addSeconds($delay));

            return;
        }

        // ── Exhausted ──────────────────────────────────────────────────────────
        $payment->update(['status' => PaymentStatus::UNKNOWN]);
        $order->update([
            'payment_status' => PaymentStatus::UNKNOWN,
            'failure_reason' => 'Reconciliation exhausted after ' . self::MAX_ATTEMPTS . ' attempts — manual review required',
        ]);

        Log::error('CheckPaymentStatusJob: reconciliation exhausted', [
            'order'    => $order->order_number,
            'payment'  => $payment->id,
            'attempts' => $this->attempt,
        ]);
    }

    public function failed(Throwable $e): void
    {
        Log::error('CheckPaymentStatusJob: job exception', [
            'payment_id' => $this->paymentId,
            'attempt'    => $this->attempt,
            'error'      => $e->getMessage(),
        ]);
    }
}
