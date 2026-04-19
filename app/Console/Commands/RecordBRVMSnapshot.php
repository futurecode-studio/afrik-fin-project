<?php

namespace App\Console\Commands;

use App\Services\BRVMScraperService;
use Illuminate\Console\Command;

/**
 * Enregistre un snapshot quotidien des indices BRVM dans la table market_index_history.
 * À lancer quotidiennement (schedule dans Kernel) pour constituer l'historique réel
 * utilisé par le graphique de la page /investir/actions-brvm.
 */
class RecordBRVMSnapshot extends Command
{
    protected $signature = 'brvm:snapshot {--force : Force le rafraîchissement du cache avant snapshot}';
    protected $description = 'Enregistre les indices BRVM du jour (BRVM Composite, BRVM 30, …) pour alimenter l\'historique.';

    public function handle(BRVMScraperService $service): int
    {
        if ($this->option('force')) {
            $service->refreshData();
            $this->info('Cache BRVM vidé avant snapshot.');
        }

        $count = $service->recordDailySnapshot();

        if ($count === 0) {
            $this->warn('Aucun indice disponible. Snapshot non enregistré.');
            return self::FAILURE;
        }

        $this->info("{$count} indice(s) enregistré(s) pour aujourd'hui.");
        return self::SUCCESS;
    }
}
