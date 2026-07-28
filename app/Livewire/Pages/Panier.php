<?php

namespace App\Livewire\Pages;

use App\Services\FormationCartService;
use Livewire\Component;

class Panier extends Component
{
    public function remove(int $formationId, FormationCartService $cart): void
    {
        $cart->remove($formationId);
    }

    public function clear(FormationCartService $cart): void
    {
        $cart->clear();
    }

    public function render(FormationCartService $cart)
    {
        $lines = $cart->resolved();

        return view('livewire.pages.panier', [
            'lines' => $lines,
            'subtotal' => $cart->subtotal(),
            'count' => $cart->count(),
        ])
            ->extends('layouts.site', ['title' => 'Votre Panier — Africaine des Finances'])
            ->section('content');
    }
}
