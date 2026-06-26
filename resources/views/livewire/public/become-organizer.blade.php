<div>
    {{-- Hero --}}
    <section class="relative py-6 overflow-hidden" style="background: #0f766e;">
        {{-- Bottom fade into white --}}
        <div class="absolute bottom-0 inset-x-0 h-24 bg-linear-to-t from-white to-transparent pointer-events-none"></div>

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-center gap-10">

                {{-- Left: copy + CTA --}}
                <div class="lg:w-5/12 shrink-0">
                    <span
                        class="inline-flex items-center gap-2 rounded-full bg-white/20 border border-white/30 px-3 py-1 text-xs font-semibold text-white mb-6">
                        <span class="h-1.5 w-1.5 rounded-full bg-white"></span>
                        Fast no fuss
                    </span>
                    <h1
                        class="text-3xl md:text-4xl lg:text-5xl font-extrabold tracking-tight text-white leading-tight mb-6">
                        <span class="block whitespace-nowrap">Turn Your Events Into</span>
                        <span class="block text-white/80 whitespace-nowrap">Success Stories</span>
                    </h1>
                    <p class="text-lg text-white/80 mb-8 leading-relaxed">
                        Join Kenya's leading event platform and reach thousands of eager attendees. Sell tickets, manage
                        check-ins, and grow your audience — all in one place.
                    </p>
                    <div class="flex flex-wrap items-center gap-4 mb-4">
                        @auth
                            <a href="{{ route('organizer.onboard') }}"
                                class="inline-flex items-center justify-center gap-3 rounded-full bg-accent-600 h-10 px-5 text-sm font-semibold text-white text-nowrap hover:bg-accent-700 transition-[color,background] duration-200 focus-visible:outline focus-visible:outline-offset-1">
                                Get Started Free
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M5 12h14" />
                                    <path d="m12 5 7 7-7 7" />
                                </svg>
                            </a>
                        @else
                            <a href="#signup"
                                class="inline-flex items-center justify-center gap-3 rounded-full bg-accent-600 h-10 px-5 text-sm font-semibold text-white text-nowrap hover:bg-accent-700 transition-[color,background] duration-200 focus-visible:outline focus-visible:outline-offset-1">
                                Get Started Free
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M5 12h14" />
                                    <path d="m12 5 7 7-7 7" />
                                </svg>
                            </a>
                        @endauth
                        <a href="#how-it-works"
                            class="text-sm font-semibold text-white/80 hover:text-white transition-colors">
                            See how it works →
                        </a>
                    </div>
                </div>

                {{-- Right: hero visual --}}
                <div class="lg:w-7/12 w-full">
                    <video autoplay muted loop playsinline class="w-full h-auto rounded-2xl">
                        <source src="{{ asset('hero-vedio.webm') }}" type="video/webm">
                        <source src="{{ asset('hero-vedio.mp4') }}" type="video/mp4">
                    </video>
                </div>

            </div>
        </div>
    </section>

    {{-- Stats --}}
    <section class="py-12 bg-white">
        <div class="mx-auto max-w-7xl px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="rounded-2xl p-8 py-10 text-center" style="background: #e6f7f5;">
                    <div class="text-2xl md:text-3xl font-bold mb-2" style="color: #0f766e;">5,000+</div>
                    <div class="text-xs font-semibold tracking-widest uppercase text-gray-500">Active Organizers</div>
                </div>
                <div class="rounded-2xl p-8 py-10 text-center" style="background: #e6f7f5;">
                    <div class="text-2xl md:text-3xl font-bold mb-2" style="color: #0f766e;">250K+</div>
                    <div class="text-xs font-semibold tracking-widest uppercase text-gray-500">Tickets Sold Monthly
                    </div>
                </div>
                <div class="rounded-2xl p-8 py-10 text-center" style="background: #e6f7f5;">
                    <div class="text-2xl md:text-3xl font-bold mb-2" style="color: #0f766e;">4.8/5</div>
                    <div class="text-xs font-semibold tracking-widest uppercase text-gray-500">Organizer Rating</div>
                </div>
                <div class="rounded-2xl p-8 py-10 text-center" style="background: #e6f7f5;">
                    <div class="text-2xl md:text-3xl font-bold mb-2" style="color: #0f766e;">10%</div>
                    <div class="text-xs font-semibold tracking-widest uppercase text-gray-500">Platform Fee</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Why section --}}
    <section class="py-16 bg-white">
        <div class="mx-auto max-w-7xl px-4">
            <div class="text-center mb-12">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">Why Organizers Choose
                    {{ $__settings->app_name }}</h2>
                <p class="text-base text-gray-500 max-w-2xl mx-auto">Everything you need to create, promote, and manage
                    successful events</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-8 max-w-5xl mx-auto">
                <div
                    class="rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                    <div class="p-5">
                        <div class="text-xs font-semibold tracking-widest uppercase mb-1.5 text-gray-400">Fill More
                            Seats</div>
                        <h3 class="text-base font-semibold text-gray-900 mb-1">Reach Thousands of Attendees</h3>
                        <p class="text-sm text-gray-500">Connect with engaged audiences across Kenya. Your events get
                            discovered by buyers actively looking for what you offer.</p>
                    </div>
                    <div class="relative overflow-hidden rounded-xl mx-3 mb-3"
                        style="aspect-ratio: 16 / 10; background: #ffffff; border: 1px solid #e6f7f5; box-shadow: 0 2px 16px rgba(16,91,85,0.10), 0 1px 4px rgba(0,0,0,0.06);">
                        <div class="absolute"
                            style="width: 240px; height: 240px; border-radius: 50%; background: #1a7a72; opacity: 0.13; filter: blur(65px); top: -70px; right: -50px;">
                        </div>
                        <div class="absolute"
                            style="width: 160px; height: 160px; border-radius: 50%; background: #C8450A; opacity: 0.1; filter: blur(50px); bottom: -40px; left: 20px;">
                        </div>
                        <div class="absolute inset-0 p-5 flex flex-col gap-3.5">
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="text-xs font-semibold tracking-widest uppercase mb-1"
                                        style="color: #6b7280;">Featured Event</div>
                                    <div class="text-gray-900 text-xl font-bold leading-tight">Nairobi Jazz
                                        Festival</div>
                                    <div class="text-sm mt-0.5" style="color: #6b7280;">Sat, Aug 12 · Uhuru
                                        Park Amphitheater</div>
                                </div>
                                <div class="flex flex-col items-end gap-1.5"><span
                                        class="inline-flex items-center text-nowrap gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold"
                                        style="background-color: #C8450A; color: white;">🔥
                                        Trending</span>
                                    <div class="text-xs" style="color: #6b7280;">3 days
                                        left</div>
                                </div>
                            </div>
                            <div class="rounded-xl p-4 " style="background: #f0faf9; border: 1px solid #d1fae5;">
                                <div class="flex items-center justify-between mb-2"><span class="text-xs font-medium"
                                        style="color: #6b7280;">Seat
                                        Capacity</span><span class="text-gray-900 text-sm font-bold">1,240
                                        / 1,500</span></div>
                                <div class="w-full h-2 rounded-full" style="background: #e5e7eb;">
                                    <div class="h-2 rounded-full"
                                        style="width: 82.6%; background: linear-gradient(90deg, #1a7a72, #105B55);">
                                    </div>
                                </div>
                                <div class="flex justify-between mt-2"><span class="text-xs"
                                        style="color: #6b7280;">82.6% sold</span><span class="text-xs font-semibold"
                                        style="color: #E08C12;">260 tickets remaining</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="flex">
                                    <div style="margin-left: 0px; z-index: 7;">
                                        <div
                                            style="width: 28px; height: 28px; border-radius: 50%; background-color: rgb(224, 85, 32); flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; color: white; border: 2px solid #e5e7eb; letter-spacing: -0.5px;">
                                            JK</div>
                                    </div>
                                    <div style="margin-left: -8px; z-index: 6;">
                                        <div
                                            style="width: 28px; height: 28px; border-radius: 50%; background-color: rgb(99, 102, 241); flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; color: white; border: 2px solid #e5e7eb; letter-spacing: -0.5px;">
                                            AM</div>
                                    </div>
                                    <div style="margin-left: -8px; z-index: 5;">
                                        <div
                                            style="width: 28px; height: 28px; border-radius: 50%; background-color: rgb(8, 145, 178); flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; color: white; border: 2px solid #e5e7eb; letter-spacing: -0.5px;">
                                            SW</div>
                                    </div>
                                    <div style="margin-left: -8px; z-index: 4;">
                                        <div
                                            style="width: 28px; height: 28px; border-radius: 50%; background-color: rgb(5, 150, 105); flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; color: white; border: 2px solid #e5e7eb; letter-spacing: -0.5px;">
                                            DM</div>
                                    </div>
                                    <div style="margin-left: -8px; z-index: 3;">
                                        <div
                                            style="width: 28px; height: 28px; border-radius: 50%; background-color: rgb(217, 119, 6); flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; color: white; border: 2px solid #e5e7eb; letter-spacing: -0.5px;">
                                            FO</div>
                                    </div>
                                    <div style="margin-left: -8px; z-index: 2;">
                                        <div
                                            style="width: 28px; height: 28px; border-radius: 50%; background-color: rgb(124, 58, 237); flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; color: white; border: 2px solid #e5e7eb; letter-spacing: -0.5px;">
                                            TN</div>
                                    </div>
                                    <div style="margin-left: -8px; z-index: 1;">
                                        <div
                                            style="width: 28px; height: 28px; border-radius: 50%; background-color: rgb(190, 24, 93); flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; color: white; border: 2px solid #e5e7eb; letter-spacing: -0.5px;">
                                            BW</div>
                                    </div>
                                    <div
                                        style="margin-left: -8px; width: 28px; height: 28px; border-radius: 50%; background: #e6f7f5; border: 2px solid #a7f3d0; display: flex; align-items: center; justify-content: center; font-size: 8px; color: #105B55; font-weight: 700;">
                                        +847</div>
                                </div><span class="text-xs" style="color: #6b7280;">attending this
                                    event</span>
                            </div>
                            <div class="flex flex-col gap-2 mt-auto">
                                <div class="flex items-center gap-2.5 px-3 py-2 rounded-xl"
                                    style="background: #f0faf9; border: 1px solid #d1fae5;">
                                    <span style="font-size: 14px;">🎫</span><span class="flex-1 text-xs"
                                        style="color: #111827;">Sara N. just bought 2
                                        VIP tickets</span><span class="text-xs" style="color: #9ca3af;">just
                                        now</span>
                                </div>
                                <div class="flex items-center gap-2.5 px-3 py-2 rounded-xl"
                                    style="background: #f0faf9; border: 1px solid #d1fae5;">
                                    <span style="font-size: 14px;">📤</span><span class="flex-1 text-xs"
                                        style="color: #111827;">Shared 340 times
                                        today</span><span class="text-xs" style="color: #9ca3af;">live</span>
                                </div>
                                <div class="flex items-center gap-2.5 px-3 py-2 rounded-xl"
                                    style="background: #f0faf9; border: 1px solid #d1fae5;">
                                    <span style="font-size: 14px;">📍</span><span class="flex-1 text-xs"
                                        style="color: #111827;">Trending #1 in Music —
                                        Nairobi</span><span class="text-xs" style="color: #9ca3af;">1h ago</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                    <div class="p-5">
                        <div class="text-xs font-semibold tracking-widest uppercase mb-1.5 text-gray-400">Get Paid
                            Faster</div>
                        <h3 class="text-base font-semibold text-gray-900 mb-1">Secure & Fast Payments</h3>
                        <p class="text-sm text-gray-500">Reliable payouts with M-Pesa, card, and more. Track every
                            transaction and get paid on your schedule.</p>
                    </div>
                    <div class="relative overflow-hidden rounded-xl mx-3 mb-3"
                        style="aspect-ratio: 16 / 10; background: #ffffff; border: 1px solid #e6f7f5; box-shadow: 0 2px 16px rgba(16,91,85,0.10), 0 1px 4px rgba(0,0,0,0.06);">
                        <div class="absolute"
                            style="width: 200px; height: 200px; border-radius: 50%; background: #C8450A; opacity: 0.1; filter: blur(55px); top: -50px; left: -30px;">
                        </div>
                        <div class="absolute"
                            style="width: 180px; height: 180px; border-radius: 50%; background: rgb(34, 197, 94); opacity: 0.07; filter: blur(55px); bottom: -40px; right: 40px;">
                        </div>
                        <div class="absolute inset-0 p-5 flex flex-col gap-3.5">
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="text-xs font-semibold tracking-widest uppercase mb-1"
                                        style="color: #6b7280;">Revenue Today</div>
                                    <div class="text-gray-900 font-bold"
                                        style="font-size: 30px; letter-spacing: -0.5px;">KES 142,500
                                    </div>
                                    <div class="flex items-center gap-2 mt-1.5"><span
                                            class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold"
                                            style="background-color: rgba(34, 197, 94, 0.2); color: #16a34a;">↑
                                            +23%</span><span class="text-xs" style="color: #6b7280;">vs.
                                            yesterday</span></div>
                                </div>
                                <div>
                                    <div class="text-xs mb-1.5" style="color: #6b7280;">This Week</div>
                                    <div class="flex items-end gap-1" style="height: 44px;">
                                        <div
                                            style="flex: 1 1 0%; border-radius: 3px 3px 0px 0px; height: 31.6901%; background-color: #1a7a72; opacity: 0.35;">
                                        </div>
                                        <div
                                            style="flex: 1 1 0%; border-radius: 3px 3px 0px 0px; height: 43.662%; background-color: #1a7a72; opacity: 0.458333;">
                                        </div>
                                        <div
                                            style="flex: 1 1 0%; border-radius: 3px 3px 0px 0px; height: 26.7606%; background-color: #1a7a72; opacity: 0.566667;">
                                        </div>
                                        <div
                                            style="flex: 1 1 0%; border-radius: 3px 3px 0px 0px; height: 57.0423%; background-color: #1a7a72; opacity: 0.675;">
                                        </div>
                                        <div
                                            style="flex: 1 1 0%; border-radius: 3px 3px 0px 0px; height: 66.9014%; background-color: #1a7a72; opacity: 0.783333;">
                                        </div>
                                        <div
                                            style="flex: 1 1 0%; border-radius: 3px 3px 0px 0px; height: 83.0986%; background-color: #1a7a72; opacity: 0.891667;">
                                        </div>
                                        <div
                                            style="flex: 1 1 0%; border-radius: 3px 3px 0px 0px; height: 100%; background-color: #1a7a72; opacity: 1;">
                                        </div>
                                    </div>
                                    <div class="flex justify-between mt-1"><span class="text-center flex-1"
                                            style="font-size: 9px; color: #6b7280;">M</span><span
                                            class="text-center flex-1"
                                            style="font-size: 9px; color: #6b7280;">T</span><span
                                            class="text-center flex-1"
                                            style="font-size: 9px; color: #6b7280;">W</span><span
                                            class="text-center flex-1"
                                            style="font-size: 9px; color: #6b7280;">T</span><span
                                            class="text-center flex-1"
                                            style="font-size: 9px; color: #6b7280;">F</span><span
                                            class="text-center flex-1"
                                            style="font-size: 9px; color: #6b7280;">S</span><span
                                            class="text-center flex-1"
                                            style="font-size: 9px; color: #6b7280;">S</span>
                                    </div>
                                </div>
                            </div>
                            <div class="rounded-xl p-4 flex-1"
                                style="background: #f0faf9; border: 1px solid #d1fae5;">
                                <div class="text-xs font-semibold mb-3" style="color: #6b7280;">Recent Payments</div>
                                <div class="flex flex-col gap-2.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0"
                                            style="background: rgba(34, 197, 94, 0.18);">
                                            <div
                                                style="width: 7px; height: 7px; border-radius: 50%; background: rgb(74, 222, 128);">
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-gray-900 text-xs font-semibold">John K.</div>
                                            <div class="text-xs" style="color: #6b7280;">M-Pesa</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-gray-900 text-xs font-semibold">KES 1,500
                                            </div>
                                            <div class="text-xs" style="color: #9ca3af;">2m ago</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0"
                                            style="background: rgba(34, 197, 94, 0.18);">
                                            <div
                                                style="width: 7px; height: 7px; border-radius: 50%; background: rgb(74, 222, 128);">
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-gray-900 text-xs font-semibold">Amina W.</div>
                                            <div class="text-xs" style="color: #6b7280;">Card</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-gray-900 text-xs font-semibold">KES 3,000
                                            </div>
                                            <div class="text-xs" style="color: #9ca3af;">5m ago</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0"
                                            style="background: rgba(34, 197, 94, 0.18);">
                                            <div
                                                style="width: 7px; height: 7px; border-radius: 50%; background: rgb(74, 222, 128);">
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-gray-900 text-xs font-semibold">David M.</div>
                                            <div class="text-xs" style="color: #6b7280;">M-Pesa</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-gray-900 text-xs font-semibold">KES 1,500
                                            </div>
                                            <div class="text-xs" style="color: #9ca3af;">8m ago</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0"
                                            style="background: rgba(34, 197, 94, 0.18);">
                                            <div
                                                style="width: 7px; height: 7px; border-radius: 50%; background: rgb(74, 222, 128);">
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-gray-900 text-xs font-semibold">Fatima O.
                                            </div>
                                            <div class="text-xs" style="color: #6b7280;">M-Pesa</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-gray-900 text-xs font-semibold">KES 4,500
                                            </div>
                                            <div class="text-xs" style="color: #9ca3af;">12m ago</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-3.5 rounded-xl"
                                style="background: linear-gradient(90deg, rgba(200, 69, 10, 0.22), rgba(200, 69, 10, 0.12)); border: 1px solid rgba(200, 69, 10, 0.38);">
                                <div>
                                    <div class="text-xs" style="color: #6b7280;">Next
                                        Payout · Fri, Jun 27</div>
                                    <div class="text-gray-900 font-bold text-sm mt-0.5">KES 98,400 ready to
                                        transfer</div>
                                </div>
                                <div class="px-3 py-1.5 rounded-lg text-xs font-bold text-white"
                                    style="background: #C8450A;">Transfer →</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                    <div class="p-5">
                        <div class="text-xs font-semibold tracking-widest uppercase mb-1.5 text-gray-400">Understand
                            Your Audience</div>
                        <h3 class="text-base font-semibold text-gray-900 mb-1">Grow With Real Insights</h3>
                        <p class="text-sm text-gray-500">Real-time analytics on who is buying, when, and where — so you
                            can make smarter decisions every event.</p>
                    </div>
                    <div class="relative overflow-hidden rounded-xl mx-3 mb-3"
                        style="aspect-ratio: 16 / 10; background: #ffffff; border: 1px solid #e6f7f5; box-shadow: 0 2px 16px rgba(16,91,85,0.10), 0 1px 4px rgba(0,0,0,0.06);">
                        <div class="absolute"
                            style="width: 220px; height: 220px; border-radius: 50%; background: rgb(99, 102, 241); opacity: 0.08; filter: blur(65px); bottom: -50px; right: -50px;">
                        </div>
                        <div class="absolute inset-0 p-5 flex flex-col gap-3.5">
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="text-xs font-semibold tracking-widest uppercase mb-1"
                                        style="color: #6b7280;">Sales Trend</div>
                                    <div class="text-gray-900 text-xl font-bold">+68% this month</div>
                                    <div class="text-xs mt-0.5" style="color: #6b7280;">Last 12 weeks</div>
                                </div><span
                                    class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold"
                                    style="background-color: #e6f7f5; color: #105B55;">↑
                                    Accelerating</span>
                            </div>
                            <div class="rounded-xl p-3 " style="background: #f0faf9; border: 1px solid #d1fae5;">
                                <svg viewBox="0 0 300 54" width="100%" height="54">
                                    <path
                                        d="M 0,54 L 0.0,46.0 L 27.3,43.0 L 54.5,45.0 L 81.8,38.6 L 109.1,35.6 L 136.4,37.6 L 163.6,28.2 L 190.9,25.3 L 218.2,27.3 L 245.5,18.9 L 272.7,12.5 L 300.0,10.0 L 300,54 Z"
                                        fill="rgba(26,122,114,0.12)"></path>
                                    <path
                                        d="M 0.0,46.0 L 27.3,43.0 L 54.5,45.0 L 81.8,38.6 L 109.1,35.6 L 136.4,37.6 L 163.6,28.2 L 190.9,25.3 L 218.2,27.3 L 245.5,18.9 L 272.7,12.5 L 300.0,10.0"
                                        fill="none" stroke="#1a7a72" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                    <circle cx="300" cy="10" r="4" fill="#1a7a72" stroke="#ffffff"
                                        stroke-width="2"></circle>
                                </svg>
                            </div>
                            <div class="grid grid-cols-3 gap-2.5">
                                <div class="rounded-xl p-3 text-center"
                                    style="background: #f0faf9; border: 1px solid #d1fae5;">
                                    <div class="text-xs mb-1" style="color: #6b7280;">
                                        Top Age Group</div>
                                    <div class="text-gray-900 font-bold text-sm">25–34</div>
                                    <div class="text-xs mt-0.5" style="color: #6b7280;">45% of buyers</div>
                                </div>
                                <div class="rounded-xl p-3 text-center"
                                    style="background: #f0faf9; border: 1px solid #d1fae5;">
                                    <div class="text-xs mb-1" style="color: #6b7280;">
                                        Top City</div>
                                    <div class="text-gray-900 font-bold text-sm">Nairobi</div>
                                    <div class="text-xs mt-0.5" style="color: #6b7280;">71% local</div>
                                </div>
                                <div class="rounded-xl p-3 text-center"
                                    style="background: #f0faf9; border: 1px solid #d1fae5;">
                                    <div class="text-xs mb-1" style="color: #6b7280;">
                                        Peak Time</div>
                                    <div class="text-gray-900 font-bold text-sm">8–10 PM</div>
                                    <div class="text-xs mt-0.5" style="color: #6b7280;">Sat &amp; Sun</div>
                                </div>
                            </div>
                            <div class="rounded-xl p-4 flex-1"
                                style="background: #f0faf9; border: 1px solid #d1fae5;">
                                <div class="text-xs font-semibold mb-3" style="color: #6b7280;">Traffic Sources</div>
                                <div class="flex flex-col gap-2.5">
                                    <div class="flex items-center gap-3">
                                        <div class="text-xs flex-shrink-0" style="color: #374151; width: 76px;">
                                            Instagram</div>
                                        <div class="flex-1 h-1.5 rounded-full" style="background: #e5e7eb;">
                                            <div class="h-1.5 rounded-full"
                                                style="width: 42%; background-color: rgb(232, 121, 161);">
                                            </div>
                                        </div>
                                        <div class="text-xs text-right" style="color: #4b5563; width: 28px;">42%
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="text-xs flex-shrink-0" style="color: #374151; width: 76px;">
                                            Direct</div>
                                        <div class="flex-1 h-1.5 rounded-full" style="background: #e5e7eb;">
                                            <div class="h-1.5 rounded-full"
                                                style="width: 28%; background-color: #9ca3af;">
                                            </div>
                                        </div>
                                        <div class="text-xs text-right" style="color: #4b5563; width: 28px;">28%
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="text-xs flex-shrink-0" style="color: #374151; width: 76px;">
                                            Twitter / X</div>
                                        <div class="flex-1 h-1.5 rounded-full" style="background: #e5e7eb;">
                                            <div class="h-1.5 rounded-full"
                                                style="width: 18%; background-color: rgb(96, 165, 250);">
                                            </div>
                                        </div>
                                        <div class="text-xs text-right" style="color: #4b5563; width: 28px;">18%
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="text-xs flex-shrink-0" style="color: #374151; width: 76px;">
                                            WhatsApp</div>
                                        <div class="flex-1 h-1.5 rounded-full" style="background: #e5e7eb;">
                                            <div class="h-1.5 rounded-full"
                                                style="width: 12%; background-color: #16a34a;">
                                            </div>
                                        </div>
                                        <div class="text-xs text-right" style="color: #4b5563; width: 28px;">12%
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                    <div class="p-5">
                        <div class="text-xs font-semibold tracking-widest uppercase mb-1.5 text-gray-400">Keep Fake
                            Tickets Out</div>
                        <h3 class="text-base font-semibold text-gray-900 mb-1">Built-In Fraud Protection</h3>
                        <p class="text-sm text-gray-500">QR-code check-in and ticket verification stop counterfeits at
                            the door and protect your revenue.</p>
                    </div>
                    <div class="relative overflow-hidden rounded-xl mx-3 mb-3"
                        style="aspect-ratio: 16 / 10; background: #ffffff; border: 1px solid #e6f7f5; box-shadow: 0 2px 16px rgba(16,91,85,0.10), 0 1px 4px rgba(0,0,0,0.06);">
                        <div class="absolute"
                            style="width: 220px; height: 220px; border-radius: 50%; background: rgb(34, 197, 94); opacity: 0.06; filter: blur(70px); top: -60px; right: -60px;">
                        </div>
                        <div class="absolute inset-0 p-5 flex gap-4">
                            {{-- QR code SVG --}}
                            <div class="flex flex-col items-center justify-center gap-3 shrink-0"
                                style="width: 140px;">
                                <div
                                    style="background-color: white; padding: 6px; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,0.10);">
                                    <svg width="100" height="100" viewBox="0 0 21 21" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <!-- top-left finder -->
                                        <rect x="1" y="1" width="7" height="7" rx="1"
                                            fill="#0f172a" />
                                        <rect x="2" y="2" width="5" height="5" rx="0.5"
                                            fill="white" />
                                        <rect x="3" y="3" width="3" height="3" fill="#0f172a" />
                                        <!-- top-right finder -->
                                        <rect x="13" y="1" width="7" height="7" rx="1"
                                            fill="#0f172a" />
                                        <rect x="14" y="2" width="5" height="5" rx="0.5"
                                            fill="white" />
                                        <rect x="15" y="3" width="3" height="3" fill="#0f172a" />
                                        <!-- bottom-left finder -->
                                        <rect x="1" y="13" width="7" height="7" rx="1"
                                            fill="#0f172a" />
                                        <rect x="2" y="14" width="5" height="5" rx="0.5"
                                            fill="white" />
                                        <rect x="3" y="15" width="3" height="3" fill="#0f172a" />
                                        <!-- data dots -->
                                        <rect x="9" y="1" width="2" height="2" fill="#0f172a" />
                                        <rect x="9" y="4" width="1" height="1" fill="#0f172a" />
                                        <rect x="11" y="2" width="1" height="2" fill="#0f172a" />
                                        <rect x="9" y="9" width="2" height="2" fill="#0f172a" />
                                        <rect x="12" y="9" width="1" height="1" fill="#0f172a" />
                                        <rect x="14" y="9" width="2" height="2" fill="#0f172a" />
                                        <rect x="11" y="11" width="2" height="1" fill="#0f172a" />
                                        <rect x="9" y="13" width="1" height="2" fill="#0f172a" />
                                        <rect x="11" y="14" width="2" height="1" fill="#0f172a" />
                                        <rect x="13" y="13" width="1" height="1" fill="#0f172a" />
                                        <rect x="15" y="15" width="2" height="2" fill="#0f172a" />
                                        <rect x="18" y="13" width="2" height="2" fill="#0f172a" />
                                        <rect x="13" y="16" width="2" height="1" fill="#0f172a" />
                                        <rect x="18" y="16" width="2" height="3" fill="#0f172a" />
                                        <rect x="16" y="18" width="1" height="1" fill="#0f172a" />
                                    </svg>
                                </div>
                                <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-full"
                                    style="background: rgba(34, 197, 94, 0.12); border: 1px solid rgba(34, 197, 94, 0.35);">
                                    <div class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse"></div>
                                    <span class="text-xs font-bold" style="color: #16a34a;">VERIFIED ✓</span>
                                </div>
                            </div>
                            {{-- Live check-in stats --}}
                            <div class="flex flex-col gap-2.5 flex-1">
                                <div>
                                    <div class="text-xs font-semibold tracking-widest uppercase mb-1"
                                        style="color: #6b7280;">Live Check-In</div>
                                    <div class="flex items-baseline gap-1.5">
                                        <span class="text-gray-900 font-bold" style="font-size: 26px;">847</span>
                                        <span class="text-sm" style="color: #6b7280;">/ 1,000 scanned</span>
                                    </div>
                                    <div class="w-full h-2 rounded-full mt-1.5" style="background: #e5e7eb;">
                                        <div class="h-2 rounded-full"
                                            style="width: 84.7%; background: linear-gradient(90deg, rgb(22, 163, 74), rgb(74, 222, 128));">
                                        </div>
                                    </div>
                                    <div class="text-xs mt-1" style="color: #6b7280;">84.7% checked in</div>
                                </div>
                                <div class="grid grid-cols-2 gap-1.5">
                                    <div class="rounded-xl p-2.5"
                                        style="background: #f0faf9; border: 1px solid #d1fae5;">
                                        <div class="text-xs" style="color: #6b7280;">Avg Scan</div>
                                        <div class="text-gray-900 font-bold text-sm">0.4s</div>
                                    </div>
                                    <div class="rounded-xl p-2.5"
                                        style="background: #f0faf9; border: 1px solid #d1fae5;">
                                        <div class="text-xs" style="color: #6b7280;">Fake Attempts</div>
                                        <div class="font-bold text-sm" style="color: #C8450A;">0</div>
                                    </div>
                                </div>
                                <div class="rounded-xl p-2.5 flex-1"
                                    style="background: #f0faf9; border: 1px solid #d1fae5;">
                                    <div class="text-xs font-semibold mb-1.5" style="color: #6b7280;">Recent Check-ins
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        @foreach ([['KN', 'rgb(124,58,237)', 'Ngugi K.'], ['AM', 'rgb(8,145,178)', 'Aisha M.'], ['TO', 'rgb(5,150,105)', 'Tom O.']] as $ci)
                                            <div class="flex items-center gap-2">
                                                <div
                                                    style="width:20px;height:20px;border-radius:50%;background:{{ $ci[1] }};display:flex;align-items:center;justify-content:center;font-size:8px;font-weight:700;color:white;flex-shrink:0;">
                                                    {{ $ci[0] }}</div>
                                                <span class="text-xs"
                                                    style="color: #374151;">{{ $ci[2] }}</span>
                                                <span class="ml-auto text-xs" style="color: #16a34a;">✓</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                    <div class="p-5">
                        <div class="text-xs font-semibold tracking-widest uppercase mb-1.5 text-gray-400">We're Here
                            When You Need Us</div>
                        <h3 class="text-base font-semibold text-gray-900 mb-1">24/7 Dedicated Support</h3>
                        <p class="text-sm text-gray-500">Our team is always on standby to help you and your attendees —
                            before, during, and after the event.</p>
                    </div>
                    <div class="relative overflow-hidden rounded-xl mx-3 mb-3"
                        style="aspect-ratio: 16 / 10; background: #ffffff; border: 1px solid #e6f7f5; box-shadow: 0 2px 16px rgba(16,91,85,0.10), 0 1px 4px rgba(0,0,0,0.06);">
                        <div class="absolute"
                            style="width: 220px; height: 220px; border-radius: 50%; background: rgb(167, 139, 250); opacity: 0.08; filter: blur(60px); top: -60px; right: -40px;">
                        </div>
                        <div class="absolute inset-0 p-5 flex gap-5">
                            <div class="flex flex-col gap-3 flex-1">
                                <div class="flex items-center gap-3">
                                    <div class="relative">
                                        <div
                                            style="width: 38px; height: 38px; border-radius: 50%; background-color: rgb(124, 58, 237); flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 700; color: white; border: 2px solid #e5e7eb; letter-spacing: -0.5px;">
                                            KN</div>
                                        <div class="absolute bottom-0 right-0 w-3 h-3 rounded-full bg-green-400"
                                            style="border: 2px solid white;"></div>
                                    </div>
                                    <div>
                                        <div class="text-gray-900 text-sm font-bold">Kendi N.</div>
                                        <div class="flex items-center gap-1.5">
                                            <div class="w-1.5 h-1.5 rounded-full bg-green-400"></div>
                                            <span class="text-xs" style="color: #6b7280;">Online ·
                                                Support Agent</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col gap-2 flex-1 overflow-hidden">
                                    <div class="self-start px-3 py-2 rounded-2xl rounded-tl-md text-xs text-gray-900"
                                        style="background: #e6f7f5; max-width: 80%;">
                                        Hi there! How can I help you today? 👋</div>
                                    <div class="self-end px-3 py-2 rounded-2xl rounded-tr-md text-xs text-white"
                                        style="background: #C8450A; max-width: 80%;">I can't
                                        find my tickets after payment</div>
                                    <div class="self-start px-3 py-2 rounded-2xl rounded-tl-md text-xs text-gray-900"
                                        style="background: #e6f7f5; max-width: 85%;">
                                        No worries! I can see your booking — sending tickets to your
                                        email now ✅</div>
                                    <div class="self-end px-3 py-2 rounded-2xl rounded-tr-md text-xs text-white"
                                        style="background: #C8450A; max-width: 80%;">Thank you
                                        so much! You're amazing 🙌</div>
                                </div>
                                <div class="self-start flex items-center gap-1.5 px-3 py-2 rounded-2xl"
                                    style="background: #f0faf9;">
                                    <div class="w-1.5 h-1.5 rounded-full bg-gray-300 animate-bounce"
                                        style="animation-delay: 0s;"></div>
                                    <div class="w-1.5 h-1.5 rounded-full bg-gray-300 animate-bounce"
                                        style="animation-delay: 0.15s;"></div>
                                    <div class="w-1.5 h-1.5 rounded-full bg-gray-300 animate-bounce"
                                        style="animation-delay: 0.3s;"></div>
                                </div>
                            </div>
                            <div class="flex flex-col gap-3" style="width: 160px;">
                                <div class="rounded-xl p-4 " style="background: #f0faf9; border: 1px solid #d1fae5;">
                                    <div class="text-xs mb-1" style="color: #6b7280;">
                                        Avg Response</div>
                                    <div class="text-gray-900 font-bold text-2xl">4 min</div>
                                    <div class="text-xs mt-1" style="color: #16a34a;">Target
                                        met ✓</div>
                                </div>
                                <div class="rounded-xl p-4 " style="background: #f0faf9; border: 1px solid #d1fae5;">
                                    <div class="text-xs mb-2" style="color: #6b7280;">
                                        Customer CSAT</div>
                                    <div class="flex items-baseline gap-1.5"><span
                                            class="text-gray-900 font-bold text-2xl">4.9</span><span class="text-xs"
                                            style="color: #6b7280;">/5</span></div>
                                    <div class="flex gap-0.5 mt-1"><span
                                            style="color: #E08C12; font-size: 14px;">★</span><span
                                            style="color: #E08C12; font-size: 14px;">★</span><span
                                            style="color: #E08C12; font-size: 14px;">★</span><span
                                            style="color: #E08C12; font-size: 14px;">★</span><span
                                            style="color: #E08C12; font-size: 14px;">★</span>
                                    </div>
                                    <div class="text-xs mt-1" style="color: #9ca3af;">
                                        2,847 reviews</div>
                                </div>
                                <div class="rounded-xl p-3 " style="background: #f0faf9; border: 1px solid #d1fae5;">
                                    <div class="text-xs mb-2" style="color: #6b7280;">
                                        Team Online</div>
                                    <div class="flex">
                                        <div style="margin-left: 0px;">
                                            <div
                                                style="width: 26px; height: 26px; border-radius: 50%; background-color: rgb(124, 58, 237); flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 700; color: white; border: 2px solid #e5e7eb; letter-spacing: -0.5px;">
                                                KN</div>
                                        </div>
                                        <div style="margin-left: -6px;">
                                            <div
                                                style="width: 26px; height: 26px; border-radius: 50%; background-color: rgb(8, 145, 178); flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 700; color: white; border: 2px solid #e5e7eb; letter-spacing: -0.5px;">
                                                AB</div>
                                        </div>
                                        <div style="margin-left: -6px;">
                                            <div
                                                style="width: 26px; height: 26px; border-radius: 50%; background-color: rgb(5, 150, 105); flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 700; color: white; border: 2px solid #e5e7eb; letter-spacing: -0.5px;">
                                                TM</div>
                                        </div>
                                    </div>
                                    <div class="text-xs mt-1.5" style="color: #6b7280;">3 agents ready</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                    <div class="p-5">
                        <div class="text-xs font-semibold tracking-widest uppercase mb-1.5 text-gray-400">Launch in
                            Minutes</div>
                        <h3 class="text-base font-semibold text-gray-900 mb-1">Effortless Event Setup</h3>
                        <p class="text-sm text-gray-500">Create, configure tickets, and publish your event in minutes.
                            No technical knowledge required.</p>
                    </div>
                    <div class="relative overflow-hidden rounded-xl mx-3 mb-3"
                        style="aspect-ratio: 16 / 10; background: #ffffff; border: 1px solid #e6f7f5; box-shadow: 0 2px 16px rgba(16,91,85,0.10), 0 1px 4px rgba(0,0,0,0.06);">
                        <div class="absolute"
                            style="width: 220px; height: 220px; border-radius: 50%; background: #C8450A; opacity: 0.1; filter: blur(60px); bottom: -60px; left: -40px;">
                        </div>
                        <div class="absolute inset-0 p-5 flex gap-5">
                            <div class="flex flex-col gap-4 flex-1">
                                <div class="flex items-center">
                                    <div class="flex items-center">
                                        <div class="flex items-center gap-1.5">
                                            <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold"
                                                style="background: rgb(34, 197, 94); color: white;">✓
                                            </div><span class="text-xs font-medium"
                                                style="color: #16a34a;">Details</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-8 h-px mx-2" style="background: rgb(74, 222, 128);"></div>
                                        <div class="flex items-center gap-1.5">
                                            <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold"
                                                style="background: rgb(34, 197, 94); color: white;">✓
                                            </div><span class="text-xs font-medium"
                                                style="color: #16a34a;">Tickets</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-8 h-px mx-2" style="background: #d1d5db;"></div>
                                        <div class="flex items-center gap-1.5">
                                            <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold"
                                                style="background: #C8450A; color: white;">3
                                            </div><span class="text-xs font-medium"
                                                style="color: #374151;">Publish</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="rounded-xl p-4 flex-1"
                                    style="background: #f0faf9; border: 1px solid #d1fae5;">
                                    <div class="flex flex-col gap-3">
                                        <div>
                                            <div class="text-xs mb-1.5" style="color: #6b7280;">Event Name
                                            </div>
                                            <div class="px-3 py-2 rounded-lg text-gray-900 text-sm font-medium"
                                                style="background: #f0faf9; border: 1px solid #d1fae5;">
                                                Nairobi Jazz Festival 2025</div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2.5">
                                            <div>
                                                <div class="text-xs mb-1.5" style="color: #6b7280;">Date</div>
                                                <div class="px-3 py-2 rounded-lg text-gray-900 text-xs"
                                                    style="background: #f0faf9; border: 1px solid #d1fae5;">
                                                    Aug 12, 2025</div>
                                            </div>
                                            <div>
                                                <div class="text-xs mb-1.5" style="color: #6b7280;">Ticket
                                                    Price</div>
                                                <div class="px-3 py-2 rounded-lg text-gray-900 text-xs"
                                                    style="background: #f0faf9; border: 1px solid #d1fae5;">
                                                    KES 1,500</div>
                                            </div>
                                        </div>
                                        <div class="text-xs" style="color: #6b7280;">✓
                                            Venue confirmed &nbsp;·&nbsp; ✓ Tickets configured
                                            &nbsp;·&nbsp; ✓ Ready</div>
                                    </div>
                                </div><button
                                    class="w-full py-3 rounded-xl font-bold text-white text-sm flex items-center justify-center gap-2"
                                    style="background: #C8450A; box-shadow: rgba(200, 69, 10, 0.5) 0px 4px 22px;"><span>🚀</span>
                                    Publish Event</button>
                            </div>
                            <div class="flex flex-col gap-3" style="width: 168px;">
                                <div class="flex items-center gap-2.5 p-3 rounded-xl"
                                    style="background: rgba(34, 197, 94, 0.13); border: 1px solid rgba(34, 197, 94, 0.3);">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0"
                                        style="background: rgba(34, 197, 94, 0.2);"><span
                                            style="font-size: 16px;">🎉</span></div>
                                    <div>
                                        <div class="text-xs font-bold" style="color: #16a34a;">Event is LIVE!</div>
                                        <div class="text-xs" style="color: #6b7280;">
                                            Sales open now</div>
                                    </div>
                                </div>
                                <div class="rounded-xl p-3 flex-1"
                                    style="background: #f0faf9; border: 1px solid #d1fae5;">
                                    <div class="text-xs mb-2" style="color: #6b7280;">
                                        Preview</div>
                                    <div class="w-full rounded-lg mb-2.5 flex items-center justify-center"
                                        style="height: 60px; background: linear-gradient(135deg, rgb(16, 91, 85), #1a7a72);">
                                        <span style="font-size: 26px;">🎷</span>
                                    </div>
                                    <div class="text-gray-900 text-xs font-bold leading-tight">Nairobi Jazz
                                        Festival</div>
                                    <div class="text-xs mt-0.5" style="color: #6b7280;">Aug 12 · Uhuru Park
                                    </div>
                                    <div class="flex items-center justify-between mt-2"><span
                                            class="text-gray-900 text-xs font-semibold">KES
                                            1,500</span><span
                                            class="px-2 py-0.5 rounded-full text-xs font-bold text-white"
                                            style="background: #C8450A;">Buy</span></div>
                                </div>
                                <div class="rounded-xl p-3 " style="background: #f0faf9; border: 1px solid #d1fae5;">
                                    <div class="text-xs mb-2" style="color: #6b7280;">
                                        Share</div>
                                    <div class="grid grid-cols-4 gap-1.5">
                                        <div class="py-1.5 rounded-lg text-center text-xs"
                                            style="background: #f0faf9; color: #374151;">
                                            𝕏</div>
                                        <div class="py-1.5 rounded-lg text-center text-xs"
                                            style="background: #f0faf9; color: #374151;">
                                            IG</div>
                                        <div class="py-1.5 rounded-lg text-center text-xs"
                                            style="background: #f0faf9; color: #374151;">
                                            WA</div>
                                        <div class="py-1.5 rounded-lg text-center text-xs"
                                            style="background: #f0faf9; color: #374151;">
                                            🔗</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Features list --}}
    <section class="py-16 bg-white">
        <div class="mx-auto max-w-7xl px-4">
            <div class="max-w-5xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">Powerful Features for Every Event
                    </h2>
                    <p class="text-base text-gray-500">From small meetups to large festivals, we've got you covered</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach (['Customizable event pages with rich media', 'Flexible ticket types and pricing', 'Built-in email marketing tools', 'Real-time sales reporting', 'QR code check-in system', 'Attendee management dashboard', 'Mobile-optimized checkout', 'Multiple payment gateways (M-Pesa, Card, etc.)', 'Social media integration', 'Discount codes and promotions', 'Waitlist management', 'Automated reminders and updates'] as $item)
                        <div class="flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mt-0.5 shrink-0"
                                fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" />
                                <path d="m9 12 2 2 4-4" />
                            </svg>
                            <span class="text-gray-600">{{ $item }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Customer story --}}
    <section class="py-16 bg-brand-50">
        <div class="mx-auto max-w-7xl px-4">
            <div class="text-center mb-10">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">Trusted by Event Organizers Across Kenya
                </h2>
            </div>
            <video autoplay muted loop playsinline class="w-full h-auto shadow-lg">
                <source src="{{ asset('customer-story.webm') }}" type="video/webm">
            </video>
        </div>
    </section>

    {{-- How it works --}}
    <section id="how-it-works" class="bg-white py-16">
        <div class="mx-auto max-w-7xl px-4">
            <div class="text-center mb-10">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">How It Works</h2>
                <p class="text-base text-gray-500">Get started in 3 simple steps</p>
            </div>

            <div class="mx-auto mb-16 max-w-7xl text-center">
                <!-- Cards -->
                <div class="grid gap-6 md:grid-cols-3">

                    <!-- Card 1: Create Account -->
                    <div>
                        <div class="relative overflow-hidden rounded-2xl aspect-[4/5]"
                            style="background: linear-gradient(145deg, #105B55 0%, #0a3d39 100%);">
                            <video src="{{ asset('OnboardingCard.mp4') }}"
                                class="absolute inset-0 h-full w-full object-cover" autoplay loop muted
                                playsinline></video>
                        </div>
                        <div class="mt-6">
                            <h3 class="text-lg font-semibold text-gray-900">Create Your Account</h3>
                            <p class="mt-2 text-sm leading-7 text-gray-500">Sign up and set up your organizer profile
                                in minutes.</p>
                        </div>
                    </div>

                    <!-- Card 2: Promote & Sell -->
                    <div>
                        <div class="relative overflow-hidden rounded-2xl aspect-[4/5]" style="background: #C8450A;">
                            <video src="{{ asset('TicketSalesCard.mp4') }}"
                                class="absolute inset-0 h-full w-full object-cover" autoplay loop muted
                                playsinline></video>

                            <div
                                class="absolute bottom-6 left-6 right-6 rounded-2xl border border-white/20 bg-black/30 p-4 backdrop-blur-xl">
                                <!-- Metric row -->
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <p class="text-[10px] font-semibold uppercase tracking-[0.15em] text-white/50">
                                            Ticket Sales</p>
                                        <p class="mt-1 text-3xl font-bold text-white leading-none tracking-tight">248
                                        </p>
                                        <p class="mt-1.5 text-[11px] font-semibold" style="color: #E08C12;">↑ +32
                                            today</p>
                                    </div>
                                    <div
                                        class="h-9 w-9 rounded-xl border border-white/20 bg-white/10 flex items-center justify-center text-sm">
                                        🎟️</div>
                                </div>
                                <!-- Bar chart -->
                                <div class="relative">
                                    <div
                                        class="absolute inset-x-0 top-0 bottom-5 pointer-events-none flex flex-col justify-between">
                                        <div class="h-px bg-white/[0.07]"></div>
                                        <div class="h-px bg-white/[0.07]"></div>
                                        <div class="h-px bg-white/[0.07]"></div>
                                        <div class="h-px bg-white/[0.07]"></div>
                                    </div>
                                    <div class="flex items-end gap-1 h-14">
                                        <div class="flex-1 flex items-end">
                                            <div class="w-full rounded-t-sm bg-gradient-to-t from-white/25 to-white/10"
                                                style="height:32%"></div>
                                        </div>
                                        <div class="flex-1 flex items-end">
                                            <div class="w-full rounded-t-sm bg-gradient-to-t from-white/25 to-white/10"
                                                style="height:48%"></div>
                                        </div>
                                        <div class="flex-1 flex items-end">
                                            <div class="w-full rounded-t-sm bg-gradient-to-t from-white/25 to-white/10"
                                                style="height:58%"></div>
                                        </div>
                                        <div class="flex-1 flex items-end">
                                            <div class="w-full rounded-t-sm bg-gradient-to-t from-white/25 to-white/10"
                                                style="height:44%"></div>
                                        </div>
                                        <div class="flex-1 flex items-end">
                                            <div class="w-full rounded-t-sm bg-gradient-to-t from-white/25 to-white/10"
                                                style="height:70%"></div>
                                        </div>
                                        <div class="flex-1 flex items-end">
                                            <div class="w-full rounded-t-sm bg-gradient-to-t from-white/25 to-white/10"
                                                style="height:85%"></div>
                                        </div>
                                        <!-- Active bar (today) -->
                                        <div class="flex-1 flex items-end">
                                            <div class="w-full rounded-t-sm"
                                                style="height:100%; background: linear-gradient(to top, #E08C12, #f5b942); box-shadow: 0 0 12px 2px rgba(224,140,18,0.45);">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex gap-1 mt-1.5">
                                        <p class="flex-1 text-center text-[9px] font-medium text-white/30">M</p>
                                        <p class="flex-1 text-center text-[9px] font-medium text-white/30">T</p>
                                        <p class="flex-1 text-center text-[9px] font-medium text-white/30">W</p>
                                        <p class="flex-1 text-center text-[9px] font-medium text-white/30">T</p>
                                        <p class="flex-1 text-center text-[9px] font-medium text-white/30">F</p>
                                        <p class="flex-1 text-center text-[9px] font-medium text-white/30">S</p>
                                        <p class="flex-1 text-center text-[9px] font-bold" style="color: #E08C12;">S
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6">
                            <h3 class="text-lg font-semibold text-gray-900">Promote &amp; Sell</h3>
                            <p class="mt-2 text-sm leading-7 text-gray-500">Share your event and start selling tickets
                                immediately.</p>
                        </div>
                    </div>

                    <!-- Card 3: Manage & Grow -->
                    <div>
                        <div class="relative overflow-hidden rounded-2xl aspect-[4/5]" style="background: #1a7a72;">
                            <video src="{{ asset('ManageGrowCard.mp4') }}"
                                class="absolute inset-0 h-full w-full object-cover" autoplay loop muted
                                playsinline></video>

                            <div
                                class="absolute bottom-6 left-6 right-6 rounded-2xl border border-white/20 bg-black/30 p-4 backdrop-blur-xl">
                                <div class="mb-4 inline-flex rounded-full px-3 py-1 text-xs"
                                    style="background: rgba(224,140,18,0.2); color: #f5cc6e;">
                                    📈 Growth Insight
                                </div>
                                <p class="text-sm font-medium leading-6 text-white">Your event is gaining momentum with
                                    higher engagement this week.</p>
                                <ul class="mt-4 space-y-2 text-xs text-white/80">
                                    <li>🎟️ 248 Tickets Sold</li>
                                    <li>👥 1,240 Event Page Visits</li>
                                    <li>📣 18% More Shares This Week</li>
                                </ul>
                                <div class="mt-5 rounded-xl bg-white/10 p-3">
                                    <div class="flex items-center justify-between text-xs text-white/70">
                                        <span>Conversion Rate</span>
                                        <span class="font-medium" style="color: #E08C12;">20%</span>
                                    </div>
                                    <div class="mt-2 h-2 overflow-hidden rounded-full bg-white/10">
                                        <div class="h-full w-1/5 rounded-full" style="background: #E08C12;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6">
                            <h3 class="text-lg font-semibold text-gray-900">Manage &amp; Grow</h3>
                            <p class="mt-2 text-sm leading-7 text-gray-500">Monitor ticket sales, understand your
                                audience, and use real-time insights to grow every event.</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    {{-- CTA / Signup --}}
    <section id="signup" class="py-20 bg-white">
        <div class="mx-auto max-w-7xl px-4">
            <div
                class="bg-white rounded-2xl border border-gray-200 shadow-xl shadow-gray-200/60 max-w-2xl mx-auto p-8">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Ready to Get Started?</h2>
                    <p class="text-gray-500">Create your organizer account and list your first event today</p>
                </div>

                @guest
                    <form wire:submit="submit" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Full name</label>
                                <input wire:model="name" type="text" autocomplete="name" placeholder="John Doe"
                                    class="mt-1 block w-full rounded-xl border bg-white px-3 py-2.5 text-sm text-gray-900 placeholder-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-500 hover:border-gray-300 transition-all duration-150 {{ $errors->has('name') ? 'border-red-400' : 'border-gray-200' }}">
                                @error('name')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Organization name</label>
                                <input wire:model.blur="display_name" type="text" placeholder="Nairobi Events Co."
                                    class="mt-1 block w-full rounded-xl border bg-white px-3 py-2.5 text-sm text-gray-900 placeholder-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-500 hover:border-gray-300 transition-all duration-150 {{ $errors->has('display_name') ? 'border-red-400' : 'border-gray-200' }}">
                                @error('display_name')
                                    <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
                                        <svg class="h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor"
                                            stroke-width="2" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="10" />
                                            <path d="M12 8v4m0 4h.01" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                                @if (!$errors->has('display_name') && strlen($display_name) >= 2)
                                    <p class="mt-1 text-xs text-emerald-600 flex items-center gap-1">
                                        <svg class="h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor"
                                            stroke-width="2" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="10" />
                                            <path d="m9 12 2 2 4-4" />
                                        </svg>
                                        Looks good!
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email address</label>
                            <input wire:model="email" type="email" autocomplete="email" placeholder="you@example.com"
                                class="mt-1 block w-full rounded-xl border bg-white px-3 py-2.5 text-sm text-gray-900 placeholder-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-500 hover:border-gray-300 transition-all duration-150 {{ $errors->has('email') ? 'border-red-400' : 'border-gray-200' }}">
                            @error('email')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Password</label>
                                <input wire:model="password" type="password" autocomplete="new-password"
                                    class="mt-1 block w-full rounded-xl border bg-white px-3 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-500 hover:border-gray-300 transition-all duration-150 {{ $errors->has('password') ? 'border-red-400' : 'border-gray-200' }}">
                                @error('password')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Confirm password</label>
                                <input wire:model="password_confirmation" type="password" autocomplete="new-password"
                                    class="mt-1 block w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-500 hover:border-gray-300 transition-all duration-150">
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-3 rounded-full bg-brand-600 h-10 px-5 text-sm font-semibold text-white text-nowrap hover:bg-brand-700 transition-[color,background] duration-200 focus-visible:outline focus-visible:outline-offset-1">
                            Create organizer account
                        </button>

                        <p class="text-xs text-center text-gray-400">
                            By signing up, you agree to our Terms of Service and Privacy Policy.
                            Already have an account? <a href="{{ route('login') }}"
                                class="text-brand-600 hover:text-brand-700 transition-colors">Sign in</a>
                        </p>
                    </form>
                @else
                    <div class="text-center py-4">
                        <p class="text-gray-600 mb-4">You're already signed in. Head to your organizer dashboard to get
                            started.</p>
                        <a href="{{ route('organizer.onboard') }}"
                            class="inline-flex items-center justify-center gap-3 rounded-full bg-brand-600 h-10 px-5 text-sm font-semibold text-white text-nowrap hover:bg-brand-700 transition-[color,background] duration-200 focus-visible:outline focus-visible:outline-offset-1">
                            Go to onboarding
                        </a>
                    </div>
                @endguest
            </div>
        </div>
    </section>
</div>
