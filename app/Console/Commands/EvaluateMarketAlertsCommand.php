<?php

namespace App\Console\Commands;

use App\Services\MarketAlertEvaluator;
use Illuminate\Console\Command;

class EvaluateMarketAlertsCommand extends Command
{
    protected $signature = 'market:evaluate-alerts {--user= : Limiter à un utilisateur}';

    protected $description = 'Évalue les alertes marché actives contre les cotations BRVM';

    public function handle(MarketAlertEvaluator $evaluator): int
    {
        $userId = $this->option('user') !== null ? (int) $this->option('user') : null;
        $triggered = $evaluator->evaluate($userId);

        $this->info($triggered->count().' alerte(s) déclenchée(s).');

        foreach ($triggered as $alert) {
            $this->line(sprintf(
                '  #%d %s — %s',
                $alert->id,
                $alert->stock?->symbol ?? '?',
                $alert->notes
            ));
        }

        return self::SUCCESS;
    }
}
