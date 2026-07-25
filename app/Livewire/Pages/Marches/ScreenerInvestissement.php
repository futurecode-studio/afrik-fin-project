<?php

namespace App\Livewire\Pages\Marches;

use App\Services\MarketsDataService;
use Livewire\Attributes\Url;
use Livewire\Component;

class ScreenerInvestissement extends Component
{
    #[Url(except: '')]
    public string $sector = '';

    #[Url(except: '')]
    public string $min_price = '';

    #[Url(except: '')]
    public string $max_price = '';

    #[Url(except: '')]
    public string $min_variation = '';

    #[Url(except: '')]
    public string $min_volume = '';

    #[Url(as: 'sort', except: 'variation_percent')]
    public string $sort = 'variation_percent';

    #[Url(as: 'dir', except: 'desc')]
    public string $dir = 'desc';

    public function render(MarketsDataService $markets)
    {
        $results = $markets->screen([
            'sector' => $this->sector !== '' ? $this->sector : null,
            'min_price' => $this->min_price,
            'max_price' => $this->max_price,
            'min_variation' => $this->min_variation,
            'min_volume' => $this->min_volume,
            'sort' => $this->sort,
            'dir' => $this->dir,
        ]);

        return view('livewire.pages.marches.screener-investissement', [
            'results' => $results,
            'sectors' => $markets->sectors(),
        ])
            ->extends('layouts.site', ['title' => 'Screener d’Investissement — Africaine des Finances'])
            ->section('content');
    }
}
