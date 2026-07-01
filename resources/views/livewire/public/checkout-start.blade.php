<div class="mx-auto max-w-2xl px-4 py-12 sm:px-6 lg:px-8">

    <div class="mb-8">
        <a href="{{ route('events.show', $event->slug) }}"
           class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 transition-colors">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to event
        </a>
        <h1 class="mt-4 text-2xl font-bold text-gray-900">Complete your order</h1>
        <p class="mt-1 text-sm text-gray-500">{{ $event->title }}</p>
    </div>

    {{-- Validation / order error --}}
    @if($errorMessage)
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
            {{ $errorMessage }}
        </div>
    @endif

    <div class="space-y-6">

            {{-- Order summary --}}
            <div class="rounded-2xl border border-gray-200 bg-white shadow-md shadow-gray-100">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500">Order summary</h2>
                </div>
                <div class="divide-y divide-gray-50 px-6">
                    @foreach($itemSummary as $line)
                        <div class="flex items-center justify-between py-3">
                            <div>
                                <span class="text-sm font-medium text-gray-800">{{ $line['name'] }}</span>
                                <span class="ml-2 text-sm text-gray-400">× {{ $line['quantity'] }}</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-800">
                                KES {{ number_format($line['subtotal'], 2) }}
                            </span>
                        </div>
                    @endforeach
                </div>
                <div class="space-y-2 rounded-b-2xl bg-white px-6 py-4">
                    <div class="flex items-center justify-between text-sm text-gray-600">
                        <span>Subtotal</span>
                        <span>{{ $currency }} {{ number_format($subtotal, 2) }}</span>
                    </div>
                    @if($discountAmount > 0)
                        <div class="flex items-center justify-between text-sm text-emerald-600">
                            <span>Discount ({{ $promoResult['promo_code'] }})</span>
                            <span>− {{ $currency }} {{ number_format($discountAmount, 2) }}</span>
                        </div>
                    @endif
                    <div class="flex items-center justify-between border-t border-gray-200 pt-2">
                        <span class="font-semibold text-gray-800">Total</span>
                        <span class="text-lg font-bold text-gold-600">
                            {{ $currency }} {{ number_format($total, 2) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Promo code --}}
            @if(!$promoResult || !$promoResult['success'])
                <div class="rounded-2xl border border-gray-200 bg-white px-6 py-5 shadow-md shadow-gray-100">
                    <h2 class="mb-3 text-sm font-semibold uppercase tracking-wide text-gray-500">Promo code</h2>
                    <div class="flex gap-2">
                        <input
                            type="text"
                            wire:model="promoCodeInput"
                            placeholder="Enter code"
                            class="block w-full rounded-xl border border-gray-200 bg-white py-2.5 px-3 text-sm text-gray-900 uppercase placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-500 hover:border-gray-300 transition-all duration-150"
                        />
                        <button
                            wire:click="applyPromo"
                            class="inline-flex items-center justify-center rounded-full border border-gray-200 h-10 px-4 text-sm font-semibold text-gray-700 hover:bg-white transition-colors whitespace-nowrap"
                        >
                            Apply
                        </button>
                    </div>
                    @if($promoResult && !$promoResult['success'])
                        <p class="mt-2 text-xs text-red-600">{{ $promoResult['message'] }}</p>
                    @endif
                </div>
            @else
                <div class="flex items-center justify-between rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-3">
                    <div class="flex items-center gap-2 text-sm text-emerald-700">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-semibold">{{ $promoResult['promo_code'] }}</span>
                        <span class="text-emerald-600">— {{ $promoResult['promo_name'] }}</span>
                    </div>
                    <button wire:click="removePromo" class="text-xs text-emerald-600 hover:text-emerald-800 font-medium transition-colors">
                        Remove
                    </button>
                </div>
            @endif

            {{-- Payment method selection (only when multiple providers exist) --}}
            @if($providers->count() > 1)
            <div class="rounded-2xl border border-gray-200 bg-white px-6 py-5 shadow-md shadow-gray-100">
                <h2 class="mb-4 text-sm font-semibold uppercase tracking-wide text-gray-500">Payment method</h2>
                <div class="grid gap-3 sm:grid-cols-2">
                    @foreach($providers as $provider)
                        <label class="flex cursor-pointer items-center gap-3 rounded-xl border p-4 transition-colors {{ $selectedProviderId === $provider->id ? 'border-brand-500 bg-brand-50' : 'border-gray-200 hover:border-gray-300' }}">
                            <input type="radio" wire:model.live="selectedProviderId" value="{{ $provider->id }}" class="sr-only">
                            @if($provider->logo_path)
                                <img src="{{ Storage::disk('r2')->url($provider->logo_path) }}" alt="{{ $provider->name }}" class="h-8 w-8 object-contain shrink-0">
                            @else
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                </span>
                            @endif
                            <span class="text-sm font-medium text-gray-800">{{ $provider->name }}</span>
                            @if($selectedProviderId === $provider->id)
                                <svg class="ml-auto h-4 w-4 shrink-0 text-brand-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </label>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Guest details --}}
            @guest
            <div class="rounded-2xl border border-gray-200 bg-white px-6 py-6 shadow-md shadow-gray-100 space-y-4">
                <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500">Your details</h2>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Full name</label>
                    <input
                        id="name"
                        type="text"
                        wire:model="name"
                        placeholder="Jane Doe"
                        class="mt-1.5 block w-full rounded-xl border border-gray-200 bg-white py-2.5 px-3 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-500 hover:border-gray-300 transition-all duration-150"
                    />
                    @error('name') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                    <input
                        id="email"
                        type="email"
                        wire:model="email"
                        placeholder="jane@example.com"
                        class="mt-1.5 block w-full rounded-xl border border-gray-200 bg-white py-2.5 px-3 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-500 hover:border-gray-300 transition-all duration-150"
                    />
                    @error('email') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    <p class="mt-1.5 text-xs text-gray-400">Your tickets will be sent to this address.</p>
                </div>
            </div>
            @endguest

            {{-- Phone number input --}}
            <div class="rounded-2xl border border-gray-200 bg-white px-6 py-6 shadow-md shadow-gray-100">
                <h2 class="mb-4 text-sm font-semibold uppercase tracking-wide text-gray-500">M-Pesa payment</h2>

                <label for="phone" class="block text-sm font-medium text-gray-700">
                    M-Pesa phone number
                </label>
                <div class="mt-1.5 flex rounded-xl border border-gray-200 bg-white shadow-sm focus-within:ring-2 focus-within:ring-brand-600/20 focus-within:border-brand-500 hover:border-gray-300 transition-all duration-150">
                    <span class="inline-flex items-center rounded-l-xl border-r border-gray-300 bg-white px-3 text-sm text-gray-500">
                        +254
                    </span>
                    <input
                        id="phone"
                        type="tel"
                        wire:model="phone"
                        placeholder="7XXXXXXXX"
                        class="block w-full rounded-r-xl border-0 bg-transparent py-2.5 pl-3 pr-4 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-0"
                    />
                </div>
                @error('phone')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror

                <p class="mt-2 text-xs text-gray-400">
                    You will receive a PIN prompt on this number. Enter your M-Pesa PIN to pay.
                    <span class="text-amber-600 font-medium">Safaricom numbers only.</span>
                </p>
            </div>

            {{-- Pay button --}}
            <button
                wire:click="pay"
                wire:loading.attr="disabled"
                wire:target="pay"
                class="w-full inline-flex items-center justify-center rounded-full bg-accent-600 h-10 px-5 text-sm font-semibold text-white hover:bg-accent-700 transition disabled:opacity-60"
            >
                <span class="whitespace-nowrap">Pay {{ $currency }} {{ number_format($total, 2) }} via M-Pesa</span>
            </button>

            <p class="text-center text-xs text-gray-400">
                Secured by Safaricom M-Pesa. Your payment is encrypted and safe.
            </p>
        </div>

</div>
