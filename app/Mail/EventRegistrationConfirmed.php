<?php

namespace App\Mail;

use App\Models\EventRegistration;
use App\Services\EventRegistrationService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventRegistrationConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public EventRegistration $registration)
    {
        $this->registration->loadMissing(['event']);
    }

    public function envelope(): Envelope
    {
        $event = $this->registration->event;
        $subject = $event->hasOnlineAccess()
            ? 'Accès & ticket — '.$event->title
            : 'Votre ticket — '.$event->title;

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        $qrSvg = base64_encode(
            \QrCode::format('svg')->size(180)->generate($this->registration->qr_code)
        );

        return new Content(
            view: 'emails.event-registration-confirmed',
            with: [
                'registration' => $this->registration,
                'event' => $this->registration->event,
                'ticketUrl' => route('event.ticket.public', $this->registration->qr_code),
                'eventUrl' => $this->registration->event->publicUrl(),
                'qrSvgBase64' => $qrSvg,
            ],
        );
    }

    public function attachments(): array
    {
        try {
            $pdf = app(EventRegistrationService::class)->generateTicketPdf($this->registration);

            return [
                Attachment::fromData(
                    fn () => $pdf->output(),
                    'ticket-' . $this->registration->event->slug . '.pdf'
                )->withMime('application/pdf'),
            ];
        } catch (\Throwable $e) {
            report($e);

            return [];
        }
    }
}
