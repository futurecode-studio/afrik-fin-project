<?php

namespace App\Livewire\Pages;

use App\Models\Formation;
use App\Models\Enrollment;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FormationDetail extends Component
{
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
            session()->flash('info', 'Vous êtes déjà inscrit à cette formation.');
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

        $paymentService = new PaymentService();
        $result = $paymentService->enrollForFree(Auth::user(), $this->formation);

        if ($result['success']) {
            $this->isEnrolled = true;
            $this->enrollment = $result['enrollment'];
            session()->flash('success', $result['message']);
        } else {
            session()->flash('error', $result['message'] ?? 'Une erreur est survenue.');
        }

        $this->closePaymentModal();
    }

    public function initiatePayment()
    {
        if (!Auth::check()) {
            session()->flash('error', 'Veuillez vous connecter.');
            return;
        }

        $paymentService = new PaymentService();
        $result = $paymentService->initiatePayment(
            Auth::user(),
            $this->formation,
            $this->paymentProvider,
            $this->phone
        );

        if (!$result['success']) {
            session()->flash('error', $result['message']);
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
        $paymentService = new PaymentService();
        
        if ($this->paymentProvider === 'kkiapay') {
            $result = $paymentService->handleKkiapayCallback($data);
        } else {
            $result = $paymentService->handleFedapayCallback($data);
        }

        if ($result['success']) {
            $this->isEnrolled = true;
            $this->enrollment = $result['enrollment'];
            session()->flash('success', 'Paiement réussi ! Vous êtes maintenant inscrit à la formation.');
        } else {
            session()->flash('error', $result['message'] ?? 'Le paiement a échoué.');
        }

        $this->closePaymentModal();
    }
    
    public function render()
    {
        return view('livewire.pages.formation-detail')
            ->extends('layouts.site', ['title' => $this->formation->titre])
            ->section('content');
    }
}
