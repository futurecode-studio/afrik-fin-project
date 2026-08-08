<?php

namespace App\Services\Mansa;

use App\Models\MarketIndex;
use App\Models\MarketIndexHistory;
use App\Models\Stock;
use App\Models\StockPrice;
use App\Services\BrvmSharesCatalog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Sync BRVM via Mansa → PostgreSQL/MySQL.
 * Cotations (stocks + indices) + enrichissement capitalisations (screener / fundamentals).
 */
class BrvmMarketSyncService
{
    public function __construct(
        private MansaMarketsClient $client,
        private BrvmSharesCatalog $sharesCatalog,
    ) {}

    /**
     * @return array{stocks:int, history:int, indices:int, skipped_history:int, market_caps:int}
     */
    public function sync(bool $withIndices = true): array
    {
        $stats = [
            'stocks' => 0,
            'history' => 0,
            'indices' => 0,
            'skipped_history' => 0,
            'market_caps' => 0,
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

                $price = $this->num($item['price'] ?? $item['close'] ?? $item['last'] ?? null);
                $change = $this->num($item['change'] ?? null);
                $changePct = $this->num($item['change_pct'] ?? $item['change_percent'] ?? null);
                $volume = (int) ($item['volume'] ?? 0);
                $name = (string) ($item['name'] ?? $ticker);
                $scrapedAt = isset($item['scraped_at'])
                    ? Carbon::parse($item['scraped_at'])
                    : $sourceUpdatedAt;

                $apiOpen = $this->num($item['open'] ?? $item['open_price'] ?? $item['day_open'] ?? null);
                $apiHigh = $this->num($item['high'] ?? $item['high_price'] ?? $item['day_high'] ?? $item['dayHigh'] ?? null);
                $apiLow = $this->num($item['low'] ?? $item['low_price'] ?? $item['day_low'] ?? $item['dayLow'] ?? null);
                $marketCap = $this->extractMarketCap($item);
                $sector = isset($item['sector']) && is_string($item['sector']) && $item['sector'] !== ''
                    ? $item['sector']
                    : null;
                $sector = match ($ticker) {
                    'BIIC' => 'Finance',
                    'NTLC' => 'Industrie',
                    default => $sector,
                };

                $previous = null;
                if ($price !== null && $change !== null) {
                    $previous = $price - $change;
                } else {
                    $previous = $this->num($item['previous_close'] ?? $item['previous_price'] ?? $item['prev_close'] ?? null);
                }

                $existing = Stock::query()
                    ->where('symbol', $ticker)
                    ->where('exchange', 'BRVM')
                    ->first();

                [$open, $high, $low] = $this->resolveSessionOhlc(
                    $existing,
                    $price,
                    $apiOpen,
                    $apiHigh,
                    $apiLow,
                    $scrapedAt
                );

                $payload = [
                    'company_name' => $name,
                    'currency' => $currency,
                    'current_price' => $price ?? 0,
                    'previous_price' => $previous,
                    'change_amount' => $change,
                    'variation_percent' => $changePct ?? 0,
                    'volume' => $volume,
                    'open_price' => $open,
                    'high_price' => $high,
                    'low_price' => $low,
                    'is_active' => true,
                    'source' => 'mansa',
                    'source_updated_at' => $scrapedAt,
                    'last_updated' => now(),
                ];

                // Priorité : actions × cours (catalogue BRVM) — plus fiable que les seeds / feeds partiels
                $shares = $this->sharesCatalog->sharesFor($ticker);
                if ($shares !== null) {
                    $payload['shares_outstanding'] = $shares;
                    $fromShares = $this->sharesCatalog->marketCapMillions($shares, (float) ($price ?? 0));
                    if ($fromShares !== null) {
                        $payload['market_cap'] = $fromShares;
                    }
                } elseif ($marketCap !== null) {
                    $payload['market_cap'] = Stock::normalizeCapToMillions($marketCap);
                }

                if ($sector !== null) {
                    $payload['sector'] = $sector;
                }

                $stock = Stock::updateOrCreate(
                    ['symbol' => $ticker, 'exchange' => 'BRVM'],
                    $payload
                );

                $seen[] = $stock->id;
                $stats['stocks']++;

                if ($this->shouldRecordHistory($stock, $price, $volume, $changePct)) {
                    StockPrice::create([
                        'stock_id' => $stock->id,
                        'price' => $price ?? 0,
                        'open' => $open,
                        'high' => $high,
                        'low' => $low,
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

        $stats['market_caps'] = $this->enrichMarketCaps();

        $this->flushCaches();

        Log::info('BRVM Mansa sync terminé', $stats);

        return $stats;
    }

    /**
     * Capitalisations : 1) actions × cours (catalogue BRVM), 2) screener/fundamentals Mansa.
     */
    public function enrichMarketCaps(): int
    {
        $updated = $this->recomputeMarketCapsFromShares();

        // Screener batch (complète les titres sans nombre d'actions)
        try {
            $payload = $this->client->screener([
                'exchange' => 'BRVM',
                'limit' => 200,
            ]);
            $updated += $this->applyMarketCapsFromRows($this->flattenScreenerRows($payload));
        } catch (\Throwable $e) {
            Log::warning('Mansa screener BRVM indisponible: '.$e->getMessage());
        }

        // Fundamentals / metrics pour les titres encore sans cap (par lots)
        $missing = Stock::query()
            ->where('exchange', 'BRVM')
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('market_cap')->orWhere('market_cap', '<=', 0);
            })
            ->orderBy('symbol')
            ->limit(20)
            ->get(['id', 'symbol']);

        foreach ($missing as $stock) {
            $failKey = 'mansa.cap.fail.'.$stock->symbol;
            if (Cache::has($failKey)) {
                continue;
            }

            $cap = $this->fetchMarketCapForTicker($stock->symbol);
            if ($cap === null) {
                Cache::put($failKey, 1, now()->addHours(12));
                continue;
            }

            $millions = Stock::normalizeCapToMillions($cap);
            if ($millions === null) {
                Cache::put($failKey, 1, now()->addHours(12));
                continue;
            }

            Stock::query()->where('id', $stock->id)->update(['market_cap' => $millions]);
            $updated++;
            usleep(80_000);
        }

        return $updated;
    }

    /**
     * Recalcule market_cap pour tous les titres ayant un nombre d'actions connu.
     */
    public function recomputeMarketCapsFromShares(): int
    {
        $catalog = $this->sharesCatalog->all();
        if ($catalog === []) {
            return 0;
        }

        $updated = 0;
        $stocks = Stock::query()
            ->where('exchange', 'BRVM')
            ->where('is_active', true)
            ->get(['id', 'symbol', 'current_price', 'shares_outstanding', 'market_cap']);

        foreach ($stocks as $stock) {
            $ticker = strtoupper((string) $stock->symbol);
            $shares = $catalog[$ticker] ?? (int) ($stock->shares_outstanding ?? 0);
            if ($shares <= 0) {
                continue;
            }

            $price = (float) ($stock->current_price ?? 0);
            $millions = $this->sharesCatalog->marketCapMillions($shares, $price);
            if ($millions === null) {
                continue;
            }

            $payload = [
                'shares_outstanding' => $shares,
                'market_cap' => $millions,
            ];

            // Évite les writes inutiles
            if ((int) ($stock->shares_outstanding ?? 0) === $shares
                && abs((float) ($stock->market_cap ?? 0) - $millions) < 0.01) {
                continue;
            }

            Stock::query()->where('id', $stock->id)->update($payload);
            $updated++;
        }

        return $updated;
    }

    /**
     * @param  array<int, array<string, mixed>>  $rows
     */
    private function applyMarketCapsFromRows(array $rows): int
    {
        $updated = 0;

        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }

            $ticker = strtoupper(trim((string) ($row['ticker'] ?? $row['symbol'] ?? $row['code'] ?? '')));
            if ($ticker === '') {
                continue;
            }

            $cap = $this->extractMarketCap($row);
            if ($cap === null) {
                continue;
            }

            $millions = Stock::normalizeCapToMillions($cap);
            if ($millions === null) {
                continue;
            }

            $affected = Stock::query()
                ->where('exchange', 'BRVM')
                ->where('symbol', $ticker)
                ->update(['market_cap' => $millions]);

            $updated += (int) $affected;
        }

        return $updated;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function flattenScreenerRows(array $payload): array
    {
        $data = $payload['data'] ?? [];

        if (! is_array($data)) {
            return [];
        }

        if (array_is_list($data)) {
            return $data;
        }

        foreach (['stocks', 'results', 'items', 'rows'] as $key) {
            if (isset($data[$key]) && is_array($data[$key])) {
                return array_values($data[$key]);
            }
        }

        return [];
    }

    private function fetchMarketCapForTicker(string $ticker): ?float
    {
        foreach (['fundamentals', 'metrics', 'stock'] as $method) {
            try {
                $payload = match ($method) {
                    'fundamentals' => $this->client->fundamentals('BRVM', $ticker),
                    'metrics' => $this->client->metrics('BRVM', $ticker),
                    default => $this->client->stock('BRVM', $ticker),
                };
            } catch (\Throwable) {
                continue;
            }

            $data = $payload['data'] ?? $payload;
            if (! is_array($data)) {
                continue;
            }

            // Certains endpoints encapsulent sous "fundamentals" / "metrics"
            foreach ([$data, $data['fundamentals'] ?? null, $data['metrics'] ?? null, $data['valuation'] ?? null] as $block) {
                if (! is_array($block)) {
                    continue;
                }
                $cap = $this->extractMarketCap($block);
                if ($cap !== null) {
                    return $cap;
                }
            }
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $item
     */
    private function extractMarketCap(array $item): ?float
    {
        foreach ([
            'market_cap',
            'marketCap',
            'market_capitalization',
            'marketCapitalization',
            'capitalization',
            'capitalisation',
            'mkt_cap',
            'mktCap',
        ] as $key) {
            if (! array_key_exists($key, $item)) {
                continue;
            }
            $v = $this->num($item[$key]);
            if ($v !== null && $v > 0) {
                return $v;
            }
        }

        return null;
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

    /**
     * Calcule open / high / low de séance.
     * Mansa liste souvent sans OHLC → on suit le plus haut / plus bas observés sur la journée.
     *
     * @return array{0:?float,1:?float,2:?float}
     */
    private function resolveSessionOhlc(
        ?Stock $existing,
        ?float $price,
        ?float $apiOpen,
        ?float $apiHigh,
        ?float $apiLow,
        Carbon $scrapedAt
    ): array {
        $tz = 'Africa/Abidjan';
        $sameDay = $existing
            && $existing->source_updated_at
            && $existing->source_updated_at->timezone($tz)->isSameDay($scrapedAt->copy()->timezone($tz));

        $open = $apiOpen;
        if ($open === null) {
            if ($sameDay && $existing->open_price !== null && (float) $existing->open_price > 0) {
                $open = (float) $existing->open_price;
            } else {
                $open = $price;
            }
        }

        $high = $apiHigh;
        $low = $apiLow;

        if ($high === null || $low === null) {
            $observed = [];
            if ($price !== null) {
                $observed[] = $price;
            }
            if ($open !== null) {
                $observed[] = $open;
            }
            if ($sameDay && $existing) {
                if ($existing->high_price !== null && (float) $existing->high_price > 0) {
                    $observed[] = (float) $existing->high_price;
                }
                if ($existing->low_price !== null && (float) $existing->low_price > 0) {
                    $observed[] = (float) $existing->low_price;
                }
                if ($existing->current_price !== null && (float) $existing->current_price > 0) {
                    $observed[] = (float) $existing->current_price;
                }

                // Complète avec l’historique de la journée si dispo
                $dayStart = $scrapedAt->copy()->timezone($tz)->startOfDay();
                $dayEnd = $scrapedAt->copy()->timezone($tz)->endOfDay();
                $hist = StockPrice::query()
                    ->where('stock_id', $existing->id)
                    ->whereBetween('recorded_at', [$dayStart, $dayEnd])
                    ->selectRaw('MAX(price) as max_p, MIN(price) as min_p')
                    ->first();
                if ($hist && $hist->max_p !== null) {
                    $observed[] = (float) $hist->max_p;
                }
                if ($hist && $hist->min_p !== null) {
                    $observed[] = (float) $hist->min_p;
                }
            }

            if ($observed !== []) {
                $high = $high ?? max($observed);
                $low = $low ?? min($observed);
            }
        }

        return [$open, $high, $low];
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
