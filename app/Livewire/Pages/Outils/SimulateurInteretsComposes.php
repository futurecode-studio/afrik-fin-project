<?php

namespace App\Livewire\Pages\Outils;

use Livewire\Component;

class SimulateurInteretsComposes extends Component
{
    public float $capital = 1000000;
    public float $versement = 50000;
    public int $annees = 10;
    public float $taux = 8;

    public function render()
    {
        $r = max($this->taux, 0) / 100 / 12;
        $n = max($this->annees, 0) * 12;
        $pmt = max($this->versement, 0);
        $pv = max($this->capital, 0);

        $future = $pv;
        $chart = [];
        for ($m = 1; $m <= max($n, 1); $m++) {
            $future = ($future + $pmt) * (1 + $r);
            if ($m % 12 === 0 || $m === $n) {
                $chart[] = ['year' => (int) ceil($m / 12), 'value' => round($future)];
            }
        }
        if ($n === 0) {
            $future = $pv;
            $chart = [['year' => 0, 'value' => round($pv)]];
        }

        $invested = $pv + ($pmt * $n);
        $gain = $future - $invested;

        return view('livewire.pages.outils.simulateur-interets-composes', [
            'future' => round($future),
            'invested' => round($invested),
            'gain' => round($gain),
            'chart' => $chart,
        ])
            ->extends('layouts.site', ['title' => 'Simulateur d’Intérêts Composés — Africaine des Finances'])
            ->section('content');
    }
}
