<?php

namespace App\Livewire\Client;

use App\Models\PortfolioHolding;
use App\Services\MarketsDataService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class RapportMensuel extends Component
{
    public string $month = '';

    public function mount(): void
    {
        $this->month = now()->format('Y-m');
    }

    public function render(MarketsDataService $markets)
    {
        $holdings = PortfolioHolding::with('stock')
            ->where('user_id', Auth::id())
            ->get();

        $total = 0.0;
        $rows = [];
        foreach ($holdings as $h) {
            $price = $h->stock?->current_price ?? $h->avg_cost ?? 0;
            $value = (float) $h->quantity * (float) $price;
            $cost = (float) $h->quantity * (float) ($h->avg_cost ?? $price);
            $perf = $cost > 0 ? (($value - $cost) / $cost) * 100 : 0;
            $total += $value;
            $rows[] = [
                'label' => $h->stock?->symbol ?? $h->label ?? 'Actif',
                'type' => $h->asset_type,
                'value' => $value,
                'perf' => $perf,
                'weight' => 0,
            ];
        }
        foreach ($rows as &$r) {
            $r['weight'] = $total > 0 ? ($r['value'] / $total) * 100 : 0;
        }
        unset($r);

        $benchmark = $markets->indexLatest('BRVM-C');
        $periodLabel = \Carbon\Carbon::createFromFormat('Y-m', $this->month)->translatedFormat('F Y');

        return view('livewire.client.rapport-mensuel', [
            'rows' => $rows,
            'total' => $total,
            'benchmark' => $benchmark,
            'periodLabel' => $periodLabel,
            'avgPerf' => collect($rows)->avg('perf') ?? 0,
        ])
            ->extends('layouts.client', ['title' => 'Rapport Mensuel de Gestion'])
            ->section('content');
    }
}
