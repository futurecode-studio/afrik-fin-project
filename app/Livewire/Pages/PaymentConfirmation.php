<?php

namespace App\Livewire\Pages;

use App\Models\Payment;
use Livewire\Component;

class PaymentConfirmation extends Component
{
    public ?Payment $payment = null;

    public function mount()
    {
        $ref = request('reference') ?? request('ref');
        if ($ref) {
            $this->payment = Payment::query()
                ->with(['formation', 'user'])
                ->where(function ($q) use ($ref) {
                    $q->where('reference', $ref)->orWhere('transaction_id', $ref);
                })
                ->first();
        }

        if (! $this->payment && auth()->check()) {
            $this->payment = Payment::query()
                ->with(['formation', 'user'])
                ->where('user_id', auth()->id())
                ->where('status', 'completed')
                ->latest('paid_at')
                ->first();
        }
    }

    public function render()
    {
        return view('livewire.pages.payment-confirmation')
            ->extends('layouts.site', ['title' => 'Confirmation de Paiement — Africaine des Finances'])
            ->section('content');
    }
}
