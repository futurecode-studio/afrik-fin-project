<?php

namespace App\Services;

use App\Models\GovernmentBond;
use App\Models\MarketIndex;
use App\Models\MarketIndexHistory;
use App\Models\Stock;
use Illuminate\Support\Collection;

/**
 * Données marchés (stocks / indices / obligations) avec cache court
 * pour limiter les allers-retours vers la DB distante.
 */
class MarketsDataService
{
    public function stocks(): Collection
    {
        return cache()->remember('markets.stocks.active', 120, function () {
            return Stock::query()
                ->where('is_active', true)
                ->orderBy('symbol')
                ->get();
        });
    }

    public function stockBySymbol(string $symbol): ?Stock
    {
        $symbol = strtoupper(trim($symbol));

        return $this->stocks()->first(fn (Stock $s) => strtoupper($s->symbol) === $symbol)
            ?? Stock::query()->where('is_active', true)->where('symbol', $symbol)->first();
    }

    /**
     * Enrichit open/high/low depuis Mansa (détail ticker) si absents en base.
     * 1 appel API max, mis en cache 30 min.
     */
    public function enrichStockSessionLevels(Stock $stock): Stock
    {
        $needsHigh = $stock->high_price === null || (float) $stock->high_price <= 0;
        $needsLow = $stock->low_price === null || (float) $stock->low_price <= 0;
        $needsOpen = $stock->open_price === null || (float) $stock->open_price <= 0;

        if (! $needsHigh && ! $needsLow && ! $needsOpen) {
            return $stock;
        }

        // Fallback immédiat hors API : dérive depuis les cours déjà connus
        $derivedHigh = $stock->effectiveHigh();
        $derivedLow = $stock->effectiveLow();
        $dirty = false;

        if ($needsHigh && $derivedHigh !== null) {
            $stock->high_price = $derivedHigh;
            $dirty = true;
            $needsHigh = false;
        }
        if ($needsLow && $derivedLow !== null) {
            $stock->low_price = $derivedLow;
            $dirty = true;
            $needsLow = false;
        }
        if ($needsOpen && $stock->current_price !== null && (float) $stock->current_price > 0) {
            $stock->open_price = (float) $stock->current_price;
            $dirty = true;
        }

        if ($dirty) {
            $stock->save();
            cache()->forget('markets.stocks.active');
        }

        if ((! $needsHigh && ! $needsLow) || ! filled(config('services.mansa.api_key'))) {
            return $stock->fresh() ?? $stock;
        }

        $cacheKey = 'mansa.stock.ohlc.'.$stock->symbol;
        $quote = cache()->remember($cacheKey, 1800, function () use ($stock) {
            try {
                $payload = app(\App\Services\Mansa\MansaMarketsClient::class)
                    ->stock('BRVM', $stock->symbol);
                $data = $payload['data'] ?? $payload;

                return is_array($data) ? $data : null;
            } catch (\Throwable $e) {
                return null;
            }
        });

        if (! is_array($quote)) {
            return $stock->fresh() ?? $stock;
        }

        $high = $this->numFromQuote($quote, ['high', 'high_price', 'day_high', 'dayHigh']);
        $low = $this->numFromQuote($quote, ['low', 'low_price', 'day_low', 'dayLow']);
        $open = $this->numFromQuote($quote, ['open', 'open_price', 'day_open']);

        $updated = false;
        if ($high !== null && ($stock->high_price === null || (float) $stock->high_price <= 0 || $high > (float) $stock->high_price)) {
            $stock->high_price = $high;
            $updated = true;
        }
        if ($low !== null && ($stock->low_price === null || (float) $stock->low_price <= 0 || $low < (float) $stock->low_price)) {
            $stock->low_price = $low;
            $updated = true;
        }
        if ($open !== null && ($stock->open_price === null || (float) $stock->open_price <= 0)) {
            $stock->open_price = $open;
            $updated = true;
        }

        if ($updated) {
            $stock->save();
            cache()->forget('markets.stocks.active');
        }

        return $stock->fresh() ?? $stock;
    }

