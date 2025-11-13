<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class Services extends Component
{
    public function render()
    {
        return view('livewire.pages.services')
            ->extends('layouts.site', ['title' => 'Accueil'])
            ->section('content');
    }
}
