<?php

namespace App\Services;

use App\Mail\EventRegistrationConfirmed;
use App\Mail\EventReminderNotification;
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
        $registration->loadMissing(['event']);

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
        $registration->loadMissing(['event']);

        if (! in_array($daysBefore, [1, 7], true)) {
            Log::warning("EventCommunication: rappel J-{$daysBefore} non supporté pour registration #{$registration->id}");

            return;
        }

        if (empty($registration->email)) {
            Log::warning("EventCommunication: email manquant pour rappel registration #{$registration->id}");

            return;
        }

        $field = $daysBefore === 7 ? 'reminder_7_days_sent_at' : 'reminder_1_day_sent_at';
        if ($registration->{$field}) {
            return;
        }

        try {
            Mail::to($registration->email)->send(new EventReminderNotification($registration, $daysBefore));
            $registration->forceFill([$field => now()])->save();
        } catch (\Throwable $e) {
            Log::error("EventCommunication: échec rappel J-{$daysBefore} registration #{$registration->id} — {$e->getMessage()}");
            report($e);
        }
    }

    /**
     * Notifier promotion liste d'attente.
     */
    public function sendWaitlistPromoted(EventWaitlist $waitlist): void
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