    private function numFromQuote(array $quote, array $keys): ?float
    {
        foreach ($keys as $key) {
            if (! array_key_exists($key, $quote) || $quote[$key] === null || $quote[$key] === '') {
                continue;
            }
            $v = (float) $quote[$key];
            if ($v > 0) {
                return $v;
            }
        }

        return null;
    }

    public function topGainers(int $limit = 10): Collection
    {
        return $this->stocks()->sortByDesc('variation_percent')->take($limit)->values();
    }

    public function topLosers(int $limit = 10): Collection
    {
        return $this->stocks()->sortBy('variation_percent')->take($limit)->values();
    }

    public function topVolume(int $limit = 10): Collection
    {
        return $this->stocks()->sortByDesc('volume')->take($limit)->values();
    }

    public function sectors(): Collection
    {
        return $this->stocks()->pluck('sector')->filter()->unique()->sort()->values();
    }

    public function totalVolume(): int
    {
        return (int) $this->stocks()->sum('volume');
    }

    public function totalMarketCap(): float
    {
        return (float) $this->stocks()->sum('market_cap');
    }

    public function indexLatest(string $name): ?MarketIndexHistory
    {
        return cache()->remember("markets.index.latest.{$name}", 120, function () use ($name) {
            return MarketIndexHistory::query()
                ->where('index_name', $name)
                ->orderByDesc('snapshot_date')
                ->first();
        });
    }

    /**
     * Panneau marché navbar : indices + séries historiques + top hausses/baisses.
     *
     * @return array{indices: array<int, array>, gainers: array, losers: array}
     */
    public function navMarketPanel(): array
    {
        $wanted = [
            'BRVM-C' => [
                'title' => 'Composite',
                'aliases' => ['BRVM Composite', 'BRVM Composite Index', 'BRVM-C', 'BRVM-CI', 'BRVM C'],
                'history' => ['BRVM Composite', 'BRVM Composite Index'],
            ],
            'BRVM-30' => [
                'title' => 'Top 30',
                'aliases' => ['BRVM 30', 'BRVM-30', 'BRVM30'],
                'history' => ['BRVM 30', 'BRVM-30'],
            ],
            'BRVM-PRE' => [
                'title' => 'Prestige',
                'aliases' => ['BRVM Prestige', 'BRVM-PRE', 'BRVM PRE', 'BRVM-Prestige'],
                'history' => ['BRVM Prestige'],
            ],
            'BRVM-PRN' => [
                'title' => 'Principal',
                'aliases' => ['BRVM Principal', 'BRVM-PRN', 'BRVM PRN'],
                'history' => ['BRVM Principal'],
            ],
        ];

        $liveByCode = MarketIndex::query()
            ->where('exchange', 'BRVM')
            ->get()
            ->keyBy(fn ($i) => strtoupper((string) $i->code));

        $indices = [];
        foreach ($wanted as $key => $meta) {
            $value = null;
            $variation = null;
            $historyName = $meta['history'][0];

            foreach ($meta['aliases'] as $alias) {
                $liveKey = strtoupper($alias);
                if ($liveByCode->has($liveKey)) {
                    $row = $liveByCode->get($liveKey);
                    $value = (float) $row->value;
                    $variation = (float) ($row->change_percent ?? 0);
                    break;
                }
            }

            $series = collect();
            foreach ($meta['history'] as $histName) {
                $rows = MarketIndexHistory::query()
                    ->where('index_name', $histName)
                    ->where('snapshot_date', '>=', now()->subDays(200)->toDateString())
                    ->orderBy('snapshot_date')
                    ->get(['snapshot_date', 'value', 'variation_percent']);

                if ($rows->count() > $series->count()) {
                    $series = $rows;
                    $historyName = $histName;
                }
            }

            if ($series->isEmpty() && $value === null) {
                continue;
            }

            $latestHist = $series->last();
            if ($value === null && $latestHist) {
                $value = (float) $latestHist->value;
                $variation = (float) ($latestHist->variation_percent ?? 0);
            }

            $points = $series->map(fn ($r) => [
                'd' => $r->snapshot_date->format('Y-m-d'),
                'v' => round((float) $r->value, 2),
            ])->values()->all();

            $spark = collect($points)->take(-14)->pluck('v')->values()->all();

            $indices[] = [
                'key' => $key,
                'label' => $key,
                'title' => $meta['title'],
                'db_name' => $historyName,
                'value' => round((float) $value, 2),
                'variation' => round((float) $variation, 2),
                'series' => $points,
                'spark' => $spark,
            ];
        }

        return [
            'indices' => $indices,
            'gainers' => $this->topGainers(3)->map(fn ($s) => [
                'symbol' => $s->symbol,
                'current_price' => (float) $s->current_price,
                'variation_percent' => (float) $s->variation_percent,
            ])->values()->all(),
            'losers' => $this->topLosers(3)->map(fn ($s) => [
                'symbol' => $s->symbol,
                'current_price' => (float) $s->current_price,
                'variation_percent' => (float) $s->variation_percent,
            ])->values()->all(),
        ];
    }

