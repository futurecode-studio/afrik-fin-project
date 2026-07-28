<?php

namespace App\Services;

use App\Mail\EventRegistrationConfirmed;
use App\Models\EventOrder;
use App\Models\EventRegistration;
use App\Models\EventWaitlist;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EventCommunicationService
{
    /**
     * Envoyer email confirmation d'inscription + ticket PDF / QR.
     */
    public function sendRegistrationConfirmed(EventRegistration $registration): void
    {
        $registration->loadMissing(['event', 'ticketType']);

        if (empty($registration->email)) {
            Log::warning("EventCommunication: email manquant pour registration #{$registration->id}");
            return;
        }

        try {
            Mail::to($registration->email)->send(new EventRegistrationConfirmed($registration));
        } catch (\Throwable $e) {
            Log::error("EventCommunication: échec envoi ticket #{$registration->id} — {$e->getMessage()}");
            report($e);
        }
    }

    /**
     * Envoyer rappel événement.
     */
    public function sendReminder(EventRegistration $registration, int $daysBefore): void
    {
        // À brancher plus tard (commande EventRemindersCommand)
    }

    /**
     * Notifier promotion liste d'attente.
     */
    public function sendWaitlistPromoted(EventWaitlist $waitlist): void
    {
        // À brancher plus tard
    }

    /**
     * Confirmation commande merchandise.
     */
    public function sendOrderConfirmed(EventOrder $order): void
    {
        // À brancher plus tard
    }

    /**
     * Notifier admin seuil critique.
     */
    public function notifyAdminCapacityThreshold(EventRegistration $registration): void
    {
        // Notification admin via email ou dashboard
    }
}
