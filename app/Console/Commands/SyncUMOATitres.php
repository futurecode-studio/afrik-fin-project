<?php

namespace App\Console\Commands;

use App\Services\UMOATitresService;
use Illuminate\Console\Command;

/**
 * Synchronise la table government_bonds avec les adjudications publiées
 * sur UMOA-Titres (https://www.umoatitres.org).
 *
 * Usage :
 *   php artisan umoa:sync                  # sync simple (upsert)
 *   php artisan umoa:sync --purge          # + désactive les titres qui n'apparaissent plus
 *   php artisan umoa:sync --force          # vide le cache avant sync
 */
class SyncUMOATitres extends Command
{
    protected $signature = 'umoa:sync
        {--force : Vide le cache avant de relancer le scraping}
        {--purge : Désactive les obligations UMOA-Titres qui n\'apparaissent plus dans les résultats}';

    protected $description = 'Synchronise les obligations UEMOA depuis UMOA-Titres (scraping HTML, sans API)';

    public function handle(UMOATitresService $service): int
    {
        if ($this->option('force')) {
            $service->clearCache();
            $this->info('Cache UMOA-Titres vidé.');
        }

        $this->info('Récupération des adjudications UMOA-Titres...');
        $stats = $service->syncBonds($this->option('purge'));

        $this->newLine();
        $this->table(
            ['Total parsés', 'Créés', 'Mis à jour', 'Désactivés'],
            [[$stats['total'], $stats['created'], $stats['updated'], $stats['deactivated']]]
        );

        if ($stats['total'] === 0) {
            $this->warn('Aucune adjudication parsée. Causes possibles :');
            $this->line('  • La structure HTML d\'UMOA-Titres a changé (ajuster les regex dans UMOATitresService)');
            $this->line('  • Le site est temporairement indisponible');
            $this->line('  • Aucune adjudication publiée à cette date');
            return self::FAILURE;
        }

        $this->info('Synchronisation terminée avec succès.');
        return self::SUCCESS;
    }
}
