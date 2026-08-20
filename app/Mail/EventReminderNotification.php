<?php

namespace App\Mail;

use App\Models\EventRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventReminderNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public EventRegistration $registration,
        public int $daysBefore
    ) {}

    public function envelope(): Envelope
    {
        $event = $this->registration->event;
        $when = $this->daysBefore === 1 ? 'demain' : 'dans '.$this->daysBefore.' jours';

        return new Envelope(
            subject: "Rappel : {$event->title} {$when}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.event-reminder',
            with: [
                'event' => $this->registration->event,
                'eventUrl' => $this->registration->event->publicUrl(),
                'ticketUrl' => $this->registration->event->ticketUrl($this->registration->qr_code),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
