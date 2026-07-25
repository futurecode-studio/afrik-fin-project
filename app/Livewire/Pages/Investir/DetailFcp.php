<?php

namespace App\Livewire\Pages\Investir;

use App\Services\MutualFundsApiService;
use Livewire\Component;

class DetailFcp extends Component
{
    public string $id = '';

    public ?array $fund = null;

    public bool $loading = true;

    public ?string $error = null;

    public function mount(string $id): void
    {
        $this->id = $id;
        $this->loadFund();
    }

    public function loadFund(): void
    {
        $this->loading = true;
        $this->error = null;
        try {
            $this->fund = app(MutualFundsApiService::class)->getFundById($this->id);
            if (! $this->fund) {
                $this->error = 'Fonds introuvable ou VL non disponible sur Sikafinance.';
            }
        } catch (\Throwable) {
            $this->fund = null;
            $this->error = 'Impossible de charger ce fonds pour le moment.';
        } finally {
            $this->loading = false;
        }
    }

    public function refresh(): void
    {
        app(MutualFundsApiService::class)->clearCache();
        $this->loadFund();
    }

    public function render(MutualFundsApiService $funds)
    {
        $related = [];
        if ($this->fund) {
            try {
                $related = array_values(array_filter(
                    $funds->getMutualFunds(),
                    fn ($f) => ($f['category'] ?? '') === ($this->fund['category'] ?? '')
                        && ($f['id'] ?? '') !== $this->id
                ));
                $related = array_slice($related, 0, 3);
            } catch (\Throwable) {
                $related = [];
            }
        }

        $title = $this->fund['name'] ?? 'Détail FCP';

        return view('livewire.pages.investir.detail-fcp', [
            'related' => $related,
        ])
            ->extends('layouts.site', ['title' => $title.' — Africaine des Finances'])
            ->section('content');
    }
}
