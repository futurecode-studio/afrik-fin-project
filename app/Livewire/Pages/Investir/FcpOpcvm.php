<?php

namespace App\Livewire\Pages\Investir;

use App\Services\MutualFundsApiService;
use Livewire\Attributes\Url;
use Livewire\Component;

class FcpOpcvm extends Component
{
    #[Url(as: 'q', except: '')]
    public string $search = '';

    #[Url(as: 'cat', except: '')]
    public string $category = '';

    public array $funds = [];

    public bool $loading = true;

    public ?string $error = null;

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
        } catch (\Throwable) {
            $this->funds = [];
            $this->error = 'Impossible de récupérer les VL Sikafinance pour le moment.';
        } finally {
            $this->loading = false;
        }
    }

    public function refresh(): void
    {
        app(MutualFundsApiService::class)->clearCache();
        $this->loadFunds();
    }

    public function render()
    {
        $list = $this->funds;

        if ($this->category !== '') {
            $list = array_values(array_filter($list, fn ($f) => ($f['category'] ?? '') === $this->category));
        }

        if ($this->search !== '') {
            $n = mb_strtolower($this->search);
            $list = array_values(array_filter($list, function ($f) use ($n) {
                $hay = mb_strtolower(($f['name'] ?? '').' '.($f['company'] ?? '').' '.($f['isin'] ?? ''));

                return str_contains($hay, $n);
            }));
        }

        $categories = array_values(array_unique(array_column($this->funds, 'category')));
        sort($categories);

        return view('livewire.pages.investir.fcp-opcvm', [
            'list' => $list,
            'categories' => $categories,
        ])
            ->extends('layouts.site', ['title' => 'FCP et OPCVM — Africaine des Finances'])
            ->section('content');
    }
}
