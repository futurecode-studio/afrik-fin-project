<?php

namespace App\Livewire\Pages;

use App\Models\Formation;
use App\Models\Enrollment;
use App\Services\FormationCartService;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Livewire\Concerns\WithSweetAlert;

class Formations extends Component
{
    use WithSweetAlert;
    public $showPaymentModal = false;
    public $selectedFormation = null;
    public $paymentProvider = 'kkiapay';
    public $phone = '';
    public $search = '';
    public $filterNiveau = '';
    public $filterType = ''; // 'gratuit' ou 'payant'

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
        
        if (!$this->selectedFormation) {
            $this->swalError('Formation non trouvée.');
            return;
        }

        // Si l'utilisateur n'est pas connecté, rediriger vers la connexion
        if (!Auth::check()) {
            // Stocker l'ID de la formation pour après connexion
            session(['intended_formation' => $formationId]);
            return $this->redirect(route('connexion'), navigate: true);
        }

        // Si la formation est gratuite, inscrire directement
        if ($this->selectedFormation->isFree()) {
            $this->enrollFree();
            return;
        }

        // Vérifier si déjà inscrit
        if (Auth::user()->isEnrolledIn($this->selectedFormation)) {
            $this->swalInfo('Vous êtes déjà inscrit à cette formation.');
            return;
        }

        $this->showPaymentModal = true;
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
        $this->reset(['selectedFormation', 'paymentProvider', 'phone']);
    }

    public function enrollFree()
    {
        if (!Auth::check()) {
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
        if (!Auth::check()) {
            $this->swalError('Veuillez vous connecter.');
            return;
        }

        if (!$this->selectedFormation) {
            $this->swalError('Veuillez sélectionner une formation.');
            return;
        }

        $paymentService = app(PaymentService::class);
        $result = $paymentService->initiatePayment(
            Auth::user(),
            $this->selectedFormation,
            $this->paymentProvider,
            $this->phone
        );

        if (!$result['success']) {
            $this->swalError($result['message']);
            return;
        }

        // Émettre l'événement pour déclencher le paiement côté client
        $this->dispatch('openPaymentWidget', [
            'provider' => $this->paymentProvider,
            'amount' => (int) $result['amount'],
            'reference' => $result['reference'],
            'email' => Auth::user()->email,
            'name' => Auth::user()->name,
            'phone' => $this->phone,
            'formation' => $this->selectedFormation->titre,
        ]);
    }

    public function handlePaymentSuccess($data)
    {
        $paymentService = app(PaymentService::class);
        
        if ($this->paymentProvider === 'kkiapay') {
            $result = $paymentService->handleKkiapayCallback($data);
        } else {
            $result = $paymentService->handleFedapayCallback($data);
        }

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
                    $q->where('titre', 'like', '%' . $this->search . '%')
                      ->orWhere('description_courte', 'like', '%' . $this->search . '%');
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

        return view('livewire.pages.formations', [
            'formations' => $formations,
        ])
            ->extends('layouts.site', ['title' => 'Formations'])
            ->section('content');
    }
}
