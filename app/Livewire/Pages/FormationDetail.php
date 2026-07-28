<?php

namespace App\Livewire\Pages;

use App\Livewire\Concerns\WithCustomerPayment;
use App\Livewire\Concerns\WithSweetAlert;
use App\Models\Formation;
use App\Services\FormationCartService;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FormationDetail extends Component
{
    use WithCustomerPayment;
    use WithSweetAlert;

    public $formation;

    public $showPaymentModal = false;

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

        if (Auth::check()) {
            $this->enrollment = Auth::user()->getEnrollment($this->formation);
            $this->isEnrolled = $this->enrollment && $this->enrollment->isActive();
        }

        $this->resolvePaymentGateway();
    }

    public function addToCart(FormationCartService $cart): void
    {
        $cart->add((int) $this->formation->id);
        $this->swalSuccess('Formation ajoutée au panier.');
    }

    public function openPaymentModal()
    {
        if (! Auth::check()) {
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
        $this->phone = '';
        $this->paymentMethod = 'mtn';
        $this->resolvePaymentGateway();
    }

    public function enrollFree()
    {
        if (! Auth::check()) {
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
        if (! Auth::check()) {
            $this->swalError('Veuillez vous connecter.');

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
            $this->formation,
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
            'formation' => $this->formation->titre,
            'callback_info' => [
                'type' => 'formation',
                'reference' => $result['reference'],
                'payment_id' => $result['payment']->id ?? null,
                'enrollment_id' => $result['enrollment']->id ?? null,
                'formation_id' => $this->formation->id,
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
            $this->isEnrolled = true;
            $this->enrollment = $result['enrollment'] ?? null;
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
        $paymentService = app(PaymentService::class);

        return view('livewire.pages.formation-detail', [
            'paymentMethods' => $paymentService->customerPaymentMethods(),
            'paymentsReady' => $paymentService->paymentsAvailable(),
            'methodLabel' => $paymentService->customerMethodLabel($this->paymentMethod),
            'needsPhone' => $this->paymentMethodNeedsPhone(),
            'lessonsCount' => $lessonsCount,
            'studentsCount' => $studentsCount,
        ])
            ->extends('layouts.site', ['title' => $this->formation->titre])
            ->section('content');
    }
}
