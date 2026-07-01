<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'order_id', 'mpesa_shortcode_id',
    'status', 'provider', 'amount', 'currency', 'method',
    'reference', 'phone',
    'checkout_request_id', 'merchant_request_id', 'receipt_number',
    'response', 'failure_reason',
    'status_query_attempts', 'last_status_query_at', 'callback_received_at',
    'initiated_at', 'completed_at',
])]
class Payment extends Model
{
    protected function casts(): array
    {
        return [
            'status'               => PaymentStatus::class,
            'amount'               => 'decimal:2',
            'response'             => 'array',
            'last_status_query_at' => 'datetime',
            'callback_received_at' => 'datetime',
            'initiated_at'         => 'datetime',
            'completed_at'         => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function shortcode(): BelongsTo
    {
        return $this->belongsTo(MpesaShortcode::class, 'mpesa_shortcode_id');
    }
}
