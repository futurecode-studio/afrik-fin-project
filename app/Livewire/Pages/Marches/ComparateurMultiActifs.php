<?php

namespace App\Livewire\Pages\Marches;

use App\Models\GovernmentBond;
use App\Services\MarketsDataService;
use Livewire\Attributes\Url;
use Livewire\Component;

class ComparateurMultiActifs extends Component
{
    #[Url(as: 'a', except: '')]
    public string $assetA = '';

    #[Url(as: 'b', except: '')]
    public string $assetB = '';

    public string $period = '1y';

    public function mount(MarketsDataService $markets): void
    {
        if ($this->assetA === '') {
            $this->assetA = 'stock:'.($markets->topVolume(1)->first()?->symbol ?? 'SNTS');
        }
        if ($this->assetB === '') {
            $second = $markets->topVolume(2)->skip(1)->first()?->symbol;
            $this->assetB = $second ? 'stock:'.$second : 'index:BRVM-C';
        }
    }

    public function render(MarketsDataService $markets)
    {
        $stocks = $markets->stocks();
        $left = $this->resolve($this->assetA, $markets);
        $right = $this->resolve($this->assetB, $markets);

        $metrics = [
            ['label' => 'Cours / VL', 'a' => $left['price'] ?? null, 'b' => $right['price'] ?? null, 'fmt' => 'money'],
            ['label' => 'Variation', 'a' => $left['var'] ?? null, 'b' => $right['var'] ?? null, 'fmt' => 'pct'],
            ['label' => 'Volatilité (proxy)', 'a' => $left['vol'] ?? null, 'b' => $right['vol'] ?? null, 'fmt' => 'pct'],
            ['label' => 'Type', 'a' => $left['type'] ?? '—', 'b' => $right['type'] ?? '—', 'fmt' => 'text'],
        ];

        return view('livewire.pages.marches.comparateur-multi-actifs', [
            'stocks' => $stocks,
            'left' => $left,
            'right' => $right,
            'metrics' => $metrics,
            'bonds' => GovernmentBond::query()->orderByDesc('updated_at')->limit(20)->get(),
        ])
            ->extends('layouts.site', ['title' => 'Comparateur Multi-Actifs — Africaine des Finances'])
            ->section('content');
    }

    private function resolve(string $key, MarketsDataService $markets): array
    {
        [$kind, $id] = array_pad(explode(':', $key, 2), 2, '');
        if ($kind === 'stock') {
            $s = $markets->stockBySymbol($id);
            if (! $s) {
                return ['name' => $id, 'type' => 'Action', 'price' => null, 'var' => null, 'vol' => null];
            }

            return [
                'name' => $s->company_name.' ('.$s->symbol.')',
                'type' => 'Action BRVM',
                'price' => (float) $s->current_price,
                'var' => (float) $s->variation_percent,
                'vol' => abs((float) $s->variation_percent) * 4,
                'symbol' => $s->symbol,
            ];
        }
        if ($kind === 'index') {
            $idx = $markets->indexLatest($id);

            return [
                'name' => $id,
                'type' => 'Indice',
                'price' => $idx?->value ? (float) $idx->value : null,
                'var' => $idx?->variation_percent ? (float) $idx->variation_percent : null,
                'vol' => $idx?->variation_percent ? abs((float) $idx->variation_percent) * 3 : null,
            ];
        }
        if ($kind === 'bond') {
            $b = GovernmentBond::find($id);

            return [
                'name' => $b?->name ?? 'Obligation',
                'type' => 'Obligation',
                'price' => $b?->current_price ? (float) $b->current_price : ($b?->yield_to_maturity ? (float) $b->yield_to_maturity : null),
                'var' => null,
                'vol' => 2.5,
            ];
        }

        return ['name' => $key ?: '—', 'type' => 'Actif', 'price' => null, 'var' => null, 'vol' => null];
    }
}
