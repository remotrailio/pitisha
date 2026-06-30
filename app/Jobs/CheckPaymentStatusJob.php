<?php

namespace App\Jobs;

use App\Enums\PaymentStatus;
use App\Models\Order;
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
        public readonly int $orderId,
        public readonly int $attempt = 1,
    ) {}

    public function handle(MpesaService $mpesa): void
    {
        $order = Order::find($this->orderId);

        if (! $order) {
            Log::warning('CheckPaymentStatusJob: order not found', ['order_id' => $this->orderId]);
            return;
        }

        // Already resolved — stop the chain
        if ($order->isPaid() || $order->payment_status === PaymentStatus::FAILED) {
            Log::info('CheckPaymentStatusJob: order already resolved, stopping', [
                'order'  => $order->order_number,
                'status' => $order->payment_status->value,
            ]);
            return;
        }

        if (! $order->mpesa_checkout_request_id) {
            Log::warning('CheckPaymentStatusJob: no checkout_request_id, skipping', [
                'order' => $order->order_number,
            ]);
            return;
        }

        $order->update([
            'status_query_attempts' => $this->attempt,
            'last_status_query_at'  => now(),
        ]);

        Log::info('CheckPaymentStatusJob: querying M-Pesa', [
            'order'   => $order->order_number,
            'attempt' => $this->attempt . '/' . self::MAX_ATTEMPTS,
        ]);

        $result = $mpesa->queryStatus($order);

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
            );

            Log::info('CheckPaymentStatusJob: payment confirmed via status query', [
                'order'   => $order->order_number,
                'receipt' => $receipt,
                'attempt' => $this->attempt,
            ]);

            GenerateTicketsJob::dispatch($this->orderId);
            return;
        }

        // ── Definitive failure ─────────────────────────────────────────────────
        if ($result['resolved'] && ! $result['paid']) {
            $reason = $result['result_desc'] ?? 'Payment declined (code ' . $result['result_code'] . ')';

            $order->markFailed($reason);

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

            self::dispatch($this->orderId, $this->attempt + 1)
                ->delay(now()->addSeconds($delay));

            return;
        }

        // ── Exhausted ──────────────────────────────────────────────────────────
        $order->update([
            'payment_status' => PaymentStatus::UNKNOWN,
            'failure_reason' => 'Reconciliation exhausted after ' . self::MAX_ATTEMPTS . ' attempts — manual review required',
        ]);

        Log::error('CheckPaymentStatusJob: reconciliation exhausted', [
            'order'    => $order->order_number,
            'attempts' => $this->attempt,
        ]);
    }

    public function failed(Throwable $e): void
    {
        Log::error('CheckPaymentStatusJob: job exception', [
            'order_id' => $this->orderId,
            'attempt'  => $this->attempt,
            'error'    => $e->getMessage(),
        ]);
    }
}
