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
    public function register(?User $user, Event $event, array $data, ?EventTicketType $ticketType = null): EventRegistration
    {
        return DB::transaction(function () use ($user, $event, $data, $ticketType) {
            // Vérifier doublon actif (utilisateur connecté) — hors annulations et paiements abandonnés
            if ($user) {
                $existing = EventRegistration::where('event_id', $event->id)
                    ->where('user_id', $user->id)
                    ->whereIn('status', ['confirmed', 'checked_in', 'pending_payment', 'registered'])
                    ->first();

                if ($existing) {
                    if (in_array($existing->status, ['pending_payment', 'registered'], true)) {
                        // Reprendre le paiement en attente plutôt que de bloquer
                        return $existing;
                    }
                    throw new \Exception('Vous êtes déjà inscrit à cet événement.');
                }
            }

            // Doublon par email (invité ou compte)
            $email = $data['email'] ?? ($user?->email ?? '');
            if ($email !== '') {
                $existingEmail = EventRegistration::where('event_id', $event->id)
                    ->where('email', $email)
                    ->whereIn('status', ['confirmed', 'checked_in', 'pending_payment', 'registered'])
                    ->first();

                if ($existingEmail) {
                    if (in_array($existingEmail->status, ['pending_payment', 'registered'], true)
                        && (! $user || (int) $existingEmail->user_id === (int) $user->id)) {
                        return $existingEmail;
                    }
                    throw new \Exception('Une inscription existe déjà avec cet email pour cet événement.');
                }
            }

            $seatAvailable = $event->seatsRemaining() > 0;
            if (!$seatAvailable) {
                $this->addToWaitlist($event, $user, $email, $data['phone'] ?? null);
                throw new \Exception('Les places sont épuisées. Vous avez été ajouté à la liste d\'attente.');
            }

            $isPaid = ($ticketType && (float) $ticketType->price > 0)
                || ! empty($data['requires_payment']);

            $registration = EventRegistration::create([
                'event_id' => $event->id,
                'user_id' => $user?->id,
                'ticket_type_id' => $ticketType?->id,
                'first_name' => $data['first_name'] ?? ($user?->name ?? ''),
                'last_name' => $data['last_name'] ?? '',
                'email' => $email,
                'phone' => $data['phone'] ?? null,
                'institution_name' => $data['institution_name'] ?? null,
                'job_title' => $data['job_title'] ?? null,
                't_shirt_size' => $data['t_shirt_size'] ?? null,
                'medical_notes' => $data['medical_notes'] ?? null,
                'emergency_contact_name' => $data['emergency_contact_name'] ?? null,
                'emergency_contact_phone' => $data['emergency_contact_phone'] ?? null,
                // Payant : en attente jusqu'au callback de paiement réel
                'status' => $isPaid ? 'pending_payment' : 'confirmed',
                'qr_code' => $this->generateQrCodeString(),
                'source' => $data['source'] ?? 'web',
            ]);

            // Ne consomme la place qu'à la confirmation (gratuit immédiat / payant après paiement)
            if (! $isPaid) {
                $event->increment('registration_count');
                if ($ticketType) {
                    $ticketType->increment('sold');
                }
            }

            return $registration;
        });
    }

    /**
     * Confirmer une inscription après paiement réussi.
     */
    public function confirmRegistration(EventRegistration $registration): void
    {
        if (in_array($registration->status, ['confirmed', 'checked_in'], true)) {
            app(EventCommunicationService::class)->sendRegistrationConfirmed($registration->fresh(['event', 'ticketType']));

            return;
        }

        if (! in_array($registration->status, ['pending_payment', 'registered'], true)) {
            return;
        }

        DB::transaction(function () use ($registration) {
            $needsSeat = $registration->status === 'pending_payment';
            $registration->update(['status' => 'confirmed']);

            if ($needsSeat) {
                $event = $registration->event()->lockForUpdate()->first();
                $event?->increment('registration_count');
                if ($registration->ticket_type_id) {
                    $registration->ticketType()?->lockForUpdate()->increment('sold');
                }
            }
        });

        app(EventCommunicationService::class)->sendRegistrationConfirmed($registration->fresh(['event', 'ticketType']));
    }

    /**
     * Check-in un participant.
     */
    public function checkIn(EventRegistration $registration, User $operator, string $method = 'qr_scan', ?string $deviceId = null): EventCheckIn
    {
        if ($registration->isCheckedIn()) {
            throw new \Exception('Ce participant est déjà enregistré.');
        }

        if ($registration->status === 'registered' || $registration->status === 'pending_payment') {
            throw new \Exception('Inscription non confirmée (paiement en attente).');
        }

        if (!in_array($registration->status, ['confirmed'], true)) {
            throw new \Exception('Cette inscription ne peut pas être émargée.');
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
            // Les pending_payment n'ont pas encore consommé de place
            $seatWasTaken = in_array($registration->status, ['confirmed', 'checked_in', 'registered'], true);

            $registration->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancellation_reason' => $reason,
            ]);

            if ($seatWasTaken) {
                $event = $registration->event;
                $event->decrement('registration_count');

                if ($registration->ticket_type_id) {
                    $registration->ticketType?->decrement('sold');
                }

                $this->promoteWaitlist($event);
            }
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
     * Valider un QR code et retourner l'inscription (hors annulées).
     */
    public function findByQr(string $qrCode): ?EventRegistration
    {
        $code = trim($qrCode);

        // Accepte aussi une URL de ticket publique collée / scannée
        if (preg_match('#/evenements/ticket/([^/?#]+)#', $code, $m)) {
            $code = urldecode($m[1]);
        }

        return EventRegistration::where('qr_code', $code)
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->first();
    }
}
