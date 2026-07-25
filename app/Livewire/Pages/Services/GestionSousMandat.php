<?php

namespace App\Livewire\Pages\Services;

use App\Livewire\Concerns\HandlesInvestmentAppointment;
use Livewire\Component;

class GestionSousMandat extends Component
{
    use HandlesInvestmentAppointment;

    public function mount(): void
    {
        $this->prefillFromAuthUser();
    }

    public function submit(): void
    {
        $this->submitAppointment('gestion_mandat');
    }

    public function render()
    {
        return view('livewire.pages.services.gestion-sous-mandat')
            ->extends('layouts.site', ['title' => 'Gestion Sous Mandat — Africaine des Finances'])
            ->section('content');
    }
}
