<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventCheckIn;
use App\Models\EventRegistration;
use App\Models\EventTicketType;
use App\Models\EventWaitlist;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class EventRegistrationService
{
    /**
     * Inscrire un utilisateur à un événement.
     * Gère la capacité, la liste d'attente et la génération QR.
     */
    public function register(User $user, Event $event, array $data, ?EventTicketType $ticketType = null): EventRegistration
    {
        return DB::transaction(function () use ($user, $event, $data, $ticketType) {
            // Vérifier doublon actif
            $existing = EventRegistration::where('event_id', $event->id)
                ->where('user_id', $user->id)
                ->whereNotIn('status', ['cancelled','no_show'])
                ->first();

            if ($existing) {
                throw new \Exception('Vous êtes déjà inscrit à cet événement.');
            }

            $seatAvailable = $event->seatsRemaining() > 0;
            if (!$seatAvailable) {
                $this->addToWaitlist($event, $user, $data['email'] ?? $user->email, $data['phone'] ?? null);
                throw new \Exception('Les places sont épuisées. Vous avez été ajouté à la liste d\'attente.');
            }

            $registration = EventRegistration::create([
                'event_id' => $event->id,
                'user_id' => $user->id,
                'ticket_type_id' => $ticketType?->id,
                'first_name' => $data['first_name'] ?? $user->name,
                'last_name' => $data['last_name'] ?? '',
                'email' => $data['email'] ?? $user->email,
                'phone' => $data['phone'] ?? null,
                'institution_name' => $data['institution_name'] ?? null,
                'job_title' => $data['job_title'] ?? null,
                't_shirt_size' => $data['t_shirt_size'] ?? null,
                'medical_notes' => $data['medical_notes'] ?? null,
                'emergency_contact_name' => $data['emergency_contact_name'] ?? null,
                'emergency_contact_phone' => $data['emergency_contact_phone'] ?? null,
                'status' => $ticketType && $ticketType->price > 0 ? 'registered' : 'confirmed',
                'qr_code' => $this->generateQrCodeString(),
                'source' => $data['source'] ?? 'web',
            ]);

            $event->increment('registration_count');
            if ($ticketType) {
                $ticketType->increment('sold');
            }

            return $registration;
        });
    }

    /**
     * Confirmer une inscription après paiement réussi.
     */
    public function confirmRegistration(EventRegistration $registration): void
    {
        if ($registration->status === 'registered') {
            $registration->update(['status' => 'confirmed']);
        }
    }

    /**
     * Check-in un participant.
     */
    public function checkIn(EventRegistration $registration, User $operator, string $method = 'qr_scan', ?string $deviceId = null): EventCheckIn
    {
        if ($registration->isCheckedIn()) {
            throw new \Exception('Ce participant est déjà enregistré.');
        }

        return DB::transaction(function () use ($registration, $operator, $method, $deviceId) {
            $registration->update(['status' => 'checked_in', 'checked_in_at' => now()]);

            return EventCheckIn::create([
                'registration_id' => $registration->id,
                'checked_in_by' => $operator->id,
                'method' => $method,
                'device_id' => $deviceId,
                'checked_in_at' => now(),
            ]);
        });
    }

    /**
     * Annuler une inscription et libérer la place.
     */
    public function cancel(EventRegistration $registration, ?string $reason = null): void
    {
        DB::transaction(function () use ($registration, $reason) {
            $registration->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancellation_reason' => $reason,
            ]);

            $event = $registration->event;
            $event->decrement('registration_count');

            if ($registration->ticket_type_id) {
                $registration->ticketType?->decrement('sold');
            }

            $this->promoteWaitlist($event);
        });
    }

    /**
     * Ajouter à la liste d'attente.
     */
    public function addToWaitlist(Event $event, ?User $user, string $email, ?string $phone = null): EventWaitlist
    {
        $position = EventWaitlist::where('event_id', $event->id)->where('status', 'waiting')->max('position') ?? 0;
        $position++;

        return EventWaitlist::create([
            'event_id' => $event->id,
            'user_id' => $user?->id,
            'email' => $email,
            'phone' => $phone,
            'status' => 'waiting',
            'position' => $position,
        ]);
    }

    /**
     * Promouvoir la liste d'attente si place libérée.
     */
    public function promoteWaitlist(Event $event): void
    {
        if ($event->seatsRemaining() <= 0) return;

        $next = EventWaitlist::waiting()->where('event_id', $event->id)->first();
        if (!$next) return;

        $next->update(['status' => 'converted', 'notified_at' => now()]);
        // Envoi notification ici (délégué au CommunicationService)
        Log::info("Waitlist promoted for event {$event->id}, user {$next->email}");
    }

    /**
     * Générer un QR code string unique signé.
     */
    public function generateQrCodeString(): string
    {
        $raw = Str::random(16) . time();
        $secret = config('app.key');
        return hash_hmac('sha256', $raw, $secret) . '-' . substr($raw, 0, 8);
    }

    /**
     * Générer le ticket PDF.
     */
    public function generateTicketPdf(EventRegistration $registration)
    {
        $pdf = Pdf::loadView('events.ticket', [
            'registration' => $registration,
            'event' => $registration->event,
            'user' => $registration->user,
        ]);
        $pdf->setPaper('A4', 'portrait');
        return $pdf;
    }

    /**
     * Valider un QR code et retourner l'inscription.
     */
    public function findByQr(string $qrCode): ?EventRegistration
    {
        return EventRegistration::where('qr_code', $qrCode)
            ->whereIn('status', ['registered','confirmed'])
            ->first();
    }
}
