# Memberships Feature

> Status: Planned — not yet implemented.

## Concept

Events are one-time. Memberships are ongoing. A **business** (gym, pool, co-working space, studio) creates membership plans. A **user** subscribes to a plan, gets a permanent **member card** (QR code), and scans it on every visit.

---

## Models

| Model | Description |
|---|---|
| `MembershipPlan` | Belongs to an Organizer. Has name, price, billing period, daily check-in limit, max members. |
| `Subscription` | Belongs to User + Plan. Has status, starts_at, expires_at, member_code (QR). |
| `SubscriptionPayment` | Each payment against a subscription — first payment and renewals. |
| `MembershipCheckIn` | Every scan at the door: subscription_id, checked_in_by, checked_in_at. |

### Subscription statuses
`pending` → `active` → `expired` / `frozen` / `cancelled`

### Billing periods
`monthly` / `quarterly` / `biannual` / `annual` / `one_time`

---

## Key Differences from Ticketing

- Check-in is **daily, not once** — need a daily limit and check-in history per subscription.
- **Recurring billing** — M-Pesa has no native subscriptions. Send STK push reminder 3 days before expiry; member pays to renew.
- **Freeze/pause** — member travelling, expiry pauses and resumes when they unfreeze. Shift `expires_at` forward by frozen duration.
- **Member card** is permanent (not consumed on scan), but shows live status: Active / Expired / Frozen.

---

## What Can Be Reused from Ticketing

- `MpesaService` — same STK push, just creates a `SubscriptionPayment` instead of `Payment`.
- Same M-Pesa callback handler — extend to check `subscription_payments` if no match in `payments`.
- Same check-in UI pattern — scan QR → view card → checker presses button.
- Same organizer panel — add a Memberships section alongside Events.

---

## What Is Genuinely New

- **Renewal reminder job** — runs daily, finds subscriptions expiring in 3 days, sends SMS + email.
- **Freeze/unfreeze logic** — shifts `expires_at` forward by the frozen duration on unfreeze.
- **Check-in frequency enforcement** — has this member already checked in today?
- **Member analytics** — visit history, streak, last visit date.
- **Organizer view** — who's active, who's expiring soon, monthly revenue.

---

## Business Verticals

Gym, swimming pool, co-working space, yoga/pilates studio, boxing club, library, beach club, rooftop bar with monthly membership, community market (vendor stall subscriptions).

---

## Open Questions

1. **Same organizer account for events + memberships?** — A gym might also host fitness competitions. Recommended: yes, same `Organizer` model, memberships are just another product type.
2. **Free plans?** — Skip STK push, activate immediately.
3. **Checker permissions** — Organizer-level (all staff can check in any member) vs plan-level.
4. **Grace period after expiry?** — How many days before access is fully blocked.
5. **Refunds on cancellation** — Manual M-Pesa reversal or credit to next cycle?
