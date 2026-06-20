<div class="mx-auto max-w-2xl px-4 py-12 sm:px-6 lg:px-8">

    {{-- Success banner --}}
    <div class="mb-8 rounded-2xl p-8 text-center text-white shadow-lg" style="background: linear-gradient(135deg, #0f766e, #0d9488, #14b8a6);">
        <svg class="mx-auto mb-3 h-14 w-14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <h1 class="text-2xl font-bold">You're going!</h1>
        <p class="mt-1 text-teal-100">{{ $order->event->title }}</p>
        <p class="mt-3 text-xs text-teal-200">Order #{{ $order->order_number }}</p>
    </div>

    {{-- Event details --}}
    <div class="mb-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-md shadow-gray-100">
        <h2 class="mb-3 text-sm font-semibold uppercase tracking-wide text-gray-500">Event details</h2>
        <div class="space-y-2 text-sm text-gray-700">
            <div class="flex items-center gap-2">
                <svg class="h-4 w-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>{{ $order->event->start_at?->format('D, d M Y · H:i') }}</span>
            </div>
            @if($order->event->venue_name)
                <div class="flex items-center gap-2">
                    <svg class="h-4 w-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>{{ $order->event->venue_name }}@if($order->event->city), {{ $order->event->city }}@endif</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Tickets --}}
    <div class="mb-6 rounded-2xl border border-gray-200 bg-white shadow-md shadow-gray-100">
        <div class="border-b border-gray-100 px-6 py-4">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500">
                Your tickets ({{ $order->tickets->count() }})
            </h2>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach($order->tickets as $ticket)
                <div class="flex items-center gap-4 px-6 py-4">
                    <div class="shrink-0 rounded-xl border border-gray-100 bg-white p-1.5">
                        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(80)->margin(0)->generate($ticket->qr_code) !!}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800">{{ $ticket->orderItem->ticketType->name }}</p>
                        <p class="mt-0.5 font-mono text-xs text-gray-400 tracking-widest">{{ $ticket->ticket_code }}</p>
                    </div>
                    <span class="inline-flex items-center rounded-full bg-emerald-50 border border-emerald-100 px-2.5 py-1 text-xs font-medium text-emerald-700">
                        Valid
                    </span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Payment summary --}}
    <div class="mb-8 rounded-2xl border border-gray-200 bg-white shadow-md shadow-gray-100">
        <div class="border-b border-gray-100 px-6 py-4">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-500">Payment</h2>
        </div>
        <div class="space-y-2 px-6 py-4 text-sm text-gray-700">
            <div class="flex justify-between">
                <span>Subtotal</span>
                <span>KES {{ number_format($order->subtotal, 2) }}</span>
            </div>
            @if($order->fees > 0)
                <div class="flex justify-between">
                    <span>Platform fee</span>
                    <span>KES {{ number_format($order->fees, 2) }}</span>
                </div>
            @endif
            @if($order->discount > 0)
                <div class="flex justify-between text-emerald-600">
                    <span>Discount</span>
                    <span>− KES {{ number_format($order->discount, 2) }}</span>
                </div>
            @endif
            <div class="flex justify-between border-t border-gray-100 pt-2 font-semibold">
                <span>Total paid</span>
                <span class="text-teal-600">KES {{ number_format($order->total, 2) }}</span>
            </div>
            @if($order->mpesa_receipt_number)
                <div class="flex justify-between text-xs text-gray-400 pt-1">
                    <span>M-Pesa receipt</span>
                    <span class="font-mono">{{ $order->mpesa_receipt_number }}</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex flex-col gap-3 sm:flex-row">
        @auth
        <a href="{{ route('my.tickets') }}"
           class="flex flex-1 items-center justify-center gap-3 rounded-full bg-teal-600 h-10 px-5 text-sm font-semibold text-white text-nowrap hover:bg-teal-700 transition-[color,background] duration-200 focus-visible:outline focus-visible:outline-offset-1">
            View all my tickets
        </a>
        @endauth
        @if($order->tickets->isNotEmpty())
        <a href="{{ route('orders.tickets.download', $order->uuid) . ($order->guest_token ? '?token=' . $order->guest_token : '') }}"
           class="flex flex-1 items-center justify-center gap-3 rounded-full bg-teal-600 h-10 px-5 text-sm font-semibold text-white text-nowrap hover:bg-teal-700 transition-[color,background] duration-200 focus-visible:outline focus-visible:outline-offset-1">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Download PDF
        </a>
        @endif
        <a href="{{ route('events.show', $order->event->slug) }}"
           class="flex flex-1 items-center justify-center gap-3 rounded-full border border-gray-200 bg-white h-10 px-5 text-sm font-semibold text-gray-700 text-nowrap hover:bg-gray-50 hover:border-gray-300 transition-[color,background] duration-200 focus-visible:outline focus-visible:outline-offset-1">
            Back to event
        </a>
    </div>

</div>
