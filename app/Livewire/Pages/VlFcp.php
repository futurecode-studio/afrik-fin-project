<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use App\Services\MutualFundsApiService;

class VlFcp extends Component
{
    public $selectedCategory = 'Tous';
    public $mutualFunds = [];
    public $categories = [];
    public $lastUpdated = null;
    public $isLoading = true;
    public $error = null;

    public function mount()
    {
        $this->loadFunds();
    }

    public function loadFunds()
    {
        try {
            $this->isLoading = true;
            $this->error = null;

            // Réinstancier le service à chaque appel (Livewire cycle)
            $service = app(MutualFundsApiService::class);

            // Récupérer tous les fonds
            $allFunds = $service->getMutualFunds();
            
            // Récupérer les catégories
            $this->categories = $service->getCategories();
            
            // Filtrer par catégorie si sélectionnée
            if ($this->selectedCategory !== 'Tous') {
                $this->mutualFunds = $service->getFundsByCategory($this->selectedCategory);
            } else {
                $this->mutualFunds = $allFunds;
            }

            // Formater la date de dernière mise à jour
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
        // Invalider le cache et recharger
        $service = app(MutualFundsApiService::class);
        $service->clearCache();
        $this->loadFunds();
    }

    public function filterByCategory($category)
    {
        $this->selectedCategory = $category;
        $this->loadFunds();
    }

    public function render()
    {
        return view('livewire.pages.vl-fcp')
            ->extends('layouts.site', ['title' => 'VL / FCP']) 
            ->section('content');
    }
}
