<?php

namespace App\Services\Mansa;

use App\Models\MarketIndex;
use App\Models\MarketIndexHistory;
use App\Models\Stock;
use App\Models\StockPrice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Sync BRVM via Mansa → PostgreSQL/MySQL.
 * 1–2 appels Mansa max par exécution (stocks + indices).
 */
class BrvmMarketSyncService
{
    public function __construct(private MansaMarketsClient $client) {}

    /**
     * @return array{stocks:int, history:int, indices:int, skipped_history:int}
     */
    public function sync(bool $withIndices = true): array
    {
        $stats = [
            'stocks' => 0,
            'history' => 0,
            'indices' => 0,
            'skipped_history' => 0,
        ];

        $payload = $this->client->allStocks('BRVM');
        $rows = $payload['data'] ?? [];
        $meta = $payload['meta'] ?? [];
        $currency = $meta['currency'] ?? 'XOF';
        $sourceUpdatedAt = isset($meta['updated_at'])
            ? Carbon::parse($meta['updated_at'])
            : now();

        DB::transaction(function () use ($rows, $currency, $sourceUpdatedAt, &$stats) {
            $seen = [];

            foreach ($rows as $item) {
                $ticker = strtoupper(trim((string) ($item['ticker'] ?? '')));
                if ($ticker === '') {
                    continue;
                }

                $price = $this->num($item['price'] ?? null);
                $change = $this->num($item['change'] ?? null);
                $changePct = $this->num($item['change_pct'] ?? $item['change_percent'] ?? null);
                $volume = (int) ($item['volume'] ?? 0);
                $name = (string) ($item['name'] ?? $ticker);
                $scrapedAt = isset($item['scraped_at'])
                    ? Carbon::parse($item['scraped_at'])
                    : $sourceUpdatedAt;

                $previous = null;
                if ($price !== null && $change !== null) {
                    $previous = $price - $change;
                }

                $stock = Stock::updateOrCreate(
                    ['symbol' => $ticker, 'exchange' => 'BRVM'],
                    [
                        'company_name' => $name,
                        'currency' => $currency,
                        'current_price' => $price ?? 0,
                        'previous_price' => $previous,
                        'change_amount' => $change,
                        'variation_percent' => $changePct ?? 0,
                        'volume' => $volume,
                        'is_active' => true,
                        'source' => 'mansa',
                        'source_updated_at' => $scrapedAt,
                        'last_updated' => now(),
                    ]
                );

                $seen[] = $stock->id;
                $stats['stocks']++;

                if ($this->shouldRecordHistory($stock, $price, $volume, $changePct)) {
                    StockPrice::create([
                        'stock_id' => $stock->id,
                        'price' => $price ?? 0,
                        'open' => $stock->open_price,
                        'high' => $stock->high_price,
                        'low' => $stock->low_price,
                        'volume' => $volume,
                        'change_amount' => $change,
                        'change_percent' => $changePct,
                        'recorded_at' => $scrapedAt,
                    ]);
                    $stats['history']++;
                } else {
                    $stats['skipped_history']++;
                }
            }

            if ($seen !== []) {
                Stock::query()
                    ->where('exchange', 'BRVM')
                    ->whereNotIn('id', $seen)
                    ->update(['is_active' => false]);
            }
        });

        if ($withIndices) {
            $stats['indices'] = $this->syncIndices();
        }

        $this->flushCaches();

        Log::info('BRVM Mansa sync terminé', $stats);

        return $stats;
    }

    public function syncIndices(): int
    {
        try {
            $payload = $this->client->indices('BRVM');
        } catch (\Throwable $e) {
            Log::warning('Mansa indices BRVM indisponibles: '.$e->getMessage());

            return 0;
        }

        $rows = $payload['data'] ?? [];
        if (! is_array($rows)) {
            return 0;
        }

        $count = 0;
        $today = now()->toDateString();

        foreach ($rows as $item) {
            $code = strtoupper(trim((string) ($item['code'] ?? $item['slug'] ?? '')));
            $name = (string) ($item['name'] ?? $code);
            if ($code === '' && $name === '') {
                continue;
            }
            if ($code === '') {
                $code = strtoupper(str_replace(' ', '-', $name));
            }

            $value = $this->num($item['value'] ?? $item['currentPrice'] ?? null);
            if ($value === null) {
                continue;
            }

            $changePct = $this->num($item['change_pct'] ?? $item['changePercentage'] ?? $item['change_percent'] ?? null);
            $change = $this->num($item['change'] ?? $item['change_points'] ?? null);
            $updatedAt = isset($item['updated_at'])
                ? Carbon::parse($item['updated_at'])
                : (isset($item['currentDateTime']) ? Carbon::parse($item['currentDateTime']) : now());

            MarketIndex::updateOrCreate(
                ['code' => $code, 'exchange' => 'BRVM'],
                [
                    'name' => $name,
                    'currency' => $item['currency'] ?? 'XOF',
                    'value' => $value,
                    'change' => $change,
                    'change_percent' => $changePct,
                    'source' => 'mansa',
                    'source_updated_at' => $updatedAt,
                ]
            );

            MarketIndexHistory::updateOrCreate(
                ['index_name' => $name, 'snapshot_date' => $today],
                [
                    'value' => $value,
                    'variation_percent' => $changePct,
                    'source' => 'mansa',
                ]
            );

            $count++;
        }

        return $count;
    }

    private function shouldRecordHistory(Stock $stock, ?float $price, int $volume, ?float $changePct): bool
    {
        if ($price === null) {
            return false;
        }

        $last = StockPrice::query()
            ->where('stock_id', $stock->id)
            ->orderByDesc('recorded_at')
            ->first();

        if (! $last) {
            return true;
        }

        // Évite les doublons si Mansa n’a pas bougé
        return (float) $last->price !== (float) $price
            || (int) $last->volume !== $volume
            || (float) ($last->change_percent ?? 0) !== (float) ($changePct ?? 0);
    }

    private function flushCaches(): void
    {
        foreach ([
            'brvm_stocks',
            'brvm_indices',
            'markets.stocks.active',
            'markets.index.names',
            'brvm.stocks',
            'api.v1.market.overview',
            'api.v1.stocks',
            'api.v1.indices',
        ] as $key) {
            Cache::forget($key);
        }

        // Prefixed / pattern caches
        Cache::forget('markets.bonds.active');
    }

    private function num(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (float) $value;
    }
}
