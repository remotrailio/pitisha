<?php

namespace App\Mail;

use App\Models\EventCheckerInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CheckerInvitation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly EventCheckerInvitation $invitation,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You\'ve been invited to check in attendees — ' . $this->invitation->event->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.checker-invitation',
        );
    }
}
