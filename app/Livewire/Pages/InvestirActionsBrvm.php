<?php

namespace App\Livewire\Pages;

use App\Models\InvestmentAppointment;
use App\Mail\InvestmentAppointmentNotification;
use App\Services\BRVMScraperService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;
use Carbon\Carbon;

class InvestirActionsBrvm extends Component
{
    // Formulaire de rendez-vous
    public $name = '';
    public $email = '';
    public $phone = '';
    public $company = '';
    public $message = '';

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

    protected $rules = [
        'name' => 'required|string|min:2|max:255',
        'email' => 'required|email|max:255',
        'phone' => ['required', 'string', 'regex:/^[+\d\s\-\(\)]{8,20}$/'],
        'company' => 'nullable|string|max:255',
        'message' => 'nullable|string|max:1000',
    ];

    protected $messages = [
        'name.required' => 'Votre nom est requis.',
        'name.min' => 'Votre nom doit contenir au moins 2 caractères.',
        'email.required' => 'Votre email est requis.',
        'email.email' => "L'email fourni n'est pas valide.",
        'phone.required' => 'Votre numéro de téléphone est requis.',
        'phone.regex' => 'Format de téléphone invalide (ex : +229 01 23 45 67).',
    ];

    public function boot(BRVMScraperService $brvmService)
    {
        $this->brvmService = $brvmService;
    }

    public function mount()
    {
        if (Auth::check()) {
            $this->name = Auth::user()->name;
            $this->email = Auth::user()->email;
        }

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

    public function loadChartData()
    {
        $days = 30;
        $labels = [];
        $data = [];
        
        $currentIndexValue = 347.81;
        
        foreach ($this->indices as $index) {
            $name = strtolower($index['name'] ?? '');
            if (str_contains($name, 'composite') || str_contains($name, 'brvm-c')) {
                $currentIndexValue = $index['value'] ?? $currentIndexValue;
                break;
            }
        }
        
        $seed = (int) date('Ymd');
        mt_srand($seed);
        
        $totalVariation = (mt_rand(-500, 500) / 100);
        $dailyVariation = $totalVariation / $days;
        
        $startValue = $currentIndexValue / (1 + ($totalVariation / 100));
        $baseValue = $startValue;
        
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('d/m');
            
            $noise = (mt_rand(-50, 50) / 100);
            $dayVariation = $dailyVariation + $noise;
            $baseValue = $baseValue * (1 + ($dayVariation / 100));
            
            if ($i === 0) {
                $baseValue = $currentIndexValue;
            }
            
            $data[] = round($baseValue, 2);
        }
        
        mt_srand();
        
        $this->chartData = [
            'labels' => $labels,
            'data' => $data,
            'currentValue' => $currentIndexValue,
            'is_simulated' => true, // Les données historiques affichées sont une simulation tant qu'un flux officiel n'est pas branché.
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
        foreach ($this->allStocks as $stock) {
            if ($stock['symbol'] === $symbol) {
                $this->selectedStock = $stock;
                $this->showModal = true;
                break;
            }
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedStock = null;
    }

    public function submit()
    {
        // Rate-limiting anti-spam : 3 tentatives / 10 min par IP ou utilisateur
        $throttleKey = 'brvm-appointment:' . (Auth::id() ?: request()->ip());
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            session()->flash('error', "Trop de demandes. Réessayez dans {$seconds} secondes.");
            return;
        }
        RateLimiter::hit($throttleKey, 600);

        $this->validate();

        $appointment = InvestmentAppointment::create([
            'user_id' => Auth::id(),
            'investment_type' => 'actions_brvm',
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'company' => $this->company,
            'message' => $this->message,
            'status' => 'pending',
        ]);

        // Envoi des emails de manière non-bloquante (queue si disponible, sinon try/catch)
        $adminEmail = config('mail.from.address') ?: env('MAIL_ADMIN_ADDRESS');
        try {
            Mail::to($this->email)->queue(new InvestmentAppointmentNotification($appointment, false));
            if ($adminEmail) {
                Mail::to($adminEmail)->queue(new InvestmentAppointmentNotification($appointment, true));
            }
        } catch (\Throwable $e) {
            Log::error('Envoi email RDV BRVM échoué', [
                'appointment_id' => $appointment->id,
                'error' => $e->getMessage(),
            ]);
            // On n'interrompt pas l'utilisateur : la demande est enregistrée en base.
        }

        session()->flash('success', 'Votre demande de rendez-vous a été envoyée avec succès ! Nous vous contacterons bientôt.');

        $this->reset(['company', 'message']);
    }

    public function render()
    {
        return view('livewire.pages.investir-actions-brvm')
            ->extends('layouts.site', ['title' => 'Investir sur les Actions BRVM'])
            ->section('content');
    }
}
