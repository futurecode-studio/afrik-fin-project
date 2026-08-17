<?php

namespace App\Livewire\Pages\Investir;

use App\Models\Partner;
use Livewire\Attributes\Url;
use Livewire\Component;

class PartenairesAgrees extends Component
{
    #[Url(as: 'type', except: '')]
    public string $type = '';

    #[Url(as: 'q', except: '')]
    public string $search = '';

    public function render()
    {
        $query = Partner::active()->orderBy('order')->orderBy('nom');

        if ($this->type !== '') {
            $query->where('type', $this->type);
        }

        if ($this->search !== '') {
            $needle = '%'.mb_strtolower($this->search).'%';
            $query->where(function ($q) use ($needle) {
                $q->whereRaw('LOWER(nom) LIKE ?', [$needle])
                    ->orWhereRaw('LOWER(description) LIKE ?', [$needle])
                    ->orWhereRaw('LOWER(contact) LIKE ?', [$needle]);
            });
        }

        $partners = $query->get();
        if ($partners->isEmpty() && $this->type === '' && $this->search === '') {
            $partners = Partner::catalogCollection();
        }
        $grouped = $partners->groupBy('type');

        return view('livewire.pages.investir.partenaires-agrees', [
            'partners' => $partners,
            'grouped' => $grouped,
            'types' => Partner::TYPES,
        ])
            ->extends('layouts.site', ['title' => 'Partenaires Agréés — Africaine des Finances'])
            ->section('content');
    }
}
