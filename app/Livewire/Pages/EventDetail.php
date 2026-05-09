<?php

namespace App\Livewire\Pages;

use App\Models\Event;
use App\Services\EventRegistrationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EventDetail extends Component
{
    public Event $event;
    public $selectedTicketTypeId = null;
    public $showRegistrationModal = false;

    // Formulaire inscription
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
    public $paymentProvider = 'kkiapay';

    // Commande produits
    public $showProductModal = false;
    public $selectedProductId = null;
    public $selectedVariantId = null;
    public $productQuantity = 1;
    public $productFirstName = '';
    public $productLastName = '';
    public $productEmail = '';
    public $productPhone = '';

    public function mount($slug)
    {
        $this->event = Event::where('slug', $slug)
            ->with([
                'ticketTypes' => fn($q) => $q->where('is_active', true),
                'programItems', 'speakers', 'sponsors', 'documents', 'galleries',
                'products' => fn($q) => $q->where('is_active', true)->with('variants'),
            ])
            ->firstOrFail();

        if (Auth::check()) {
            $this->first_name = Auth::user()->name;
            $this->email = Auth::user()->email;
            $this->phone = Auth::user()->phone;
        }
    }

    public function selectTicket($ticketTypeId)
    {
        $this->selectedTicketTypeId = $ticketTypeId;
    }

    public function openRegistrationModal()
    {
        if (!$this->event->isRegistrationOpen()) {
            session()->flash('error', 'Les inscriptions sont fermées pour cet événement.');
            return;
        }

        $this->showRegistrationModal = true;
    }

    public function closeRegistrationModal()
    {
        $this->showRegistrationModal = false;
        $this->resetValidation();
    }

    public function openProductModal($productId)
    {
        $this->selectedProductId = $productId;
        $this->selectedVariantId = null;
        $this->productQuantity = 1;
        $this->showProductModal = true;

        if (Auth::check()) {
            $this->productFirstName = Auth::user()->name;
            $this->productEmail = Auth::user()->email;
        }
    }

    public function closeProductModal()
    {
        $this->showProductModal = false;
        $this->resetValidation();
    }

    public function submitProductOrder()
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

        $variant = \App\Models\EventProductVariant::with('product')->find($this->selectedVariantId);
        if (!$variant || !$variant->isAvailable($this->productQuantity)) {
            session()->flash('error', 'Stock insuffisant pour cette variante.');
            return;
        }

        $product = $variant->product;
        $unitPrice = $variant->price ?? $product->price;
        $total = $unitPrice * $this->productQuantity;

        $order = \App\Models\EventOrder::create([
            'event_id' => $this->event->id,
            'user_id' => Auth::id(),
            'order_number' => 'EVNTORD-' . strtoupper(uniqid()),
            'subtotal' => $total,
            'tax' => 0,
            'total' => $total,
            'currency' => 'XOF',
            'status' => 'pending',
            'notes' => 'Commande par ' . trim($this->productFirstName . ' ' . $this->productLastName) . ' (' . $this->productEmail . ')',
        ]);

        \App\Models\EventOrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'variant_id' => $variant->id,
            'product_name' => $product->name . ' — ' . $variant->variant_name . ($variant->size ? ' (' . $variant->size . ')' : ''),
            'quantity' => $this->productQuantity,
            'unit_price' => $unitPrice,
            'total_price' => $total,
        ]);

        $this->dispatch('openPaymentWidget', [
            'provider' => $this->paymentProvider,
            'amount' => (int) $total,
            'reference' => $order->order_number,
            'email' => $this->productEmail,
            'name' => $this->productFirstName . ' ' . $this->productLastName,
            'phone' => $this->productPhone,
            'formation' => $product->name,
        ]);

        $this->showProductModal = false;

        // Redirection vers la page de confirmation
        return $this->redirect(route('event.order.confirmation', $order->order_number), navigate: true);
    }

    public function submitRegistration(EventRegistrationService $service)
    {
        $ticketType = $this->event->ticketTypes->firstWhere('id', $this->selectedTicketTypeId);

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
        ];

        try {
            $registration = $service->register(Auth::user(), $this->event, $data, $ticketType);

            if ($ticketType && $ticketType->price > 0) {
                $this->dispatch('initiatePayment', [
                    'provider' => $this->paymentProvider,
                    'amount' => (int) $ticketType->price,
                    'reference' => 'EVNTR-' . $registration->id . '-' . time(),
                    'email' => $this->email,
                    'name' => $this->first_name . ' ' . $this->last_name,
                    'phone' => $this->phone,
                    'registration_id' => $registration->id,
                ]);
            } else {
                session()->flash('success', 'Inscription confirmée ! <a href="' . route('event.ticket.public', $registration->qr_code) . '" class="underline font-bold">Télécharger mon ticket</a>');
            }

            $this->showRegistrationModal = false;
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function getIsUserRegisteredProperty(): bool
    {
        if (!Auth::check()) return false;
        return $this->event->registrations()
            ->where('user_id', Auth::id())
            ->whereNotIn('status', ['cancelled','no_show'])
            ->exists();
    }

    public function render()
    {
        return view('livewire.pages.event-detail', [
            'isRegistered' => $this->getIsUserRegisteredProperty(),
        ])->extends('layouts.site', ['title' => $this->event->title])->section('content');
    }
}
