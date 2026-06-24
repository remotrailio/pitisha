<?php

namespace App\Services;

use App\Models\Event;
use App\Models\PromoCode;

class PromoCodeEngine
{
    public static function apply(Event $event, string $code, float $subtotal): array
    {
        $code = strtoupper(trim($code));

        $promo = PromoCode::where('event_id', $event->id)
            ->where('code', $code)
            ->first();

        if (! $promo) {
            return self::fail('Promo code does not exist.');
        }

        if (! $promo->is_active) {
            return self::fail('This promo code is unavailable.');
        }

        if ($promo->starts_at && now()->lt($promo->starts_at)) {
            return self::fail('This promo code is not active yet.');
        }

        if ($promo->expires_at && now()->gt($promo->expires_at)) {
            return self::fail('This promo code has expired.');
        }

        if ($promo->used_count >= $promo->max_uses) {
            return self::fail('This promo code has reached its usage limit.');
        }

        if ($promo->minimum_order_amount && $subtotal < (float) $promo->minimum_order_amount) {
            return self::fail('Order does not meet the minimum amount required for this promo code.');
        }

        $discount = $promo->type === 'percentage'
            ? round($subtotal * ((float) $promo->value / 100), 2)
            : (float) $promo->value;

        $discount    = min($discount, $subtotal);
        $final_total = round($subtotal - $discount, 2);

        return [
            'success'        => true,
            'message'        => null,
            'subtotal'       => $subtotal,
            'discount_amount' => $discount,
            'final_total'    => $final_total,
            'promo_code'     => $promo->code,
            'promo_name'     => $promo->name,
            'promo_type'     => $promo->type,
            'promo_value'    => (float) $promo->value,
            'promo_id'       => $promo->id,
        ];
    }

    public static function consume(PromoCode $promo): void
    {
        $promo->increment('used_count');
    }

    private static function fail(string $message): array
    {
        return [
            'success'         => false,
            'message'         => $message,
            'subtotal'        => 0,
            'discount_amount' => 0,
            'final_total'     => 0,
            'promo_code'      => null,
            'promo_name'      => null,
            'promo_type'      => null,
            'promo_value'     => 0,
            'promo_id'        => null,
        ];
    }
}
