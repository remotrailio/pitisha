<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

#[Fillable(['payment_provider_id', 'name', 'shortcode', 'passkey', 'is_active', 'activated_at', 'notes'])]
class MpesaShortcode extends Model
{
    protected function casts(): array
    {
        return [
            'passkey'      => 'encrypted',
            'is_active'    => 'boolean',
            'activated_at' => 'datetime',
        ];
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(PaymentProvider::class, 'payment_provider_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function orders(): HasManyThrough
    {
        return $this->hasManyThrough(Order::class, Payment::class, 'mpesa_shortcode_id', 'id', 'id', 'order_id');
    }

    public function activate(): void
    {
        static::where('is_active', true)->update(['is_active' => false]);

        $this->update([
            'is_active'    => true,
            'activated_at' => now(),
        ]);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
