<?php

namespace App\Livewire\Pages;

use App\Models\Formation;
use App\Models\MarketIndexHistory;
use App\Models\Partner;
use App\Models\SiteService;
use App\Models\Stock;
use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        $data = cache()->remember('home.page.data.v3', 120, function () {
            $partners = Partner::active()->get();

            $formations = Formation::publie()
                ->latest('published_at')
                ->take(3)
                ->get();

            $siteServices = SiteService::active()->take(6)->get();

            $stocks = Stock::query()
                ->where('is_active', true)
                ->get(['id', 'symbol', 'company_name', 'current_price', 'variation_percent', 'volume']);

            $topGainers = $stocks->sortByDesc('variation_percent')->take(3)->values();
            $topLosers = $stocks->sortBy('variation_percent')->take(3)->values();
            $tickerStocks = $stocks->sortByDesc('volume')->take(8)->values();
            $totalVolume = (int) $stocks->sum('volume');

            $indexHistory = MarketIndexHistory::query()
                ->where('index_name', 'BRVM Composite')
                ->orderByDesc('snapshot_date')
                ->take(12)
                ->get()
                ->sortBy('snapshot_date')
                ->values();

            $compositeLatest = $indexHistory->last();

            $min = (float) ($indexHistory->min('value') ?: 0);
            $max = (float) ($indexHistory->max('value') ?: 1);
            $span = max($max - $min, 0.0001);

            $chartPoints = $indexHistory->map(function ($row) use ($min, $span) {
                return [
                    'date' => $row->snapshot_date->format('d/m'),
                    'value' => (float) $row->value,
                    'variation' => (float) $row->variation_percent,
                    'height' => (int) round(25 + (((float) $row->value - $min) / $span) * 70),
                ];
            })->values();

            return [
                'partners' => $partners,
                'partnersByType' => $partners->groupBy('type'),
                'formations' => $formations,
                'siteServices' => $siteServices,
                'topGainers' => $topGainers,
                'topLosers' => $topLosers,
                'tickerStocks' => $tickerStocks,
                'totalVolume' => $totalVolume,
                'compositeLatest' => $compositeLatest,
                'chartPoints' => $chartPoints,
            ];
        });

        return view('livewire.pages.home', $data)
            ->extends('layouts.site', ['title' => 'Accueil'])
            ->section('content');
    }
}
