<?php

namespace App\Jobs;

use App\Enums\PaymentStatus;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class ReconcilePendingPaymentsJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue;

    public int $tries = 1;

    public function handle(): void
    {
        // Orders where we sent an STK push but never got a definitive result,
        // created in the last 24 hours and not checked in the last 15 minutes.
        $orders = Order::whereIn('payment_status', [
                PaymentStatus::PROCESSING->value,
                PaymentStatus::UNKNOWN->value,
            ])
            ->whereNotNull('mpesa_checkout_request_id')
            ->where('created_at', '>=', now()->subDay())
            ->where(function ($q) {
                $q->whereNull('last_status_query_at')
                  ->orWhere('last_status_query_at', '<', now()->subMinutes(15));
            })
            ->get();

        if ($orders->isEmpty()) {
            Log::info('ReconcilePendingPaymentsJob: no orders require reconciliation');
            return;
        }

        Log::info('ReconcilePendingPaymentsJob: reconciling unresolved orders', [
            'count'  => $orders->count(),
            'orders' => $orders->pluck('order_number'),
        ]);

        foreach ($orders as $order) {
            // Use the next attempt number, but cap at MAX_ATTEMPTS - 1 so the job
            // can still make one more query and potentially mark as UNKNOWN itself
            $attempt = max(1, min($order->status_query_attempts + 1, 4));

            // Slight stagger to avoid hammering the Safaricom API simultaneously
            CheckPaymentStatusJob::dispatch($order->id, $attempt)
                ->delay(now()->addSeconds(random_int(1, 10)));

            Log::info('ReconcilePendingPaymentsJob: dispatched check', [
                'order'   => $order->order_number,
                'attempt' => $attempt,
            ]);
        }
    }
}
