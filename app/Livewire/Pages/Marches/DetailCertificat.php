<?php

namespace App\Livewire\Pages\Marches;

use App\Models\StructuredProduct;
use Livewire\Component;

class DetailCertificat extends Component
{
    public StructuredProduct $product;

    public function mount(string $slug): void
    {
        $this->product = StructuredProduct::published()->where('slug', $slug)->firstOrFail();
    }

    public function render()
    {
        $related = StructuredProduct::published()
            ->where('id', '!=', $this->product->id)
            ->limit(3)
            ->get();

        return view('livewire.pages.marches.detail-certificat', [
            'product' => $this->product,
            'related' => $related,
        ])
            ->extends('layouts.site', ['title' => $this->product->name.' — Africaine des Finances'])
            ->section('content');
    }
}
