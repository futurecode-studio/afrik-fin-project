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
        'Diversifies' => 8.0,
    ];

    /** @var array<string, string> */
    private const TYPE_DESCRIPTIONS = [
        'Actions' => 'FCP Actions : profil dynamique, volatilité et potentiel plus élevés.',
        'Obligations' => 'FCP Obligations : profil prudent, rendement indicatif plus modéré.',
        'Diversifies' => 'FCP Diversifiés : profil équilibré entre actions et obligations.',
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
        for ($month = 1; $month <= $months; $month++) {
            $value = ($value + $versement) * (1 + $monthlyRate);
        }

        $future = (int) round($value);

        return [
            'future' => $future,
            'invested' => (int) round($invested),
            'gain' => (int) round($future - $invested),
            'chart' => $this->buildVolatileChart($capital, $versement, $months, $monthlyRate, $future),
        ];
    }

    /**
     * Courbe avec fluctuations réalistes (montées / baisses) tout en rejoignant la valeur finale.
     *
     * @return array<int, array{year: float|int, total: int}>
     */
    private function buildVolatileChart(float $capital, float $versement, int $months, float $monthlyRate, int $future): array
    {
        if ($months === 0) {
            return [['year' => 0, 'total' => (int) round($capital)]];
        }

        $monthlyValues = [(float) $capital];
        $value = (float) $capital;
        $volatility = $this->monthlyVolatility($this->fcpType);

        for ($month = 1; $month <= $months; $month++) {
            $noise = $this->deterministicMonthlyNoise($month);
            $monthReturn = $monthlyRate + ($noise * $volatility);
            $value = ($value + $versement) * (1 + $monthReturn);
            $monthlyValues[] = $value;
        }

        $stochasticEnd = max($monthlyValues[count($monthlyValues) - 1], 1);
        $scale = $future / $stochasticEnd;

        $interval = $months <= 24 ? 3 : 6;
        $chart = [['year' => 0, 'total' => (int) round($capital)]];

        for ($month = 1; $month <= $months; $month++) {
            if ($month % $interval !== 0 && $month !== $months) {
                continue;
            }

            $investedAtPoint = $capital + ($versement * $month);
            $scaled = (int) round($monthlyValues[$month] * $scale);
            $scaled = max($scaled, (int) round($investedAtPoint * 0.9));

            $chart[] = [
                'year' => round($month / 12, 1),
                'total' => $scaled,
            ];
        }

        $chart[count($chart) - 1]['total'] = $future;

        return $chart;
    }

    private function monthlyVolatility(string $type): float
    {
        return match ($type) {
            'Actions' => 0.05,
            'Obligations' => 0.015,
            'Diversifies' => 0.03,
            default => 0.03,
        };
    }

    private function deterministicMonthlyNoise(int $month): float
    {
        $seed = crc32(sprintf(
            '%s|%.2f|%.2f|%d',
            $this->fcpType,
            $this->capital,
            $this->versement,
            $this->annees
        ));

        $t = $month + (($seed % 1000) / 1000);

        return (sin($t * 1.73) + (0.5 * sin(($t * 3.29) + 2.1)) + (0.25 * cos(($t * 5.11) + 0.5))) / 1.75;
    }

    private function rateForType(string $type): float
    {
        return self::INDICATIVE_RATES[$type] ?? 7.0;
    }
}