    public function indexHistory(string $name, int $days = 30): Collection
    {
        return cache()->remember("markets.index.history.{$name}.{$days}", 300, function () use ($name, $days) {
            return MarketIndexHistory::forIndex($name, $days)->get();
        });
    }

    public function indexNames(): Collection
    {
        return cache()->remember('markets.index.names', 300, function () {
            return MarketIndexHistory::query()
                ->select('index_name')
                ->distinct()
                ->orderBy('index_name')
                ->pluck('index_name');
        });
    }

    public function bonds(): Collection
    {
        return cache()->remember('markets.bonds.active', 120, function () {
            return GovernmentBond::active()->notMatured()->ordered()->get();
        });
    }

    public function bondById(int $id): ?GovernmentBond
    {
        return GovernmentBond::active()->find($id);
    }

    public function search(string $q, int $limit = 20): array
    {
        $q = trim($q);
        if ($q === '') {
            return ['stocks' => collect(), 'bonds' => collect()];
        }

        $needle = mb_strtolower($q);

        $stocks = $this->stocks()->filter(function (Stock $s) use ($needle) {
            return str_contains(mb_strtolower($s->symbol), $needle)
                || str_contains(mb_strtolower((string) $s->company_name), $needle)
                || str_contains(mb_strtolower((string) $s->sector), $needle);
        })->take($limit)->values();

        $bonds = $this->bonds()->filter(function (GovernmentBond $b) use ($needle) {
            return str_contains(mb_strtolower((string) $b->name), $needle)
                || str_contains(mb_strtolower((string) $b->issuer), $needle)
                || str_contains(mb_strtolower((string) $b->country), $needle)
                || str_contains(mb_strtolower((string) $b->isin_code), $needle);
        })->take($limit)->values();

        return compact('stocks', 'bonds');
    }

    public function chartBarsFromHistory(Collection $history, int $bars = 12): array
    {
        $bars = max(1, (int) $bars);
        if ($history->isEmpty()) {
            return array_fill(0, $bars, 40);
        }

        $slice = $history->take(-$bars)->values();
        $min = (float) $slice->min('value');
        $max = (float) $slice->max('value');
        $span = max($max - $min, 0.0001);

        return $slice->map(function ($row) use ($min, $span) {
            return (int) round(20 + (((float) $row->value - $min) / $span) * 70);
        })->pad($bars, 30)->all();
    }

