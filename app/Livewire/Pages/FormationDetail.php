<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class FormationDetail extends Component
{
    public $formation;
    
    public function mount($slug = null)
    {
        // Données de démonstration pour les formations
        $formations = [
            'gestion-budgetaire' => [
                'title' => 'Gestion Budgétaire et Épargne',
                'category' => 'Finance Personnelle',
                'description' => 'Apprenez à gérer efficacement vos finances personnelles et à constituer une épargne solide.',
                'fullDescription' => 'Cette formation complète vous enseignera les fondamentaux de la gestion budgétaire, les techniques d\'épargne efficaces et les stratégies pour atteindre vos objectifs financiers. Adaptée au contexte africain, elle intègre des exemples concrets et des études de cas locales.',
                'duration' => '8 semaines',
                'level' => 'Débutant',
                'price' => '75 000 XOF',
                'instructor' => 'Dr. Amina Konaté',
                'instructorTitle' => 'Expert en finance personnelle',
                'students' => 1234,
                'rating' => 4.8,
                'modules' => [
                    'Introduction à la finance personnelle',
                    'Création et suivi d\'un budget',
                    'Techniques d\'épargne',
                    'Gestion des dettes',
                    'Planification d\'objectifs financiers',
                    'Investissements de base',
                    'Fiscalité et optimisation'
                ],
                'objectives' => [
                    'Maîtriser les principes de base de la finance personnelle',
                    'Créer et maintenir un budget équilibré',
                    'Développer des habitudes d\'épargne efficaces',
                    'Comprendre les différents types d\'investissements',
                    'Planifier vos objectifs financiers à long terme'
                ],
                'prerequisites' => [
                    'Aucun prérequis nécessaire',
                    'Accès à internet et un ordinateur',
                    'Motivation à apprendre'
                ]
            ],
            'initiation-bourse' => [
                'title' => 'Initiation à la Bourse',
                'category' => 'Investissement',
                'description' => 'Découvrez les fondamentaux de l\'investissement en bourse et les stratégies pour débuter.',
                'fullDescription' => 'Plongez dans l\'univers des marchés financiers avec cette formation d\'initiation. Vous apprendrez à analyser les actions, comprendre les indices boursiers africains et développer des stratégies d\'investissement adaptées à votre profil de risque.',
                'duration' => '12 semaines',
                'level' => 'Débutant',
                'price' => '120 000 XOF',
                'instructor' => 'Jean-Paul Mbemba',
                'instructorTitle' => 'Analyste financier',
                'students' => 856,
                'rating' => 4.9,
                'modules' => [
                    'Introduction aux marchés financiers',
                    'Analyse fondamentale',
                    'Analyse technique',
                    'Gestion de portefeuille',
                    'Risque et diversification',
                    'Marchés africains (BRVM, JSE, etc.)',
                    'Stratégies d\'investissement',
                    'Psychologie du trading'
                ],
                'objectives' => [
                    'Comprendre le fonctionnement des marchés boursiers',
                    'Analyser les entreprises et leurs actions',
                    'Construire un portefeuille diversifié',
                    'Gérer le risque efficacement',
                    'Développer une stratégie d\'investissement personnelle'
                ],
                'prerequisites' => [
                    'Connaissances de base en finance',
                    'Calculatrice et accès à internet',
                    'Notions de mathématiques financières'
                ]
            ],
            'blockchain-crypto' => [
                'title' => 'Blockchain et Crypto-actifs',
                'category' => 'Crypto-monnaies',
                'description' => 'Comprendre la technologie blockchain et les opportunités des crypto-monnaies.',
                'fullDescription' => 'Explorez le monde révolutionnaire de la blockchain et des crypto-monnaies. Cette formation couvre les aspects techniques, économiques et pratiques des actifs numériques, avec un focus particulier sur les opportunités en Afrique.',
                'duration' => '6 semaines',
                'level' => 'Intermédiaire',
                'price' => '95 000 XOF',
                'instructor' => 'Dr. Youssef Alami',
                'instructorTitle' => 'Expert en blockchain',
                'students' => 623,
                'rating' => 4.7,
                'modules' => [
                    'Introduction à la blockchain',
                    'Bitcoin et crypto-monnaies majeures',
                    'DeFi (Finance Décentralisée)',
                    'NFTs et tokens',
                    'Sécurité et stockage',
                    'Réglementation en Afrique',
                    'Opportunités d\'investissement',
                    'Cas d\'usage pratiques'
                ],
                'objectives' => [
                    'Comprendre la technologie blockchain',
                    'Identifier les opportunités d\'investissement',
                    'Sécuriser ses actifs numériques',
                    'Naviguer dans l\'écosystème DeFi',
                    'Évaluer les projets blockchain'
                ],
                'prerequisites' => [
                    'Connaissances informatiques de base',
                    'Compréhension des concepts financiers',
                    'Curiosité pour la technologie'
                ]
            ]
        ];
        
        $this->formation = $formations[$slug] ?? $formations['gestion-budgetaire'];
    }
    
    public function render()
    {
        return view('livewire.pages.formation-detail')
            ->extends('layouts.site', ['title' => 'Accueil'])
            ->section('content');
    }
}
