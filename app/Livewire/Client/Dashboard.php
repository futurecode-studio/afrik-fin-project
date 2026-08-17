<?php

namespace App\Livewire\Client;

use Livewire\Component;

class Dashboard extends Component
{
    public function mount()
    {
        // Espace client Niveau 1 : landing sur Événement (sidebar)
        $this->redirect(route('client.my-events'), navigate: true);
    }

    public function render()
    {
        return view('livewire.client.dashboard')
            ->extends('layouts.client', ['title' => 'Tableau de bord'])
            ->section('content');
    }
}
