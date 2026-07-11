<?php

namespace App\Models;

use App\Enums\EventStatus;
use App\Enums\EventVisibility;
use App\Enums\NavCategory;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\RewardType;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\EventCheckerInvitation;
use App\Models\User;

#[Fillable([
    'organizer_id', 'title', 'slug', 'excerpt', 'description',
    'banner_image', 'venue_name', 'venue_address', 'destination_id', 'country',
    'latitude', 'longitude', 'is_online', 'meeting_url',
    'timezone', 'start_at', 'end_at', 'visibility', 'status', 'published_at',
    'enable_referrals', 'referral_target', 'reward_type', 'reward_ticket_type_id', 'reward_value',
])]
class Event extends Model
{
    use HasFactory;

    protected $appends = ['banner_url'];

    protected static function booted(): void
    {
        static::updating(function (Event $event) {
            if ($event->isDirty('end_at') && $event->status === EventStatus::ENDED) {
                $event->status = EventStatus::DRAFT;
            }
        });

        static::deleting(function (Event $event) {
            if ($event->start_at <= now()) {
                throw new \RuntimeException('Cannot delete an event that has already started.');
            }

            if ($event->orders()->where('payment_status', PaymentStatus::PAID)->exists()) {
                throw new \RuntimeException('Cannot delete an event that has paid orders.');
            }

            // Bust nav cache before pivot rows are removed by cascade.
            if ($event->categories()->whereIn('name', NavCategory::names())->exists()) {
                Cache::forget('nav.top_categories');
            }
        });

        static::saved(function (Event $event) {
            if ($event->categories()->whereIn('name', NavCategory::names())->exists()) {
                Cache::forget('nav.top_categories');
            }
        });

        static::creating(function (Event $event) {
            $event->uuid ??= (string) Str::uuid();

            if (empty($event->slug)) {
                $base = Str::slug($event->title);
                $slug = $base;
                $i = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $base . '-' . $i++;
                }
                $event->slug = $slug;
            }
        });
    }

    protected function casts(): array
    {
        return [
            'is_online'        => 'boolean',
            'enable_referrals' => 'boolean',
            'start_at'         => 'datetime',
            'end_at'           => 'datetime',
            'published_at'     => 'datetime',
            'latitude'         => 'decimal:7',
            'longitude'        => 'decimal:7',
            'reward_value'     => 'decimal:2',
            'reward_type'      => RewardType::class,
            'visibility'       => EventVisibility::class,
            'status'           => EventStatus::class,
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getBannerUrlAttribute(): ?string
    {
        return $this->banner_image
            ? Storage::disk('r2')->url($this->banner_image)
            : null;
    }

    public function promoCodes(): HasMany
    {
        return $this->hasMany(PromoCode::class);
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(EventReferral::class);
    }

    public function referralRewards(): HasMany
    {
        return $this->hasMany(EventReferralReward::class);
    }

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(Organizer::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    public function ticketTypes(): HasMany
    {
        return $this->hasMany(\App\Models\TicketType::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function attendees(): HasMany
    {
        return $this->hasMany(Order::class)->where('status', OrderStatus::COMPLETED);
    }

    public function isDeletable(): bool
    {
        if ($this->start_at <= now()) {
            return false;
        }

        return ! $this->orders()->where('payment_status', PaymentStatus::PAID)->exists();
    }

    public function tickets(): HasManyThrough
    {
        return $this->hasManyThrough(Ticket::class, Order::class);
    }

    public function checkers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_checkers')->withTimestamps();
    }

    public function checkerInvitations(): HasMany
    {
        return $this->hasMany(EventCheckerInvitation::class);
    }
}
