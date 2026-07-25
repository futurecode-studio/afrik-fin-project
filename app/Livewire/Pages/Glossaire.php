<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class Glossaire extends Component
{
    public string $search = '';
    public string $letter = 'all';

    public function render()
    {
        $terms = [
            ['term' => 'Action (Equity)', 'def' => 'Titre de propriété d’une société cotée. Sur la BRVM, les actions s’échangent en XOF.'],
            ['term' => 'Obligation (Bond)', 'def' => 'Titre de créance émis par un État ou une entreprise, rémunéré par un coupon.'],
            ['term' => 'PER (Price Earning Ratio)', 'def' => 'Rapport cours / bénéfice par action. Aide à comparer la valorisation relative.'],
            ['term' => 'Valeur Liquidative (NAV)', 'def' => 'Prix d’une part d’OPCVM / FCP, calculé périodiquement par la SGO.'],
            ['term' => 'Dividende', 'def' => 'Part du bénéfice redistribuée aux actionnaires, souvent annuelle.'],
            ['term' => 'BRVM', 'def' => 'Bourse Régionale des Valeurs Mobilières de l’UEMOA, basée à Abidjan.'],
            ['term' => 'Coupon', 'def' => 'Intérêt périodique versé au détenteur d’une obligation.'],
            ['term' => 'SGI', 'def' => 'Société de Gestion et d’Intermédiation : exécute les ordres et tient les comptes-titres.'],
            ['term' => 'SGO', 'def' => 'Société de Gestion d’OPCVM : gère les fonds (FCP, SICAV).'],
            ['term' => 'OPCVM', 'def' => 'Organisme de Placement Collectif en Valeurs Mobilières.'],
            ['term' => 'AMF-UMOA', 'def' => 'Autorité des Marchés Financiers de l’UEMOA, régulateur régional.'],
            ['term' => 'Liquidité', 'def' => 'Facilité à acheter ou vendre un titre sans impact excessif sur le prix.'],
            ['term' => 'Volatilité', 'def' => 'Amplitude des variations de cours sur une période donnée.'],
            ['term' => 'Spread', 'def' => 'Écart entre le prix d’achat et le prix de vente proposés.'],
            ['term' => 'Capitalisation', 'def' => 'Valeur de marché totale d’une société (cours × nombre de titres).'],
            ['term' => 'Indice boursier', 'def' => 'Indicateur synthétique de la performance d’un panier de titres (ex. BRVM Composite).'],
        ];

        $q = mb_strtolower(trim($this->search));
        $filtered = collect($terms)->filter(function ($t) use ($q) {
            $first = mb_strtoupper(mb_substr($t['term'], 0, 1));
            if ($this->letter !== 'all' && $first !== $this->letter) {
                return false;
            }
            if ($q === '') {
                return true;
            }

            return str_contains(mb_strtolower($t['term']), $q) || str_contains(mb_strtolower($t['def']), $q);
        })->sortBy('term')->values();

        $letters = collect($terms)->map(fn ($t) => mb_strtoupper(mb_substr($t['term'], 0, 1)))->unique()->sort()->values();

        return view('livewire.pages.glossaire', compact('filtered', 'letters'))
            ->extends('layouts.site', ['title' => 'Glossaire Financier — Africaine des Finances'])
            ->section('content');
    }
}
