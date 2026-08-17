<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use App\Models\Partner;

class Partners extends Component
{
    public function render()
    {
        $partners = Partner::active()->orderBy('order')->get();
        if ($partners->isEmpty()) {
            $partners = Partner::catalogCollection();
        }
        
        return view('livewire.pages.partners', [
            'partners' => $partners,
        ])
            ->extends('layouts.site', ['title' => 'Partenaires'])
            ->section('content');
    }
}