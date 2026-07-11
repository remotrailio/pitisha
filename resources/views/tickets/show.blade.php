@php $__settings = app_settings(); @endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $ticket->ticket_code }} — {{ $__settings->app_name }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-warm-50 flex flex-col items-center justify-start p-4 pt-10 pb-16">

    {{-- Brand --}}
    <a href="{{ route('home') }}" class="mb-8 text-brand-600 font-bold text-lg tracking-tight">
        {{ $__settings->app_name }}
    </a>

    <div class="w-full max-w-sm space-y-3">

        {{-- Flash: checked in just now --}}
        @if(session('checkin_success'))
        <div class="rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800 font-medium text-center">
            Checked in successfully.
        </div>
        @endif

        @if(session('checkin_already_used'))
        <div class="rounded-xl bg-amber-50 border border-amber-200 px-4 py-3 text-sm text-amber-800 font-medium text-center">
            This ticket was already checked in.
        </div>
        @endif

        {{-- Status badge --}}
        <div class="text-center">
            @if(! $isPaid)
                <span class="inline-flex items-center gap-1.5 rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                    <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                    Invalid — unpaid order
                </span>
            @elseif($ticket->checked_in_at)
                <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                    <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                    Already checked in · {{ $ticket->checked_in_at->format('d M Y H:i') }}
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                    <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                    Valid
                </span>
            @endif
        </div>

        {{-- Ticket card --}}
        <div class="rounded-2xl bg-white border border-gray-200 overflow-hidden shadow-sm">

            {{-- Event header --}}
            <div class="bg-brand-600 px-5 py-4">
                <p class="text-xs font-semibold text-white/70 uppercase tracking-widest mb-0.5">
                    {{ $ticket->orderItem?->ticketType?->name ?? 'General Admission' }}
                </p>
                <p class="text-base font-bold text-white leading-snug">{{ $event->title }}</p>
            </div>

            {{-- Details --}}
            <div class="divide-y divide-gray-100 text-sm">
                @if($event->start_at)
                <div class="flex justify-between px-5 py-3">
                    <span class="text-gray-400 font-medium">Date</span>
                    <span class="text-gray-900 font-semibold">{{ $event->start_at->format('D, d M Y · H:i') }}</span>
                </div>
                @endif

                @if($event->venue_name)
                <div class="flex justify-between px-5 py-3">
                    <span class="text-gray-400 font-medium">Venue</span>
                    <span class="text-gray-900 font-semibold text-right">
                        {{ $event->venue_name }}@if($event->city), {{ $event->city }}@endif
                    </span>
                </div>
                @endif

                <div class="flex justify-between px-5 py-3">
                    <span class="text-gray-400 font-medium">Attendee</span>
                    <span class="text-gray-900 font-semibold">
                        {{ $ticket->attendee_name ?? $ticket->order->user?->name ?? '—' }}
                    </span>
                </div>

                <div class="flex justify-between px-5 py-3">
                    <span class="text-gray-400 font-medium">Order</span>
                    <span class="text-gray-900 font-semibold">{{ $ticket->order->order_number }}</span>
                </div>
            </div>

            {{-- Ticket code --}}
            <div class="bg-gray-50 border-t border-dashed border-gray-200 px-5 py-3 text-center">
                <span class="font-mono text-sm font-bold tracking-widest text-brand-600">
                    {{ $ticket->ticket_code }}
                </span>
            </div>
        </div>

        {{-- Check-in button (checkers only) --}}
        @if($canCheckIn)
        <form method="POST" action="{{ route('tickets.check-in', $ticket->ticket_code) }}">
            @csrf
            <button type="submit"
                class="w-full inline-flex items-center justify-center gap-2 rounded-full bg-green-600 h-12 px-5 text-sm font-semibold text-white hover:bg-green-700 transition-colors">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                </svg>
                Check In Attendee
            </button>
        </form>
        @endif

        {{-- Login prompt for potential checkers who aren't logged in --}}
        @guest
        <p class="text-center text-xs text-gray-400">
            <a href="{{ route('login') }}?redirect={{ urlencode(request()->url()) }}" class="underline underline-offset-2 hover:text-brand-600">
                Sign in
            </a>
            to check in this attendee
        </p>
        @endguest

    </div>

</body>
</html>
