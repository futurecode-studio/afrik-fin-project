<?php

namespace App\Livewire\Pages;

use App\Models\Formation;
use App\Models\Enrollment;
use App\Services\FormationCartService;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Livewire\Concerns\WithSweetAlert;

class FormationDetail extends Component
{
    use WithSweetAlert;
    public $formation;
    public $showPaymentModal = false;
    public $paymentProvider = 'kkiapay';
    public $phone = '';
    public $isEnrolled = false;
    public $enrollment = null;

    protected $listeners = ['paymentSuccess' => 'handlePaymentSuccess'];
    
    public function mount($slug = null)
    {
        $this->formation = Formation::where('slug', $slug)
            ->orWhere('id', $slug)
            ->publie()
            ->with(['modules.lessons', 'user'])
            ->firstOrFail();

        // Vérifier si l'utilisateur est inscrit
        if (Auth::check()) {
            $this->enrollment = Auth::user()->getEnrollment($this->formation);
            $this->isEnrolled = $this->enrollment && $this->enrollment->isActive();
        }

        $providers = app(PaymentService::class)->availablePaymentProviders();
        if ($providers !== [] && ! in_array($this->paymentProvider, $providers, true)) {
            $this->paymentProvider = $providers[0];
        }
    }

    public function addToCart(FormationCartService $cart): void
    {
        $cart->add((int) $this->formation->id);
        $this->swalSuccess('Formation ajoutée au panier.');
    }

    public function openPaymentModal()
    {
        // Si l'utilisateur n'est pas connecté, rediriger vers la connexion
        if (!Auth::check()) {
            session(['intended_formation' => $this->formation->id]);
            return $this->redirect(route('connexion'), navigate: true);
        }

        if ($this->formation->isFree()) {
            $this->enrollFree();
            return;
        }

        if ($this->isEnrolled) {
            $this->swalInfo('Vous êtes déjà inscrit à cette formation.');
            return;
        }

        $this->showPaymentModal = true;
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
        $this->reset(['paymentProvider', 'phone']);
    }

    public function enrollFree()
    {
        if (!Auth::check()) {
            session(['intended_formation' => $this->formation->id]);
            return $this->redirect(route('connexion'), navigate: true);
        }

        $paymentService = app(PaymentService::class);
        $result = $paymentService->enrollForFree(Auth::user(), $this->formation);

        if ($result['success']) {
            $this->isEnrolled = true;
            $this->enrollment = $result['enrollment'];
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

        $paymentService = app(PaymentService::class);
        if (! $paymentService->isProviderReady($this->paymentProvider)) {
            $this->swalError('Ce moyen de paiement n’est pas configuré. Contactez l’administrateur.');
            return;
        }

        $result = $paymentService->initiatePayment(
            Auth::user(),
            $this->formation,
            $this->paymentProvider,
            $this->phone
        );

        if (!$result['success']) {
            $this->swalError($result['message']);
            return;
        }

        $this->dispatch('openPaymentWidget', [
            'provider' => $this->paymentProvider,
            'amount' => (int) $result['amount'],
            'reference' => $result['reference'],
            'email' => Auth::user()->email,
            'name' => Auth::user()->name,
            'phone' => $this->phone,
            'formation' => $this->formation->titre,
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
            $this->isEnrolled = true;
            $this->enrollment = $result['enrollment'];
            $this->swalSuccess('Paiement réussi ! Vous êtes maintenant inscrit à la formation.');
        } else {
            $this->swalError($result['message'] ?? 'Le paiement a échoué.');
        }

        $this->closePaymentModal();
    }

    public function accessCourse()
    {
        if (! Auth::check() || ! $this->isEnrolled) {
            return $this->openPaymentModal();
        }

        return $this->redirect(route('client.formation', $this->formation->slug), navigate: true);
    }
    
    public function render()
    {
        $lessonsCount = $this->formation->modules->sum(fn ($m) => $m->lessons->count());
        $studentsCount = $this->formation->enrollments()->whereIn('status', ['active', 'completed'])->count();

        return view('livewire.pages.formation-detail', [
            'paymentProviders' => app(PaymentService::class)->availablePaymentProviders(),
            'lessonsCount' => $lessonsCount,
            'studentsCount' => $studentsCount,
        ])
            ->extends('layouts.site', ['title' => $this->formation->titre])
            ->section('content');
    }
}
