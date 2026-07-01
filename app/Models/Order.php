<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

#[Fillable([
    'user_id', 'event_id', 'guest_token',
    'subtotal', 'fees', 'discount', 'discount_code', 'discount_name', 'discount_type', 'discount_value',
    'referrer_code', 'total', 'currency',
    'status', 'payment_status', 'failure_reason',
    'expires_at', 'paid_at',
])]
class Order extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            $order->uuid         ??= (string) Str::uuid();
            $order->order_number ??= static::generateOrderNumber();
        });
    }

    protected function casts(): array
    {
        return [
            'subtotal'       => 'decimal:2',
            'fees'           => 'decimal:2',
            'discount'       => 'decimal:2',
            'total'          => 'decimal:2',
            'status'         => OrderStatus::class,
            'payment_status' => PaymentStatus::class,
            'expires_at'     => 'datetime',
            'paid_at'        => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function latestPayment(): HasOne
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    public function isPending(): bool
    {
        return $this->status === OrderStatus::PENDING;
    }

    public function isCompleted(): bool
    {
        return $this->status === OrderStatus::COMPLETED;
    }

    public function isPaid(): bool
    {
        return $this->payment_status === PaymentStatus::PAID;
    }

    public function calculateTotal(): float
    {
        return (float) ($this->subtotal + $this->fees - $this->discount);
    }

    public function markFailed(string $reason, ?Payment $payment = null): void
    {
        $payment?->update([
            'status'         => PaymentStatus::FAILED,
            'failure_reason' => $reason,
        ]);

        $this->update([
            'status'         => OrderStatus::CANCELLED,
            'payment_status' => PaymentStatus::FAILED,
            'failure_reason' => $reason,
        ]);
    }

    public function markPaid(string $paymentReference, ?string $mpesaReceipt = null, ?array $callbackPayload = null, ?Payment $payment = null): void
    {
        $payment?->update(array_filter([
            'status'              => PaymentStatus::PAID,
            'reference'           => $paymentReference,
            'receipt_number'      => $mpesaReceipt,
            'response'            => $callbackPayload,
            'completed_at'        => now(),
            'callback_received_at' => $callbackPayload ? now() : null,
        ], fn ($v) => $v !== null));

        $this->update([
            'payment_status' => PaymentStatus::PAID,
            'status'         => OrderStatus::COMPLETED,
            'paid_at'        => now(),
        ]);

        // sold only increments on confirmed payment, never at reservation time
        $this->loadMissing('items.ticketType');
        foreach ($this->items as $item) {
            $item->ticketType()->increment('sold', $item->quantity);
        }
    }

    public function markExpired(): void
    {
        $this->update([
            'status'         => OrderStatus::EXPIRED,
            'payment_status' => PaymentStatus::FAILED,
        ]);
    }

    public function releaseInventory(): void
    {
        $this->loadMissing('items.ticketType');

        foreach ($this->items as $item) {
            $item->ticketType()->decrement('sold', $item->quantity);
        }
    }

    protected static function generateOrderNumber(): string
    {
        $year = now()->year;
        $last = static::whereYear('created_at', $year)->orderByDesc('id')->first();
        $seq  = $last ? ((int) substr($last->order_number, -6)) + 1 : 1;

        return sprintf('ORD-%d-%06d', $year, $seq);
    }
}
