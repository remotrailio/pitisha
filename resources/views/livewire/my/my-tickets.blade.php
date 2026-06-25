<div>
    {{-- Page header --}}
    <div class="border-b border-gray-100 bg-white py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900">My Tickets</h1>
            <p class="mt-1 text-sm text-gray-500">Your event tickets in one place.</p>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

        {{-- Filter tabs --}}
        <div class="mb-6 inline-flex rounded-full bg-white border border-gray-200 p-1 gap-1">
            @foreach (['all' => 'All', 'upcoming' => 'Upcoming', 'past' => 'Past'] as $value => $label)
                <button wire:click="$set('filter', '{{ $value }}')"
                    class="rounded-full px-4 h-8 text-sm font-medium transition-all duration-200 {{ $filter === $value ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        @if ($orders->isNotEmpty())
            <div class="space-y-4">
                @foreach ($orders as $order)
                    @php $event = $order->event; @endphp
                    <div
                        class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-md shadow-gray-200/40 transition-all duration-200 hover:shadow-lg hover:shadow-gray-200/60">

                        {{-- Event section: image + info --}}
                        <div class="flex flex-col sm:flex-row">

                            {{-- Event image --}}
                            <a href="{{ $event ? route('events.show', $event->slug) : '#' }}"
                                class="group block shrink-0 sm:w-44 overflow-hidden">
                                @if ($event?->banner_url)
                                    <img src="{{ $event->banner_url }}" alt="{{ $event->title }}"
                                        class="h-40 w-full object-cover sm:h-full transition-transform duration-500 group-hover:scale-105">
                                @else
                                    <div
                                        class="flex h-40 w-full items-center justify-center bg-linear-to-br from-gray-100 to-gray-200 sm:h-full">
                                        <svg class="h-10 w-10 text-gray-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                        </svg>
                                    </div>
                                @endif
                            </a>

                            {{-- Event info --}}
                            <div class="flex flex-1 flex-col justify-between gap-4 p-5">
                                <div class="flex flex-col gap-2">
                                    @foreach($event?->categories ?? [] as $cat)
                                        <span
                                            class="inline-flex w-fit items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-gray-600">
                                            {{ $cat->name }}
                                        </span>
                                    @endforeach

                                    <h2 class="text-base font-bold leading-snug text-gray-900">
                                        {{ $event?->title ?? 'Unknown Event' }}
                                    </h2>

                                    <div class="flex flex-col gap-1">
                                        @if ($event?->start_at)
                                            <div class="flex items-center gap-1.5 text-xs text-gray-500">
                                                <svg class="h-3.5 w-3.5 shrink-0 text-gray-400" fill="none"
                                                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path d="M8 2v4M16 2v4" stroke-linecap="round" />
                                                    <rect width="18" height="18" x="3" y="4" rx="2" />
                                                    <path d="M3 10h18" />
                                                </svg>
                                                <span>{{ $event->start_at->format('D, d M Y · H:i') }}</span>
                                            </div>
                                        @endif

                                        @if ($event?->is_online)
                                            <div class="flex items-center gap-1.5 text-xs text-gray-500">
                                                <svg class="h-3.5 w-3.5 shrink-0 text-gray-400" fill="none"
                                                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15 10l4.553-2.277A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z" />
                                                </svg>
                                                <span>Online</span>
                                            </div>
                                        @elseif ($event?->city)
                                            <div class="flex items-center gap-1.5 text-xs text-gray-500">
                                                <svg class="h-3.5 w-3.5 shrink-0 text-gray-400" fill="none"
                                                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path
                                                        d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0" />
                                                    <circle cx="12" cy="10" r="3" />
                                                </svg>
                                                <span>{{ collect([$event->venue_name, $event->city])->filter()->implode(', ') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Actions --}}
                                <div class="flex flex-wrap items-center gap-2">
                                    @if ($event)
                                        <a href="{{ route('events.show', $event->slug) }}"
                                            class="inline-flex items-center justify-center gap-3 rounded-full border border-gray-200 h-9 px-4 text-xs font-semibold text-gray-700 text-nowrap hover:bg-white transition-[color,background] duration-200 focus-visible:outline focus-visible:outline-offset-1">
                                            View Event
                                        </a>
                                    @endif
                                    <a href="{{ route('orders.confirmation', $order->uuid) }}"
                                        class="inline-flex items-center justify-center gap-3 rounded-full border border-gray-200 h-9 px-4 text-xs font-semibold text-gray-700 text-nowrap hover:bg-white transition-[color,background] duration-200 focus-visible:outline focus-visible:outline-offset-1">
                                        Order Details
                                    </a>
                                    @if ($order->tickets->isNotEmpty())
                                        <a href="{{ route('orders.tickets.download', $order->uuid) }}"
                                            class="inline-flex items-center justify-center gap-2 rounded-full bg-brand-600 h-9 px-4 text-xs font-semibold text-white text-nowrap hover:bg-brand-700 transition-[color,background] duration-200 focus-visible:outline focus-visible:outline-offset-1">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                                stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            Download PDF
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

            <div class="mt-8">{{ $orders->links() }}</div>
        @else
            <div class="rounded-2xl border border-dashed border-gray-200 py-24 text-center">
                <svg class="mx-auto mb-4 h-10 w-10 text-gray-300" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                </svg>
                <p class="text-sm font-medium text-gray-500">
                    @if ($filter === 'upcoming')
                        No upcoming tickets.
                    @elseif ($filter === 'past')
                        No past tickets.
                    @else
                        No tickets yet.
                    @endif
                </p>
                <a href="{{ route('events.index') }}"
                    class="mt-4 inline-flex items-center justify-center gap-3 rounded-full bg-brand-600 h-10 px-5 text-sm font-semibold text-white text-nowrap hover:bg-brand-700 transition-[color,background] duration-200 focus-visible:outline focus-visible:outline-offset-1">
                    Browse Events
                </a>
            </div>
        @endif
    </div>
</div>
