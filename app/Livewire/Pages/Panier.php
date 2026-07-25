<?php

namespace App\Livewire\Pages;

use App\Services\FormationCartService;
use Livewire\Component;

class Panier extends Component
{
    public function increment(int $formationId, FormationCartService $cart): void
    {
        $item = collect($cart->items())->firstWhere('formation_id', $formationId);
        $qty = $item ? (int) $item['quantity'] + 1 : 1;
        $cart->update($formationId, $qty);
    }

    public function decrement(int $formationId, FormationCartService $cart): void
    {
        $item = collect($cart->items())->firstWhere('formation_id', $formationId);
        if (! $item) {
            return;
        }
        $cart->update($formationId, (int) $item['quantity'] - 1);
    }

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
