<?php

namespace App\Livewire\Pages;

use App\Livewire\Concerns\WithCustomerPayment;
use App\Livewire\Concerns\WithSweetAlert;
use App\Models\Formation;
use App\Services\FormationCartService;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Formations extends Component
{
    use WithCustomerPayment;
    use WithSweetAlert;

    public $showPaymentModal = false;

    public $selectedFormation = null;

    public $phone = '';

    public $search = '';

    public $filterNiveau = '';

    public $filterType = '';

    protected $queryString = ['search', 'filterNiveau', 'filterType'];

    protected $listeners = ['paymentSuccess' => 'handlePaymentSuccess'];

    public function addToCart(int $formationId, FormationCartService $cart): void
    {
        if (! Formation::publie()->whereKey($formationId)->exists()) {
            $this->swalError('Formation introuvable.');

            return;
        }
        $cart->add($formationId);
        $this->swalSuccess('Formation ajoutée au panier.');
    }

    public function openPaymentModal($formationId)
    {
        $this->selectedFormation = Formation::find($formationId);

        if (! $this->selectedFormation) {
            $this->swalError('Formation non trouvée.');

            return;
        }

        if (! Auth::check()) {
            session(['intended_formation' => $formationId]);

            return $this->redirect(route('connexion'), navigate: true);
        }

        if ($this->selectedFormation->isFree()) {
            $this->enrollFree();

            return;
        }

        if (Auth::user()->isEnrolledIn($this->selectedFormation)) {
            $this->swalInfo('Vous êtes déjà inscrit à cette formation.');

            return;
        }

        $this->resolvePaymentGateway();
        $this->showPaymentModal = true;
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
        $this->selectedFormation = null;
        $this->phone = '';
        $this->paymentMethod = 'mtn';
        $this->resolvePaymentGateway();
    }

    public function enrollFree()
    {
        if (! Auth::check()) {
            $this->swalInfo('Veuillez vous connecter pour vous inscrire à cette formation.');

            return $this->redirect(route('connexion'), navigate: true);
        }

        $paymentService = app(PaymentService::class);
        $result = $paymentService->enrollForFree(Auth::user(), $this->selectedFormation);

        if ($result['success']) {
            $this->swalSuccess($result['message']);
        } else {
            $this->swalError($result['message'] ?? 'Une erreur est survenue.');
        }

        $this->closePaymentModal();
    }

    public function initiatePayment()
    {
        if (! Auth::check()) {
            $this->swalError('Veuillez vous connecter.');

            return;
        }

        if (! $this->selectedFormation) {
            $this->swalError('Veuillez sélectionner une formation.');

            return;
        }

        $paymentService = app(PaymentService::class);
        $gateway = $this->resolvePaymentGateway();
        if (! $gateway) {
            $this->swalError('Paiement temporairement indisponible. Réessayez plus tard.');

            return;
        }

        $result = $paymentService->initiatePayment(
            Auth::user(),
            $this->selectedFormation,
            $gateway,
            $this->phone ?: null,
            $this->paymentMethod
        );

        if (! $result['success']) {
            $this->swalError($result['message']);

            return;
        }

        $this->dispatch('openPaymentWidget', array_merge($this->paymentWidgetMeta(), [
            'amount' => (int) $result['amount'],
            'reference' => $result['reference'],
            'email' => Auth::user()->email,
            'name' => Auth::user()->name,
            'phone' => $this->phone,
            'formation' => $this->selectedFormation->titre,
            'callback_info' => [
                'type' => 'formation',
                'reference' => $result['reference'],
                'payment_id' => $result['payment']->id ?? null,
                'enrollment_id' => $result['enrollment']->id ?? null,
                'formation_id' => $this->selectedFormation->id,
                'user_id' => Auth::id(),
            ],
        ]));
    }

    public function handlePaymentSuccess($payload)
    {
        $data = is_array($payload) && isset($payload[0]) ? $payload[0] : $payload;
        $status = strtoupper((string) ($data['status'] ?? $data['reason'] ?? ''));
        if (! in_array($status, ['SUCCESS', 'SUCCESSFUL', 'APPROVED', 'COMPLETED'], true)) {
            $this->swalError('Paiement non validé. Vous n’êtes pas inscrit à la formation.');

            return;
        }

        $paymentService = app(PaymentService::class);
        $result = $this->handleGatewayCallback(is_array($data) ? $data : [], $paymentService);

        if ($result['success']) {
            $this->swalSuccess('Paiement réussi ! Vous êtes maintenant inscrit à la formation.');
        } else {
            $this->swalError($result['message'] ?? 'Le paiement a échoué.');
        }

        $this->closePaymentModal();
    }

    public function render()
    {
        $formations = Formation::publie()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('titre', 'like', '%'.$this->search.'%')
                        ->orWhere('description_courte', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->filterNiveau, function ($query) {
                $query->where('niveau', $this->filterNiveau);
            })
            ->when($this->filterType === 'gratuit', function ($query) {
                $query->gratuite();
            })
            ->when($this->filterType === 'payant', function ($query) {
                $query->payante();
            })
            ->orderBy('published_at', 'desc')
            ->get();

        $paymentService = app(PaymentService::class);

        return view('livewire.pages.formations', [
            'formations' => $formations,
            'methodLabel' => $paymentService->customerMethodLabel($this->paymentMethod),
            'needsPhone' => $this->paymentMethodNeedsPhone(),
            'paymentsReady' => $paymentService->paymentsAvailable(),
        ])
            ->extends('layouts.site', ['title' => 'Formations'])
            ->section('content');
    }
}
