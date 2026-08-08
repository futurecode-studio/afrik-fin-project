<?php

namespace App\Livewire\Pages;

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

        $members = [
            [
                'name' => 'Marc C. Emmanuel EBO',
                'role' => 'Directeur général',
                'bio' => 'Docteur en sciences de gestion, enseignant-chercheur et financier, Marc C. Emmanuel EBO dirige Africaine des Finances. Il porte la vision d\'une finance accessible, pédagogique et orientée vers l\'épargne intelligente sur le marché financier régional.',
                'tags' => ['Direction', 'Gestion', 'Marchés financiers'],
                'image' => 'assets/images/team/ceo.jpeg',
            ],
            [
                'name' => 'Mohamed Fawaz ANGO',
                'role' => 'Conseiller financier',
                'bio' => 'Spécialiste de la relation client et de l\'accompagnement des investisseurs, Mohamed Fawaz ANGO est titulaire d\'un Master en Économie Appliquée et Politique de Développement. Il propose des solutions financières adaptées avec professionnalisme, transparence et création de valeur durable.',
                'tags' => ['Relation client', 'Investissement', 'Analyse économique'],
                'image' => 'assets/images/team/mohamed.PNG',
            ],
            [
                'name' => 'Cyrille Omondoun OGNONDOUN',
                'role' => 'Conseiller financier',
                'bio' => 'Diplômé en Économie et Finance Internationale de l\'Université de Parakou, Cyrille Omondoun OGNONDOUN accompagne particuliers, entreprises et institutions dans leurs projets d\'épargne et de valorisation de patrimoine sur le marché financier de l\'UEMOA.',
                'tags' => ['Épargne', 'Patrimoine', 'UEMOA'],
                'image' => 'assets/images/team/cyrille.jpeg',
            ],
            [
                'name' => 'Eureka HOUNKPATIN',
                'role' => 'Chargé de clientèle',
                'bio' => 'Eureka HOUNKPATIN accompagne les particuliers et les entreprises dans la découverte de solutions financières adaptées à leurs besoins, avec un engagement fondé sur l\'écoute, le professionnalisme et la construction de relations de confiance durables.',
                'tags' => ['Clientèle', 'Solutions financières', 'Confiance'],
                'image' => 'assets/images/team/eureka.jpg',
            ],
            [
                'name' => 'Micheline Gloria HOUNTONDJI',
                'role' => 'Conseillère clientèle',
                'bio' => 'Passionnée par la relation client et les marchés financiers, Micheline Gloria HOUNTONDJI accompagne chaque client dans la réalisation de ses projets d\'investissement avec écoute, rigueur et professionnalisme.',
                'tags' => ['Relation client', 'Investissement', 'Expérience client'],
                'image' => 'assets/images/team/micheline.jpeg',
            ],
            [
                'name' => 'Flora HESSOU',
                'role' => 'Conseillère clientèle',
                'bio' => 'Passionnée par la finance et l\'investissement, Flora HESSOU conseille, oriente et accompagne les particuliers et les institutionnels dans la gestion de leur trésorerie et la construction d\'un patrimoine solide.',
                'tags' => ['Finance', 'Investissement', 'Patrimoine'],
                'image' => 'assets/images/team/flora.jpeg',
            ],
            [
                'name' => 'Morel AGONSANOU',
                'role' => 'Conseiller financier',
                'bio' => 'Conseiller financier chez Africaine des Finances, Morel AGONSANOU offre un accompagnement personnalisé en épargne, investissement et gestion de patrimoine sur le Marché Financier Régional BRVM via les SGI et SGO.',
                'tags' => ['Épargne', 'BRVM', 'Gestion de patrimoine'],
                'image' => 'assets/images/team/morel.jpeg',
            ],
            [
                'name' => 'Donantin Rogatien Bij-Or AHOLOU',
                'role' => 'Conseiller financier',
                'bio' => 'Diplômé de l\'ENA du Bénin en Administration du Travail et de la Sécurité Sociale, Donantin Rogatien Bij-Or AHOLOU a développé une solide culture financière dans la microfinance avant de se spécialiser dans l\'accompagnement sur le marché financier régional de l\'UEMOA.',
                'tags' => ['Conseil financier', 'Microfinance', 'Patrimoine'],
                'image' => 'assets/images/team/donatin.JPG',
            ],
        ];
        
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
