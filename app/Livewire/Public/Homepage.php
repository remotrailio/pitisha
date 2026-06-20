<?php

namespace App\Livewire\Public;

use App\Enums\EventStatus;
use App\Enums\EventVisibility;
use App\Enums\PaymentStatus;
use App\Models\Category;
use App\Models\Event;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app', ['title' => 'Ticketeke – Discover Events'])]
class Homepage extends Component
{
    public function render()
    {
        $featured = Event::with(['organizer', 'category', 'ticketTypes'])
            ->withCount('attendees')
            ->whereIn('status', [EventStatus::PUBLISHED, EventStatus::LIVE])
            ->where('visibility', EventVisibility::PUBLIC)
            ->whereHas('ticketTypes')
            ->where('end_at', '>=', now())
            ->orderByDesc('published_at')
            ->limit(6)
            ->get();

        $upcoming = Event::with(['organizer', 'category', 'ticketTypes'])
            ->withCount('attendees')
            ->whereIn('status', [EventStatus::PUBLISHED, EventStatus::LIVE])
            ->where('visibility', EventVisibility::PUBLIC)
            ->whereHas('ticketTypes')
            ->where('end_at', '>=', now())
            ->orderBy('start_at')
            ->limit(8)
            ->get();

        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $heroCategories = Category::where('is_active', true)
            ->withCount(['events' => fn ($q) => $q
                ->whereIn('status', [EventStatus::PUBLISHED, EventStatus::LIVE])
                ->where('visibility', EventVisibility::PUBLIC)
                ->whereHas('ticketTypes')
                ->where('end_at', '>=', now())
            ])
            ->having('events_count', '>', 0)
            ->orderByDesc('events_count')
            ->limit(5)
            ->get();

        $purchasedEventIds = collect();
        if (Auth::check()) {
            $allEventIds = $featured->pluck('id')->merge($upcoming->pluck('id'));
            $purchasedEventIds = Order::where('user_id', Auth::id())
                ->where('payment_status', PaymentStatus::PAID)
                ->whereIn('event_id', $allEventIds)
                ->pluck('event_id');
        }

        return view('livewire.public.homepage', compact('featured', 'upcoming', 'categories', 'heroCategories', 'purchasedEventIds'));
    }
}
