<?php

namespace App\Livewire\Pages;

use App\Models\SiteService;
use Livewire\Component;

class Services extends Component
{
    public function render()
    {
        $services = SiteService::active()->get();

        return view('livewire.pages.services', compact('services'))
            ->extends('layouts.site', ['title' => 'Nos Services — Africaine des Finances'])
            ->section('content');
    }
}
