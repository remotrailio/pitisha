<?php

namespace App\Jobs;

use App\Enums\PaymentStatus;
use App\Models\Payment;
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
        // Payments where we sent an STK push but never got a definitive result,
        // created in the last 24 hours and not checked in the last 15 minutes.
        $payments = Payment::whereIn('status', [
                PaymentStatus::PROCESSING->value,
                PaymentStatus::UNKNOWN->value,
            ])
            ->whereNotNull('checkout_request_id')
            ->where('created_at', '>=', now()->subDay())
            ->where(function ($q) {
                $q->whereNull('last_status_query_at')
                  ->orWhere('last_status_query_at', '<', now()->subMinutes(15));
            })
            ->get();

        if ($payments->isEmpty()) {
            Log::info('ReconcilePendingPaymentsJob: no payments require reconciliation');
            return;
        }

        Log::info('ReconcilePendingPaymentsJob: reconciling unresolved payments', [
            'count' => $payments->count(),
        ]);

        foreach ($payments as $payment) {
            $attempt = max(1, min($payment->status_query_attempts + 1, 4));

            // Slight stagger to avoid hammering the Safaricom API simultaneously
            CheckPaymentStatusJob::dispatch($payment->id, $attempt)
                ->delay(now()->addSeconds(random_int(1, 10)));

            Log::info('ReconcilePendingPaymentsJob: dispatched check', [
                'payment' => $payment->id,
                'attempt' => $attempt,
            ]);
        }
    }
}
