<?php

namespace App\Livewire\Public;

use App\Enums\EventStatus;
use App\Enums\EventVisibility;
use App\Models\Event;
use App\Services\ReferralEngine;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EventDetails extends Component
{
    public Event $event;

    public function mount(string $slug): void
    {
        $this->event = Event::with(['organizer', 'categories', 'ticketTypes' => function ($q) {
            $q->where('is_active', true)->orderBy('sort_order');
        }])
            ->where('slug', $slug)
            ->where('status', EventStatus::PUBLISHED)
            ->where('visibility', EventVisibility::PUBLIC)
            ->firstOrFail();

        // Capture referral code from ?ref= and store in session for checkout
        $ref = request()->query('ref');

        if ($ref) {
            session([
                'referrer_code'    => $ref,
                'referrer_event_id' => $this->event->id,
            ]);
        }
    }

    public function render()
    {
        $referralProgress = null;
        $isEligibleReferrer = false;

        if ($this->event->enable_referrals && Auth::check()) {
            $user = Auth::user();
            $isEligibleReferrer = ReferralEngine::isEligible($user, $this->event);

            if ($isEligibleReferrer) {
                $referralProgress = ReferralEngine::getProgress($this->event, $user);
            }
        }

        return view('livewire.public.event-details', [
            'referralProgress'   => $referralProgress,
            'isEligibleReferrer' => $isEligibleReferrer,
        ])
            ->layout('layouts.app', [
                'title'       => $this->event->title . ' – ' . config('app.name'),
                'description' => $this->event->excerpt ?? '',
                'ogImage'     => $this->event->banner_url,
                'canonical'   => route('events.show', $this->event->slug),
            ]);
    }
}