    /**
     * Carte du marché : titres dimensionnés par capitalisation (ou volume).
     *
     * @return array{sectors: array<int, array{name: string, total: float, stocks: array}>, metric: string, total: float}
     */
    public function marketMap(string $metric = 'market_cap'): array
    {
        $metric = in_array($metric, ['market_cap', 'volume', 'variation'], true) ? $metric : 'market_cap';
        $stocks = $this->stocks();

        $sectors = $stocks
            ->groupBy(fn (Stock $s) => $s->sector ?: 'Autre')
            ->map(function (Collection $group, string $name) use ($metric) {
                $items = $group->map(function (Stock $s) use ($metric) {
                    if ($metric === 'variation') {
                        $size = abs((float) $s->variation_percent) + 0.01;
                    } else {
                        $size = (float) ($s->{$metric} ?? 0);
                        if ($size <= 0) {
                            $size = max((float) $s->volume, 1);
                        }
                    }

                    return [
                        'symbol' => $s->symbol,
                        'name' => $s->company_name,
                        'sector' => $s->sector,
                        'price' => (float) $s->current_price,
                        'variation' => (float) $s->variation_percent,
                        'volume' => (int) $s->volume,
                        'market_cap' => (float) $s->market_cap,
                        'size' => $size,
                    ];
                })->sortByDesc('size')->values()->all();

                return [
                    'name' => $name,
                    'total' => array_sum(array_column($items, 'size')),
                    'avg_variation' => $group->avg('variation_percent'),
                    'stocks' => $items,
                ];
            })
            ->sortByDesc('total')
            ->values()
            ->all();

        return [
            'sectors' => $sectors,
            'metric' => $metric,
            'total' => array_sum(array_column($sectors, 'total')),
        ];
    }

    /**
     * Données plates pour un treemap type MARKETMAP (taille ≈ poids marché, couleur = variation).
     *
     * @param  string  $metric  auto|market_cap|volume|variation
     * @return array{nodes: array<int, array>, size_label: string, count: int, metric: string}
     */
    public function marketTreemap(string $metric = 'auto'): array
    {
        $metric = in_array($metric, ['auto', 'market_cap', 'volume', 'variation'], true) ? $metric : 'auto';
        $stocks = $this->stocks();

        $withCap = $stocks->filter(fn (Stock $s) => (float) $s->market_cap > 0)->count();
        $capsLookReal = $withCap >= max(5, (int) ceil($stocks->count() * 0.4));

        if ($metric === 'auto') {
            $metric = $capsLookReal ? 'market_cap' : 'volume';
        }

        $sizeLabel = match ($metric) {
            'market_cap' => 'Capitalisation',
            'variation' => '|Variation|',
            default => 'Volume échangé (× cours)',
        };

        $nodes = $stocks->map(function (Stock $s) use ($metric, $capsLookReal) {
            $cap = (float) $s->market_cap;
            $turnover = max((float) $s->volume, 1) * max((float) $s->current_price, 1);

            $size = match ($metric) {
                'market_cap' => ($capsLookReal && $cap > 0) ? $cap : $turnover,
                'variation' => abs((float) $s->variation_percent) + 0.01,
                default => $turnover,
            };

            return [
                'symbol' => $s->symbol,
                'name' => $s->company_name ?: $s->symbol,
                'sector' => $s->sector ?: 'Autre',
                'price' => (float) $s->current_price,
                'variation' => round((float) $s->variation_percent, 2),
                'volume' => (int) $s->volume,
                'market_cap' => $cap,
                'size' => max($size, 1),
                'url' => route('marches.action', $s->symbol),
            ];
        })
            ->sortByDesc('size')
            ->values()
            ->all();

        $effectiveMetric = $metric;
        if ($metric === 'market_cap' && ! $capsLookReal) {
            $effectiveMetric = 'volume';
            $sizeLabel = 'Volume échangé (× cours)';
        }

        return [
            'nodes' => $nodes,
            'size_label' => $sizeLabel,
            'count' => count($nodes),
            'metric' => $effectiveMetric,
        ];
    }

    /**
     * Rapport sectoriel à partir des cotations locales.
     */
    public function sectorReport(): array
    {
        return $this->stocks()
            ->groupBy(fn (Stock $s) => $s->sector ?: 'Autre')
            ->map(function (Collection $group, string $name) {
                $sorted = $group->sortByDesc('variation_percent')->values();

                return [
                    'name' => $name,
                    'count' => $group->count(),
                    'avg_variation' => round((float) $group->avg('variation_percent'), 2),
                    'total_volume' => (int) $group->sum('volume'),
                    'total_cap' => (float) $group->sum('market_cap'),
                    'best' => $sorted->first(),
                    'worst' => $sorted->last(),
                    'stocks' => $sorted,
                ];
            })
            ->sortByDesc('total_volume')
            ->values()
            ->all();
    }

