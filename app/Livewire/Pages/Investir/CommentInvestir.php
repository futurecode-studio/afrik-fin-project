<?php

namespace App\Livewire\Pages\Investir;

use Livewire\Component;

class CommentInvestir extends Component
{
    public function render()
    {
        $steps = [
            [
                'n' => '01',
                'title' => 'Définir votre profil',
                'text' => 'Évaluez votre horizon, vos objectifs et votre tolérance au risque.',
                'route' => 'investir.profil-test',
                'cta' => 'Passer le test',
                'icon' => 'psychology',
            ],
            [
                'n' => '02',
                'title' => 'Comprendre les marchés',
                'text' => 'Explorez les cotations BRVM, indices et obligations UEMOA.',
                'route' => 'marches.index',
                'cta' => 'Voir les marchés',
                'icon' => 'candlestick_chart',
            ],
            [
                'n' => '03',
                'title' => 'Choisir un véhicule',
                'text' => 'Actions, obligations ou FCP/OPCVM selon votre profil.',
                'route' => 'investir.opcvm',
                'cta' => 'Découvrir les FCP',
                'icon' => 'account_balance',
            ],
            [
                'n' => '04',
                'title' => 'Passer par un partenaire agréé',
                'text' => 'Les SGI / SGO agréées exécutent vos ordres et gèrent vos parts.',
                'route' => 'investir.partenaires',
                'cta' => 'Voir les partenaires',
                'icon' => 'handshake',
            ],
        ];

        return view('livewire.pages.investir.comment-investir', compact('steps'))
            ->extends('layouts.site', ['title' => 'Comment investir — Africaine des Finances'])
            ->section('content');
    }
}
