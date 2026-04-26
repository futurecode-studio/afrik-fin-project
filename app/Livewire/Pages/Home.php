<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use App\Models\Partner;

class Home extends Component
{
    public function render()
    {
        $partnersByType = Partner::active()->get()->groupBy('type');

        return view('livewire.pages.home', [
            'partnersByType' => $partnersByType,
        ])
            ->extends('layouts.site', ['title' => 'Accueil'])
            ->section('content'); 
    }
}
