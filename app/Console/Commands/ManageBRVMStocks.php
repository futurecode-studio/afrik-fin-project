<?php

namespace App\Console\Commands;

use App\Services\BRVMScraperService;
use Illuminate\Console\Command;

class ManageBRVMStocks extends Command
{
    protected $signature = 'brvm:stocks {action=list : Action (list, info, refresh, clear, sync)}';

    protected $description = 'Gérer les cotations BRVM (données locales / sync Mansa)';

    public function __construct(private BRVMScraperService $brvmService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        return match ($this->argument('action')) {
            'list' => $this->listStocks(),
            'info' => $this->infoCmd(),
            'refresh', 'sync' => $this->sync(),
            'clear' => $this->clearCache(),
            default => $this->invalid(),
        };
    }

    private function listStocks(): int
    {
        $stocks = $this->brvmService->getStocks();
        $this->info(count($stocks).' titre(s) BRVM');
        $this->table(
            ['Symbole', 'Nom', 'Prix', 'Var %', 'Volume', 'Source'],
            collect($stocks)->take(20)->map(fn ($s) => [
                $s['symbol'],
                mb_substr($s['company_name'] ?? '', 0, 28),
                $s['current_price'],
                $s['variation_percent'],
                $s['volume'],
                $s['source'] ?? '',
            ])->all()
        );

        return self::SUCCESS;
    }

    private function infoCmd(): int
    {
        $stocks = $this->brvmService->getStocks();
        $indices = $this->brvmService->getIndices();
        $this->line('Stocks: '.count($stocks));
        $this->line('Indices: '.count($indices));
        $this->line('Mansa key: '.(filled(config('services.mansa.api_key')) ? 'OK' : 'MANQUANTE'));
        $this->line('Configured: '.($this->brvmService->isConfigured() ? 'yes' : 'no'));

        return self::SUCCESS;
    }

    private function sync(): int
    {
        if (! filled(config('services.mansa.api_key'))) {
            $this->error('MANSA_API_KEY manquante');

            return self::FAILURE;
        }

        $this->info('Sync Mansa…');
        $stats = $this->brvmService->syncFromMansa(true);
        $this->info("Stocks={$stats['stocks']} history={$stats['history']} indices={$stats['indices']}");

        return self::SUCCESS;
    }

    private function clearCache(): int
    {
        $this->brvmService->refreshData();
        $this->info('Caches BRVM vidés.');

        return self::SUCCESS;
    }

    private function invalid(): int
    {
        $this->error('Action invalide. Utilisez: list, info, refresh, clear, sync');

        return self::FAILURE;
    }
}
