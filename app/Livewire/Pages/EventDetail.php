<?php

namespace App\Livewire\Pages;

use App\Livewire\Concerns\WithCustomerPayment;
use App\Livewire\Concerns\WithSweetAlert;
use App\Models\Event;
use App\Models\EventOrder;
use App\Models\EventOrderItem;
use App\Models\EventProductVariant;
use App\Models\EventRegistration;
use App\Services\EventCommunicationService;
use App\Services\EventPaymentService;
use App\Services\EventRegistrationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EventDetail extends Component
{
    use WithCustomerPayment;
    use WithSweetAlert;

    public Event $event;

    public $selectedTicketTypeId = null;

    public $showRegistrationModal = false;

    /** @var int 1 formule | 2 infos | 3 boutique | 4 paiement */
    public int $regStep = 1;

    // Infos participant
    public $first_name = '';

    public $last_name = '';

    public $email = '';

    public $phone = '';

    public $institution_name = '';

    public $job_title = '';

    public $t_shirt_size = '';

    public $medical_notes = '';

    public $emergency_contact_name = '';

    public $emergency_contact_phone = '';

    // Infos participant — passerelle résolue via WithCustomerPayment

    /**
     * Panier boutique : [variant_id => quantity]
     *
     * @var array<int|string, int>
     */
    public array $cart = [];

    // Commande produit seule (hors inscription)
    public $showProductModal = false;

    public $selectedProductId = null;

    public $selectedVariantId = null;

    public $productQuantity = 1;

    public $productFirstName = '';

    public $productLastName = '';

    public $productEmail = '';

    public $productPhone = '';

    public $pendingRegistrationId = null;

    public $pendingOrderId = null;

    protected $listeners = [
        'paymentSuccess' => 'handlePaymentSuccess',
        'paymentWidgetFailed' => 'cancelPendingPayment',
    ];

    public function mount($slug): void
    {
        $this->event = Event::where('slug', $slug)
            ->with([
                'ticketTypes' => fn ($q) => $q->where('is_active', true)->orderBy('display_order'),
                'programItems' => fn ($q) => $q->orderBy('display_order')->orderBy('starts_at'),
                'speakers' => fn ($q) => $q->orderBy('display_order'),
                'documents' => fn ($q) => $q->where('is_downloadable', true)->orderBy('display_order'),
                'sponsors', 'galleries',
                'products' => fn ($q) => $q->where('is_active', true)->with('variants'),
            ])
            ->firstOrFail();

        if (Auth::check()) {
            $parts = preg_split('/\s+/', trim(Auth::user()->name ?? ''), 2);
            $this->first_name = $parts[0] ?? Auth::user()->name;
            $this->last_name = $parts[1] ?? '';
            $this->email = Auth::user()->email;
            $this->phone = Auth::user()->phone ?? '';
            $this->productFirstName = $this->first_name;
            $this->productLastName = $this->last_name;
            $this->productEmail = $this->email;
        }

        if ($this->event->requiresTicketSelection() && $this->event->ticketTypes->count() === 1) {
            $this->selectedTicketTypeId = $this->event->ticketTypes->first()->id;
        }
    }

    public function selectTicket($ticketTypeId): void
    {
        $this->selectedTicketTypeId = (int) $ticketTypeId;
    }

    public function openRegistrationModal(?int $ticketTypeId = null): void
    {
        if (! $this->event->isRegistrationOpen()) {
            $this->swalError('Les inscriptions sont fermées pour cet événement.');

            return;
        }

        if ($this->event->usesTickets() && ! $this->event->requiresTicketSelection()) {
            $this->swalError('Configurez au moins un type de billet (gratuit ou payant) pour ouvrir les inscriptions.');

            return;
        }

        $this->cart = [];
        $this->resetValidation();

        if ($ticketTypeId) {
            $this->selectedTicketTypeId = $ticketTypeId;
        }

        $ticketCount = $this->event->ticketTypes->count();
        if ($this->event->requiresTicketSelection() && $ticketCount > 1 && ! $this->selectedTicketTypeId) {
            $this->regStep = 1;
        } elseif ($this->event->requiresTicketSelection() && $ticketCount === 1) {
            $this->selectedTicketTypeId = $this->event->ticketTypes->first()->id;
            $this->regStep = 2;
        } elseif ($this->event->requiresTicketSelection() && $this->selectedTicketTypeId) {
            $this->regStep = 2; // Payer sur un tarif → on passe aux infos
        } else {
            $this->regStep = 2; // Pas de billets configurés → infos
        }

        $this->showRegistrationModal = true;
    }

    public function closeRegistrationModal(): void
    {
        $this->showRegistrationModal = false;
        $this->regStep = 1;
        $this->resetValidation();
    }

    public function nextStep(): void
    {
        if ($this->regStep === 1) {
            if ($this->event->requiresTicketSelection() && ! $this->selectedTicketTypeId) {
                $this->addError('selectedTicketTypeId', 'Veuillez choisir une formule.');

                return;
            }
            $this->regStep = 2;

            return;
        }

        if ($this->regStep === 2) {
            $this->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'nullable|string|max:50',
            ], [
                'first_name.required' => 'Le prénom est obligatoire.',
                'last_name.required' => 'Le nom est obligatoire.',
                'email.required' => 'L\'email est obligatoire.',
            ]);

            $this->regStep = $this->event->products->isNotEmpty() ? 3 : 4;

            return;
        }

        if ($this->regStep === 3) {
            $this->regStep = 4;

            return;
        }

        // L'etape 4 lance le paiement via le bouton Payer.
    }

    public function prevStep(): void
    {
        if ($this->regStep <= 1) {
            return;
        }

        if ($this->regStep === 4 && $this->event->products->isEmpty()) {
            $this->regStep = 2;

            return;
        }

        if ($this->regStep === 2 && (! $this->event->requiresTicketSelection() || $this->event->ticketTypes->count() <= 1)) {
            return;
        }

        $this->regStep--;
    }

    public function addToCart(int $variantId): void
    {
        $variant = EventProductVariant::with('product')->find($variantId);
        if (! $variant || $variant->product?->event_id !== $this->event->id) {
            return;
        }
        if (! $variant->isAvailable(1)) {
            $this->swalError('Stock insuffisant.');

            return;
        }

        $qty = (int) ($this->cart[$variantId] ?? 0);
        if (! $variant->isAvailable($qty + 1)) {
            $this->swalError('Stock insuffisant pour cette variante.');

            return;
        }

        $this->cart[$variantId] = $qty + 1;
    }

    public function removeFromCart(int $variantId): void
    {
        unset($this->cart[$variantId]);
        $this->cart = $this->cart;
    }

    public function setCartQty(int $variantId, int $qty): void
    {
        if ($qty < 1) {
            $this->removeFromCart($variantId);

            return;
        }
        $variant = EventProductVariant::find($variantId);
        if (! $variant || ! $variant->isAvailable($qty)) {
            $this->swalError('Stock insuffisant.');

            return;
        }
        $this->cart[$variantId] = $qty;
    }

    public function cartLines(): array
    {
        $lines = [];
        foreach ($this->cart as $variantId => $qty) {
            $variant = EventProductVariant::with('product')->find($variantId);
            if (! $variant) {
                continue;
            }
            $unit = $variant->effectivePrice();
            $lines[] = [
                'variant_id' => (int) $variantId,
                'name' => ($variant->product?->name ?? 'Article').' — '.$variant->variant_name.($variant->size ? ' ('.$variant->size.')' : ''),
                'qty' => (int) $qty,
                'unit' => $unit,
                'total' => $unit * (int) $qty,
            ];
        }

        return $lines;
    }

    public function ticketPrice(): float
    {
        if (! $this->selectedTicketTypeId) {
            return 0;
        }
        $tt = $this->event->ticketTypes->firstWhere('id', (int) $this->selectedTicketTypeId);

        return (float) ($tt?->price ?? 0);
    }

    public function cartTotal(): float
    {
        return collect($this->cartLines())->sum('total');
    }

    public function grandTotal(): float
    {
        return $this->ticketPrice() + $this->cartTotal();
    }

    public function submitRegistration(EventRegistrationService $service, EventCommunicationService $comms): void
    {
        if ($this->event->requiresTicketSelection()) {
            $this->validate([
                'selectedTicketTypeId' => 'required|exists:event_ticket_types,id',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
            ]);
        } else {
            $this->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
            ]);
        }

        $ticketType = $this->event->requiresTicketSelection()
            ? $this->event->ticketTypes->firstWhere('id', (int) $this->selectedTicketTypeId)
            : null;

        if ($this->event->requiresTicketSelection() && ! $ticketType) {
            $this->swalError('Formule invalide.');

            return;
        }

        $data = [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'institution_name' => $this->institution_name,
            'job_title' => $this->job_title,
            't_shirt_size' => $this->t_shirt_size,
            'medical_notes' => $this->medical_notes,
            'emergency_contact_name' => $this->emergency_contact_name,
            'emergency_contact_phone' => $this->emergency_contact_phone,
            'source' => 'web',
            'requires_payment' => $this->grandTotal() > 0,
        ];

        try {
            $total = $this->grandTotal();

            if ($total > 0) {
                $gateway = $this->resolvePaymentGateway();
                if (! $gateway) {
                    $this->swalError('Paiement indisponible pour le moment. Aucune passerelle n’est configurée — votre inscription n’a pas été créée.');

                    return;
                }
            }

            $registration = $service->register(Auth::user(), $this->event, $data, $ticketType);
            $this->pendingRegistrationId = $registration->id;

            $order = null;
            if ($registration->isAwaitingPayment() || $total > 0) {
                $order = $this->createCartOrder($registration);
                if ($order) {
                    $this->pendingOrderId = $order->id;
                }
            }

            // Recalcule au cas où le panier a changé
            $total = $this->ticketPrice() + $this->cartTotal();
            if ($order) {
                $total = $this->ticketPrice() + (float) $order->total;
            }

            if ($total <= 0) {
                if ($registration->isAwaitingPayment()) {
                    $service->confirmRegistration($registration);
                } else {
                    $comms->sendRegistrationConfirmed($registration);
                }
                $this->showRegistrationModal = false;
                $this->redirect(route('event.ticket.public', $registration->qr_code), navigate: true);

                return;
            }

            // Paiement FeexPay: l'inscription reste pending_payment jusqu'au callback.
            $reference = 'EVNTR-'.$registration->id.'-'.time();
            $meta = $this->paymentWidgetMeta();
            if (empty($meta['provider'])) {
                $service->cancel($registration, 'Passerelle de paiement indisponible');
                if ($order) {
                    $order->update(['status' => 'cancelled']);
                }
                $this->pendingRegistrationId = null;
                $this->pendingOrderId = null;
                $this->swalError('Impossible d’ouvrir le paiement. Réessayez plus tard.');

                return;
            }

            $this->dispatch('openPaymentWidget', array_merge($meta, [
                'amount' => (int) round($total),
                'reference' => $reference,
                'email' => $this->email,
                'name' => trim($this->first_name.' '.$this->last_name),
                'phone' => $this->phone,
                'registration_id' => $registration->id,
                'order_id' => $order?->id,
                'formation' => $this->event->title,
                'callback_info' => [
                    'type' => 'event_registration',
                    'reference' => $reference,
                    'registration_id' => $registration->id,
                    'order_id' => $order?->id,
                    'event_id' => $this->event->id,
                ],
            ]));
        } catch (\Exception $e) {
            $this->swalError($e->getMessage());
        }
    }

    public function cancelPendingPayment(EventRegistrationService $service): void
    {
        if ($this->pendingRegistrationId) {
            $registration = EventRegistration::find($this->pendingRegistrationId);
            if ($registration && $registration->isAwaitingPayment()) {
                $service->cancel($registration, 'Paiement non finalisé / widget indisponible');
            }
            $this->pendingRegistrationId = null;
        }
        if ($this->pendingOrderId) {
            $order = EventOrder::find($this->pendingOrderId);
            if ($order && $order->status === 'pending') {
                $order->update(['status' => 'cancelled']);
            }
            $this->pendingOrderId = null;
        }
        $this->regStep = 4;
        $this->swalError('Le paiement n’a pas pu être lancé. Vous n’êtes pas inscrit — réessayez.');
    }

    protected function createCartOrder(EventRegistration $registration): ?EventOrder
    {
        $lines = $this->cartLines();
        if ($lines === []) {
            return null;
        }

        $subtotal = collect($lines)->sum('total');
        $order = EventOrder::create([
            'event_id' => $this->event->id,
            'user_id' => Auth::id(),
            'registration_id' => $registration->id,
            'order_number' => 'EVNTORD-'.strtoupper(uniqid()),
            'subtotal' => $subtotal,
            'tax' => 0,
            'total' => $subtotal,
            'currency' => 'XOF',
            'status' => 'pending',
            'notes' => 'Inscription #'.$registration->id.' — '.$this->first_name.' '.$this->last_name,
        ]);

        foreach ($lines as $line) {
            $variant = EventProductVariant::find($line['variant_id']);
            EventOrderItem::create([
                'order_id' => $order->id,
                'product_id' => $variant?->product_id,
                'variant_id' => $line['variant_id'],
                'product_name' => $line['name'],
                'quantity' => $line['qty'],
                'unit_price' => $line['unit'],
                'total_price' => $line['total'],
            ]);
        }

        return $order;
    }

    /* —— Boutique seule (hors inscription) —— */

    public function openProductModal($productId): void
    {
        $this->selectedProductId = $productId;
        $this->selectedVariantId = null;
        $this->productQuantity = 1;
        $this->showProductModal = true;

        if (Auth::check()) {
            $parts = preg_split('/\s+/', trim(Auth::user()->name ?? ''), 2);
            $this->productFirstName = $parts[0] ?? Auth::user()->name;
            $this->productLastName = $parts[1] ?? '';
            $this->productEmail = Auth::user()->email;
        }
    }

    public function closeProductModal(): void
    {
        $this->showProductModal = false;
        $this->resetValidation();
    }

    public function submitProductOrder(): void
    {
        $this->validate([
            'productFirstName' => 'required|string|max:255',
            'productLastName' => 'required|string|max:255',
            'productEmail' => 'required|email',
            'productPhone' => 'nullable|string|max:50',
            'selectedVariantId' => 'required|exists:event_product_variants,id',
            'productQuantity' => 'required|integer|min:1|max:10',
        ], [
            'productFirstName.required' => 'Le prénom est obligatoire.',
            'productLastName.required' => 'Le nom est obligatoire.',
            'productEmail.required' => 'L\'email est obligatoire.',
            'selectedVariantId.required' => 'Veuillez choisir une variante.',
        ]);

        $variant = EventProductVariant::with('product')->find($this->selectedVariantId);
        if (! $variant || ! $variant->isAvailable($this->productQuantity)) {
            $this->swalError('Stock insuffisant pour cette variante.');

            return;
        }

        $product = $variant->product;
        $unitPrice = $variant->effectivePrice();
        $total = $unitPrice * $this->productQuantity;

        $order = EventOrder::create([
            'event_id' => $this->event->id,
            'user_id' => Auth::id(),
            'order_number' => 'EVNTORD-'.strtoupper(uniqid()),
            'subtotal' => $total,
            'tax' => 0,
            'total' => $total,
            'currency' => 'XOF',
            'status' => 'pending',
            'notes' => 'Commande par '.trim($this->productFirstName.' '.$this->productLastName).' ('.$this->productEmail.')',
        ]);

        EventOrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'variant_id' => $variant->id,
            'product_name' => $product->name.' — '.$variant->variant_name.($variant->size ? ' ('.$variant->size.')' : ''),
            'quantity' => $this->productQuantity,
            'unit_price' => $unitPrice,
            'total_price' => $total,
        ]);

        $this->pendingOrderId = $order->id;

        $meta = $this->paymentWidgetMeta();
        if (empty($meta['provider'])) {
            $order->update(['status' => 'cancelled']);
            $this->pendingOrderId = null;
            $this->swalError('Paiement indisponible. La commande n’a pas été finalisée.');

            return;
        }

        $this->dispatch('openPaymentWidget', array_merge($meta, [
            'amount' => (int) round($total),
            'reference' => $order->order_number,
            'email' => $this->productEmail,
            'name' => $this->productFirstName.' '.$this->productLastName,
            'phone' => $this->productPhone,
            'order_id' => $order->id,
            'formation' => $product->name,
            'callback_info' => [
                'type' => 'event_order',
                'reference' => $order->order_number,
                'order_id' => $order->id,
                'event_id' => $this->event->id,
            ],
        ]));

        $this->showProductModal = false;
    }

    public function handlePaymentSuccess($payload, EventPaymentService $paymentService): void
    {
        $data = is_array($payload) && isset($payload[0]) ? $payload[0] : $payload;
        $status = strtoupper((string) ($data['status'] ?? ''));
        $ok = in_array($status, ['SUCCESS', 'SUCCESSFUL', 'APPROVED', 'COMPLETED'], true);
        // FedaPay renvoie parfois reason=SUCCESSFUL sans status
        if (! $ok && strtoupper((string) ($data['reason'] ?? '')) === 'SUCCESSFUL') {
            $ok = true;
        }
        if (! $ok) {
            $this->swalError('Paiement non validé. Vous n’êtes pas inscrit.');

            return;
        }

        $reference = (string) ($data['reference'] ?? '');
        $registrationId = $data['registration_id'] ?? $this->pendingRegistrationId;
        $orderId = $data['order_id'] ?? $this->pendingOrderId;

        if (str_starts_with($reference, 'EVNTORD-') && ! str_starts_with($reference, 'EVNTR-')) {
            if (! $orderId && preg_match('/EVNTORD-.*?-(\d+)$/', $reference, $m)) {
                $orderId = (int) $m[1];
            }
            $paymentService->handlePaymentSuccess($reference, [
                'order_id' => $orderId,
                'transaction_id' => $data['transactionId'] ?? null,
                'status' => $data['status'] ?? 'SUCCESS',
            ]);
            $this->pendingOrderId = null;
            $this->showRegistrationModal = false;
            if ($orderId) {
                $order = EventOrder::find($orderId);
                if ($order) {
                    $this->redirect(route('event.order.confirmation', $order->order_number), navigate: true);

                    return;
                }
            }
            $this->swalSuccess('Paiement boutique confirmé.');

            return;
        }

        if (! $registrationId && preg_match('/EVNTR-(\d+)/', $reference, $m)) {
            $registrationId = (int) $m[1];
        }

        if (! $registrationId) {
            $this->swalError('Paiement reçu mais inscription introuvable.');

            return;
        }

        $paymentService->handlePaymentSuccess($reference, [
            'registration_id' => $registrationId,
            'transaction_id' => $data['transactionId'] ?? null,
            'status' => $data['status'] ?? 'SUCCESS',
        ]);

        if ($orderId) {
            $order = EventOrder::find($orderId);
            if ($order && $order->status === 'pending') {
                $paymentService->handlePaymentSuccess($order->order_number, [
                    'order_id' => $order->id,
                    'transaction_id' => $data['transactionId'] ?? null,
                    'status' => $data['status'] ?? 'SUCCESS',
                ]);
            }
        }

        $registration = EventRegistration::find($registrationId);
        $this->pendingRegistrationId = null;
        $this->pendingOrderId = null;
        $this->showRegistrationModal = false;

        if ($registration) {
            $this->redirect(route('event.ticket.public', $registration->qr_code), navigate: true);
        }
    }

    public function getIsUserRegisteredProperty(): bool
    {
        if (! Auth::check()) {
            return false;
        }

        return $this->event->registrations()
            ->where('user_id', Auth::id())
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->exists();
    }

    public function render()
    {
        return view('livewire.pages.event-detail', [
            'isRegistered' => $this->getIsUserRegisteredProperty(),
            'publicUrl' => $this->event->publicUrl(),
            'cartLines' => $this->cartLines(),
            'ticketPrice' => $this->ticketPrice(),
            'cartTotal' => $this->cartTotal(),
            'grandTotal' => $this->grandTotal(),
            'selectedTicket' => $this->selectedTicketTypeId
                ? $this->event->ticketTypes->firstWhere('id', (int) $this->selectedTicketTypeId)
                : null,
        ])->extends('layouts.site', ['title' => $this->event->title])->section('content');
    }
}
