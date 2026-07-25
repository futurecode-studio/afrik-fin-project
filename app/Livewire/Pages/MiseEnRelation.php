<?php

namespace App\Livewire\Pages;

use App\Livewire\Concerns\HandlesInvestmentAppointment;
use App\Models\Partner;
use Livewire\Component;

class MiseEnRelation extends Component
{
    use HandlesInvestmentAppointment;

    public string $first_name = '';
    public string $last_name = '';
    public string $investor_profile = '';
    public string $budget = '';
    public string $service = 'marche_boursier';
    public ?int $partner_id = null;

    public function mount()
    {
        $this->prefillFromAuthUser();
        if ($this->name) {
            $parts = preg_split('/\s+/', trim($this->name), 2);
            $this->first_name = $parts[0] ?? '';
            $this->last_name = $parts[1] ?? '';
        }
        if (request()->filled('partner')) {
            $this->partner_id = (int) request('partner');
        }
    }

    protected function appointmentRules(): array
    {
        return [
            'first_name' => 'required|string|min:2|max:120',
            'last_name' => 'required|string|min:2|max:120',
            'email' => 'required|email|max:255',
            'phone' => ['required', 'string', 'regex:/^[+\d\s\-\(\)]{8,20}$/'],
            'investor_profile' => 'required|string|in:retail,hnwi,institutionnel,entreprise',
            'budget' => 'nullable|string|max:80',
            'service' => 'required|string|max:80',
            'message' => 'nullable|string|max:2000',
            'partner_id' => 'nullable|exists:partners,id',
        ];
    }

    public function submit()
    {
        $this->name = trim($this->first_name.' '.$this->last_name);
        $partner = $this->partner_id ? Partner::find($this->partner_id) : null;
        $extra = collect([
            'Profil: '.$this->investor_profile,
            'Budget: '.($this->budget ?: 'n/d'),
            'Service: '.$this->service,
            $partner ? 'Partenaire souhaité: '.$partner->nom : null,
            $this->message,
        ])->filter()->implode("\n");
        $this->message = $extra;
        $this->company = $partner?->nom;

        $ok = $this->submitAppointment('mise_en_relation');
        if ($ok) {
            $this->reset(['message', 'budget', 'first_name', 'last_name']);
        }
    }

    public function render()
    {
        $partners = Partner::active()->orderBy('order')->get();
        $selected = $this->partner_id ? $partners->firstWhere('id', $this->partner_id) : null;

        return view('livewire.pages.mise-en-relation', compact('partners', 'selected'))
            ->extends('layouts.site', ['title' => 'Demander une mise en relation — Africaine des Finances'])
            ->section('content');
    }
}
