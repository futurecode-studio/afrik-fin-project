<?php

namespace App\Livewire\Pages\Investir;

use App\Services\InvestorProfileService;
use App\Services\MutualFundsApiService;
use Livewire\Component;

class VotreProfil extends Component
{
    public function mount(InvestorProfileService $profiles): void
    {
        if (! $profiles->get()) {
            $this->redirect(route('investir.profil-test'), navigate: true);
        }
    }

    public function remake(InvestorProfileService $profiles): void
    {
        $profiles->clear();
        $this->redirect(route('investir.profil-test'), navigate: true);
    }

    public function render(InvestorProfileService $profiles, MutualFundsApiService $funds)
    {
        $profile = $profiles->get();
        if (! $profile) {
            return view('livewire.pages.investir.votre-profil', [
                'profile' => [
                    'type' => 'equilibre',
                    'label' => '—',
                    'color' => '#001a61',
                    'score' => 0,
                    'max_score' => 0,
                    'description' => '',
                    'allocation' => [],
                ],
                'suggested' => [],
            ])->extends('layouts.site', ['title' => 'Votre diagnostic'])->section('content');
        }

        $suggested = [];
        try {
            $all = $funds->getMutualFunds();
            $preferred = match ($profile['type'] ?? '') {
                'conservateur' => ['Obligations', 'Mixte'],
                'dynamique' => ['Actions'],
                default => ['Mixte', 'Actions'],
            };
            $suggested = array_values(array_filter(
                $all,
                fn ($f) => in_array($f['category'] ?? '', $preferred, true)
            ));
            $suggested = array_slice($suggested, 0, 4);
        } catch (\Throwable) {
            $suggested = [];
        }

        return view('livewire.pages.investir.votre-profil', [
            'profile' => $profile,
            'suggested' => $suggested,
        ])
            ->extends('layouts.site', ['title' => 'Votre diagnostic — Africaine des Finances'])
            ->section('content');
    }
}
