<?php

namespace App\Livewire\Pages;

use App\Livewire\Concerns\HandlesInvestmentAppointment;
use App\Services\MutualFundsApiService;
use Livewire\Component;

class InvestirFcp extends Component
{
    use HandlesInvestmentAppointment;

    // Données FCP
    public $selectedCategory = 'Tous';
    public $mutualFunds = [];
    public $categories = [];
    public $lastUpdated = null;
    public $isLoading = true;
    public $error = null;

    protected function rules(): array
    {
        return $this->appointmentRules();
    }

    public function mount()
    {
        $this->prefillFromAuthUser();
        $this->loadFunds();
    }

    public function loadFunds()
    {
        try {
            $this->isLoading = true;
            $this->error = null;

            $service = app(MutualFundsApiService::class);

            $allFunds = $service->getMutualFunds();
            $this->categories = $service->getCategories();
            
            if ($this->selectedCategory !== 'Tous') {
                $this->mutualFunds = $service->getFundsByCategory($this->selectedCategory);
            } else {
                $this->mutualFunds = $allFunds;
            }

            $this->lastUpdated = now()->format('d/m/Y à H:i:s');
            $this->isLoading = false;

        } catch (\Exception $e) {
            $this->error = 'Une erreur est survenue lors du chargement des données: ' . $e->getMessage();
            $this->mutualFunds = [];
            $this->isLoading = false;
        }
    }

    public function refreshFunds()
    {
        $service = app(MutualFundsApiService::class);
        $service->clearCache();
        $this->loadFunds();
    }

    public function filterByCategory($category)
    {
        $this->selectedCategory = $category;
        $this->loadFunds();
    }

    public function submit()
    {
        $this->submitAppointment('fcp');
    }

    public function render()
    {
        return view('livewire.pages.investir-fcp')
            ->extends('layouts.site', ['title' => 'Investir sur les Fonds Communs de Placement'])
            ->section('content');
    }
}
