<?php

namespace App\Livewire\Pages;

use App\Livewire\Concerns\HandlesInvestmentAppointment;
use App\Models\GovernmentBond;
use App\Services\UMOATitresService;
use Livewire\Component;
use Livewire\Attributes\Url;

class InvestirObligations extends Component
{
    use HandlesInvestmentAppointment;

    // ─── Filtres & tri (URL-bindés) ────────────────────────────────
    #[Url(as: 'q', except: '')]
    public string $searchTerm = '';

    #[Url(as: 'pays', except: 'all')]
    public string $countryFilter = 'all';

    #[Url(as: 'type', except: 'all')]
    public string $typeFilter = 'all'; // BAT | OAT | OATR | OATI

    #[Url(as: 'risque', except: 'all')]
    public string $riskFilter = 'all'; // low | medium | high

    #[Url(as: 'maturite', except: 'all')]
    public string $maturityFilter = 'all'; // short | medium | long

    #[Url(as: 'tri', except: 'auction_date')]
    public string $sortBy = 'auction_date'; // interest_rate | maturity_date | auction_date

    #[Url(as: 'ordre', except: 'desc')]
    public string $sortDirection = 'desc';

    // ─── Modal détails ────────────────────────────────────────────
    public bool $showModal = false;
    public ?int $selectedBondId = null;

    // ─── Calculateur de rendement ────────────────────────────────
    public ?int $calculatorBondId = null;
    public $calculatorAmount = 1000000; // FCFA

    // ─── Métadonnées d'affichage ─────────────────────────────────
    public ?string $lastSyncAt = null;

    protected function rules(): array
    {
        return $this->appointmentRules();
    }

    public function mount(): void
    {
        $this->prefillFromAuthUser();
        $this->refreshLastSyncAt();
    }

    private function refreshLastSyncAt(): void
    {
        $latest = GovernmentBond::where('data_source', 'umoa_titres')
            ->whereNotNull('last_synced_at')
            ->max('last_synced_at');
        $this->lastSyncAt = $latest ? \Carbon\Carbon::parse($latest)->diffForHumans() : null;
    }

    /**
     * Requête principale : filtres, recherche, tri.
     */
    public function getBondsProperty()
    {
        $q = GovernmentBond::active()->notMatured();

        if ($this->searchTerm !== '') {
            $needle = '%' . str_replace(' ', '%', trim($this->searchTerm)) . '%';
            $q->where(function ($w) use ($needle) {
                $w->where('name', 'like', $needle)
                  ->orWhere('issuer', 'like', $needle)
                  ->orWhere('country', 'like', $needle)
                  ->orWhere('isin_code', 'like', $needle);
            });
        }

        if ($this->countryFilter !== 'all') {
            $q->where('country', $this->countryFilter);
        }

        if ($this->typeFilter !== 'all') {
            // Le type est encodé dans le nom (ex: "BAT Bénin — 3 mois ...")
            $q->where('name', 'like', $this->typeFilter . ' %');
        }

        if ($this->riskFilter !== 'all') {
            $q->where('risk_level', $this->riskFilter);
        }

        if ($this->maturityFilter !== 'all') {
            match ($this->maturityFilter) {
                'short' => $q->where('maturity_years', '<=', 1),
                'medium' => $q->whereBetween('maturity_years', [2, 5]),
                'long' => $q->where('maturity_years', '>=', 6),
                default => null,
            };
        }

        $sortColumn = in_array($this->sortBy, ['interest_rate', 'maturity_date', 'auction_date', 'yield_to_maturity'])
            ? $this->sortBy : 'auction_date';
        $direction = $this->sortDirection === 'asc' ? 'asc' : 'desc';

        return $q->orderBy($sortColumn, $direction)->orderBy('display_order')->get();
    }

    /**
     * Statistiques agrégées pour la carte "résumé".
     */
    public function getStatsProperty(): array
    {
        $all = GovernmentBond::active()->notMatured()->get();
        return [
            'total' => $all->count(),
            'avg_rate' => $all->avg('interest_rate'),
            'max_rate' => $all->max('interest_rate'),
            'countries' => $all->pluck('country')->unique()->count(),
            'recent' => $all->where('auction_date', '>=', now()->subDays(30)->toDateString())->count(),
        ];
    }

    public function getAvailableCountriesProperty(): array
    {
        return GovernmentBond::active()->notMatured()
            ->select('country')
            ->distinct()
            ->orderBy('country')
            ->pluck('country')
            ->toArray();
    }

    // ─── Modal détails ────────────────────────────────────────────
    public function showBondDetails(int $id): void
    {
        $this->selectedBondId = $id;
        $this->calculatorBondId = $id;
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->selectedBondId = null;
    }

    public function getSelectedBondProperty(): ?GovernmentBond
    {
        return $this->selectedBondId ? GovernmentBond::find($this->selectedBondId) : null;
    }

    // ─── Calculateur ──────────────────────────────────────────────
    /**
     * Calcule le rendement d'un investissement sur une obligation donnée.
     *
     * Pour les BAT (zéro coupon) : intérêts précomptés → gain = amount × rate × years
     * Pour les OAT : somme des coupons sur la durée + remboursement du capital à l'échéance
     */
    public function getYieldProjectionProperty(): ?array
    {
        $bond = $this->selectedBond;
        if (!$bond) return null;

        $amount = max(0, (float) $this->calculatorAmount);
        if ($amount <= 0) return null;

        $rate = (float) $bond->interest_rate;
        $years = max(0.25, (float) $bond->maturity_years);
        $isBat = str_starts_with($bond->name, 'BAT');

        if ($isBat) {
            // BAT : zéro coupon, intérêts précomptés
            $totalInterest = $amount * ($rate / 100) * $years;
            return [
                'is_bat' => true,
                'amount' => $amount,
                'rate' => $rate,
                'years' => $years,
                'total_interest' => $totalInterest,
                'total_return' => $amount + $totalInterest,
                'coupon_count' => 0,
                'coupon_value' => 0,
            ];
        }

        // OAT : coupons annuels (ou semestriels → mêmes totaux)
        $couponCount = (int) ceil($years);
        $annualCoupon = $amount * ($rate / 100);
        $totalCoupons = $annualCoupon * $years;

        return [
            'is_bat' => false,
            'amount' => $amount,
            'rate' => $rate,
            'years' => $years,
            'total_interest' => $totalCoupons,
            'total_return' => $amount + $totalCoupons,
            'coupon_count' => $couponCount,
            'coupon_value' => $annualCoupon,
        ];
    }

    // ─── Actions diverses ────────────────────────────────────────
    public function resetFilters(): void
    {
        $this->reset([
            'searchTerm', 'countryFilter', 'typeFilter',
            'riskFilter', 'maturityFilter', 'sortBy', 'sortDirection',
        ]);
        $this->sortBy = 'auction_date';
        $this->sortDirection = 'desc';
    }

    public function refreshData(UMOATitresService $service): void
    {
        $service->clearCache();
        $stats = $service->syncBonds(false);
        $this->refreshLastSyncAt();
        session()->flash('success', "Synchronisation UMOA-Titres : {$stats['created']} nouveau(x) · {$stats['updated']} mis à jour.");
    }

    public function submit(): void
    {
        $this->submitAppointment('obligations');
    }

    public function render()
    {
        return view('livewire.pages.investir-obligations')
            ->extends('layouts.site', ['title' => "Investir sur les Obligations d'États UEMOA"])
            ->section('content');
    }
}
