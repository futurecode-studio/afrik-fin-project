<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class Formations extends Component
{
    public function render()
    {
        return view('livewire.pages.formations')
            ->extends('layouts.site', ['title' => 'Accueil'])
            ->section('content');
    }
}
