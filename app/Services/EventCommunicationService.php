<?php

namespace App\Services;

use App\Models\EventOrder;
use App\Models\EventRegistration;
use App\Models\EventWaitlist;
use Illuminate\Support\Facades\Mail;

class EventCommunicationService
{
    /**
     * Envoyer email confirmation d'inscription.
     */
    public function sendRegistrationConfirmed(EventRegistration $registration): void
    {
        // Placeholder : implémenter Mailables/Event Mails
        // Mail::to($registration->email)->send(new EventRegistrationConfirmed($registration));
    }

    /**
     * Envoyer rappel événement.
     */
    public function sendReminder(EventRegistration $registration, int $daysBefore): void
    {
        // Mail::to($registration->email)->send(new EventReminder($registration, $daysBefore));
    }

    /**
     * Notifier promotion liste d'attente.
     */
    public function sendWaitlistPromoted(EventWaitlist $waitlist): void
    {
        // Mail::to($waitlist->email)->send(new EventWaitlistPromoted($waitlist));
    }

    /**
     * Confirmation commande merchandise.
     */
    public function sendOrderConfirmed(EventOrder $order): void
    {
        // Mail::to($order->user->email)->send(new EventOrderConfirmed($order));
    }

    /**
     * Notifier admin seuil critique.
     */
    public function notifyAdminCapacityThreshold(EventRegistration $registration): void
    {
        // Notification admin via email ou dashboard
    }
}
