<?php

namespace App\Livewire\Pages;

use App\Models\Article;
use App\Models\Formation;
use App\Models\MarketIndexHistory;
use App\Models\Partner;
use App\Models\SiteService;
use App\Models\Stock;
use App\Services\MarketsDataService;
use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        $data = cache()->remember('home.page.data.v6', 120, function () {
            $partners = Partner::active()->get();

            $formations = Formation::publie()
                ->latest('published_at')
                ->take(3)
                ->get();

            $siteServices = SiteService::active()->take(6)->get();

            $featuredArticle = Article::featured()
                ->latest('published_at')
                ->first();

            $latestArticles = Article::published()
                ->when($featuredArticle, fn ($q) => $q->where('id', '!=', $featuredArticle->id))
                ->latest('published_at')
                ->take(4)
                ->get();

            // Si aucune mise en avant : prendre le plus récent en hero
            if (!$featuredArticle) {
                $featuredArticle = Article::published()->latest('published_at')->first();
                if ($featuredArticle) {
                    $latestArticles = Article::published()
                        ->where('id', '!=', $featuredArticle->id)
                        ->latest('published_at')
                        ->take(4)
                        ->get();
                }
            }

            $stocks = Stock::query()
                ->where('is_active', true)
                ->get(['id', 'symbol', 'company_name', 'current_price', 'variation_percent', 'volume']);

            $topGainers = $stocks->sortByDesc('variation_percent')->take(5)->values();
            $topLosers = $stocks->sortBy('variation_percent')->take(5)->values();
            $tickerStocks = $stocks->sortByDesc('volume')->take(12)->values();
            $totalVolume = (int) $stocks->sum('volume');

            $indexHistory = MarketIndexHistory::query()
                ->where('index_name', 'BRVM Composite')
                ->orderByDesc('snapshot_date')
                ->take(30)
                ->get()
                ->sortBy('snapshot_date')
                ->values();

            $compositeLatest = $indexHistory->last();
            $compositePrev = $indexHistory->count() > 1 ? $indexHistory[$indexHistory->count() - 2] : null;

            $chartLabels = $indexHistory->map(fn ($row) => $row->snapshot_date->format('d/m'))->values()->all();
            $chartValues = $indexHistory->map(fn ($row) => round((float) $row->value, 2))->values()->all();

            $volumeLeaders = $stocks->sortByDesc('volume')->take(6)->values()->map(fn ($s) => [
                'symbol' => $s->symbol,
                'volume' => (int) $s->volume,
                'variation' => (float) $s->variation_percent,
                'price' => (float) $s->current_price,
            ]);

            $marketTreemap = app(MarketsDataService::class)->marketTreemap();

            return [
                'partners' => $partners,
                'partnersByType' => $partners->groupBy('type'),
                'formations' => $formations,
                'siteServices' => $siteServices,
                'featuredArticle' => $featuredArticle,
                'latestArticles' => $latestArticles,
                'topGainers' => $topGainers,
                'topLosers' => $topLosers,
                'tickerStocks' => $tickerStocks,
                'totalVolume' => $totalVolume,
                'compositeLatest' => $compositeLatest,
                'compositePrev' => $compositePrev,
                'chartLabels' => $chartLabels,
                'chartValues' => $chartValues,
                'volumeLeaders' => $volumeLeaders,
                'marketTreemap' => $marketTreemap,
            ];
        });

        return view('livewire.pages.home', $data)
            ->extends('layouts.site', ['title' => 'Accueil'])
            ->section('content');
    }
}
