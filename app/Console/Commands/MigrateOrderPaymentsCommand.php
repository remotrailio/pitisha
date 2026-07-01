<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateOrderPaymentsCommand extends Command
{
    protected $signature = 'app:migrate-order-payments
                            {--dry-run : Preview what would happen without making any changes}
                            {--skip-data : Skip data migration — only add/drop columns}';

    protected $description = 'One-time: add new order columns, migrate payment data to payments table, drop old payment columns from orders';

    // Columns on orders that move to payments
    private const COLUMNS_TO_DROP = [
        'payment_provider',
        'payment_reference',
        'payment_method',
        'mpesa_shortcode_id',  // old FK column — dropped if present
        'mpesa_receipt_number',
        'mpesa_checkout_request_id',
        'merchant_request_id',
        'mpesa_phone',
        'mpesa_response',
        'status_query_attempts',
        'last_status_query_at',
        'callback_received_at',
    ];

    // payment_status value → payments.status value
    private const STATUS_MAP = [
        'processing' => 'processing',
        'paid'       => 'paid',
        'failed'     => 'failed',
        'unknown'    => 'unknown',
        'refunded'   => 'refunded',
        'unpaid'     => 'failed', // edge case: had checkout_request_id but status reset
    ];

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $skipData = $this->option('skip-data');

        if ($dryRun) {
            $this->warn('[dry-run] No changes will be made.');
        }

        // ── 0. Prerequisites ───────────────────────────────────────────────
        if (! Schema::hasTable('payments')) {
            $this->error('payments table does not exist. Run `php artisan migrate` first.');
            return self::FAILURE;
        }

        // ── 1. Add missing columns to orders ──────────────────────────────
        $this->addMissingOrderColumns($dryRun);

        // ── 2. Migrate payment data ────────────────────────────────────────
        if (! $skipData) {
            $this->migratePaymentData($dryRun);
        }

        // ── 3. Drop old columns from orders ────────────────────────────────
        $this->dropOldColumns($dryRun);

        $this->newLine();
        $this->info($dryRun ? '[dry-run] Done — no changes were made.' : 'Migration complete.');

        return self::SUCCESS;
    }

    private function addMissingOrderColumns(bool $dryRun): void
    {
        $this->line('');
        $this->line('<comment>Step 1: Add missing columns to orders</comment>');

        $toAdd = [];

        if (! Schema::hasColumn('orders', 'guest_token')) {
            $toAdd[] = 'guest_token VARCHAR(255) NULL UNIQUE';
        }

        if (! Schema::hasColumn('orders', 'referrer_code')) {
            $toAdd[] = 'referrer_code VARCHAR(50) NULL';
        }

        if (empty($toAdd)) {
            $this->line('  guest_token and referrer_code already present — skipping.');
            return;
        }

        foreach ($toAdd as $col) {
            $name = explode(' ', $col)[0];
            $this->line("  + adding: {$name}");

            if (! $dryRun) {
                DB::statement("ALTER TABLE orders ADD COLUMN {$col}");
            }
        }
    }

    private function migratePaymentData(bool $dryRun): void
    {
        $this->line('');
        $this->line('<comment>Step 2: Migrate payment data from orders → payments</comment>');

        // Skip if the source columns are already gone
        if (! Schema::hasColumn('orders', 'mpesa_checkout_request_id')) {
            $this->line('  Source columns already removed — skipping data migration.');
            return;
        }

        // Only select columns that actually exist — the DB may be in a partial state
        $allCols = [
            'id', 'total', 'currency', 'payment_status',
            'payment_provider', 'payment_reference', 'payment_method',
            'mpesa_shortcode_id',
            'mpesa_receipt_number', 'mpesa_checkout_request_id',
            'merchant_request_id', 'mpesa_phone', 'mpesa_response',
            'status_query_attempts', 'last_status_query_at', 'callback_received_at',
            'paid_at', 'created_at',
        ];
        $selectCols = array_values(array_filter($allCols, fn ($c) => Schema::hasColumn('orders', $c)));

        // Orders with payment activity not yet in payments table
        $orders = DB::table('orders')
            ->whereNotNull('mpesa_checkout_request_id')
            ->whereNotIn('id', DB::table('payments')->select('order_id'))
            ->select($selectCols)
            ->get();

        $this->line("  Found {$orders->count()} order(s) with payment data to migrate.");

        if ($orders->isEmpty()) {
            return;
        }

        if ($dryRun) {
            $orders->each(fn ($o) => $this->line("  [dry-run] would migrate order #{$o->id} ({$o->payment_status})"));
            return;
        }

        $bar = $this->output->createProgressBar($orders->count());
        $bar->start();

        $now = now()->toDateTimeString();

        foreach ($orders as $order) {
            $order = (array) $order;
            $status = self::STATUS_MAP[$order['payment_status']] ?? 'failed';

            DB::table('payments')->insert([
                'order_id' => $order['id'],
                'status'   => $status,
                'provider'              => $order['payment_provider'] ?? null,
                'amount'                => $order['total'],
                'currency'              => $order['currency'],
                'method'                => $order['payment_method'] ?? null,
                'reference'             => $order['payment_reference'] ?? null,
                'phone'                 => $order['mpesa_phone'] ?? null,
                'checkout_request_id'   => $order['mpesa_checkout_request_id'] ?? null,
                'merchant_request_id'   => $order['merchant_request_id'] ?? null,
                'receipt_number'        => $order['mpesa_receipt_number'] ?? null,
                'response'              => $order['mpesa_response'] ?? null,
                'status_query_attempts' => $order['status_query_attempts'] ?? 0,
                'last_status_query_at'  => $order['last_status_query_at'] ?? null,
                'callback_received_at'  => $order['callback_received_at'] ?? null,
                'initiated_at'          => $order['created_at'] ?? null,
                'completed_at'          => $order['paid_at'] ?? null,
                'created_at'            => $now,
                'updated_at'            => $now,
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->line("  Migrated {$orders->count()} payment record(s).");
    }

    private function dropOldColumns(bool $dryRun): void
    {
        $this->line('');
        $this->line('<comment>Step 3: Drop old payment columns from orders</comment>');

        $existing = array_filter(
            self::COLUMNS_TO_DROP,
            fn ($col) => Schema::hasColumn('orders', $col)
        );

        if (empty($existing)) {
            $this->line('  Columns already removed — nothing to drop.');
            return;
        }

        foreach ($existing as $col) {
            $this->line("  - dropping: {$col}");
        }

        if ($dryRun) {
            return;
        }

        // Drop FK on mpesa_shortcode_id if it exists (MySQL name conventions vary)
        if (in_array('mpesa_shortcode_id', $existing)) {
            try {
                DB::statement('ALTER TABLE orders DROP FOREIGN KEY fk_orders_mpesa_shortcode');
            } catch (\Throwable) {
                // FK may not exist or have a different name — safe to continue
                try {
                    DB::statement('ALTER TABLE orders DROP FOREIGN KEY orders_mpesa_shortcode_id_foreign');
                } catch (\Throwable) {
                    // ignore
                }
            }
        }

        // Drop all columns in one statement for atomicity
        $drops = implode(', ', array_map(fn ($c) => "DROP COLUMN `{$c}`", $existing));
        DB::statement("ALTER TABLE orders {$drops}");

        $this->line('  Done.');
    }
}
