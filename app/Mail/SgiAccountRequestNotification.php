<?php

namespace App\Mail;

use App\Models\SgiAccountRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SgiAccountRequestNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public SgiAccountRequest $request,
        public string $type = 'client_confirmation'
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: match ($this->type) {
                'admin_new' => 'Nouvelle demande de mise en relation SGI / SGO',
                'client_reminder' => 'Votre demande de mise en relation SGI / SGO',
                'admin_reminder' => 'Relance à traiter : demande SGI / SGO en attente',
                default => 'Confirmation de votre demande de mise en relation',
            },
        );
    }

    public function content(): Content
    {
        return new Content(
            view: match ($this->type) {
                'admin_new' => 'emails.sgi-account-request-admin',
                'client_reminder' => 'emails.sgi-account-request-reminder-client',
                'admin_reminder' => 'emails.sgi-account-request-reminder-admin',
                default => 'emails.sgi-account-request-client',
            },
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
