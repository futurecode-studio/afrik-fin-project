<?php

namespace App\Livewire\Pages;

use App\Livewire\Concerns\HandlesInvestmentAppointment;
use App\Services\MutualFundsApiService;
use Livewire\Attributes\Url;
use Livewire\Component;

class InvestirFcp extends Component
{
    use HandlesInvestmentAppointment;

    // ─── Filtres & tri (URL-bindés) ────────────────────────────────
    #[Url(as: 'q', except: '')]
    public string $searchTerm = '';

    #[Url(as: 'cat', except: 'Tous')]
    public string $selectedCategory = 'Tous';

    #[Url(as: 'pays', except: 'all')]
    public string $countryFilter = 'all';

    #[Url(as: 'tri', except: 'name')]
    public string $sortBy = 'name'; // name | nav_numeric | variation_percentage

    #[Url(as: 'ordre', except: 'asc')]
    public string $sortDirection = 'asc';

    // ─── Modal détails ────────────────────────────────────────────
    public bool $showModal = false;
    public ?string $selectedFundId = null;

    // ─── Données (caches in-memory) ──────────────────────────────
    public array $allFunds = [];
    public bool $isLoading = true;
    public ?string $error = null;
    public ?string $lastUpdated = null;

    protected function rules(): array
    {
        return $this->appointmentRules();
    }

    public function mount(): void
    {
        $this->prefillFromAuthUser();
        $this->loadFunds();
    }

    public function loadFunds(): void
    {
        $this->isLoading = true;
        $this->error = null;

        try {
            $service = app(MutualFundsApiService::class);
            $this->allFunds = $service->getMutualFunds();
            $this->lastUpdated = now()->format('d/m/Y à H:i');
        } catch (\Throwable $e) {
            $this->error = 'Impossible de récupérer les données Sikafinance pour le moment. Réessayez dans quelques minutes.';
            $this->allFunds = [];
        } finally {
            $this->isLoading = false;
        }
    }

    public function refreshFunds(): void
    {
        app(MutualFundsApiService::class)->clearCache();
        $this->loadFunds();
        session()->flash('success', 'Données Sikafinance actualisées avec succès.');
    }

    public function filterByCategory(string $category): void
    {
        $this->selectedCategory = $category;
    }

    public function resetFilters(): void
    {
        $this->reset(['searchTerm', 'countryFilter']);
        $this->selectedCategory = 'Tous';
        $this->sortBy = 'name';
        $this->sortDirection = 'asc';
    }

    // ─── Computed ─────────────────────────────────────────────────

    /**
     * Liste filtrée + triée.
     * @return array<int,array<string,mixed>>
     */
    public function getFundsProperty(): array
    {
        $funds = $this->allFunds;

        // Catégorie
        if ($this->selectedCategory !== 'Tous') {
            $funds = array_filter($funds, fn($f) => $f['category'] === $this->selectedCategory);
        }

        // Pays
        if ($this->countryFilter !== 'all') {
            $funds = array_filter($funds, fn($f) => $f['country'] === $this->countryFilter);
        }

        // Recherche
        $needle = trim(mb_strtolower($this->searchTerm));
        if ($needle !== '') {
            $funds = array_filter($funds, function ($f) use ($needle) {
                $hay = mb_strtolower($f['name'] . ' ' . $f['company'] . ' ' . ($f['isin'] ?? '') . ' ' . $f['country']);
                return str_contains($hay, $needle);
            });
        }

        $funds = array_values($funds);

        // Tri
        $dir = $this->sortDirection === 'desc' ? -1 : 1;
        usort($funds, function ($a, $b) use ($dir) {
            $col = $this->sortBy;
            $va = $a[$col] ?? '';
            $vb = $b[$col] ?? '';
            if (is_numeric($va) && is_numeric($vb)) {
                return ($va <=> $vb) * $dir;
            }
            return strcmp((string) $va, (string) $vb) * $dir;
        });

        return $funds;
    }

    public function getCategoriesProperty(): array
    {
        $cats = array_unique(array_column($this->allFunds, 'category'));
        sort($cats);
        return array_values($cats);
    }

    public function getAvailableCountriesProperty(): array
    {
        $countries = array_unique(array_column($this->allFunds, 'country'));
        sort($countries);
        return array_values($countries);
    }

    public function getStatsProperty(): array
    {
        $all = $this->allFunds;
        $count = count($all);
        if ($count === 0) {
            return ['total' => 0, 'avg_variation' => 0, 'top_gainer' => null, 'top_loser' => null, 'countries' => 0];
        }
        $variations = array_column($all, 'variation_percentage');
        $gainers = array_filter($all, fn($f) => $f['variation_percentage'] > 0);
        usort($all, fn($a, $b) => $b['variation_percentage'] <=> $a['variation_percentage']);
        return [
            'total' => $count,
            'avg_variation' => array_sum($variations) / $count,
            'gainers' => count($gainers),
            'top_gainer' => $all[0] ?? null,
            'top_loser' => end($all) ?: null,
            'countries' => count(array_unique(array_column($this->allFunds, 'country'))),
        ];
    }

    // ─── Modal ────────────────────────────────────────────────────

    public function showFundDetails(string $id): void
    {
        $this->selectedFundId = $id;
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->selectedFundId = null;
    }

    public function getSelectedFundProperty(): ?array
    {
        if (!$this->selectedFundId) return null;
        foreach ($this->allFunds as $f) {
            if ($f['id'] === $this->selectedFundId) return $f;
        }
        return null;
    }

    // ─── RDV ──────────────────────────────────────────────────────

    public function submit(): void
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
