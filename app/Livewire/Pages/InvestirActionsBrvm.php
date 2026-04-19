<?php

namespace App\Livewire\Pages;

use App\Livewire\Concerns\HandlesInvestmentAppointment;
use App\Services\BRVMScraperService;
use Livewire\Component;

class InvestirActionsBrvm extends Component
{
    use HandlesInvestmentAppointment;

    // Données BRVM
    public $stocks = [];
    public $allStocks = [];
    public $indices = [];
    public $chartData = [];
    public $lastUpdate;
    public $isLoading = false;
    public $errorMessage = null;
    public $dataSource = null;

    // Filtres
    public $searchTerm = '';
    public $sectorFilter = '';
    public $variationFilter = '';
    public $sortBy = 'symbol';
    public $sortDirection = 'asc';

    // Modal détails
    public $showModal = false;
    public $selectedStock = null;

    protected $brvmService;

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'sectorFilter' => ['except' => ''],
        'variationFilter' => ['except' => ''],
        'sortBy' => ['except' => 'symbol'],
        'sortDirection' => ['except' => 'asc'],
    ];

    protected function rules(): array
    {
        return $this->appointmentRules();
    }

    public function boot(BRVMScraperService $brvmService)
    {
        $this->brvmService = $brvmService;
    }

    public function mount()
    {
        $this->prefillFromAuthUser();
        $this->loadData();
        $this->loadChartData();
    }

    public function loadData()
    {
        $this->isLoading = true;
        $this->errorMessage = null;

        try {
            $this->allStocks = $this->brvmService->getStocks();
            $this->indices = $this->brvmService->getIndices();
            $this->lastUpdate = now()->format('d/m/Y à H:i');
            
            if (!empty($this->allStocks)) {
                $this->dataSource = $this->allStocks[0]['source'] ?? 'unknown';
            }
            
            $this->applyFilters();
        } catch (\Exception $e) {
            $this->errorMessage = "Erreur lors du chargement des données : " . $e->getMessage();
            $this->lastUpdate = "Erreur";
        }

        $this->isLoading = false;
    }

    public function applyFilters()
    {
        $stocks = collect($this->allStocks);

        if (!empty($this->searchTerm)) {
            $search = strtolower($this->searchTerm);
            $stocks = $stocks->filter(function ($stock) use ($search) {
                return str_contains(strtolower($stock['symbol'] ?? ''), $search) ||
                       str_contains(strtolower($stock['company_name'] ?? ''), $search);
            });
        }

        if (!empty($this->sectorFilter)) {
            $stocks = $stocks->filter(function ($stock) {
                return ($stock['sector'] ?? '') === $this->sectorFilter;
            });
        }

        if (!empty($this->variationFilter)) {
            $stocks = $stocks->filter(function ($stock) {
                $variation = $stock['variation_percent'] ?? 0;
                return match ($this->variationFilter) {
                    'up' => $variation > 0,
                    'down' => $variation < 0,
                    'stable' => $variation == 0,
                    default => true,
                };
            });
        }

        $stocks = $stocks->sortBy(function ($stock) {
            return match ($this->sortBy) {
                'symbol' => $stock['symbol'] ?? '',
                'company_name' => $stock['company_name'] ?? '',
                'current_price' => $stock['current_price'] ?? 0,
                'variation_percent' => $stock['variation_percent'] ?? 0,
                'volume' => $stock['volume'] ?? 0,
                'sector' => $stock['sector'] ?? '',
                default => $stock['symbol'] ?? '',
            };
        }, SORT_REGULAR, $this->sortDirection === 'desc');

        $this->stocks = $stocks->values()->toArray();
    }

    public function updatedSearchTerm()
    {
        $this->applyFilters();
    }

    public function updatedSectorFilter()
    {
        $this->applyFilters();
    }

    public function updatedVariationFilter()
    {
        $this->applyFilters();
    }

    public function sortByColumn($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
        $this->applyFilters();
    }

    public function resetFilters()
    {
        $this->searchTerm = '';
        $this->sectorFilter = '';
        $this->variationFilter = '';
        $this->sortBy = 'symbol';
        $this->sortDirection = 'asc';
        $this->applyFilters();
    }

    public function getSectorsProperty()
    {
        return collect($this->allStocks)
            ->pluck('sector')
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->toArray();
    }

    /**
     * Statistiques globales du marché : nombre de valeurs en hausse / baisse / stable
     * et volume total échangé. Utilisé pour la synthèse en en-tête du tableau.
     */
    public function getMarketSummaryProperty(): array
    {
        $up = $down = $stable = 0;
        $totalVolume = 0;

        foreach ($this->allStocks as $stock) {
            $variation = $stock['variation_percent'] ?? 0;
            if ($variation > 0) $up++;
            elseif ($variation < 0) $down++;
            else $stable++;
            $totalVolume += (int) ($stock['volume'] ?? 0);
        }

        return [
            'total' => count($this->allStocks),
            'up' => $up,
            'down' => $down,
            'stable' => $stable,
            'total_volume' => $totalVolume,
        ];
    }

    /**
     * Mapping statique secteur → classes Tailwind (évite la duplication en vue).
     */
    public function getSectorColorsProperty(): array
    {
        return [
            'Finance' => 'bg-blue-100 text-blue-800',
            'Banque' => 'bg-blue-100 text-blue-800',
            'Télécommunications' => 'bg-purple-100 text-purple-800',
            'Agriculture' => 'bg-green-100 text-green-800',
            'Industrie' => 'bg-orange-100 text-orange-800',
            'Distribution' => 'bg-yellow-100 text-yellow-800',
            'Services Publics' => 'bg-cyan-100 text-cyan-800',
            'Transport' => 'bg-indigo-100 text-indigo-800',
        ];
    }

    /**
     * Charge les données du graphique depuis l'historique réel (table market_index_history).
     * Tente de trouver un indice "Composite" parmi ceux disponibles, sinon prend le premier.
     * Si l'historique est vide ou contient 1 seul point, on enregistre au moins le snapshot
     * du jour afin que l'historique commence à se constituer.
     */
    public function loadChartData()
    {
        // 1. Trouver l'indice cible (Composite de préférence)
        $targetIndex = null;
        $currentIndexValue = 0;

        foreach ($this->indices as $index) {
            $name = strtolower($index['name'] ?? '');
            if (str_contains($name, 'composite') || str_contains($name, 'brvm-c')) {
                $targetIndex = $index;
                break;
            }
        }
        if (!$targetIndex && !empty($this->indices)) {
            $targetIndex = $this->indices[0];
        }

        if (!$targetIndex) {
            $this->chartData = ['labels' => [], 'data' => [], 'currentValue' => 0, 'source' => null, 'index_name' => null];
            return;
        }

        $indexName = $targetIndex['name'];
        $currentIndexValue = (float) ($targetIndex['value'] ?? 0);

        // 2. Enregistrer le snapshot du jour (idempotent) pour que l'historique se construise
        try {
            $this->brvmService->recordDailySnapshot();
        } catch (\Throwable $e) {
            \Log::warning('Snapshot BRVM on-demand échoué: ' . $e->getMessage());
        }

        // 3. Lire l'historique réel en base
        $history = $this->brvmService->getIndexHistory($indexName, 30);

        $labels = array_column($history, 'date');
        $data = array_column($history, 'value');
        $source = $history[0]['source'] ?? null;

        $this->chartData = [
            'labels' => $labels,
            'data' => $data,
            'currentValue' => $currentIndexValue,
            'source' => $source,
            'index_name' => $indexName,
            'points_count' => count($data),
        ];
    }

    public function refresh()
    {
        $this->brvmService->refreshData();
        $this->loadData();
        $this->loadChartData();
        session()->flash('success', 'Données actualisées avec succès.');
    }

    public function showStockDetails(string $symbol)
    {
        // Recherche idiomatique via Collection (code plus concis que la boucle manuelle)
        $stock = collect($this->allStocks)->firstWhere('symbol', $symbol);
        if ($stock) {
            $this->selectedStock = $stock;
            $this->showModal = true;
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedStock = null;
    }

    public function submit()
    {
        $this->submitAppointment('actions_brvm');
    }

    public function render()
    {
        return view('livewire.pages.investir-actions-brvm')
            ->extends('layouts.site', ['title' => 'Investir sur les Actions BRVM'])
            ->section('content');
    }
}
