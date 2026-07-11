<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

#[Fillable(['name', 'email', 'password', 'role', 'referral_code'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function canAccessPanel(Panel $panel): bool
    {
        return match ($panel->getId()) {
            'admin' => $this->role === UserRole::ADMIN,
            'organizer' => $this->role === UserRole::ORGANIZER,
            default => false,
        };
    }

    public function organizer(): HasOne
    {
        return $this->hasOne(Organizer::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    public function isOrganizer(): bool
    {
        return $this->role === UserRole::ORGANIZER;
    }

    public function isAttendee(): bool
    {
        return $this->role === UserRole::ATTENDEE;
    }

    public function referralsGiven(): HasMany
    {
        return $this->hasMany(EventReferral::class, 'referrer_user_id');
    }

    public function referralRewards(): HasMany
    {
        return $this->hasMany(EventReferralReward::class);
    }

    public function checkerEvents(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_checkers')->withTimestamps();
    }

    public function canCheckInEvent(Event $event): bool
    {
        if ($this->isOrganizer() && $this->organizer?->id === $event->organizer_id) {
            return true;
        }

        return $this->checkerEvents()->where('event_id', $event->id)->exists();
    }

    protected static function booted(): void
    {
        static::creating(function (User $user) {
            $user->uuid ??= (string) Str::uuid();

            if (empty($user->referral_code)) {
                do {
                    $code = strtoupper(Str::random(8));
                } while (static::where('referral_code', $code)->exists());

                $user->referral_code = $code;
            }
        });
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }
}
