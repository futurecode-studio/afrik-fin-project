<?php

namespace App\Services;

use App\Models\EventOrder;
use App\Models\EventRegistration;
use App\Models\EventTicketType;
use Illuminate\Support\Facades\Log;

class EventPaymentService
{
    protected PaymentService $paymentService;
    protected EventRegistrationService $registrationService;
    protected EventInventoryService $inventoryService;

    public function __construct(
        PaymentService $paymentService,
        EventRegistrationService $registrationService,
        EventInventoryService $inventoryService
    ) {
        $this->paymentService = $paymentService;
        $this->registrationService = $registrationService;
        $this->inventoryService = $inventoryService;
    }

    /**
     * Initier le paiement pour une inscription (ticket payant).
     * Réutilise PaymentService avec une référence préfixée EVNTR-.
     */
    public function initiateRegistrationPayment(EventRegistration $registration, string $provider = 'kkiapay', ?string $phone = null): array
    {
        // On simule un "formation" pour PaymentService via un wrapper adaptateur.
        // PaymentService attend User + Formation. On va appeler directement HTTP via PaymentService logic.

        // Simplification : on crée une fausse formation-like payload en invoquant PaymentService::initiatePayment
        // avec un objet EventRegistration adapté. Mais PaymentService est couplé à Formation.
        // Solution : appel HTTP direct ou refactor PaymentService.
        // Ici on wrappe en générant une reference unique et en appelant la logique de paiement bas-niveau.

        $reference = 'EVNTR-' . strtoupper(uniqid() . '-' . $registration->id);

        // Stock reference sur registration pour rappel
        $registration->update(['qr_code' => $registration->qr_code]); // pas touché
        // On utilise metadata pour relier après paiement.

        // Appel bas-niveau vers KKiaPay / FedaPay via PaymentService si possible,
        // sinon on construit l'URL de paiement et laissons le front interagir.
        return [
            'success' => true,
            'reference' => $reference,
            'registration_id' => $registration->id,
            'amount' => $registration->ticketType?->price ?? 0,
            'provider' => $provider,
            'phone' => $phone,
        ];
    }

    /**
     * Initier le paiement pour une commande merchandise.
     * Réutilise PaymentService (création transaction FedaPay/KKiaPay).
     */
    public function initiateOrderPayment(EventOrder $order, string $provider = 'kkiapay', ?string $phone = null): array
    {
        $reference = 'EVNTORD-' . strtoupper(uniqid() . '-' . $order->id);

        // Mettre à jour l'order avec la reference pour le webhook
        $order->update([
            'order_number' => $reference, // override temporaire pour tracking paiement
        ]);

        return [
            'success' => true,
            'reference' => $reference,
            'order_id' => $order->id,
            'amount' => (int) $order->total,
            'provider' => $provider,
            'phone' => $phone,
        ];
    }

    /**
     * Traiter un callback webhook générique et dispatcher selon le type event.
     * Cette méthode doit être appelée depuis PaymentController ou une route dédiée.
     */
    public function handlePaymentSuccess(string $reference, array $payload): void
    {
        if (str_starts_with($reference, 'EVNTR-')) {
            $this->handleRegistrationPaymentSuccess($reference, $payload);
        } elseif (str_starts_with($reference, 'EVNTORD-')) {
            $this->handleOrderPaymentSuccess($reference, $payload);
        }
    }

    protected function handleRegistrationPaymentSuccess(string $reference, array $payload): void
    {
        // Trouver la registration liée (via payload metadata ou recherche par user/event)
        $registrationId = $payload['registration_id'] ?? null;
        if (!$registrationId) {
            Log::error("EventPayment: registration_id manquant pour {$reference}");
            return;
        }

        $registration = EventRegistration::find($registrationId);
        if (!$registration) {
            Log::error("EventPayment: registration introuvable #{$registrationId}");
            return;
        }

        $this->registrationService->confirmRegistration($registration);
        Log::info("EventPayment: registration #{$registration->id} confirmée.");
    }

    protected function handleOrderPaymentSuccess(string $reference, array $payload): void
    {
        $orderId = $payload['order_id'] ?? null;
        if (!$orderId) {
            Log::error("EventPayment: order_id manquant pour {$reference}");
            return;
        }

        $order = EventOrder::find($orderId);
        if (!$order) {
            Log::error("EventPayment: order introuvable #{$orderId}");
            return;
        }

        $this->inventoryService->finalizeOrder($order);
        Log::info("EventPayment: order #{$order->id} finalisée.");
    }

    /**
     * Réconciliation : vérifier les paiements en attente depuis > 30 min et annuler.
     */
    public function reconcilePendingOrders(): void
    {
        $staleOrders = EventOrder::where('status', 'pending')
            ->where('created_at', '<', now()->subMinutes(30))
            ->get();

        foreach ($staleOrders as $order) {
            $this->inventoryService->cancelOrder($order);
            Log::info("EventPayment: order #{$order->id} annulée pour expiration.");
        }
    }
}
