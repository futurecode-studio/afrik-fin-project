<?php

namespace App\Livewire\Pages\Marches;

use App\Services\MarketsDataService;
use Livewire\Attributes\Url;
use Livewire\Component;

class CotationsActions extends Component
{
    #[Url(as: 'q', except: '')]
    public string $search = '';

    #[Url(as: 'secteur', except: '')]
    public string $sector = '';

    #[Url(as: 'tri', except: 'symbol')]
    public string $sortBy = 'symbol';

    #[Url(as: 'dir', except: 'asc')]
    public string $sortDir = 'asc';

    public function render(MarketsDataService $markets)
    {
        $stocks = $markets->stocks();

        if ($this->search !== '') {
            $n = mb_strtolower($this->search);
            $stocks = $stocks->filter(fn ($s) =>
                str_contains(mb_strtolower($s->symbol), $n)
                || str_contains(mb_strtolower((string) $s->company_name), $n)
            );
        }

        if ($this->sector !== '') {
            $stocks = $stocks->where('sector', $this->sector);
        }

        $stocks = $this->sortDir === 'desc'
            ? $stocks->sortByDesc($this->sortBy)
            : $stocks->sortBy($this->sortBy);

        return view('livewire.pages.marches.cotations-actions', [
            'stocks' => $stocks->values(),
            'sectors' => $markets->sectors(),
        ])
            ->extends('layouts.site', ['title' => 'Cotations Actions BRVM'])
            ->section('content');
    }

    public function sort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDir = 'asc';
        }
    }
}
