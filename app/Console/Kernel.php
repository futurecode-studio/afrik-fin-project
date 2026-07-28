<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Sync BRVM via Mansa (≈30 min freshness) — heures de séance UEMOA
        // ~2 appels Mansa / run (stocks + indices) → ~30–40 req/jour max
        $schedule->command('market:sync-brvm')
            ->weekdays()
            ->everyThirtyMinutes()
            ->between('08:00', '16:30')
            ->timezone('Africa/Abidjan')
            ->withoutOverlapping()
            ->onFailure(function () {
                \Log::error('market:sync-brvm : échec de l\'exécution planifiée.');
            });

        // Évalue les alertes clients juste après les fenêtres de sync cotations
        $schedule->command('market:evaluate-alerts')
            ->weekdays()
            ->everyThirtyMinutes()
            ->between('08:05', '16:35')
            ->timezone('Africa/Abidjan')
            ->withoutOverlapping()
            ->onFailure(function () {
                \Log::error('market:evaluate-alerts : échec de l\'exécution planifiée.');
            });

        // Snapshot indices fin de séance (réutilise Mansa / table market_indices)
        $schedule->command('brvm:snapshot')
            ->weekdays()
            ->at('17:00')
            ->timezone('Africa/Abidjan')
            ->withoutOverlapping()
            ->onFailure(function () {
                \Log::error('brvm:snapshot : échec de l\'exécution planifiée.');
            });

        // Synchronisation des obligations UEMOA depuis UMOA-Titres.
        // 2 fois par jour ouvré (matin + fin de journée) pour capturer au plus vite
        // les nouvelles adjudications publiées.
        $schedule->command('umoa:sync --force --purge')
            ->weekdays()
            ->twiceDaily(8, 20)
            ->timezone('Africa/Abidjan')
            ->withoutOverlapping()
            ->onFailure(function () {
                \Log::error('umoa:sync : échec de l\'exécution planifiée.');
            });

        // Synchronisation des valeurs liquidatives FCP depuis Sikafinance.
        // Réchauffe le cache 3 fois par jour ouvré pour des VL toujours fraîches.
        $schedule->command('fcp:sync')
            ->weekdays()
            ->cron('0 7,13,19 * * *')
            ->timezone('Africa/Abidjan')
            ->withoutOverlapping()
            ->onFailure(function () {
                \Log::error('fcp:sync : échec de l\'exécution planifiée.');
            });

        // Rappels événements J-7 et J-1 à 08:00 Abidjan.
        $schedule->command('event:reminders')
            ->dailyAt('08:00')
            ->timezone('Africa/Abidjan')
            ->withoutOverlapping()
            ->onFailure(function () {
                \Log::error('event:reminders : échec de l\'exécution planifiée.');
            });
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
