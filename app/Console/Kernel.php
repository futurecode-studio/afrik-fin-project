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
        // Snapshot quotidien des indices BRVM à 19h (après clôture du marché UEMOA),
        // du lundi au vendredi. Alimente l'historique utilisé par le graphique.
        $schedule->command('brvm:snapshot --force')
            ->weekdays()
            ->at('19:00')
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
