<?php

namespace App\Livewire\Pages\Marches;

use App\Services\MarketsDataService;
use Livewire\Attributes\Url;
use Livewire\Component;

class MarcheObligataire extends Component
{
    #[Url(as: 'q', except: '')]
    public string $search = '';

    #[Url(as: 'pays', except: '')]
    public string $country = '';

    #[Url(as: 'type', except: '')]
    public string $type = '';

    public function render(MarketsDataService $markets)
    {
        $bonds = $markets->bonds();

        if ($this->search !== '') {
            $n = mb_strtolower($this->search);
            $bonds = $bonds->filter(fn ($b) =>
                str_contains(mb_strtolower((string) $b->name), $n)
                || str_contains(mb_strtolower((string) $b->issuer), $n)
                || str_contains(mb_strtolower((string) $b->isin_code), $n)
            );
        }

        if ($this->country !== '') {
            $bonds = $bonds->where('country', $this->country);
        }

        if ($this->type !== '') {
            $bonds = $bonds->filter(fn ($b) => str_starts_with((string) $b->name, $this->type . ' '));
        }

        return view('livewire.pages.marches.marche-obligataire', [
            'bonds' => $bonds->values(),
            'countries' => $markets->bonds()->pluck('country')->filter()->unique()->sort()->values(),
            'types' => collect(['BAT', 'OAT', 'OATR', 'OATI']),
        ])
            ->extends('layouts.site', ['title' => 'Marché Obligataire'])
            ->section('content');
    }
}
