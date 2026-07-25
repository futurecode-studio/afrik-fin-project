<?php

namespace App\Console\Commands;

use App\Services\Mansa\BrvmMarketSyncService;
use App\Services\Mansa\MansaMarketsClient;
use Illuminate\Console\Command;

class SyncBrvmFromMansa extends Command
{
    protected $signature = 'market:sync-brvm
                            {--indices : Forcer aussi la sync des indices (défaut: oui)}
                            {--no-indices : Ne pas appeler l’endpoint indices}';

    protected $description = 'Synchronise les cotations BRVM depuis Mansa API vers la base locale';

    public function handle(MansaMarketsClient $client, BrvmMarketSyncService $sync): int
    {
        if (! $client->isConfigured()) {
            $this->error('MANSA_API_KEY manquante. Ajoutez-la dans .env');

            return self::FAILURE;
        }

        $withIndices = ! $this->option('no-indices');

        $this->info('Sync BRVM via Mansa…');

        try {
            $stats = $sync->sync($withIndices);
        } catch (\Throwable $e) {
            $this->error('Échec: '.$e->getMessage());

            return self::FAILURE;
        }

        $this->table(
            ['Stocks MAJ', 'Historique ajouté', 'Historique skip', 'Indices'],
            [[$stats['stocks'], $stats['history'], $stats['skipped_history'], $stats['indices']]]
        );

        return self::SUCCESS;
    }
}
