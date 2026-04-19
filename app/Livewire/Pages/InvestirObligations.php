<?php

namespace App\Livewire\Pages;

use App\Livewire\Concerns\HandlesInvestmentAppointment;
use App\Models\GovernmentBond;
use Livewire\Component;

class InvestirObligations extends Component
{
    use HandlesInvestmentAppointment;

    public $bonds = [];

    protected function rules(): array
    {
        return $this->appointmentRules();
    }

    public function mount()
    {
        $this->prefillFromAuthUser();
        $this->loadBonds();
    }

    public function loadBonds()
    {
        $this->bonds = GovernmentBond::active()->ordered()->get();
    }

    public function submit()
    {
        $this->submitAppointment('obligations');
    }

    public function render()
    {
        return view('livewire.pages.investir-obligations')
            ->extends('layouts.site', ['title' => 'Investir sur les Obligations d\'États'])
            ->section('content');
    }
}
