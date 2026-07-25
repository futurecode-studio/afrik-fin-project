<?php

namespace App\Livewire\Pages\Services;

use App\Livewire\Concerns\HandlesInvestmentAppointment;
use Livewire\Component;

class PortailInstitutionnel extends Component
{
    use HandlesInvestmentAppointment;

    public string $organization = '';
    public string $role = '';

    public function mount(): void
    {
        $this->prefillFromAuthUser();
    }

    protected function appointmentRules(): array
    {
        return [
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|max:255',
            'phone' => ['required', 'string', 'regex:/^[+\d\s\-\(\)]{8,20}$/'],
            'organization' => 'required|string|min:2|max:255',
            'role' => 'nullable|string|max:120',
            'message' => 'nullable|string|max:2000',
        ];
    }

    public function submit(): void
    {
        $this->company = $this->organization;
        $extra = trim(($this->role ? "Fonction: {$this->role}\n" : '').($this->message ?? ''));
        $this->message = $extra;
        $this->submitAppointment('institutionnel');
    }

    public function render()
    {
        return view('livewire.pages.services.portail-institutionnel')
            ->extends('layouts.site', ['title' => 'Portail Institutionnel & Corporate — Africaine des Finances'])
            ->section('content');
    }
}
