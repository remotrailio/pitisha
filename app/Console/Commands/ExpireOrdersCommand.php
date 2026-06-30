<?php

namespace App\Console\Commands;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ExpireOrdersCommand extends Command
{
    protected $signature = 'app:expire-orders
                            {--dry-run : Preview how many orders would be expired without modifying anything}';

    protected $description = 'Mark pending orders past their expiry time as expired (bulk update)';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');

        $lock = Cache::lock('app:expire-orders', 60);

        if (! $lock->get()) {
            $this->warn('Another instance is already running. Skipping.');
            return self::SUCCESS;
        }

        try {
            // Safety: never touch paid or refunded orders regardless of anything else
            $neverExpire = [PaymentStatus::PAID->value, PaymentStatus::REFUNDED->value];

            // ── 1. Expire unpaid orders past their expiry time ─────────────────
            // Only touch orders with no checkout request ID — if one is set, an STK
            // push was initiated and we cannot be certain about the payment outcome.
            $unpaidQuery = Order::where('status', OrderStatus::PENDING)
                ->where('expires_at', '<', now())
                ->where('payment_status', PaymentStatus::UNPAID->value)
                ->whereNull('mpesa_checkout_request_id')
                ->whereNotIn('payment_status', $neverExpire);

            // ── 2. Force-expire stale PROCESSING/UNKNOWN orders (>24 h old) ───
            // Never resolved by reconciliation — safe to abandon.
            $staleQuery = Order::where('status', OrderStatus::PENDING)
                ->whereIn('payment_status', [
                    PaymentStatus::PROCESSING->value,
                    PaymentStatus::UNKNOWN->value,
                ])
                ->whereNotIn('payment_status', $neverExpire)
                ->where('created_at', '<', now()->subDay());

            // ── 3. Fix orphaned FAILED orders still marked PENDING ─────────────
            // A pending order cannot have a failed payment — bring them in line.
            $orphanedQuery = Order::where('status', OrderStatus::PENDING)
                ->where('payment_status', PaymentStatus::FAILED->value)
                ->whereNotIn('payment_status', $neverExpire);

            if ($dryRun) {
                $this->info("[dry-run] {$unpaidQuery->count()} unpaid order(s) would be expired.");
                $this->info("[dry-run] {$staleQuery->count()} stale processing/unknown order(s) would be force-expired.");
                $this->info("[dry-run] {$orphanedQuery->count()} orphaned failed order(s) would be cancelled.");
                return self::SUCCESS;
            }

            $unpaidCount = $unpaidQuery->update([
                'status'         => OrderStatus::EXPIRED,
                'payment_status' => PaymentStatus::FAILED,
                'failure_reason' => 'Order expired without payment',
            ]);

            $staleCount = $staleQuery->update([
                'status'         => OrderStatus::EXPIRED,
                'payment_status' => PaymentStatus::FAILED,
                'failure_reason' => 'Expired: no payment confirmation received after 24 hours',
            ]);

            $orphanedCount = $orphanedQuery->update([
                'status' => OrderStatus::CANCELLED,
            ]);

            if ($unpaidCount > 0 || $staleCount > 0 || $orphanedCount > 0) {
                Log::info('app:expire-orders completed', [
                    'expired_unpaid'   => $unpaidCount,
                    'expired_stale'    => $staleCount,
                    'cancelled_failed' => $orphanedCount,
                ]);
            }

            $this->info("Expired {$unpaidCount} unpaid, {$staleCount} stale processing/unknown, cancelled {$orphanedCount} orphaned failed order(s).");

        } finally {
            $lock->release();
        }

        return self::SUCCESS;
    }
}
