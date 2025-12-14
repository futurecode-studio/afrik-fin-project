<?php

namespace App\Console\Commands;

use App\Services\BRVMScraperService;
use Illuminate\Console\Command;

class ManageBRVMStocks extends Command
{
    protected $signature = 'brvm:stocks {action=list : Action à effectuer (list, info, refresh, clear)}';
    protected $description = 'Gérer les données boursières BRVM';

    protected $brvmService;

    public function __construct(BRVMScraperService $brvmService)
    {
        parent::__construct();
        $this->brvmService = $brvmService;
    }

    public function handle()
    {
        $action = $this->argument('action');

        switch ($action) {
            case 'list':
                $this->listStocks();
                break;
            case 'info':
                $this->showInfo();
                break;
            case 'refresh':
                $this->refreshData();
                break;
            case 'clear':
                $this->clearCache();
                break;
            default:
                $this->error("Action inconnue: {$action}");
                $this->info("Actions disponibles: list, info, refresh, clear");
        }

        return 0;
    }

    private function listStocks()
    {
        $this->info('Récupération des cours BRVM...');
        
        $stocks = $this->brvmService->getStocks();
        
        if (empty($stocks)) {
            $this->warn('Aucune donnée disponible.');
            return;
        }

        $headers = ['Symbole', 'Société', 'Cours', 'Variation', 'Secteur', 'Source'];
        $rows = [];

        foreach ($stocks as $stock) {
            $variation = $stock['variation_percent'] ?? 0;
            $variationStr = ($variation >= 0 ? '+' : '') . number_format($variation, 2) . '%';
            
            $rows[] = [
                $stock['symbol'],
                substr($stock['company_name'] ?? 'N/A', 0, 25),
                number_format($stock['current_price'] ?? 0, 0, ',', ' ') . ' FCFA',
                $variationStr,
                substr($stock['sector'] ?? 'N/A', 0, 15),
                $stock['source'] ?? 'unknown',
            ];
        }

        $this->table($headers, $rows);
        $this->info("Total: " . count($stocks) . " actions");
    }

    private function showInfo()
    {
        $this->info('=== Informations BRVM ===');
        $this->newLine();

        $stocks = $this->brvmService->getStocks();
        $indices = $this->brvmService->getIndices();

        // Statistiques générales
        $this->info('Statistiques générales:');
        $this->line("  • Nombre d'actions: " . count($stocks));
        
        // Secteurs
        $sectors = [];
        foreach ($stocks as $stock) {
            $sector = $stock['sector'] ?? 'Autre';
            $sectors[$sector] = ($sectors[$sector] ?? 0) + 1;
        }
        $this->line("  • Secteurs: " . implode(', ', array_keys($sectors)));

        $this->newLine();
        $this->info('Actions par secteur:');
        foreach ($sectors as $sector => $count) {
            $this->line("  • {$sector}: {$count} actions");
        }

        $this->newLine();
        $this->info('Indices BRVM:');
        foreach ($indices as $index) {
            $variation = $index['variation_percent'] ?? 0;
            $variationStr = ($variation >= 0 ? '+' : '') . number_format($variation, 2) . '%';
            $this->line("  • {$index['name']}: " . number_format($index['value'], 2) . " ({$variationStr}) - Source: " . ($index['source'] ?? 'unknown'));
        }

        $this->newLine();
        $this->info('Configuration:');
        $this->line("  • Cache duration: " . config('services.brvm.cache_duration', 300) . "s");
        $this->line("  • Timeout: " . config('services.brvm.timeout', 30) . "s");

        // Source des données
        if (!empty($stocks)) {
            $source = $stocks[0]['source'] ?? 'unknown';
            $this->newLine();
            $this->info("Source des données: {$source}");
        }

        // Statistiques de prix
        if (!empty($stocks)) {
            $prices = array_column($stocks, 'current_price');
            $this->newLine();
            $this->info('Statistiques de prix:');
            $this->line("  • Prix min: " . number_format(min($prices), 0, ',', ' ') . " FCFA");
            $this->line("  • Prix max: " . number_format(max($prices), 0, ',', ' ') . " FCFA");
            $this->line("  • Prix moyen: " . number_format(array_sum($prices) / count($prices), 0, ',', ' ') . " FCFA");
        }

        // Variations
        $hausses = 0;
        $baisses = 0;
        foreach ($stocks as $stock) {
            $variation = $stock['variation_percent'] ?? 0;
            if ($variation > 0) $hausses++;
            elseif ($variation < 0) $baisses++;
        }
        $this->newLine();
        $this->info('Variations:');
        $this->line("  • Hausses: {$hausses}");
        $this->line("  • Baisses: {$baisses}");
        $this->line("  • Stables: " . (count($stocks) - $hausses - $baisses));
    }

    private function refreshData()
    {
        $this->info('Rafraîchissement des données BRVM...');
        $this->brvmService->refreshData();
        
        // Recharger les données
        $stocks = $this->brvmService->getStocks();
        
        $this->info("✓ Données rafraîchies: " . count($stocks) . " actions chargées");
        
        if (!empty($stocks)) {
            $source = $stocks[0]['source'] ?? 'unknown';
            $this->info("  Source: {$source}");
        }
    }

    private function clearCache()
    {
        $this->info('Suppression du cache BRVM...');
        $this->brvmService->refreshData();
        $this->info('✓ Cache supprimé avec succès');
    }
}
