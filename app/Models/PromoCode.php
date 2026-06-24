<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'event_id', 'name', 'code', 'type', 'value',
    'starts_at', 'expires_at', 'max_uses', 'used_count',
    'minimum_order_amount', 'is_active',
])]
class PromoCode extends Model
{
    protected static function booted(): void
    {
        static::saving(function (PromoCode $promo) {
            $promo->code = strtoupper($promo->code);
        });
    }

    protected function casts(): array
    {
        return [
            'starts_at'             => 'datetime',
            'expires_at'            => 'datetime',
            'minimum_order_amount'  => 'decimal:2',
            'value'                 => 'decimal:2',
            'is_active'             => 'boolean',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
