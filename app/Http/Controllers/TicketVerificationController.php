<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;

/**
 * Legacy check-in URL (/check-in/{ticket_code}) — now redirects to the ticket view page.
 * Old QR codes in printed PDFs still encode this URL; the redirect makes them forward-compatible.
 */
class TicketVerificationController extends Controller
{
    public function __invoke(string $ticket_code): RedirectResponse
    {
        // Validate the ticket exists before redirecting (returns 404 on garbage codes)
        Ticket::where('ticket_code', $ticket_code)->firstOrFail();

        return redirect()->route('tickets.show', $ticket_code, 301);
    }
}
