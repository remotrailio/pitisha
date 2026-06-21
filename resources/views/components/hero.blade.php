@if ($featured->isEmpty())
    <section class="relative py-24 md:py-32"
        style="background-image: linear-gradient(rgba(0,0,0,0.50), rgba(0,0,0,0.50)), url('https://images.unsplash.com/photo-1523805009345-7448845a9e53?w=1600'); background-size: cover; background-position: center;">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-3xl text-center text-white">
                <h1 class="mb-6 font-heading text-4xl font-bold tracking-tight md:text-6xl">
                    Discover Amazing Events &amp; Experiences in Kenya
                </h1>

                <p class="mb-8 text-xl text-white/90 md:text-2xl">
                    From safaris to music festivals, explore the best events and create unforgettable memories
                </p>

                <div class="relative mx-auto mb-6">
                    <x-search-input placeholder="Search events, experiences, safaris..."
                        inputClass="bg-white shadow-sm py-3 text-base rounded-xl border-gray-200 focus:border-teal-500" />
                </div>

                @if ($heroCategories->isNotEmpty())
                    <div class="flex flex-wrap items-center justify-center gap-2">
                        @foreach ($heroCategories as $cat)
                            <a href="{{ route('events.index', ['selectedCategories[]' => $cat->slug]) }}"
                                class="inline-flex items-center justify-center rounded-full border border-white/30 bg-white/20 h-10 px-5 text-sm font-semibold text-white text-nowrap backdrop-blur-sm transition-[color,background] duration-200 hover:bg-white/30 focus-visible:outline focus-visible:outline-offset-1">
                                {{ $cat->name }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>
@else
    <section class="py-10">
        <div x-data="carousel()" x-show="events.length > 0" x-cloak class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
            @mouseenter="stopTimer()" @mouseleave="startTimer()">

            <!-- Overflow container -->
            <div class="overflow-hidden" x-ref="container" :style="`height: ${activeHeight}px`">
                <!-- Track — transition disabled during silent clone-jump -->
                <div :class="noTransition ? '' : 'transition-transform duration-500 ease-in-out'"
                    class="flex items-center" :style="`transform: translateX(${trackOffset}px); gap: ${gap}px`">

                    <template x-for="(event, i) in displayEvents" :key="`${event.id}-${i}`">
                        <div class="shrink-0 flex flex-col md:flex-row overflow-hidden bg-gray-50 border border-gray-200 cursor-pointer"
                            :class="i === displayActive || events.length === 1 ? 'opacity-100 shadow-2xl shadow-gray-200' :
                                'opacity-50 cursor-pointer'"
                            :style="`width: ${cardWidth}px; height: ${(events.length === 1 || i === displayActive) ? activeHeight : inactiveHeight}px; transition: ${(events.length === 1 || noCardTransition) ? 'none' : `all ${i !== displayActive ? '400ms' : '500ms'} ease-in-out ${i !== displayActive ? '100ms' : '0ms'}`}`"
                            @click="(i === displayActive || events.length === 1) ? window.location.href = '/events/' + event.slug : (i > 0 && i <= events.length && goTo(i - 1))">

                            <!-- Image (top on mobile, left on lg) -->
                            <div class="w-full h-1/2 md:w-1/2 md:h-full shrink-0 relative overflow-hidden">
                                <img :src="event.banner_url ??
                                    'https://images.unsplash.com/photo-1523805009345-7448845a9e53?w=900&q=90'"
                                    :alt="event.title" class="w-full h-full object-cover">
                                <div
                                    class="absolute inset-0 bg-linear-to-r from-transparent to-gray-50/20 pointer-events-none">
                                </div>
                            </div>

                            <!-- Content (bottom on mobile, right on lg) -->
                            <div class="w-full md:w-1/2 flex-1 flex flex-col justify-between p-4 md:p-8 overflow-hidden"
                                :style="`${isLg ? 'height:' + activeHeight + 'px;' : ''} transform: scale(${(events.length === 1 || i === displayActive) ? 1 : inactiveHeight / activeHeight}); transform-origin: top left; transition: ${(events.length === 1 || noCardTransition) ? 'none' : `transform ${i !== displayActive ? '500ms' : '500ms'} ease-in-out ${i !== displayActive ? '100ms' : '0ms'}`}`">

                                <div class="flex flex-col gap-2 md:gap-3 min-h-0 overflow-hidden">
                                    <!-- Badges -->
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span
                                            class="hidden md:inline-flex items-center gap-1 bg-teal-600 text-white text-[10px] font-bold tracking-widest uppercase px-2.5 py-1 rounded-full shadow-sm shadow-teal-600/30">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-2.5 w-2.5"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path
                                                    d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z" />
                                                <path d="M13 5v2" />
                                                <path d="M13 17v2" />
                                                <path d="M13 11v2" />
                                            </svg>
                                            Featured Event
                                        </span>
                                        <span
                                            class="inline-flex w-fit text-[10px] font-semibold px-2.5 py-1 rounded-full bg-gray-200 text-gray-700"
                                            x-text="event.category?.name ?? ''"></span>
                                    </div>

                                    <!-- Title -->
                                    <h2 class="text-lg sm:text-xl md:text-2xl lg:text-3xl font-extrabold text-gray-900 leading-tight line-clamp-2"
                                        x-text="event.title"></h2>

                                    <!-- Excerpt -->
                                    <p class="text-gray-500 text-sm leading-relaxed line-clamp-2 hidden sm:block"
                                        x-text="event.excerpt"></p>

                                    <!-- Details -->
                                    <ul class="flex flex-col gap-1.5 mt-1">
                                        <li class="flex items-center gap-2 text-sm text-gray-600">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 text-gray-400 shrink-0" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M8 2v4" />
                                                <path d="M16 2v4" />
                                                <rect width="18" height="18" x="3" y="4" rx="2" />
                                                <path d="M3 10h18" />
                                            </svg>
                                            <span
                                                x-text="new Date(event.start_at).toLocaleDateString('en-GB', {day:'numeric', month:'short', year:'numeric'})"></span>
                                        </li>
                                        <li class="flex items-center gap-2 text-sm text-gray-600">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 text-gray-400 shrink-0" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path
                                                    d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0" />
                                                <circle cx="12" cy="10" r="3" />
                                            </svg>
                                            <span
                                                x-text="event.is_online ? 'Online' : ([event.venue_name, event.city].filter(Boolean).join(', ') || 'Kenya')"></span>
                                        </li>
                                        <li class="flex items-center gap-2 text-sm text-gray-600">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 text-gray-400 shrink-0" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path
                                                    d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z" />
                                                <path d="M13 5v2" />
                                                <path d="M13 17v2" />
                                                <path d="M13 11v2" />
                                            </svg>
                                            <span class="text-gray-600">Starting from <span
                                                    class="font-bold text-gray-900"
                                                    x-text="event.ticket_types?.length ? 'KES ' + Math.min(...event.ticket_types.map(t => +t.price)).toLocaleString('en-KE') : 'Free'"></span></span>
                                        </li>
                                    </ul>
                                </div>

                                <!-- CTA -->
                                <div class="hidden md:block">
                                    <a :href="'/events/' + event.slug"
                                        class="w-full inline-flex items-center justify-center gap-3 rounded-full bg-teal-600 h-10 px-5 text-sm font-semibold text-white text-nowrap hover:bg-teal-700 transition-[color,background] duration-200 focus-visible:outline focus-visible:outline-offset-1">
                                        Get Tickets
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M5 12h14" />
                                            <path d="m12 5 7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>

                            </div>
                        </div>
                    </template>

                </div>
            </div>

            <!-- Navigation -->
            <template x-if="events.length > 1">
                <div class="flex items-center justify-between mt-8 mx-auto" :style="`width: ${cardWidth}px`">
                    <button @click="goPrev()"
                        class="flex items-center gap-2 text-sm font-medium text-gray-400 hover:text-gray-700 transition-colors group">
                        <span
                            class="flex items-center justify-center h-9 w-9 rounded-full border border-gray-200 group-hover:border-gray-400 group-hover:bg-gray-50 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="m12 19-7-7 7-7" />
                                <path d="M19 12H5" />
                            </svg>
                        </span>
                    </button>

                    <div class="flex items-center gap-2">
                        <template x-for="(event, i) in events" :key="i">
                            <button @click="goTo(i)"
                                :class="i === active ? 'bg-teal-600 w-6 h-2.5' : 'bg-gray-200 hover:bg-gray-300 w-2.5 h-2.5'"
                                class="rounded-full transition-all duration-300" :aria-label="`Go to event ${i + 1}`">
                            </button>
                        </template>
                    </div>

                    <button @click="goNext()"
                        class="flex items-center gap-2 text-sm font-medium text-gray-400 hover:text-gray-700 transition-colors group">
                        <span
                            class="flex items-center justify-center h-9 w-9 rounded-full border border-gray-200 group-hover:border-gray-400 group-hover:bg-gray-50 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M5 12h14" />
                                <path d="m12 5 7 7-7 7" />
                            </svg>
                        </span>
                    </button>
                </div>
            </template>

        </div>
    </section>

    <script>
        function carousel() {
            return {
                events: @js($featured),

                // displayActive is the position in displayEvents (0 = prepended clone, 1..N = real, N+1 = appended clone)
                displayActive: 1,
                noTransition: false,
                noCardTransition: false,
                cardWidth: 0,
                activeHeight: 0,
                inactiveHeight: 0,
                isLg: false,
                gap: 20,
                trackOffset: 0,
                _timer: null,

                // Real active index (0-based) derived from displayActive
                get active() {
                    return (this.displayActive - 1 + this.events.length) % this.events.length;
                },

                // Track including clones: [last, ...all, first]
                get displayEvents() {
                    if (this.events.length <= 1) return this.events;
                    return [
                        this.events[this.events.length - 1],
                        ...this.events,
                        this.events[0],
                    ];
                },

                init() {
                    this.$nextTick(() => {
                        this.updateSizes();
                        window.addEventListener('resize', () => this.updateSizes());
                        this.startTimer();
                    });
                },

                startTimer() {
                    if (this.events.length <= 1) return;
                    clearInterval(this._timer);
                    this._timer = setInterval(() => this.goNext(), 5000);
                },

                stopTimer() {
                    clearInterval(this._timer);
                    this._timer = null;
                },

                updateSizes() {
                    const container = this.$refs.container;
                    if (!container) return;
                    const w = window.innerWidth;
                    this.isLg = w >= 768;
                    const pct = w < 768 ? 1 : w < 1024 ? 0.78 : 0.72;
                    this.gap = w < 640 ? 12 : 20;
                    this.cardWidth = this.events.length === 1 ? container.offsetWidth : container.offsetWidth * pct;
                    this.activeHeight = w < 640 ? 450 : w < 1024 ? 440 : 420;
                    this.inactiveHeight = Math.round(this.activeHeight * 0.75);
                    this.updateOffset();
                },

                updateOffset() {
                    const container = this.$refs.container;
                    if (!container) return;
                    if (this.events.length <= 1) {
                        this.trackOffset = 0;
                        return;
                    }
                    const center = (container.offsetWidth - this.cardWidth) / 2;
                    this.trackOffset = center - this.displayActive * (this.cardWidth + this.gap);
                },

                // Jump to a real index from dots/clicks
                goTo(realIndex) {
                    this.displayActive = realIndex + 1;
                    this.updateOffset();
                    this.startTimer();
                },

                goNext() {
                    if (this.events.length <= 1) return;
                    this.displayActive++;
                    this.updateOffset();
                    // Slid into appended first-card clone → silently jump to real first card
                    if (this.displayActive === this.events.length + 1) {
                        setTimeout(() => {
                            this.noTransition = true;
                            this.noCardTransition = true;
                            this.displayActive = 1;
                            this.updateOffset();
                            requestAnimationFrame(() => requestAnimationFrame(() => {
                                this.noTransition = false;
                                this.noCardTransition = false;
                            }));
                        }, 510);
                    }
                    this.startTimer();
                },

                goPrev() {
                    if (this.events.length <= 1) return;
                    this.displayActive--;
                    this.updateOffset();
                    // Slid into prepended last-card clone → silently jump to real last card
                    if (this.displayActive === 0) {
                        setTimeout(() => {
                            this.noTransition = true;
                            this.noCardTransition = true;
                            this.displayActive = this.events.length;
                            this.updateOffset();
                            requestAnimationFrame(() => requestAnimationFrame(() => {
                                this.noTransition = false;
                                this.noCardTransition = false;
                            }));
                        }, 510);
                    }
                    this.startTimer();
                },
            }
        }
    </script>


@endif
