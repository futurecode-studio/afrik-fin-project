<?php

namespace App\Livewire\Pages\Investir;

use App\Services\MutualFundsApiService;
use Livewire\Attributes\Url;
use Livewire\Component;

class ValeursLiquidatives extends Component
{
    #[Url(as: 'cat', except: '')]
    public string $category = '';

    #[Url(as: 'tri', except: 'name')]
    public string $sortBy = 'name';

    #[Url(as: 'dir', except: 'asc')]
    public string $sortDir = 'asc';

    public array $funds = [];

    public bool $loading = true;

    public ?string $error = null;

    public ?string $updatedAt = null;

    public function mount(): void
    {
        $this->loadFunds();
    }

    public function loadFunds(): void
    {
        $this->loading = true;
        $this->error = null;
        try {
            $this->funds = app(MutualFundsApiService::class)->getMutualFunds();
            $this->updatedAt = now()->format('d/m/Y H:i');
        } catch (\Throwable) {
            $this->funds = [];
            $this->error = 'Données Sikafinance indisponibles. Réessayez plus tard.';
        } finally {
            $this->loading = false;
        }
    }

    public function refresh(): void
    {
        app(MutualFundsApiService::class)->clearCache();
        $this->loadFunds();
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

    public function render()
    {
        $list = $this->funds;
        if ($this->category !== '') {
            $list = array_values(array_filter($list, fn ($f) => ($f['category'] ?? '') === $this->category));
        }

        $dir = $this->sortDir === 'desc' ? -1 : 1;
        usort($list, function ($a, $b) use ($dir) {
            $col = $this->sortBy;
            $va = $a[$col] ?? '';
            $vb = $b[$col] ?? '';
            if (is_numeric($va) && is_numeric($vb)) {
                return ($va <=> $vb) * $dir;
            }

            return strcmp((string) $va, (string) $vb) * $dir;
        });

        $categories = array_values(array_unique(array_column($this->funds, 'category')));
        sort($categories);

        return view('livewire.pages.investir.valeurs-liquidatives', [
            'list' => $list,
            'categories' => $categories,
        ])
            ->extends('layouts.site', ['title' => 'Valeurs Liquidatives — Africaine des Finances'])
            ->section('content');
    }
}
