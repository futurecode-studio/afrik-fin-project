<?php

namespace App\Services;

use App\Models\MarketIndex;
use App\Models\MarketIndexHistory;
use App\Models\Stock;
use App\Services\Mansa\BrvmMarketSyncService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Lecture des données BRVM depuis la base (alimentée par Mansa via market:sync-brvm).
 * Plus aucun scraping RichBourse / BRVM.org.
 */
class BRVMScraperService
{
    private int $cacheDuration;

    public function __construct()
    {
        $this->cacheDuration = (int) config('services.brvm.cache_duration', 900);
    }

    public function getStocks(): array
    {
        return Cache::remember('brvm_stocks', $this->cacheDuration, function () {
            return Stock::query()
                ->where('is_active', true)
                ->where(function ($q) {
                    $q->where('exchange', 'BRVM')->orWhereNull('exchange');
                })
                ->orderBy('symbol')
                ->get()
                ->map(fn (Stock $stock) => $this->mapStock($stock))
                ->values()
                ->all();
        });
    }

    public function getIndices(): array
    {
        return Cache::remember('brvm_indices', $this->cacheDuration, function () {
            $live = MarketIndex::query()
                ->where('exchange', 'BRVM')
                ->orderBy('code')
                ->get();

            if ($live->isNotEmpty()) {
                return $live->map(fn (MarketIndex $i) => [
                    'name' => $i->name,
                    'code' => $i->code,
                    'value' => (float) $i->value,
                    'variation_percent' => $i->change_percent !== null ? (float) $i->change_percent : null,
                    'source' => $i->source ?? 'mansa',
                ])->values()->all();
            }

            // Fallback: derniers snapshots historiques
            $names = MarketIndexHistory::query()
                ->select('index_name')
                ->distinct()
                ->pluck('index_name');

            $out = [];
            foreach ($names as $name) {
                $row = MarketIndexHistory::query()
                    ->where('index_name', $name)
                    ->orderByDesc('snapshot_date')
                    ->first();
                if ($row) {
                    $out[] = [
                        'name' => $row->index_name,
                        'value' => (float) $row->value,
                        'variation_percent' => $row->variation_percent !== null ? (float) $row->variation_percent : null,
                        'source' => $row->source ?? 'history',
                    ];
                }
            }

            return $out;
        });
    }

    public function getStock(string $symbol): ?array
    {
        $symbol = strtoupper(trim($symbol));
        $stock = Stock::query()
            ->where('is_active', true)
            ->where('symbol', $symbol)
            ->first();

        return $stock ? $this->mapStock($stock) : null;
    }

    public function refreshData(): void
    {
        Cache::forget('brvm_stocks');
        Cache::forget('brvm_indices');
        Cache::forget('markets.stocks.active');
    }

    /**
     * Force une sync Mansa puis vide les caches locaux.
     */
    public function syncFromMansa(bool $withIndices = true): array
    {
        $stats = app(BrvmMarketSyncService::class)->sync($withIndices);
        $this->refreshData();

        return $stats;
    }

    public function isConfigured(): bool
    {
        return filled(config('services.mansa.api_key'))
            || Stock::query()->where('is_active', true)->exists();
    }

    public function recordDailySnapshot(): int
    {
        // Préfère sync indices Mansa (1 appel) puis écrit l'historique
        try {
            if (filled(config('services.mansa.api_key'))) {
                return app(BrvmMarketSyncService::class)->syncIndices();
            }
        } catch (\Throwable $e) {
            Log::warning('recordDailySnapshot Mansa: '.$e->getMessage());
        }

        $indices = $this->getIndices();
        $count = 0;
        $today = now()->toDateString();

        foreach ($indices as $index) {
            $name = $index['name'] ?? null;
            $value = $index['value'] ?? null;
            if (! $name || $value === null) {
                continue;
            }

            MarketIndexHistory::updateOrCreate(
                ['index_name' => $name, 'snapshot_date' => $today],
                [
                    'value' => $value,
                    'variation_percent' => $index['variation_percent'] ?? null,
                    'source' => $index['source'] ?? 'database',
                ]
            );
            $count++;
        }

        return $count;
    }

    public function getIndexHistory(string $indexName = 'BRVM Composite', int $days = 30): array
    {
        return MarketIndexHistory::forIndex($indexName, $days)->get()->map(fn ($r) => [
            'date' => $r->snapshot_date->format('d/m'),
            'value' => (float) $r->value,
            'source' => $r->source,
        ])->toArray();
    }

    private function mapStock(Stock $stock): array
    {
        return [
            'symbol' => $stock->symbol,
            'company_name' => $stock->company_name,
            'current_price' => (float) $stock->current_price,
            'previous_price' => $stock->previous_price !== null ? (float) $stock->previous_price : null,
            'variation_percent' => (float) $stock->variation_percent,
            'volume' => (int) $stock->volume,
            'market_cap' => $stock->market_cap !== null ? (float) $stock->market_cap : 0,
            'sector' => $stock->sector ?? 'Autre',
            'exchange' => $stock->exchange ?? 'BRVM',
            'currency' => $stock->currency ?? 'XOF',
            'source' => $stock->source ?? 'database',
            'updated_at' => optional($stock->source_updated_at ?? $stock->last_updated)->toIso8601String(),
        ];
    }
}
