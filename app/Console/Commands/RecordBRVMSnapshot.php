<?php

namespace App\Console\Commands;

use App\Services\BRVMScraperService;
use Illuminate\Console\Command;

/**
 * Enregistre / rafraîchit les indices BRVM (via Mansa si clé présente).
 */
class RecordBRVMSnapshot extends Command
{
    protected $signature = 'brvm:snapshot {--force : Vide le cache local avant snapshot}';
    protected $description = 'Enregistre les indices BRVM du jour pour l\'historique.';

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
