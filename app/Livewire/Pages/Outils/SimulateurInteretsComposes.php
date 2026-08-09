<?php

namespace App\Livewire\Pages\Outils;

use App\Services\MutualFundsApiService;
use Livewire\Component;

class SimulateurInteretsComposes extends Component
{
    public float $capital = 1000000;
    public float $versement = 50000;
    public int $annees = 10;
    public string $fcpType = 'Actions';

    /** @var array<int, array<string, mixed>> */
    public array $funds = [];

    public ?string $error = null;

    /** @var array<string, float> */
    private const INDICATIVE_RATES = [
        'Obligations' => 5.5,
        'Mixte' => 7.0,
        'Actions' => 9.0,
    ];

    /** @var array<string, string> */
    private const TYPE_DESCRIPTIONS = [
        'Obligations' => 'FCP obligataires: profil prudent, rendement indicatif modéré.',
        'Mixte' => 'FCP mixtes: allocation équilibrée entre instruments de taux et actions.',
        'Actions' => 'FCP actions: profil dynamique, volatilité et potentiel plus élevés.',
    ];

    public function mount(MutualFundsApiService $funds): void
    {
        try {
            $this->funds = $funds->getMutualFunds();
            $availableTypes = $this->availableTypes();

            if (! in_array($this->fcpType, $availableTypes, true) && $availableTypes !== []) {
                $this->fcpType = $availableTypes[0];
            }
        } catch (\Throwable) {
            $this->funds = [];
            $this->error = 'Impossible de charger les FCP des SGO pour le moment.';
        }
    }

    public function render()
    {
        $availableTypes = $this->availableTypes();
        if (! in_array($this->fcpType, $availableTypes, true) && $availableTypes !== []) {
            $this->fcpType = $availableTypes[0];
        }

        $selectedFunds = $this->selectedFunds();
        $projection = $this->buildProjection($selectedFunds);

        return view('livewire.pages.outils.simulateur-interets-composes', [
            'availableTypes' => $availableTypes,
            'selectedFunds' => $selectedFunds,
            'typeDescription' => self::TYPE_DESCRIPTIONS[$this->fcpType] ?? 'Projection indicative par type de FCP.',
            'typeRate' => $this->rateForType($this->fcpType),
            'future' => $projection['future'],
            'invested' => $projection['invested'],
            'gain' => $projection['gain'],
            'chart' => $projection['chart'],
            'summary' => $projection['summary'],
        ])
            ->extends('layouts.site', ['title' => 'Simulateur d’Intérêts Composés — Africaine des Finances'])
            ->section('content');
    }

    /**
     * @return array<int, string>
     */
    private function availableTypes(): array
    {
        $types = array_values(array_unique(array_filter(
            array_map(fn ($fund) => (string) ($fund['category'] ?? ''), $this->funds)
        )));

        usort($types, function (string $a, string $b) {
            $order = array_keys(self::INDICATIVE_RATES);
            $rankA = array_search($a, $order, true);
            $rankB = array_search($b, $order, true);

            return ($rankA === false ? 99 : $rankA) <=> ($rankB === false ? 99 : $rankB);
        });

        return $types;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function selectedFunds(): array
    {
        return array_values(array_filter(
            $this->funds,
            fn ($fund) => ($fund['category'] ?? '') === $this->fcpType
        ));
    }

    /**
     * @param array<int, array<string, mixed>> $funds
     * @return array{future: int, invested: int, gain: int, chart: array<int, array<string, mixed>>, summary: array<int, array<string, mixed>>}
     */
    private function buildProjection(array $funds): array
    {
        $months = max($this->annees, 0) * 12;
        $capital = max($this->capital, 0);
        $versement = max($this->versement, 0);
        $invested = $capital + ($versement * $months);

        if ($funds === []) {
            return [
                'future' => (int) round($capital),
                'invested' => (int) round($invested),
                'gain' => 0,
                'chart' => [['year' => 0, 'total' => (int) round($capital)]],
                'summary' => [],
            ];
        }

        $fundCount = count($funds);
        $monthlyCapital = $capital / $fundCount;
        $monthlyVersement = $versement / $fundCount;
        $values = [];
        $chart = [['year' => 0, 'total' => (int) round($capital)]];

        foreach ($funds as $fund) {
            $key = $this->chartKey($fund);
            $values[$key] = $monthlyCapital;
            $chart[0][$key] = (int) round($monthlyCapital);
        }

        for ($month = 1; $month <= $months; $month++) {
            $point = ['year' => round($month / 12, 1)];
            $total = 0.0;

            foreach ($funds as $fund) {
                $key = $this->chartKey($fund);
                $rate = $this->rateForType((string) ($fund['category'] ?? $this->fcpType)) / 100 / 12;
                $values[$key] = ($values[$key] + $monthlyVersement) * (1 + $rate);
                $point[$key] = (int) round($values[$key]);
                $total += $values[$key];
            }

            $point['total'] = (int) round($total);
            $chart[] = $point;
        }

        $summary = array_map(function ($fund) use ($values) {
            $key = $this->chartKey($fund);

            return [
                'key' => $key,
                'name' => $fund['name'] ?? 'FCP',
                'company' => $fund['company'] ?? 'SGO',
                'category' => $fund['category'] ?? $this->fcpType,
                'value' => (int) round($values[$key] ?? 0),
            ];
        }, $funds);

        $future = (int) round(array_sum(array_column($summary, 'value')));

        return [
            'future' => $future,
            'invested' => (int) round($invested),
            'gain' => (int) round($future - $invested),
            'chart' => $chart,
            'summary' => $summary,
        ];
    }

    private function rateForType(string $type): float
    {
        return self::INDICATIVE_RATES[$type] ?? 7.0;
    }

    /**
     * @param array<string, mixed> $fund
     */
    private function chartKey(array $fund): string
    {
        return 'fund_'.substr(md5((string) ($fund['id'] ?? $fund['name'] ?? 'fund')), 0, 10);
    }
}
