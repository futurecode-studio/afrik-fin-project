<?php

// ============================================================================
// EXEMPLES D'UTILISATION - SERVICE API VALEURS LIQUIDATIVES (VL/FCP)
// ============================================================================

// Cet fichier montre différents cas d'usage du service MutualFundsApiService

use App\Services\MutualFundsApiService;

// ============================================================================
// 1. UTILISATION DANS UN COMPOSANT LIVEWIRE
// ============================================================================

namespace App\Livewire\Examples;

use Livewire\Component;
use App\Services\MutualFundsApiService;

class MutualFundsExample extends Component
{
    public $funds = [];
    public $selectedFund = null;

    public function mount(MutualFundsApiService $service)
    {
        // Récupérer tous les fonds
        $this->funds = $service->getMutualFunds();
    }

    public function selectFund($fundId, MutualFundsApiService $service)
    {
        // Récupérer un fonds spécifique
        $this->selectedFund = $service->getFundById($fundId);
    }

    public function render()
    {
        return view('livewire.examples.mutual-funds');
    }
}

// ============================================================================
// 2. UTILISATION DANS UNE ROUTE WEB
// ============================================================================

// routes/web.php
use App\Services\MutualFundsApiService;

Route::get('/funds-dashboard', function (MutualFundsApiService $service) {
    $funds = $service->getMutualFunds();
    $categories = $service->getCategories();
    
    return view('dashboard.funds', [
        'funds' => $funds,
        'categories' => $categories,
    ]);
})->name('funds.dashboard');

// ============================================================================
// 3. UTILISATION DANS UN CONTRÔLEUR
// ============================================================================

namespace App\Http\Controllers;

use App\Services\MutualFundsApiService;

class MutualFundsController extends Controller
{
    private $service;

    public function __construct(MutualFundsApiService $service)
    {
        $this->service = $service;
    }

    // GET /funds
    public function index()
    {
        return view('funds.index', [
            'funds' => $this->service->getMutualFunds(),
            'categories' => $this->service->getCategories(),
        ]);
    }

    // GET /funds/{id}
    public function show($id)
    {
        $fund = $this->service->getFundById($id);

        if (!$fund) {
            return response()->view('errors.not-found', [], 404);
        }

        return view('funds.show', ['fund' => $fund]);
    }

    // GET /funds/category/{category}
    public function category($category)
    {
        $funds = $this->service->getFundsByCategory($category);

        return view('funds.category', [
            'category' => $category,
            'funds' => $funds,
            'count' => count($funds),
        ]);
    }

    // POST /funds/refresh
    public function refresh()
    {
        $this->service->clearCache();

        return redirect()->back()->with('success', 'Données actualisées');
    }
}

// ============================================================================
// 4. UTILISATION DANS UNE VUE BLADE
// ============================================================================

/*
<!-- resources/views/funds/dashboard.blade.php -->

@php
    $service = app(App\Services\MutualFundsApiService::class);
    $funds = $service->getMutualFunds();
    $categories = $service->getCategories();
@endphp

<div class="funds-dashboard">
    <h1>Fonds Disponibles</h1>

    <!-- Filtres par catégorie -->
    @foreach($categories as $category)
        <a href="{{ route('funds.category', $category) }}">
            {{ $category }} ({{ count($service->getFundsByCategory($category)) }})
        </a>
    @endforeach

    <!-- Tableau des fonds -->
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Société</th>
                <th>VL</th>
                <th>Variation</th>
            </tr>
        </thead>
        <tbody>
            @foreach($funds as $fund)
                <tr>
                    <td>{{ $fund['name'] }}</td>
                    <td>{{ $fund['company'] }}</td>
                    <td>{{ $fund['nav_value'] }}</td>
                    <td class="{{ $fund['variation_percentage'] >= 0 ? 'positive' : 'negative' }}">
                        {{ $fund['variation'] }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
*/

// ============================================================================
// 5. UTILISATION AVEC JAVASCRIPT/FETCH API
// ============================================================================

/*
<!-- Dans une vue Blade -->

<script>
    // Récupérer tous les fonds
    fetch('/api/mutual-funds')
        .then(response => response.json())
        .then(data => {
            console.log('Fonds:', data.data);
            displayFunds(data.data);
        })
        .catch(error => console.error('Erreur:', error));

    // Récupérer une catégorie spécifique
    async function loadCategory(category) {
        const response = await fetch(`/api/mutual-funds/category/${category}`);
        const data = await response.json();
        return data.data;
    }

    // Récupérer un fonds spécifique
    async function getFund(id) {
        const response = await fetch(`/api/mutual-funds/${id}`);
        const data = await response.json();
        
        if (data.success) {
            return data.data;
        } else {
            console.error('Fonds non trouvé');
            return null;
        }
    }

    function displayFunds(funds) {
        const html = funds.map(fund => `
            <div class="fund-card">
                <h3>${fund.name}</h3>
                <p>${fund.company}</p>
                <p class="nav">${fund.nav_value}</p>
                <p class="variation ${fund.variation_percentage >= 0 ? 'up' : 'down'}">
                    ${fund.variation}
                </p>
            </div>
        `).join('');
        
        document.getElementById('funds-container').innerHTML = html;
    }
</script>
*/

// ============================================================================
// 6. UTILISATION DANS UN JOB EN ARRIÈRE-PLAN (QUEUE)
// ============================================================================

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\MutualFundsApiService;
use App\Models\Notification;

class ProcessMutualFundsUpdate implements ShouldQueue
{
    use Queueable, SerializesModels, InteractsWithQueue;

