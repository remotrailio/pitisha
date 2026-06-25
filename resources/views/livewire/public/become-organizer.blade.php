<div>
    {{-- Hero --}}
    <section class="relative py-6 overflow-hidden" style="background: #0f766e;">
        {{-- Bottom fade into white --}}
        <div class="absolute bottom-0 inset-x-0 h-24 bg-linear-to-t from-white to-transparent pointer-events-none"></div>

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-center gap-10">

                {{-- Left: copy + CTA --}}
                <div class="lg:w-5/12 shrink-0">
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/20 border border-white/30 px-3 py-1 text-xs font-semibold text-white mb-6">
                        <span class="h-1.5 w-1.5 rounded-full bg-white"></span>
                        Fast no fuss
                    </span>
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold tracking-tight text-white leading-tight mb-6">
                        <span class="block whitespace-nowrap">Turn Your Events Into</span>
                        <span class="block text-white/80 whitespace-nowrap">Success Stories</span>
                    </h1>
                    <p class="text-lg text-white/80 mb-8 leading-relaxed">
                        Join Kenya's leading event platform and reach thousands of eager attendees. Sell tickets, manage check-ins, and grow your audience — all in one place.
                    </p>
                    <div class="flex flex-wrap items-center gap-4 mb-4">
                        @auth
                            <a href="{{ route('organizer.onboard') }}"
                                class="inline-flex items-center justify-center gap-3 rounded-full bg-accent-600 h-10 px-5 text-sm font-semibold text-white text-nowrap hover:bg-accent-700 transition-[color,background] duration-200 focus-visible:outline focus-visible:outline-offset-1">
                                Get Started Free
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M5 12h14" /><path d="m12 5 7 7-7 7" />
                                </svg>
                            </a>
                        @else
                            <a href="#signup"
                                class="inline-flex items-center justify-center gap-3 rounded-full bg-accent-600 h-10 px-5 text-sm font-semibold text-white text-nowrap hover:bg-accent-700 transition-[color,background] duration-200 focus-visible:outline focus-visible:outline-offset-1">
                                Get Started Free
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M5 12h14" /><path d="m12 5 7 7-7 7" />
                                </svg>
                            </a>
                        @endauth
                        <a href="#how-it-works" class="text-sm font-semibold text-white/80 hover:text-white transition-colors">
                            See how it works →
                        </a>
                    </div>
                </div>

                {{-- Right: hero visual --}}
                <div class="lg:w-7/12 w-full">
                    <img src="{{ asset('images/ticketeke-app-hero-teal.png') }}" alt="Ticketeke app hero" class="w-full h-auto">
                </div>

            </div>
        </div>
    </section>

    {{-- Stats --}}
    <section class="py-16 bg-white border-b border-gray-100">
        <div class="mx-auto max-w-7xl px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 max-w-7xl mx-auto">
                <div class="text-center">
                    <div class="text-2xl md:text-3xl font-bold text-brand-600 mb-2">5,000+</div>
                    <div class="text-sm text-gray-500">Active Organizers</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl md:text-3xl font-bold text-brand-600 mb-2">250K+</div>
                    <div class="text-sm text-gray-500">Tickets Sold Monthly</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl md:text-3xl font-bold text-brand-600 mb-2">4.8/5</div>
                    <div class="text-sm text-gray-500">Organizer Rating</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl md:text-3xl font-bold text-brand-600 mb-2">10%</div>
                    <div class="text-sm text-gray-500">Platform Fee</div>
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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto">
                @foreach ([
        ['icon' => 'M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 7a4 4 0 1 0 8 0 4 4 0 0 0-8 0M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75', 'title' => 'Reach Thousands', 'desc' => 'Connect with engaged audiences across Kenya and beyond'],
        ['icon' => 'M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6', 'title' => 'Secure Payments', 'desc' => 'Fast, reliable payouts with multiple payment options'],
        ['icon' => 'M22 7 13.5 15.5 8.5 10.5 2 17M16 7h6v6', 'title' => 'Grow Your Business', 'desc' => 'Access analytics and tools to scale your events'],
        ['icon' => 'M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z', 'title' => 'Fraud Protection', 'desc' => 'Advanced security to protect you and your attendees'],
        ['icon' => 'M3 14h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-7a9 9 0 0 1 18 0v7a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3', 'title' => '24/7 Support', 'desc' => 'Dedicated support team to help you succeed'],
        ['icon' => 'M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z', 'title' => 'Easy Setup', 'desc' => 'Create and publish events in minutes, not hours'],
    ] as $feature)
                    <div
                        class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                        <div class="h-12 w-12 rounded-xl bg-brand-50 flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none"
                                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $feature['icon'] }}" />
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-gray-900 mb-2">{{ $feature['title'] }}</h3>
                        <p class="text-gray-500">{{ $feature['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Features list --}}
    <section class="py-16 bg-white">
        <div class="mx-auto max-w-7xl px-4">
            <div class="max-w-5xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">Powerful Features for Every Event</h2>
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

    {{-- Testimonials --}}
    <section class="py-16 bg-brand-50">
        <div class="mx-auto max-w-7xl px-4">
            <div class="text-center mb-12">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">Trusted by Event Organizers Across Kenya</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                @php
                    $testimonials = [
                        [
                            'quote' =>
                                '"' .
                                $__settings->app_name .
                                ' has transformed our business. We\'ve doubled our bookings and the platform makes everything so easy to manage."',
                            'name' => 'Sarah Kamau',
                            'role' => 'Founder, Nairobi Food Tours',
                            'img' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=400',
                        ],
                        [
                            'quote' =>
                                '"The support team is incredible and the platform is so intuitive. Our attendees love how easy it is to buy tickets."',
                            'name' => 'David Omondi',
                            'role' => 'Event Director, Coast Music Festival',
                            'img' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400',
                        ],
                        [
                            'quote' =>
                                '"Best decision we made for our safari business. The analytics help us understand our customers better."',
                            'name' => 'Grace Wanjiku',
                            'role' => 'Founder, Safari Experiences Ltd',
                            'img' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=400',
                        ],
                    ];
                @endphp
                @foreach ($testimonials as $testimonial)
                    <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                        <p class="text-gray-600 italic mb-6">{{ $testimonial['quote'] }}</p>
                        <div class="flex items-center gap-3">
                            <img src="{{ $testimonial['img'] }}" alt="{{ $testimonial['name'] }}"
                                class="h-12 w-12 rounded-full object-cover border-2 border-gray-100">
                            <div>
                                <div class="font-semibold text-gray-900">{{ $testimonial['name'] }}</div>
                                <div class="text-sm text-gray-500">{{ $testimonial['role'] }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- How it works --}}
    <section id="how-it-works" class="py-16 bg-white">
        <div class="mx-auto max-w-7xl px-4">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">How It Works</h2>
                    <p class="text-base text-gray-500">Get started in 3 simple steps</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach ([['step' => '1', 'title' => 'Create Your Account', 'desc' => 'Sign up and set up your organizer profile in minutes'], ['step' => '2', 'title' => 'Promote & Sell', 'desc' => 'Share your event and start selling tickets immediately'], ['step' => '3', 'title' => 'Manage & Grow', 'desc' => 'Track sales, manage attendees, and grow your audience']] as $step)
                        <div class="text-center">
                            <div
                                class="h-16 w-16 rounded-2xl bg-brand-600 text-white flex items-center justify-center text-2xl font-bold mx-auto mb-4 shadow-md shadow-brand-200">
                                {{ $step['step'] }}
                            </div>
                            <h3 class="text-base font-semibold text-gray-900 mb-2">{{ $step['title'] }}</h3>
                            <p class="text-gray-500">{{ $step['desc'] }}</p>
                        </div>
                    @endforeach
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
