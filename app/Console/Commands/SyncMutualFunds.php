<?php

namespace App\Console\Commands;

use App\Services\MutualFundsApiService;
use Illuminate\Console\Command;

class SyncMutualFunds extends Command
{
    protected $signature = 'fcp:sync {--list : Afficher la liste après rechargement}';

    protected $description = 'Recharge le cache des FCP (bulletin BRVM / admin)';

    public function handle(MutualFundsApiService $service): int
    {
        $this->info('► Chargement du catalogue FCP (bulletin BRVM / admin)…');
        $start = microtime(true);

        $service->clearCache();
        $funds = $service->getMutualFunds();
        $elapsed = round(microtime(true) - $start, 1);

        if (empty($funds)) {
            $this->error('Aucun FCP dans le catalogue. Vérifiez Admin → FCP / OPCVM.');

            return self::FAILURE;
        }

        $this->info("✓ {$elapsed}s · ".count($funds).' fonds en cache ('.count($service->getCategories()).' catégories)');

        if ($this->option('list')) {
            $this->table(
                ['Nom', 'SGO', 'Catégorie', 'VL', 'Var. origine', 'Pays'],
                array_map(fn ($f) => [
                    substr($f['name'], 0, 32),
                    substr($f['company'], 0, 28),
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
