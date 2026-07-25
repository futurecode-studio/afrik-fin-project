<?php

namespace App\Livewire\Pages\Outils;

use Livewire\Component;

class SimulateurObligataire extends Component
{
    public float $nominal = 10000;
    public float $coupon = 6.5;
    public float $prixPct = 100;
    public string $frequence = 'annuel';
    public int $maturite = 5;

    public function render()
    {
        $freqMap = ['annuel' => 1, 'semestriel' => 2, 'trimestriel' => 4];
        $m = $freqMap[$this->frequence] ?? 1;
        $n = max($this->maturite, 0) * $m;
        $c = (max($this->coupon, 0) / 100) * $this->nominal / $m;
        $purchase = $this->nominal * (max($this->prixPct, 0) / 100);

        $flows = [];
        $totalCoupons = 0.0;
        for ($i = 1; $i <= $n; $i++) {
            $amount = $c + ($i === $n ? $this->nominal : 0);
            $totalCoupons += $c;
            $flows[] = [
                'period' => $i,
                'label' => 'Période '.$i,
                'coupon' => round($c),
                'principal' => $i === $n ? round($this->nominal) : 0,
                'total' => round($amount),
            ];
        }

        $totalReceived = $totalCoupons + $this->nominal;
        $profit = $totalReceived - $purchase;
        $yieldApprox = $purchase > 0 ? (($totalCoupons / max($this->maturite, 1)) / $purchase) * 100 : 0;

        return view('livewire.pages.outils.simulateur-obligataire', [
            'flows' => $flows,
            'purchase' => round($purchase),
            'totalCoupons' => round($totalCoupons),
            'totalReceived' => round($totalReceived),
            'profit' => round($profit),
            'yieldApprox' => round($yieldApprox, 2),
        ])
            ->extends('layouts.site', ['title' => 'Simulateur de Rendement Obligataire — Africaine des Finances'])
            ->section('content');
    }
}
