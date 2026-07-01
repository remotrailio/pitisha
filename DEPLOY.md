# Production Deployment Runbook — Payment Refactor

**Date:** 2026-07-01

---

## What this deploy does

- Adds `guest_token` and `referrer_code` columns to `orders`
- Fixes events table enum (adds `live`, `ended` values)
- Creates `payment_providers` table
- Creates `mpesa_shortcodes` table (with `payment_provider_id` FK)
- Creates `payments` table (moves all payment detail off `orders`)
- Migrates existing payment data from `orders` → `payments`
- Drops 12 old payment columns from `orders`
- Restarts queue workers (job constructor changed)

---

## Pre-flight checks

- [ ] Confirm you are on the `develop` branch and it is deployed
- [ ] Confirm queue worker is running (Supervisor)
- [ ] Take a database backup before starting

```bash
# Verify queue worker is up
sudo supervisorctl status
```

---

## Step 1 — Run Laravel migrations

Creates the three new tables.

```bash
php artisan migrate
```

**Expected output — three new migrations applied:**

```
  2026_07_01_000000_create_payment_providers_table ......... 45ms DONE
  2026_07_01_000001_create_mpesa_shortcodes_table .......... 48ms DONE
  2026_07_01_000002_create_payments_table .................. 52ms DONE
```

- [ ] Done

---

## Step 2 — Fix events table enum

Adds `live` and `ended` to the status column (needed to prevent truncation errors when events go live).

```sql
ALTER TABLE events
  MODIFY COLUMN status
  ENUM('draft','published','live','cancelled','completed','ended')
  NOT NULL DEFAULT 'draft';
```

- [ ] Done

---

## Step 3 — Preview the payment data migration

Dry-run to see what will happen before making any changes.

```bash
php artisan app:migrate-order-payments --dry-run
```

Check the output:

- Step 1 should show whether `guest_token` / `referrer_code` need adding
- Step 2 should list how many orders have payment data to migrate
- Step 3 should list which columns will be dropped

- [ ] Output looks correct, no unexpected errors

---

## Step 4 — Run the payment data migration

```bash
php artisan app:migrate-order-payments
```

**What it does:**

1. Adds `guest_token` (unique) and `referrer_code` columns to `orders` if missing
2. Creates a row in `payments` for every order that had a `mpesa_checkout_request_id`
3. Drops the 12 old payment columns from `orders`:
   `payment_provider`, `payment_reference`, `payment_method`,
   `mpesa_shortcode_id`, `mpesa_receipt_number`, `mpesa_checkout_request_id`,
   `merchant_request_id`, `mpesa_phone`, `mpesa_response`,
   `status_query_attempts`, `last_status_query_at`, `callback_received_at`

- [ ] Done, no errors

---

## Step 5 — Restart the queue worker

The `CheckPaymentStatusJob` constructor changed from `$orderId` to `$paymentId`.
Any jobs queued before the deploy will fail to deserialize — clear them.

```bash
php artisan queue:restart
php artisan queue:clear
```

- [ ] Done

---

## Step 6 — Verify

### Confirm orders table is clean

```sql
DESCRIBE orders;
```

Should NOT contain: `mpesa_checkout_request_id`, `mpesa_phone`, `payment_provider`, etc.
Should contain: `guest_token`, `referrer_code`, `payment_status`, `failure_reason`.

### Confirm payments table has data

```sql
SELECT COUNT(*) FROM payments;
SELECT status, COUNT(*) FROM payments GROUP BY status;
```

### Smoke test checkout

- [ ] Load an event page and go through checkout
- [ ] Confirm STK push is sent and order status page polls correctly
- [ ] Confirm M-Pesa callback marks the payment paid (check `payments` table)

### Confirm admin panel loads

- [ ] `/admin/payment-providers` — Payments group visible
- [ ] `/admin/mpesa-shortcodes` — Shortcodes list loads
- [ ] `/admin/orders` — Order view shows payment details via `latestPayment` relationship

---

## Rollback (if needed)

There is no automatic rollback. If something goes wrong before Step 4:

- The new tables can be dropped: `DROP TABLE payments, mpesa_shortcodes, payment_providers;`
- Re-deploy the previous code version

If Step 4 partially completed and columns were not yet dropped, data is still intact on `orders`. The migration command is idempotent — re-running it skips already-completed steps.

If Step 4 fully completed (columns dropped), restore from the pre-deploy database backup.

---

## Post-deploy

- [ ] Monitor error logs for the first 10 minutes: `tail -f /home/forge/ticketeke-1jngndjt.on-forge.com/current/storage/logs/laravel.log`
- [ ] Confirm Supervisor restarted the queue worker after `queue:restart`

