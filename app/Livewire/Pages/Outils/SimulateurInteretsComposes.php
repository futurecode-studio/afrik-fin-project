<?php

namespace App\Livewire\Pages\Outils;

use Livewire\Component;

class SimulateurInteretsComposes extends Component
{
    public float $capital = 1000000;

    public float $versement = 50000;

    public int $annees = 10;

    public string $fcpType = 'Actions';

    /** @var array<string, float> */
    private const INDICATIVE_RATES = [
        'Actions' => 13.0,
        'Obligations' => 6.0,
    ];

    /** @var array<string, string> */
    private const TYPE_DESCRIPTIONS = [
        'Actions' => 'FCP Actions : profil dynamique, volatilité et potentiel plus élevés.',
        'Obligations' => 'FCP Obligations : profil prudent, rendement indicatif plus modéré.',
    ];

    public function render()
    {
        $availableTypes = array_keys(self::INDICATIVE_RATES);
        if (! in_array($this->fcpType, $availableTypes, true)) {
            $this->fcpType = 'Actions';
        }

        $projection = $this->buildProjection();

        return view('livewire.pages.outils.simulateur-interets-composes', [
            'availableTypes' => $availableTypes,
            'rates' => self::INDICATIVE_RATES,
            'typeDescription' => self::TYPE_DESCRIPTIONS[$this->fcpType] ?? 'Projection indicative par type de FCP.',
            'typeRate' => $this->rateForType($this->fcpType),
            'future' => $projection['future'],
            'invested' => $projection['invested'],
            'gain' => $projection['gain'],
            'chart' => $projection['chart'],
        ])
            ->extends('layouts.site', ['title' => 'Simulateur d’Intérêts Composés — Africaine des Finances'])
            ->section('content');
    }

    /**
     * @return array{future: int, invested: int, gain: int, chart: array<int, array{year: float|int, total: int}>}
     */
    private function buildProjection(): array
    {
        $months = max($this->annees, 0) * 12;
        $capital = max($this->capital, 0);
        $versement = max($this->versement, 0);
        $invested = $capital + ($versement * $months);
        $monthlyRate = $this->rateForType($this->fcpType) / 100 / 12;

        $value = $capital;
        $chart = [['year' => 0, 'total' => (int) round($capital)]];

        for ($month = 1; $month <= $months; $month++) {
            $value = ($value + $versement) * (1 + $monthlyRate);
            if ($month % 12 === 0 || $month === $months) {
                $chart[] = [
                    'year' => round($month / 12, 1),
                    'total' => (int) round($value),
                ];
            }
        }

        $future = (int) round($value);

        return [
            'future' => $future,
            'invested' => (int) round($invested),
            'gain' => (int) round($future - $invested),
            'chart' => $chart,
        ];
    }

    private function rateForType(string $type): float
    {
        return self::INDICATIVE_RATES[$type] ?? 7.0;
    }
}
