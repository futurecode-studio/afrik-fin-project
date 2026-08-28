<?php

namespace App\Livewire\Pages;

use App\Support\TeamCatalog;
use Livewire\Component;

class Team extends Component
{
    public function render()
    {
        $company = [
            'description' => 'Africaine des Finances est un cabinet de conseils et d\'ingénierie financière, agréé en qualité d\'apporteur d\'affaires sur le marché financier régional BRVM par l\'AMF-UMOA.',
            'mission' => 'Vulgariser l\'investissement sur le marché financier régional et accompagner les particuliers, entreprises, institutions et associations dans leurs projets d\'épargne, d\'investissement et de valorisation de patrimoine.',
            'positioning' => 'Le cabinet intervient au croisement de l\'éducation financière, de l\'information de marché et de la mise en relation avec l\'écosystème agréé. Sa démarche consiste à aider les clients à comprendre les produits, mesurer les risques et avancer vers des solutions adaptées à leur profil.',
            'address' => 'Fidjrossè Hlazounto, lot 3990, parcelle g, fin des pavés du CEG l\'Entente, Cotonou, Bénin',
            'legal' => 'RCCM RB/COT/21 B 31296 · IFU 3202113721309 · Agrément AA/2022-03',
            'approval' => 'Agrément d\'apporteur d\'affaires N° AA/2022-03, décision N° 22/143 — AMF-UMOA',
        ];

        $facts = [
            ['label' => 'Forme', 'value' => 'SARL de droit béninois'],
            ['label' => 'Création', 'value' => '2021'],
            ['label' => 'Régulation', 'value' => 'AMF-UMOA'],
            ['label' => 'Marché', 'value' => 'BRVM / UEMOA'],
        ];

        $pillars = [
            [
                'icon' => 'school',
                'title' => 'Former',
                'text' => 'Des contenus pédagogiques et parcours e-learning pour comprendre la bourse, les actions, les obligations, les FCP/OPCVM et les bases de la construction patrimoniale.',
            ],
            [
                'icon' => 'candlestick_chart',
                'title' => 'Informer',
                'text' => 'Des données, cotations, repères de marché et analyses pour aider les investisseurs à décider avec plus de méthode, sans agir uniquement à l\'intuition.',
            ],
            [
                'icon' => 'handshake',
                'title' => 'Orienter',
                'text' => 'Une mise en relation avec des acteurs agréés du marché financier régional, notamment les SGI et SGO, dans le respect du cadre AMF-UMOA.',
            ],
        ];

        $steps = [
            ['title' => 'Écoute du besoin', 'text' => 'Comprendre la situation du client, son horizon, son expérience financière et son niveau de tolérance au risque.'],
            ['title' => 'Clarification du profil', 'text' => 'Expliquer les options possibles, les contraintes, les frais, la liquidité et les risques associés aux placements envisagés.'],
            ['title' => 'Orientation encadrée', 'text' => 'Accompagner vers les solutions et partenaires adaptés, sans promettre de rendement garanti ni contourner les acteurs agréés.'],
            ['title' => 'Suivi pédagogique', 'text' => 'Rester disponible pour aider le client à lire ses informations de portefeuille et progresser dans sa culture financière.'],
        ];

        $commitments = [
            'Pédagogie claire avant toute décision d\'investissement.',
            'Transparence sur les risques, les frais et les limites de chaque solution.',
            'Orientation vers l\'écosystème agréé du marché financier régional.',
            'Accompagnement adapté aux particuliers, entreprises, institutions et associations.',
        ];

        $members = TeamCatalog::members();
        
        return view('livewire.pages.team', [
            'company' => $company,
            'facts' => $facts,
            'pillars' => $pillars,
            'steps' => $steps,
            'commitments' => $commitments,
            'members' => $members,
        ])
            ->extends('layouts.site', ['title' => 'À propos'])
            ->section('content');
    }
}
