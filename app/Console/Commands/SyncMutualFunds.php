<?php

namespace App\Console\Commands;

use App\Services\MutualFundsApiService;
use Illuminate\Console\Command;

/**
 * Réchauffe le cache des valeurs liquidatives FCP depuis Sikafinance.
 *
 * Usage :
 *   php artisan fcp:sync           Scrape et peuple le cache (invalidé d'abord)
 *   php artisan fcp:sync --list    Scrape puis affiche le tableau
 */
class SyncMutualFunds extends Command
{
    protected $signature = 'fcp:sync {--list : Afficher la liste après synchronisation}';
    protected $description = 'Synchronise les valeurs liquidatives des FCP UEMOA depuis Sikafinance';

    public function handle(MutualFundsApiService $service): int
    {
        $this->info('► Synchronisation Sikafinance en cours…');
        $start = microtime(true);

        $service->clearCache();
        $funds = $service->getMutualFunds();
        $elapsed = round(microtime(true) - $start, 1);

        if (empty($funds)) {
            $this->error("Aucun fonds récupéré en {$elapsed}s. Sikafinance est-il accessible ?");
            return self::FAILURE;
        }

        $this->info("✓ {$elapsed}s · " . count($funds) . ' fonds en cache (' . count($service->getCategories()) . ' catégories)');

        if ($this->option('list')) {
            $this->table(
                ['Nom', 'Société', 'Catégorie', 'VL', 'Variation', 'Pays'],
                array_map(fn($f) => [
                    substr($f['name'], 0, 30),
                    substr($f['company'], 0, 25),
                    $f['category'],
                    $f['nav_value'],
                    $f['variation'],
                    $f['country'],
                ], $funds)
            );
        }

        return self::SUCCESS;
    }
}
