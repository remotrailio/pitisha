<div class="mx-auto max-w-2xl px-4 py-12 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Order Status</h1>
        <p class="mt-1 text-sm text-gray-500">{{ $order->event->title }}</p>
    </div>

    {{-- Auto-poll wrapper --}}
    <div @if($shouldPoll) wire:poll.{{ $pollInterval }}ms="checkStatus" @endif>

        {{-- ── PAID ─────────────────────────────────────────────────────────── --}}
        @if($isPaid)
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-8 text-center">
                <svg class="mx-auto mb-4 h-14 w-14 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h2 class="text-xl font-bold text-emerald-800">Payment confirmed!</h2>
                <p class="mt-2 text-sm text-emerald-700">Your tickets have been issued and sent to your email.</p>
                <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-center">
                    <a href="{{ route('orders.confirmation', $order->uuid) . ($guestToken ? '?token=' . $guestToken : '') }}"
                       class="inline-flex items-center justify-center rounded-full bg-emerald-600 h-10 px-5 text-sm font-semibold text-white hover:bg-emerald-700 transition-colors">
                        View tickets
                    </a>
                    @auth
                        <a href="{{ route('my.tickets') }}"
                           class="inline-flex items-center justify-center rounded-full border border-emerald-300 bg-white h-10 px-5 text-sm font-semibold text-emerald-700 hover:bg-emerald-50 transition-colors">
                            My tickets
                        </a>
                    @endauth
                </div>
            </div>

        {{-- ── UNKNOWN — reconciliation running, not yet failed ─────────────── --}}
        @elseif($status->value === 'unknown')
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-8 text-center">
                <svg class="mx-auto mb-4 h-14 w-14 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h2 class="text-xl font-bold text-amber-900">Payment under investigation</h2>
                <p class="mt-2 text-sm text-amber-800">
                    We are still verifying your payment with M-Pesa. This page will update automatically.
                </p>
                <p class="mt-3 text-sm text-amber-800">
                    <strong>If your money was deducted, do not pay again.</strong>
                    Your tickets will be issued automatically once confirmed.
                </p>
                <div class="mt-6 rounded-xl bg-white border border-amber-200 divide-y divide-amber-100 text-sm text-left">
                    <div class="flex justify-between px-4 py-3">
                        <span class="text-amber-700">Order</span>
                        <span class="font-semibold text-gray-800">{{ $order->order_number }}</span>
                    </div>
                    <div class="flex justify-between px-4 py-3">
                        <span class="text-amber-700">Amount</span>
                        <span class="font-semibold text-gray-800">{{ strtoupper($order->currency) }} {{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
                <p class="mt-5 text-xs text-amber-700">
                    Need help? Contact support and quote your order reference above.
                </p>
            </div>

        {{-- ── FAILED — definitive M-Pesa failure ──────────────────────────── --}}
        @elseif($status->value === 'failed' && $isExpired === false)
            <div class="rounded-2xl border border-red-200 bg-red-50 p-8 text-center">
                <svg class="mx-auto mb-4 h-14 w-14 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h2 class="text-xl font-bold text-red-800">Payment not completed</h2>
                @if($order->failure_reason)
                    <p class="mt-2 text-sm text-red-700">{{ $order->failure_reason }}</p>
                @else
                    <p class="mt-2 text-sm text-red-700">Your payment was declined. No money has been deducted.</p>
                @endif
                <a href="{{ route('events.show', $order->event->slug) }}"
                   class="mt-6 inline-flex items-center justify-center rounded-full bg-accent-600 h-10 px-5 text-sm font-semibold text-white hover:bg-accent-700 transition-colors">
                    Try again
                </a>
            </div>

        {{-- ── EXPIRED ───────────────────────────────────────────────────────── --}}
        @elseif($isExpired)
            <div class="rounded-2xl border border-gray-200 bg-gray-50 p-8 text-center">
                <svg class="mx-auto mb-4 h-14 w-14 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h2 class="text-xl font-bold text-gray-700">Order expired</h2>
                <p class="mt-2 text-sm text-gray-600">This order was not completed within the allowed time. No money was deducted.</p>
                <a href="{{ route('events.show', $order->event->slug) }}"
                   class="mt-6 inline-flex items-center justify-center rounded-full bg-brand-600 h-10 px-5 text-sm font-semibold text-white hover:bg-brand-700 transition-colors">
                    Start a new order
                </a>
            </div>

        {{-- ── UNPAID / PROCESSING — waiting or STK push in flight ─────────── --}}
        @else
            <div class="rounded-2xl border border-brand-100 bg-brand-50 p-8">

                {{-- Icon + heading --}}
                <div class="text-center">
                    @if($status->value === 'processing' && $order->mpesa_checkout_request_id)
                        {{-- STK push sent — prompt the user to check phone --}}
                        <svg class="mx-auto mb-4 h-14 w-14 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        <h2 class="text-xl font-bold text-brand-900">Check your phone</h2>
                        <p class="mt-2 text-sm text-brand-700">
                            An M-Pesa PIN prompt has been sent to
                            @if($order->mpesa_phone)
                                <strong>+{{ $order->mpesa_phone }}</strong>.
                            @else
                                your phone.
                            @endif
                            Enter your PIN to complete payment.
                        </p>
                    @else
                        {{-- UNPAID — job queued or STK push not yet sent --}}
                        <svg class="mx-auto mb-4 h-14 w-14 animate-spin text-brand-400" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        <h2 class="text-xl font-bold text-brand-900">Setting up your payment…</h2>
                        <p class="mt-2 text-sm text-brand-700">
                            We are preparing the M-Pesa payment request. You will receive a PIN prompt on your phone shortly.
                        </p>
                    @endif

                    <p class="mt-3 text-xs text-brand-500">This page updates automatically.</p>
                </div>

                {{-- Order summary card --}}
                <div class="mt-6 rounded-xl bg-white border border-brand-100 divide-y divide-gray-100 text-sm">
                    <div class="flex justify-between px-4 py-3">
                        <span class="text-gray-500">Order</span>
                        <span class="font-semibold text-gray-800">{{ $order->order_number }}</span>
                    </div>
                    <div class="flex justify-between px-4 py-3">
                        <span class="text-gray-500">Amount</span>
                        <span class="font-semibold text-gray-800">{{ strtoupper($order->currency) }} {{ number_format($order->total, 2) }}</span>
                    </div>
                    @if($order->mpesa_phone)
                        <div class="flex justify-between px-4 py-3">
                            <span class="text-gray-500">M-Pesa number</span>
                            <span class="font-semibold text-gray-800">+{{ $order->mpesa_phone }}</span>
                        </div>
                    @endif
                    @if($order->expires_at && !$order->expires_at->isPast())
                        <div class="flex justify-between px-4 py-3">
                            <span class="text-gray-500">Order expires in</span>
                            <span class="font-semibold text-brand-700"
                                  x-data="{
                                      expiry: new Date('{{ $order->expires_at->toISOString() }}'),
                                      label: '',
                                      init() { this.tick(); setInterval(() => this.tick(), 1000); },
                                      tick() {
                                          const s = Math.max(0, Math.floor((this.expiry - Date.now()) / 1000));
                                          const m = Math.floor(s / 60);
                                          const r = s % 60;
                                          this.label = s > 0
                                              ? m + 'm ' + String(r).padStart(2, '0') + 's'
                                              : 'Expired';
                                      }
                                  }"
                                  x-text="label">
                            </span>
                        </div>
                    @endif
                </div>

                {{-- Retry / resend section --}}
                @if($canRetry)
                    <div class="mt-5 text-center">
                        @if(! $showRetryForm)
                            <button wire:click="$set('showRetryForm', true)"
                                    class="text-sm text-brand-600 underline underline-offset-2 hover:text-brand-800 transition-colors">
                                {{ $status->value === 'processing' ? "Didn't receive the prompt? Resend" : "Request payment prompt" }}
                            </button>
                        @else
                            <div class="mt-2 rounded-xl border border-brand-200 bg-white px-5 py-5 text-left">
                                <p class="mb-3 text-sm font-medium text-gray-700">Send M-Pesa prompt to:</p>
                                <div class="flex rounded-xl border border-gray-200 bg-white focus-within:ring-2 focus-within:ring-brand-600/20 focus-within:border-brand-500">
                                    <span class="inline-flex items-center rounded-l-xl border-r border-gray-300 bg-white px-3 text-sm text-gray-500">+254</span>
                                    <input
                                        type="tel"
                                        wire:model="phone"
                                        placeholder="7XXXXXXXX"
                                        class="block w-full rounded-r-xl border-0 bg-transparent py-2.5 pl-3 pr-4 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-0"
                                    />
                                </div>
                                @error('phone') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                                <div class="mt-3 flex gap-2">
                                    <button wire:click="requestPaymentPrompt"
                                            wire:loading.attr="disabled"
                                            class="inline-flex items-center justify-center rounded-full bg-brand-600 h-10 px-5 text-sm font-semibold text-white hover:bg-brand-700 transition-colors disabled:opacity-60">
                                        <span wire:loading.remove wire:target="requestPaymentPrompt">Send prompt</span>
                                        <span wire:loading wire:target="requestPaymentPrompt">Sending…</span>
                                    </button>
                                    <button wire:click="$set('showRetryForm', false)"
                                            class="inline-flex items-center justify-center rounded-full border border-gray-200 h-10 px-4 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

            </div>
        @endif

    </div>{{-- end poll wrapper --}}

</div>
