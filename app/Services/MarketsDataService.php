<?php

namespace App\Services;

use App\Models\GovernmentBond;
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
