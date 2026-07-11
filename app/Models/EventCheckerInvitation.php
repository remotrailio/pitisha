<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[Fillable(['event_id', 'email', 'token', 'accepted_at'])]
class EventCheckerInvitation extends Model
{
    protected static function booted(): void
    {
        static::creating(function (EventCheckerInvitation $invitation) {
            $invitation->token ??= Str::random(48);
        });
    }

    protected function casts(): array
    {
        return [
            'accepted_at' => 'datetime',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function isPending(): bool
    {
        return $this->accepted_at === null && $this->created_at->addDays(7)->isFuture();
    }

    public function isExpired(): bool
    {
        return $this->accepted_at === null && $this->created_at->addDays(7)->isPast();
    }
}
