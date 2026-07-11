<?php

namespace App\Http\Controllers;

use App\Models\EventCheckerInvitation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class CheckerInviteController extends Controller
{
    public function accept(string $token): RedirectResponse
    {
        $invitation = EventCheckerInvitation::with('event')
            ->where('token', $token)
            ->firstOrFail();

        if (! Auth::check()) {
            session()->put('url.intended', route('checker-invites.accept', $token));
            return redirect()->route('login');
        }

        if ($invitation->accepted_at !== null) {
            return redirect()->route('home')
                ->with('info', 'This invitation has already been accepted.');
        }

        if ($invitation->isExpired()) {
            abort(410, 'This invitation has expired. Ask the organiser to send a new one.');
        }

        if (Auth::user()->email !== $invitation->email) {
            abort(403, 'This invitation was sent to a different email address.');
        }

        $invitation->event->checkers()->syncWithoutDetaching([Auth::id()]);
        $invitation->update(['accepted_at' => now()]);

        return redirect()->route('home')
            ->with('success', 'You can now check in attendees for ' . $invitation->event->title . '.');
    }
}
