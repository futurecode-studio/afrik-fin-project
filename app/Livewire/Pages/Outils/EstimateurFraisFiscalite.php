<?php

namespace App\Livewire\Pages\Outils;

use Livewire\Component;

class EstimateurFraisFiscalite extends Component
{
    public float $montant = 1000000;
    public string $operation = 'achat_actions'; // achat_actions, vente_actions, souscription_fcp
    public float $frais_courtage_pct = 0.8;
    public float $frais_sgi_pct = 0.5;
    public float $tva_pct = 18;
    public float $irvm_pct = 10; // prélèvement typique dividendes (indicatif)

    public function render()
    {
        $base = max($this->montant, 0);
        $courtage = $base * (max($this->frais_courtage_pct, 0) / 100);
        $sgi = $base * (max($this->frais_sgi_pct, 0) / 100);
        $ht = $courtage + $sgi;
        $tva = $ht * (max($this->tva_pct, 0) / 100);
        $totalFrais = $ht + $tva;

        $net = match ($this->operation) {
            'vente_actions' => $base - $totalFrais,
            default => $base + $totalFrais,
        };

        $irvmEstime = $this->operation === 'vente_actions'
            ? 0
            : $base * (max($this->irvm_pct, 0) / 100) * 0; // IRVM sur dividendes, pas sur le ticket

        return view('livewire.pages.outils.estimateur-frais-fiscalite', [
            'courtage' => round($courtage),
            'sgi' => round($sgi),
            'tva' => round($tva),
            'totalFrais' => round($totalFrais),
            'net' => round($net),
            'irvmEstime' => round($irvmEstime),
        ])
            ->extends('layouts.site', ['title' => 'Estimateur de Frais et Fiscalité — Africaine des Finances'])
            ->section('content');
    }
}
