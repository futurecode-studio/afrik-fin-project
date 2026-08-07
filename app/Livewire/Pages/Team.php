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
            'address' => 'Fidjrossè Hlazounto, lot 3990, parcelle g, fin des pavés du CEG l\'Entente, Cotonou, Bénin',
            'legal' => 'RCCM RB/COT/21 B 31296 · IFU 3202113721309 · Agrément AA/2022-03',
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
                'name' => 'Donantin Rogatien Bij-Or AHOLOU',
                'role' => 'Conseiller financier',
                'bio' => 'Diplômé de l\'ENA du Bénin en Administration du Travail et de la Sécurité Sociale, Donantin Rogatien Bij-Or AHOLOU a développé une solide culture financière dans la microfinance avant de se spécialiser dans l\'accompagnement sur le marché financier régional de l\'UEMOA.',
                'tags' => ['Conseil financier', 'Microfinance', 'Patrimoine'],
                'image' => 'assets/images/team/donatin.JPG',
            ],
        ];
        
        return view('livewire.pages.team', [
            'company' => $company,
            'members' => $members,
        ])
            ->extends('layouts.site', ['title' => 'Équipe'])
            ->section('content');
    }
}