    public function handle(MutualFundsApiService $service)
    {
        // Récupérer les fonds
        $funds = $service->getMutualFunds();

        // Analyser les variations
        $alerts = [];
        foreach ($funds as $fund) {
            if ($fund['variation_percentage'] > 5) {
                $alerts[] = [
                    'type' => 'high_increase',
                    'fund' => $fund['name'],
                    'variation' => $fund['variation_percentage'],
                ];
            } elseif ($fund['variation_percentage'] < -5) {
                $alerts[] = [
                    'type' => 'high_decrease',
                    'fund' => $fund['name'],
                    'variation' => $fund['variation_percentage'],
                ];
            }
        }

        // Créer les notifications
        foreach ($alerts as $alert) {
            Notification::create($alert);
        }
    }
}

// ============================================================================
// 7. UTILISATION DANS UN RAPPORT/DASHBOARD ADMIN
// ============================================================================

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Services\MutualFundsApiService;

class FundsReport extends Component
{
    public $funds = [];
    public $stats = [];

    public function mount(MutualFundsApiService $service)
    {
        $this->funds = $service->getMutualFunds();
        $this->calculateStats();
    }

    private function calculateStats()
    {
        $navValues = array_map(fn($f) => $f['nav_numeric'], $this->funds);
        $variations = array_map(fn($f) => $f['variation_percentage'], $this->funds);

        $this->stats = [
            'total_funds' => count($this->funds),
            'nav_min' => min($navValues),
            'nav_max' => max($navValues),
            'nav_avg' => array_sum($navValues) / count($navValues),
            'variation_avg' => array_sum($variations) / count($variations),
            'increasing' => count(array_filter($variations, fn($v) => $v > 0)),
            'decreasing' => count(array_filter($variations, fn($v) => $v < 0)),
            'stable' => count(array_filter($variations, fn($v) => $v == 0)),
        ];
    }

    public function render()
    {
        return view('livewire.admin.funds-report');
    }
}

// ============================================================================
// 8. UTILISATION AVEC EXPORT CSV
// ============================================================================

namespace App\Http\Controllers;

use App\Services\MutualFundsApiService;
use League\Csv\Writer;

class ExportController extends Controller
{
    public function exportFundsCSV(MutualFundsApiService $service)
    {
        $funds = $service->getMutualFunds();

        // Créer le fichier CSV
        $csv = Writer::createFromString('');
        
        // En-têtes
        $csv->insertOne(['ID', 'Nom', 'Société', 'VL', 'Variation', 'Catégorie', 'Date']);

        // Données
        foreach ($funds as $fund) {
            $csv->insertOne([
                $fund['id'],
                $fund['name'],
                $fund['company'],
                $fund['nav_numeric'],
                $fund['variation_percentage'],
                $fund['category'],
                $fund['date'],
            ]);
        }

        // Retourner le fichier
        return response($csv->toString(), 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="funds-' . date('Y-m-d') . '.csv"',
        ]);
    }
}

// ============================================================================
// 9. UTILISATION DANS UN TEST
// ============================================================================

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\MutualFundsApiService;

class MutualFundsTest extends TestCase
{
    public function test_can_fetch_mutual_funds()
    {
        $service = app(MutualFundsApiService::class);
        $funds = $service->getMutualFunds();

        $this->assertIsArray($funds);
        $this->assertNotEmpty($funds);
        $this->assertArrayHasKey('id', $funds[0]);
    }

    public function test_can_filter_by_category()
    {
        $service = app(MutualFundsApiService::class);
        $actionsFunds = $service->getFundsByCategory('Actions');

        $this->assertIsArray($actionsFunds);
        
        foreach ($actionsFunds as $fund) {
            $this->assertEquals('Actions', $fund['category']);
        }
    }
}

// ============================================================================
// 10. UTILISATION DANS UNE COMMANDE ARTISAN
// ============================================================================

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MutualFundsApiService;

class GenerateFundsReport extends Command
{
    protected $signature = 'funds:report';
    protected $description = 'Générer un rapport sur les fonds mutuels';

    public function handle(MutualFundsApiService $service)
    {
        $funds = $service->getMutualFunds();
        
        $this->info('Rapport des Fonds Mutuels');
        $this->line('===========================');
        $this->line('Total: ' . count($funds) . ' fonds');
        $this->line('');

        foreach ($service->getCategories() as $category) {
            $categoryFunds = $service->getFundsByCategory($category);
            $this->line($category . ': ' . count($categoryFunds) . ' fonds');
            
            foreach ($categoryFunds as $fund) {
                $this->line('  - ' . $fund['name'] . ' (' . $fund['nav_value'] . ')');
            }
        }
    }
}

// ============================================================================
// NOTES D'UTILISATION
// ============================================================================

/*
1. INJECTION DE DÉPENDANCES
   - Le service est automatiquement injecté via Laravel DI
   - Pas besoin de l'instancier manuellement
   - app(MutualFundsApiService::class) fonctionne aussi

2. CACHE
   - Les données sont en cache pendant 1 heure par défaut
   - Changer la durée: MUTUAL_FUNDS_CACHE_DURATION=3600
   - Effacer le cache: $service->clearCache()

3. PERFORMANCE
   - Première requête: plus lente (récupère depuis l'API)
   - Requêtes suivantes: rapides (depuis le cache)
   - Pour des updates en temps réel: réduire la durée du cache

4. GESTION D'ERREURS
   - Le service gère automatiquement les erreurs API
   - Fallback sur les données par défaut
   - Vérifier logs/laravel.log pour les erreurs

5. DONNÉES
   - Pas de données stockées en base de données
   - Toujours fraîches (du cache ou API)
   - Pas d'historique par défaut (peut être ajouté)

6. SÉCURITÉ
   - Aucune clé API requise
   - Données publiques uniquement
   - Pas de données sensibles
*/
