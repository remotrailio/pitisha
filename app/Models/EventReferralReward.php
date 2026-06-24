<?php

namespace App\Models;

use App\Enums\RewardStatus;
use App\Enums\RewardType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['event_id', 'user_id', 'reward_type', 'reward_ticket_type_id', 'reward_value', 'status', 'claimed_at'])]
class EventReferralReward extends Model
{
    protected function casts(): array
    {
        return [
            'status'      => RewardStatus::class,
            'reward_type' => RewardType::class,
            'claimed_at'  => 'datetime',
            'reward_value' => 'decimal:2',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ticketType(): BelongsTo
    {
        return $this->belongsTo(TicketType::class, 'reward_ticket_type_id');
    }

    public function isEarned(): bool
    {
        return in_array($this->status, [RewardStatus::EARNED, RewardStatus::CLAIMED]);
    }
}
