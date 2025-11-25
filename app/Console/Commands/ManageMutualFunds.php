<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MutualFundsApiService;

class ManageMutualFunds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mutual-funds {action : Action à effectuer (list, clear, refresh, info)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gérer les données des fonds mutuels';

    private $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = app(MutualFundsApiService::class);
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');

        match ($action) {
            'list' => $this->listFunds(),
            'clear' => $this->clearCache(),
            'refresh' => $this->refreshData(),
            'info' => $this->showInfo(),
            default => $this->error("Action inconnue: {$action}"),
        };
    }

    /**
     * Lister tous les fonds
     */
    private function listFunds()
    {
        $this->info('Récupération des fonds...');
        
        try {
            $funds = $this->service->getMutualFunds();

            if (empty($funds)) {
                $this->warn('Aucun fonds trouvé');
                return;
            }

            $headers = ['ID', 'Nom', 'Société', 'VL', 'Variation', 'Catégorie', 'Date'];
            $rows = [];

            foreach ($funds as $fund) {
                $rows[] = [
                    $fund['id'],
                    substr($fund['name'], 0, 25),
                    substr($fund['company'], 0, 20),
                    $fund['nav_value'],
                    $fund['variation'],
                    $fund['category'],
                    $fund['date'],
                ];
            }

            $this->table($headers, $rows);
            $this->info("Total: " . count($funds) . " fonds");

        } catch (\Exception $e) {
            $this->error('Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Effacer le cache
     */
    private function clearCache()
    {
        $this->info('Effacement du cache...');
        
        try {
            $this->service->clearCache();
            $this->info('✓ Cache effacé avec succès');
        } catch (\Exception $e) {
            $this->error('✗ Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Rafraîchir les données
     */
    private function refreshData()
    {
        $this->info('Rafraîchissement des données...');
        
        try {
            $this->service->clearCache();
            $funds = $this->service->getMutualFunds();

            if (empty($funds)) {
                $this->warn('Aucun fonds chargé');
            } else {
                $this->info("✓ {$funds[0]['date']} - " . count($funds) . " fonds chargés");
            }

        } catch (\Exception $e) {
            $this->error('✗ Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Afficher les informations du système
     */
    private function showInfo()
    {
        $this->info('=== Informations des Fonds Mutuels ===');
        
        try {
            $funds = $this->service->getMutualFunds();
            $categories = $this->service->getCategories();

            $this->line('');
            $this->info('Statistiques générales:');
            $this->line('  • Nombre total de fonds: ' . count($funds));
            $this->line('  • Catégories: ' . implode(', ', $categories));
            $this->line('');

            $this->info('Fonds par catégorie:');
            foreach ($categories as $category) {
                $count = count($this->service->getFundsByCategory($category));
                $this->line("  • {$category}: {$count} fonds");
            }

            $this->line('');
            $this->info('Configuration:');
            $this->line('  • Cache duration: ' . config('services.mutual_funds.cache_duration') . 's');
            $this->line('  • Timeout: ' . config('services.mutual_funds.timeout') . 's');
            $this->line('  • Cache driver: ' . config('cache.default'));
            $this->line('');

            // Statistiques de prix
            if (!empty($funds)) {
                $navValues = array_map(fn($f) => $f['nav_numeric'], $funds);
                $this->info('Statistiques de prix:');
                $this->line('  • VL min: ' . min($navValues));
                $this->line('  • VL max: ' . max($navValues));
                $this->line('  • VL moyenne: ' . number_format(array_sum($navValues) / count($navValues), 2));
                $this->line('');

                // Variation moyenne
                $variations = array_map(fn($f) => $f['variation_percentage'], $funds);
                $positives = count(array_filter($variations, fn($v) => $v >= 0));
                $negatives = count($variations) - $positives;
                $this->info('Variations:');
                $this->line("  • Hausse: {$positives}");
                $this->line("  • Baisse: {$negatives}");
            }

        } catch (\Exception $e) {
            $this->error('✗ Erreur: ' . $e->getMessage());
        }
    }
}
