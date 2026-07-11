<x-mail::message>
# You've been invited to check in attendees

Hi there,

**{{ $invitation->event->organizer->display_name }}** has invited you to be a gate checker for the event below. As a checker, you'll be able to scan attendee QR codes and mark them as checked in on the day.

<x-mail::panel>
**Event:** {{ $invitation->event->title }}
@if($invitation->event->start_at)
**Date:** {{ $invitation->event->start_at->format('D, d M Y · H:i') }}
@endif
@if($invitation->event->venue_name)
**Venue:** {{ $invitation->event->venue_name }}@if($invitation->event->city), {{ $invitation->event->city }}@endif
@endif
</x-mail::panel>

To accept this invitation, click the button below. You'll need to sign in (or register) with the account linked to this email address.

<x-mail::button url="{{ route('checker-invites.accept', $invitation->token) }}" color="primary">
Accept Invitation
</x-mail::button>

This link expires in **7 days**. If you weren't expecting this or don't recognise the event, you can safely ignore this email.

Thanks,
{{ app_settings()->app_name }}
</x-mail::message>
