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

    {{-- Success --}}
    @if($state === 'success')
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-8 text-center">
            <svg class="mx-auto mb-4 h-16 w-16 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h2 class="text-xl font-bold text-emerald-800">Payment confirmed!</h2>
            <p class="mt-2 text-sm text-emerald-700">Your tickets are ready.</p>
            @if($order)
                <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-center">
                    <a href="{{ route('orders.confirmation', $order->uuid) . ($guestToken ? '?token=' . $guestToken : '') }}"
                       class="inline-flex items-center justify-center gap-3 rounded-full bg-emerald-600 h-10 px-5 text-sm font-semibold text-white text-nowrap hover:bg-emerald-700 transition-[color,background] duration-200 focus-visible:outline focus-visible:outline-offset-1">
                        View tickets
                    </a>
                    @auth
                    <a href="{{ route('my.tickets') }}"
                       class="inline-flex items-center justify-center gap-3 rounded-full border border-emerald-300 bg-white h-10 px-5 text-sm font-semibold text-emerald-700 text-nowrap hover:bg-emerald-50 transition-[color,background] duration-200 focus-visible:outline focus-visible:outline-offset-1">
                        My tickets
                    </a>
                    @endauth
                </div>
            @endif
        </div>

    {{-- Failed --}}
    @elseif($state === 'failed')
        <div class="rounded-2xl border border-red-200 bg-red-50 p-8 text-center">
            <svg class="mx-auto mb-4 h-16 w-16 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h2 class="text-xl font-bold text-red-800">Payment failed</h2>
            <p class="mt-2 text-sm text-red-700">{{ $errorMessage }}</p>
            <button wire:click="retry"
                    class="mt-6 inline-flex items-center justify-center gap-3 rounded-full bg-red-600 h-10 px-5 text-sm font-semibold text-white text-nowrap hover:bg-red-700 transition-[color,background] duration-200 focus-visible:outline focus-visible:outline-offset-1">
                Try again
            </button>
        </div>

    {{-- Polling --}}
    @elseif($state === 'polling')
        <div class="rounded-2xl border border-teal-200 bg-teal-50 p-8 text-center"
             wire:poll.3000ms="poll">
            <svg class="mx-auto mb-4 h-16 w-16 animate-spin text-teal-500" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
            </svg>
            <h2 class="text-xl font-bold text-teal-900">Waiting for payment</h2>
            <p class="mt-2 text-sm text-teal-700">
                Check your phone for the M-Pesa PIN prompt and enter your PIN to complete payment.
            </p>
            <p class="mt-4 text-xs text-teal-500">This page will update automatically…</p>
        </div>

    {{-- Processing --}}
    @elseif($state === 'processing')
        <div class="rounded-2xl border border-gray-200 bg-white p-8 text-center">
            <svg class="mx-auto mb-4 h-16 w-16 animate-pulse text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h2 class="text-xl font-bold text-gray-700">Initiating payment…</h2>
            <p class="mt-2 text-sm text-gray-500">Please wait while we contact M-Pesa.</p>
        </div>

    {{-- Idle (default) --}}
    @else
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
                    <div class="flex items-center justify-between border-t border-gray-200 pt-2">
                        <span class="font-semibold text-gray-800">Total</span>
                        <span class="text-lg font-bold text-teal-600">
                            {{ $currency }} {{ number_format($total, 2) }}
                        </span>
                    </div>
                </div>
            </div>

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
                        class="mt-1.5 block w-full rounded-xl border border-gray-200 bg-gray-50 py-2.5 px-3 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-400 hover:border-gray-300 transition-all duration-150"
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
                        class="mt-1.5 block w-full rounded-xl border border-gray-200 bg-gray-50 py-2.5 px-3 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-400 hover:border-gray-300 transition-all duration-150"
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
                <div class="mt-1.5 flex rounded-xl border border-gray-200 bg-gray-50 shadow-sm focus-within:ring-2 focus-within:ring-teal-500/20 focus-within:border-teal-400 hover:border-gray-300 transition-all duration-150">
                    <span class="inline-flex items-center rounded-l-xl border-r border-gray-300 bg-gray-50 px-3 text-sm text-gray-500">
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
                </p>
            </div>

            {{-- Pay button --}}
            <button
                wire:click="pay"
                wire:loading.attr="disabled"
                wire:target="pay"
                class="w-full inline-flex items-center justify-center gap-3 rounded-full bg-teal-600 h-10 px-5 text-sm font-semibold text-white text-nowrap hover:bg-teal-700 transition-[color,background] duration-200 focus-visible:outline focus-visible:outline-offset-1 disabled:opacity-60"
            >
                <span wire:loading.remove wire:target="pay">
                    Pay {{ $currency }} {{ number_format($total, 2) }} via M-Pesa
                </span>
                <span wire:loading wire:target="pay" class="inline-flex items-center gap-2 whitespace-nowrap">
                    <svg class="h-5 w-5 shrink-0 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                    Processing…
                </span>
            </button>

            <p class="text-center text-xs text-gray-400">
                Secured by Safaricom M-Pesa. Your payment is encrypted and safe.
            </p>
        </div>
    @endif

</div>