    /**
     * Screener actions BRVM.
     */
    public function screen(array $filters = []): Collection
    {
        $stocks = $this->stocks();

        if (! empty($filters['sector'])) {
            $stocks = $stocks->where('sector', $filters['sector']);
        }
        if (isset($filters['min_price']) && $filters['min_price'] !== '' && $filters['min_price'] !== null) {
            $stocks = $stocks->where('current_price', '>=', (float) $filters['min_price']);
        }
        if (isset($filters['max_price']) && $filters['max_price'] !== '' && $filters['max_price'] !== null) {
            $stocks = $stocks->where('current_price', '<=', (float) $filters['max_price']);
        }
        if (isset($filters['min_variation']) && $filters['min_variation'] !== '' && $filters['min_variation'] !== null) {
            $stocks = $stocks->where('variation_percent', '>=', (float) $filters['min_variation']);
        }
        if (isset($filters['max_variation']) && $filters['max_variation'] !== '' && $filters['max_variation'] !== null) {
            $stocks = $stocks->where('variation_percent', '<=', (float) $filters['max_variation']);
        }
        if (isset($filters['min_volume']) && $filters['min_volume'] !== '' && $filters['min_volume'] !== null) {
            $stocks = $stocks->where('volume', '>=', (int) $filters['min_volume']);
        }

        $sort = $filters['sort'] ?? 'variation_percent';
        $dir = ($filters['dir'] ?? 'desc') === 'asc' ? 'asc' : 'desc';
        $stocks = $dir === 'asc' ? $stocks->sortBy($sort) : $stocks->sortByDesc($sort);

        return $stocks->values();
    }

    /**
     * Calendrier : adjudications / échéances obligations + événements publiés.
     *
     * @return array<int, array{date: string, type: string, title: string, subtitle: string, url: string|null}>
     */
    public function financialCalendar(?int $year = null, ?int $month = null): array
    {
        $year = $year ?: (int) now()->year;
        $month = $month ?: (int) now()->month;
        $start = now()->setDate($year, $month, 1)->startOfDay();
        $end = $start->copy()->endOfMonth();

        $events = [];

        foreach ($this->bonds() as $bond) {
            foreach ([
                ['field' => 'auction_date', 'type' => 'adjudication', 'label' => 'Adjudication'],
                ['field' => 'issue_date', 'type' => 'emission', 'label' => 'Émission'],
                ['field' => 'maturity_date', 'type' => 'echeance', 'label' => 'Échéance'],
            ] as $meta) {
                $date = $bond->{$meta['field']};
                if (! $date || $date->lt($start) || $date->gt($end)) {
                    continue;
                }
                $events[] = [
                    'date' => $date->format('Y-m-d'),
                    'day' => (int) $date->format('j'),
                    'type' => $meta['type'],
                    'label' => $meta['label'],
                    'title' => $bond->name,
                    'subtitle' => trim(($bond->issuer ?? '') . ' · ' . ($bond->country ?? '')),
                    'url' => route('marches.obligation', $bond->id),
                ];
            }
        }

        if (class_exists(\App\Models\Event::class)) {
            $siteEvents = \App\Models\Event::query()
                ->whereIn('status', ['published', 'ongoing'])
                ->whereBetween('starts_at', [$start, $end])
                ->orderBy('starts_at')
                ->get();

            foreach ($siteEvents as $ev) {
                $events[] = [
                    'date' => $ev->starts_at->format('Y-m-d'),
                    'day' => (int) $ev->starts_at->format('j'),
                    'type' => 'evenement',
                    'label' => 'Événement',
                    'title' => $ev->title,
                    'subtitle' => $ev->city ?: ($ev->location_name ?: 'Africaine des Finances'),
                    'url' => route('event-detail', $ev->slug),
                ];
            }
        }

        usort($events, fn ($a, $b) => strcmp($a['date'], $b['date']));

        return $events;
    }
}
