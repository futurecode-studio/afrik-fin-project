<?php

namespace App\Livewire\Pages\Marches;

use App\Models\StructuredProduct;
use Livewire\Component;

class ProduitsStructures extends Component
{
    public function render()
    {
        $products = StructuredProduct::published()->orderBy('distance_to_barrier_pct')->get();
        $encours = $products->sum(fn ($p) => (float) ($p->current_price ?? 0) * 1000);
        $alerts = $products->filter(fn ($p) => $p->distance_to_barrier_pct !== null && $p->distance_to_barrier_pct < 10);
        $coupons = $products->filter(fn ($p) => $p->coupon_memorized)->sortByDesc('coupon_memorized');
        $autocalls = $products->filter(fn ($p) => $p->next_autocall_date)->sortBy('next_autocall_date');

        return view('livewire.pages.marches.produits-structures', compact('products', 'encours', 'alerts', 'coupons', 'autocalls'))
            ->extends('layouts.site', ['title' => 'Suivi Produits Structurés — Africaine des Finances'])
            ->section('content');
    }
}
