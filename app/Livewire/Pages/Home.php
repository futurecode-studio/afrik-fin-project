<?php

namespace App\Livewire\Pages;

use App\Models\Article;
use App\Models\Event;
use App\Models\Formation;
use App\Models\MarketIndexHistory;
use App\Models\Partner;
use App\Models\Stock;
use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        $teamPreview = $this->teamPreview();
        $values = $this->companyValues();
        $pillars = $this->whatWeDo();

        $data = cache()->remember('home.page.data.v11', 120, function () use ($teamPreview, $values, $pillars) {
            $partners = Partner::active()->get();
            if ($partners->isEmpty()) {
                $partners = Partner::catalogCollection();
            }

            $formations = Formation::publie()
                ->latest('published_at')
                ->take(3)
                ->get();

            $featuredArticle = Article::featured()
                ->latest('published_at')
                ->first();

            $latestArticles = Article::published()
                ->when($featuredArticle, fn ($q) => $q->where('id', '!=', $featuredArticle->id))
                ->latest('published_at')
                ->take(3)
                ->get();

            if (! $featuredArticle) {
                $featuredArticle = Article::published()->latest('published_at')->first();
                if ($featuredArticle) {
                    $latestArticles = Article::published()
                        ->where('id', '!=', $featuredArticle->id)
                        ->latest('published_at')
                        ->take(3)
                        ->get();
                }
            }

            $stocks = Stock::query()
                ->where('is_active', true)
                ->orderBy('symbol')
                ->get(['id', 'symbol', 'company_name', 'current_price', 'variation_percent', 'volume']);

            $topGainers = $stocks->sortByDesc('variation_percent')->take(5)->values();
            $topLosers = $stocks->sortBy('variation_percent')->take(5)->values();
            $tickerStocks = $stocks->sortByDesc('volume')->take(12)->values();

            $indexHistory = MarketIndexHistory::query()
                ->where('index_name', 'BRVM Composite')
                ->orderByDesc('snapshot_date')
                ->take(30)
                ->get()
                ->sortBy('snapshot_date')
                ->values();

            $compositeLatest = $indexHistory->last();
            $chartLabels = $indexHistory->map(fn ($row) => $row->snapshot_date->format('d/m'))->values()->all();
            $chartValues = $indexHistory->map(fn ($row) => round((float) $row->value, 2))->values()->all();

            $upcomingEvents = Event::query()
                ->whereIn('status', ['published', 'ongoing'])
                ->upcoming()
                ->orderBy('starts_at')
                ->take(3)
                ->get();

            $webinars = Event::query()
                ->whereIn('status', ['published', 'ongoing'])
                ->upcoming()
                ->when(
                    \Illuminate\Support\Facades\Schema::hasColumn('events', 'is_jeudi_opportunite'),
                    fn ($q) => $q->orderByDesc('is_jeudi_opportunite')
                )
                ->where(function ($q) {
                    $q->whereIn('event_type', ['online', 'hybrid']);
                    if (\Illuminate\Support\Facades\Schema::hasColumn('events', 'is_jeudi_opportunite')) {
                        $q->orWhere('is_jeudi_opportunite', true);
                    }
                })
                ->orderBy('starts_at')
                ->take(2)
                ->get();

            return [
                'partners' => $partners,
                'formations' => $formations,
                'featuredArticle' => $featuredArticle,
                'latestArticles' => $latestArticles,
                'stocks' => $stocks,
                'tickerStocks' => $tickerStocks,
                'topGainers' => $topGainers,
                'topLosers' => $topLosers,
                'stockCount' => $stocks->count(),
                'compositeLatest' => $compositeLatest,
                'chartLabels' => $chartLabels,
                'chartValues' => $chartValues,
                'upcomingEvents' => $upcomingEvents,
                'webinars' => $webinars,
                'teamPreview' => $teamPreview,
                'values' => $values,
                'pillars' => $pillars,
            ];
        });

        return view('livewire.pages.home', $data)
            ->extends('layouts.site', ['title' => 'Accueil'])
            ->section('content');
    }

    /**
     * @return array<int, array{name: string, role: string, image: string}>
     */
    private function teamPreview(): array
    {
        return [
            ['name' => 'Marc C. Emmanuel EBO', 'role' => 'Directeur général', 'image' => 'assets/images/team/ceo.jpeg'],
            ['name' => 'Mohamed Fawaz ANGO', 'role' => 'Conseiller financier', 'image' => 'assets/images/team/mohamed.PNG'],
            ['name' => 'Cyrille Omondoun OGNONDOUN', 'role' => 'Conseiller financier', 'image' => 'assets/images/team/cyrille.jpeg'],
            ['name' => 'Micheline Gloria HOUNTONDJI', 'role' => 'Conseillère clientèle', 'image' => 'assets/images/team/micheline.jpeg'],
        ];
    }

    /**
     * @return array<int, array{icon: string, title: string, text: string}>
     */
    private function companyValues(): array
    {
        return [
            [
                'icon' => 'school',
                'title' => 'Pédagogie',
                'text' => 'Rendre l’investissement accessible grâce à une pédagogie claire avant toute décision.',
            ],
            [
                'icon' => 'visibility',
                'title' => 'Transparence',
                'text' => 'Expliquer les risques, les frais et les limites de chaque solution sans promesse de rendement.',
            ],
            [
                'icon' => 'handshake',
                'title' => 'Orientation responsable',
                'text' => 'Mettre en relation avec l’écosystème agréé AMF-UMOA (SGI / SGO) dans le respect du cadre réglementaire.',
            ],
            [
                'icon' => 'diversity_3',
                'title' => 'Proximité',
                'text' => 'Accompagner particuliers, entreprises, institutions et associations avec une relation de confiance.',
            ],
        ];
    }

    /**
     * @return array<int, array{icon: string, title: string, text: string}>
     */
    private function whatWeDo(): array
    {
        return [
            [
                'icon' => 'school',
                'title' => 'Former',
                'text' => 'Parcours et webinaires pour comprendre la bourse, les actions, les obligations et les FCP.',
            ],
            [
                'icon' => 'candlestick_chart',
                'title' => 'Informer',
                'text' => 'Cotations, analyses et repères de marché pour décider avec méthode.',
            ],
            [
                'icon' => 'handshake',
                'title' => 'Orienter',
                'text' => 'Mise en relation avec les SGI et SGO agréées, dans le cadre AMF-UMOA.',
            ],
        ];
    }
}
