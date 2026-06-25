@php
    $cheapestTicket = $event->ticketTypes->sortBy('price')->first();
    $lowestPrice = $cheapestTicket?->price;
    $currency = $cheapestTicket?->currency ?? 'KES';
    $showFeatured = $featured ?? false;
    $eventUrl = route('events.show', $event->slug);
    $hasPurchased = isset($purchasedEventIds) && $purchasedEventIds->contains($event->id);
@endphp

<a href="{{ $eventUrl }}"
    class="group flex flex-col overflow-hidden bg-white shadow-md shadow-gray-200/50 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-gray-200/70 cursor-pointer">

    {{-- Image — ~60% of card height --}}
    <div class="relative aspect-square overflow-hidden">
        @if ($event->banner_url)
            <img src="{{ $event->banner_url }}" alt="{{ $event->title }}"
                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
        @else
            <div class="flex h-full w-full items-center justify-center bg-linear-to-br from-gray-100 to-gray-200">
                <svg class="h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        @endif

    </div>

    {{-- Card content --}}
    <div class="flex flex-col flex-1 gap-3 p-4">

        {{-- Category + ticket purchased --}}
        @if ($event->categories->isNotEmpty() || $hasPurchased)
            <div class="flex items-center gap-2 flex-wrap">
                @foreach($event->categories as $cat)
                    <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-[10px] font-semibold text-gray-600">
                        {{ $cat->name }}
                    </span>
                @endforeach
                @if ($hasPurchased)
                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 border border-emerald-200 px-2.5 py-1 text-[10px] font-bold text-emerald-700">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                        Ticket Purchased
                    </span>
                @endif
            </div>
        @endif

        {{-- Title + price --}}
        <div class="flex items-start justify-between gap-3">
            <h3 class="flex-1 line-clamp-2 font-bold leading-snug text-gray-900">{{ $event->title }}</h3>
            <div class="shrink-0 text-right">
                @if (is_null($lowestPrice) || $lowestPrice == 0)
                    <p class="text-sm font-bold text-emerald-600">Free</p>
                @else
                    <p class="text-[10px] uppercase tracking-wide text-gray-400 leading-none mb-0.5">From</p>
                    <p class="text-sm font-bold text-gray-900 text-nowrap">{{ strtoupper($currency) }} {{ number_format($lowestPrice) }}</p>
                @endif
            </div>
        </div>

        {{-- Date + location metadata --}}
        <div class="flex flex-col gap-1.5">
            <div class="flex items-center gap-1.5 text-xs text-gray-500">
                <svg class="h-3.5 w-3.5 shrink-0 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path d="M8 2v4M16 2v4" stroke-linecap="round" />
                    <rect width="18" height="18" x="3" y="4" rx="2" />
                    <path d="M3 10h18" />
                </svg>
                <span>{{ $event->start_at->format('D, d M Y · H:i') }}</span>
            </div>

            @if ($event->is_online)
                <div class="flex items-center gap-1.5 text-xs text-gray-500">
                    <svg class="h-3.5 w-3.5 shrink-0 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 10l4.553-2.277A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z" />
                    </svg>
                    <span>Online</span>
                </div>
            @elseif ($event->city)
                <div class="flex items-center gap-1.5 text-xs text-gray-500">
                    <svg class="h-3.5 w-3.5 shrink-0 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0" />
                        <circle cx="12" cy="10" r="3" />
                    </svg>
                    <span class="line-clamp-1">{{ $event->venue_name ? $event->venue_name . ', ' : '' }}{{ $event->city }}</span>
                </div>
            @endif
        </div>

        {{-- Stats panel --}}
        <div class="mt-auto rounded-2xl bg-white px-3 py-2.5 flex items-center divide-x divide-gray-200">

            {{-- Attendees --}}
            <div class="flex flex-1 flex-col items-center gap-0.5 pr-3">
                <span class="text-xs font-semibold text-gray-900">
                    {{ ($event->attendees_count ?? 0) > 0 ? number_format($event->attendees_count) : '—' }}
                </span>
                <span class="text-[10px] uppercase tracking-wide text-gray-400">Going</span>
            </div>

            {{-- Event type --}}
            <div class="flex flex-1 flex-col items-center gap-0.5 px-3">
                <span class="text-xs font-semibold text-gray-900">{{ $event->is_online ? 'Online' : 'Physical' }}</span>
                <span class="text-[10px] uppercase tracking-wide text-gray-400">Type</span>
            </div>

            {{-- Price --}}
            <div class="flex flex-1 flex-col items-center gap-0.5 pl-3">
                @if (is_null($lowestPrice) || $lowestPrice == 0)
                    <span class="text-xs font-semibold text-emerald-600">Free</span>
                @else
                    <span class="text-xs font-semibold text-gray-900 text-nowrap">{{ number_format($lowestPrice) }}</span>
                @endif
                <span class="text-[10px] uppercase tracking-wide text-gray-400">Price</span>
            </div>
        </div>

    </div>
</a>
