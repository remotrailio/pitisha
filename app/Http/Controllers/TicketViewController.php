<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TicketViewController extends Controller
{
    public function show(string $ticket_code): View
    {
        $ticket = Ticket::with(['order.event', 'orderItem.ticketType'])
            ->where('ticket_code', $ticket_code)
            ->firstOrFail();

        $event  = $ticket->order->event;
        $isPaid = $ticket->order->payment_status === PaymentStatus::PAID;

        $canCheckIn = $isPaid
            && $ticket->checked_in_at === null
            && Auth::check()
            && Auth::user()->canCheckInEvent($event);

        return view('tickets.show', [
            'ticket'     => $ticket,
            'event'      => $event,
            'isPaid'     => $isPaid,
            'canCheckIn' => $canCheckIn,
        ]);
    }

    public function checkIn(Request $request, string $ticket_code): RedirectResponse
    {
        $ticket = Ticket::with(['order.event'])
            ->where('ticket_code', $ticket_code)
            ->firstOrFail();

        abort_unless(Auth::check(), 403);

        $event = $ticket->order->event;

        abort_unless($ticket->order->payment_status === PaymentStatus::PAID, 403, 'Order is not paid.');
        abort_unless(Auth::user()->canCheckInEvent($event), 403, 'You do not have check-in permission for this event.');

        $success = $ticket->checkIn();

        return redirect()
            ->route('tickets.show', $ticket_code)
            ->with($success ? 'checkin_success' : 'checkin_already_used', true);
    }
}
