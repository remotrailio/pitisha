<?php

namespace App\Jobs;

use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Services\MpesaService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class InitiateStkPushJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public int $tries = 1;

    public function __construct(
        public readonly int $orderId,
        public readonly string $phone,
    ) {}

    public function handle(MpesaService $mpesa): void
    {
        $order = Order::find($this->orderId);

        if (! $order) {
            Log::warning('InitiateStkPushJob: order not found', ['order_id' => $this->orderId]);
            return;
        }

        // Defensive: skip if already resolved (e.g. duplicate dispatch)
        if ($order->isPaid() || $order->payment_status === PaymentStatus::FAILED) {
            return;
        }

        // Store the phone immediately so SendTicketsSmsJob can use it
        // even if the STK push is resolved later via reconciliation
        $order->update(['mpesa_phone' => $this->phone]);

        Log::info('InitiateStkPushJob: initiating STK push', [
            'order' => $order->order_number,
            'phone' => $this->phone,
        ]);

        $response = $mpesa->initiateStkPush($order, $this->phone);

        if (! isset($response['CheckoutRequestID'])) {
            $reason = $response['errorMessage'] ?? $response['ResultDesc'] ?? 'M-Pesa did not return a CheckoutRequestID';

            $order->markFailed($reason);

            Log::error('InitiateStkPushJob: STK push rejected', [
                'order'    => $order->order_number,
                'response' => $response,
            ]);

            return;
        }

        Log::info('InitiateStkPushJob: STK push accepted — scheduling first status check in 30 s', [
            'order'            => $order->order_number,
            'checkout_request' => $response['CheckoutRequestID'],
        ]);

        // First status check after 30 s — gives the user time to enter their PIN
        CheckPaymentStatusJob::dispatch($this->orderId, attempt: 1)
            ->delay(now()->addSeconds(30));
    }

    public function failed(Throwable $e): void
    {
        Log::error('InitiateStkPushJob: job exception', [
            'order_id' => $this->orderId,
            'error'    => $e->getMessage(),
        ]);

        $order = Order::find($this->orderId);
        $order?->markFailed('STK push job failed: ' . $e->getMessage());
    }
}
