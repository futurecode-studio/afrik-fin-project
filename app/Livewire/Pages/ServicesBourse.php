<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class ServicesBourse extends Component
{
    public function render()
    {
        return view('livewire.pages.services-bourse')
            ->extends('layouts.site', ['title' => 'Services Bourse'])
            ->section('content');
    }
}
