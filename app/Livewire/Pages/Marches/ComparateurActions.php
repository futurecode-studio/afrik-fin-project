<?php

namespace App\Livewire\Pages\Marches;

use App\Services\MarketsDataService;
use Livewire\Attributes\Url;
use Livewire\Component;

class ComparateurActions extends Component
{
    #[Url(as: 'symbols', except: '')]
    public string $symbolsParam = '';

    /** @var list<string> */
    public array $selected = [];

    public string $pendingSymbol = '';

    public function mount(MarketsDataService $markets): void
    {
        $fromUrl = $this->parseSymbols($this->symbolsParam);

        if (! empty($fromUrl)) {
            $this->selected = $fromUrl;
        } else {
            $this->selected = $markets->topVolume(3)->pluck('symbol')->map(fn ($s) => strtoupper((string) $s))->all();
            $this->syncUrl();
        }
    }

    public function updatedPendingSymbol(string $value): void
    {
        $symbol = strtoupper(trim($value));
        $this->pendingSymbol = '';

        if ($symbol === '') {
            return;
        }

        $this->addSymbol($symbol);
    }

    public function addSymbol(string $symbol): void
    {
        $symbol = strtoupper(trim($symbol));

        if ($symbol === '' || in_array($symbol, $this->selected, true) || count($this->selected) >= 5) {
            return;
        }

        // Vérifie que le titre existe vraiment en DB
        $exists = app(MarketsDataService::class)->stockBySymbol($symbol);
        if (! $exists) {
            return;
        }

        $this->selected[] = $symbol;
        $this->syncUrl();
    }

    public function removeSymbol(string $symbol): void
    {
        $symbol = strtoupper(trim($symbol));
        $this->selected = array_values(array_filter(
            $this->selected,
            fn ($s) => $s !== $symbol
        ));
        $this->syncUrl();
    }

    private function syncUrl(): void
    {
        $this->symbolsParam = implode(',', $this->selected);
    }

    /**
     * @return list<string>
     */
    private function parseSymbols(string $raw): array
    {
        return collect(explode(',', $raw))
            ->map(fn ($s) => strtoupper(trim($s)))
            ->filter()
            ->unique()
            ->take(5)
            ->values()
            ->all();
    }

    public function render(MarketsDataService $markets)
    {
        $all = $markets->stocks()->keyBy(fn ($s) => strtoupper($s->symbol));

        // Conserve l'ordre de sélection (pas whereIn désordonné)
        $compared = collect($this->selected)
            ->map(fn ($symbol) => $all->get(strtoupper($symbol)))
            ->filter()
            ->values();

        $available = $all
            ->reject(fn ($s) => in_array(strtoupper($s->symbol), $this->selected, true))
            ->sortBy('symbol')
            ->values();

        return view('livewire.pages.marches.comparateur-actions', [
            'compared' => $compared,
            'available' => $available,
            'metrics' => [
                'current_price' => 'Cours (FCFA)',
                'variation_percent' => 'Variation %',
                'volume' => 'Volume',
                'market_cap' => 'Cap. (Mrd FCFA)',
                'high_price' => 'Plus haut',
                'low_price' => 'Plus bas',
                'sector' => 'Secteur',
            ],
        ])
            ->extends('layouts.site', ['title' => 'Comparateur d\'Actions'])
            ->section('content');
    }
}
