<div>
    @if ($event->banner_url)
        <section class="bg-white min-h-[70vh] flex items-center overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full py-10 lg:py-0">
                <div
                    class="flex flex-col lg:flex-row items-start justify-between gap-10 lg:gap-16 min-h-[70vh] pt-0 lg:pt-20">
                    <div
                        class="w-full lg:w-2/5 flex flex-col gap-6 text-gray-900 order-2 lg:order-1 pb-10 lg:pb-0 pt-10">
                        <h1
                            class="text-3xl sm:text-4xl lg:text-5xl font-bold leading-[1.1] tracking-tight text-gray-900">
                            {{ $event->title }}</h1>

                        @if ($event->excerpt)
                            <p class="text-sm sm:text-base text-gray-500 leading-relaxed">{{ $event->excerpt }}</p>
                        @endif

                        <div class="flex flex-col gap-3">
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="lucide lucide-calendar h-4 w-4 text-teal-600 shrink-0">
                                    <path d="M8 2v4"></path>
                                    <path d="M16 2v4"></path>
                                    <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                                    <path d="M3 10h18"></path>
                                </svg>
                                <span>
                                    @php
                                        $startDay = $event->start_at->format('j');
                                        $startMonth = $event->start_at->format('F');
                                        $startYear = $event->start_at->format('Y');
                                        $endDay = $event->end_at ? $event->end_at->format('j') : null;
                                        $sameMonth =
                                            $event->end_at &&
                                            $event->end_at->format('Ym') === $event->start_at->format('Ym');
                                    @endphp
                                    {{ $startMonth }} {{ $startDay }}@if ($event->end_at && $endDay !== $startDay)
                                        –{{ $sameMonth ? $endDay : $event->end_at->format('F j') }}
                                    @endif, {{ $startYear }}
                                    · {{ $event->start_at->format('g:i A') }}@if ($event->end_at)
                                        – {{ $event->end_at->format('g:i A') }}
                                    @endif
                                </span>
                            </div>

                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-map-pin h-4 w-4 text-teal-600 shrink-0">
                                    <path
                                        d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0">
                                    </path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                <span>
                                    @if ($event->is_online)
                                        Online Event
                                    @else
                                        {{ collect([$event->venue_name, $event->city])->filter()->implode(', ') ?:'Location TBA' }}
                                    @endif
                                </span>
                            </div>

                            @if ($event->category)
                                <a href="{{ route('events.index', ['category' => $event->category->slug]) }}"
                                    class="inline-flex items-center rounded-full bg-teal-50 border border-teal-100 px-3 py-1 text-xs font-semibold text-teal-700 hover:bg-teal-100 transition-colors w-fit">
                                    {{ $event->category->name }}
                                </a>
                            @endif
                        </div>

                        <div class="pt-1">
                            <a href="#tickets"
                                class="w-full inline-flex items-center justify-center gap-3 rounded-full bg-teal-600 h-10 px-5 text-sm font-semibold text-white text-nowrap hover:bg-teal-700 transition-[color,background] duration-200 focus-visible:outline focus-visible:outline-offset-1">
                                Book Tickets
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-arrow-right h-4 w-4">
                                    <path d="M5 12h14"></path>
                                    <path d="m12 5 7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>

                        <div class="pt-1 border-t border-gray-200">
                            @php
                                $shareUrl = urlencode(url()->current());
                                $shareTitle = urlencode('Check out this event: ' . $event->title);
                                $shareEmailSubject = urlencode($event->title);
                                $shareEmailBody = urlencode('Check out this event: ' . url()->current());
                            @endphp
                            <p class="text-xs text-gray-400 uppercase tracking-wider font-medium mb-3">Share</p>
                            <div class="flex items-center gap-2 flex-wrap">
                                <!-- WhatsApp -->
                                <a href="https://wa.me/?text={{ $shareTitle }}%20{{ $shareUrl }}"
                                    target="_blank" rel="noopener"
                                    class="flex items-center justify-center w-8 h-8 rounded-full bg-[#25D366] hover:opacity-80 transition-opacity"
                                    title="Share on WhatsApp">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="white">
                                        <path
                                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z" />
                                    </svg>
                                </a>
                                <!-- Facebook -->
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}"
                                    target="_blank" rel="noopener"
                                    class="flex items-center justify-center w-8 h-8 rounded-full bg-[#1877F2] hover:opacity-80 transition-opacity"
                                    title="Share on Facebook">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="white">
                                        <path
                                            d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                    </svg>
                                </a>
                                <!-- Instagram -->
                                <a href="https://www.instagram.com/" target="_blank" rel="noopener"
                                    class="flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-br from-[#f9ce34] via-[#ee2a7b] to-[#6228d7] hover:opacity-80 transition-opacity"
                                    title="Share on Instagram">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="white">
                                        <path
                                            d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                                    </svg>
                                </a>
                                <!-- TikTok -->
                                <a href="https://www.tiktok.com/" target="_blank" rel="noopener"
                                    class="flex items-center justify-center w-8 h-8 rounded-full bg-black hover:opacity-80 transition-opacity"
                                    title="Share on TikTok">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                        viewBox="0 0 24 24" fill="white">
                                        <path
                                            d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.75a8.16 8.16 0 0 0 4.78 1.52V6.79a4.85 4.85 0 0 1-1.01-.1z" />
                                    </svg>
                                </a>
                                <!-- X / Twitter -->
                                <a href="https://twitter.com/intent/tweet?text={{ $shareTitle }}&url={{ $shareUrl }}"
                                    target="_blank" rel="noopener"
                                    class="flex items-center justify-center w-8 h-8 rounded-full bg-black hover:opacity-80 transition-opacity"
                                    title="Share on X / Twitter">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                        viewBox="0 0 24 24" fill="white">
                                        <path
                                            d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.748l7.73-8.835L1.254 2.25H8.08l4.253 5.622 5.911-5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                                    </svg>
                                </a>
                                <!-- LinkedIn -->
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}"
                                    target="_blank" rel="noopener"
                                    class="flex items-center justify-center w-8 h-8 rounded-full bg-[#0A66C2] hover:opacity-80 transition-opacity"
                                    title="Share on LinkedIn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                        viewBox="0 0 24 24" fill="white">
                                        <path
                                            d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                                    </svg>
                                </a>
                                <!-- Email -->
                                <a href="mailto:?subject={{ $shareEmailSubject }}&body={{ $shareEmailBody }}"
                                    class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-500 hover:opacity-80 transition-opacity"
                                    title="Share via Email">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                        viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <rect width="20" height="16" x="2" y="4" rx="2" />
                                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="w-full lg:w-2/5 aspect-square relative order-1 lg:order-2 flex-shrink-0">
                        <div class="absolute inset-0 overflow-hidden shadow-xl shadow-gray-200">
                            <img src="{{ $event->banner_url }}" alt="{{ $event->title }}"
                                class="w-full h-full object-cover transition-transform duration-700 hover:scale-105">
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="lg:grid lg:grid-cols-3 lg:gap-12">

            {{-- Main content --}}
            <div class="lg:col-span-2">
                {{-- Breadcrumb --}}
                <nav class="mb-6 flex items-center gap-2 text-sm text-gray-400">
                    <a href="{{ route('events.index') }}" class="hover:text-gray-600 transition-colors">Events</a>
                    @if ($event->category)
                        <span>/</span>
                        <a href="{{ route('events.index', ['category' => $event->category->slug]) }}"
                            class="hover:text-gray-600 transition-colors">{{ $event->category->name }}</a>
                    @endif
                    <span>/</span>
                    <span class="text-gray-600 line-clamp-1">{{ $event->title }}</span>
                </nav>

                {{-- Meta badges --}}
                @if ($event->is_online)
                    <div class="mt-6 flex flex-wrap gap-3">
                        <span
                            class="inline-flex items-center rounded-full bg-emerald-50 border border-emerald-100 px-3 py-1 text-xs font-medium text-emerald-700">
                            Online
                        </span>
                    </div>
                @endif

                {{-- Description --}}
                @if ($event->description)
                    <div class="mt-10 prose prose-sm max-w-none text-gray-700">
                        {!! nl2br(e($event->description)) !!}
                    </div>
                @endif

                {{-- Organizer --}}
                @if ($event->organizer)
                    <div class="mt-10 rounded-2xl border border-gray-200 bg-gray-50 p-5 flex items-center gap-4">
                        @if ($event->organizer->logo_url)
                            <img src="{{ $event->organizer->logo_url }}" alt="{{ $event->organizer->display_name }}"
                                class="h-12 w-12 rounded-full object-cover border-2 border-white shadow-sm">
                        @else
                            <div
                                class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-teal-100 text-teal-600 font-bold text-lg border-2 border-white shadow-sm">
                                {{ mb_substr($event->organizer->display_name, 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide">Organised by</p>
                            <a href="{{ route('organizers.show', $event->organizer->slug) }}"
                                class="font-semibold text-gray-900 hover:text-teal-600 transition-colors">
                                {{ $event->organizer->display_name }}
                            </a>
                            @if ($event->organizer->bio)
                                <p class="mt-0.5 text-xs text-gray-500 line-clamp-2">{{ $event->organizer->bio }}</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            {{-- Ticket sidebar --}}
            <div id="tickets" class="mt-10 lg:mt-0">
                <div class="sticky top-6">
                    @livewire('public.ticket-selector', ['event' => $event], key($event->id))
                </div>
            </div>
        </div>
    </div>
</div>
