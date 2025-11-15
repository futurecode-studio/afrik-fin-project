<?php

namespace App\Livewire\Pages;

use App\Services\MarketstackApiService;
use App\Models\Stock;
use Livewire\Component;
use Carbon\Carbon;

class Bourse extends Component
{
    public $stocks = [];
    public $indices = [];
    public $chartData = [];
    public $lastUpdate;
    public $isLoading = false;
    public $errorMessage = null;
    public $apiConfigured = false;

    protected $marketstackService;

    public function boot(MarketstackApiService $marketstackService)
    {
        $this->marketstackService = $marketstackService;
    }

    public function mount()
    {
        $this->apiConfigured = $this->marketstackService->isConfigured();
        $this->loadData();
        $this->loadChartData();
    }

    public function loadData()
    {
        $this->isLoading = true;
        $this->errorMessage = null;

        try {
            // Charger les données depuis l'API ou la base de données
            $this->stocks = $this->marketstackService->getStocks();
            // dd($this->stocks);
            $this->indices = $this->marketstackService->getIndices();
            $this->lastUpdate = now()->format('d/m/Y à H:i');
        } catch (\Exception $e) {
            $this->errorMessage = "Erreur lors du chargement des données : " . $e->getMessage();
            $this->lastUpdate = "Erreur";
        }

        $this->isLoading = false;
    }

    /**
     * Charger les données pour le graphique
     */
    public function loadChartData()
    {
        // Générer des données pour les 30 derniers jours
        $days = 30;
        $labels = [];
        $data = [];
        
        // Récupérer la moyenne de capitalisation actuelle
        $currentValue = Stock::where('is_active', true)->avg('market_cap') ?? 1000;
        
        // Générer des valeurs simulées avec une tendance
        $baseValue = $currentValue * 0.9; // Commencer à 90% de la valeur actuelle
        
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('d/m');
            
            // Générer une variation aléatoire mais réaliste (-2% à +2% par jour)
            $variation = (mt_rand(-200, 200) / 100);
            $baseValue = $baseValue * (1 + ($variation / 100));
            $data[] = round($baseValue, 2);
        }
        
        $this->chartData = [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    /**
     * Rafraîchir les données manuellement
     */
    public function refresh()
    {
        $this->marketstackService->refreshData();
        $this->loadData();
        $this->loadChartData();
        session()->flash('success', 'Données actualisées avec succès.');
    }

    public function render()
    {
        return view('livewire.pages.bourse')
            ->extends('layouts.site', ['title' => 'Bourse BRVM'])
            ->section('content');
    }
}

