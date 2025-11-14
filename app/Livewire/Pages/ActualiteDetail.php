<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class ActualiteDetail extends Component
{
    public $article;
    
    public function mount($slug = null)
    {
        // Données de démonstration pour les articles
        $articles = [
            'opportunites-investissement-afrique-ouest' => [
                'title' => 'Nouvelles opportunités d\'investissement en Afrique de l\'Ouest',
                'category' => 'Investissement',
                'excerpt' => 'Découvrez les secteurs porteurs et les perspectives de croissance pour les investisseurs...',
                'content' => 'L\'Afrique de l\'Ouest connaît actuellement une période de croissance économique sans précédent, avec des opportunités d\'investissement émergentes dans plusieurs secteurs clés. Les marchés de la région, notamment la BRVM (Bourse Régionale des Valeurs Mobilières), offrent des rendements attractifs pour les investisseurs locaux et internationaux.

Les secteurs de la technologie, des télécommunications, de l\'agriculture et des énergies renouvelables présentent un potentiel de croissance particulièrement intéressant. Les gouvernements de la région mettent en place des politiques favorables à l\'investissement, avec des réformes fiscales et des simplifications administratives.

Cette analyse détaillée examine les opportunités spécifiques par pays et par secteur, en fournissant des recommandations concrètes pour les investisseurs souhaitant profiter de cette dynamique de croissance.',
                'author' => 'Marie Kouamé',
                'authorTitle' => 'Analyste financière',
                'date' => '15 Novembre 2024',
                'readTime' => '8 min',
                'image' => 'investissement-afrique-ouest',
                'tags' => ['Investissement', 'BRVM', 'Croissance', 'Afrique de l\'Ouest'],
                'relatedArticles' => [
                    [
                        'title' => 'La fintech africaine en pleine expansion',
                        'slug' => 'fintech-africaine-expansion',
                        'date' => '12 Novembre 2024'
                    ],
                    [
                        'title' => 'Lancement de notre nouvelle plateforme digitale',
                        'slug' => 'lancement-plateforme-digitale',
                        'date' => '10 Novembre 2024'
                    ]
                ]
            ],
            'fintech-africaine-expansion' => [
                'title' => 'La fintech africaine en pleine expansion',
                'category' => 'Fintech',
                'excerpt' => 'Analyse des tendances et innovations qui transforment le paysage financier continental...',
                'content' => 'Le secteur de la fintech en Afrique connaît une croissance exponentielle, transformant radicalement l\'accès aux services financiers pour des millions de personnes. Des startups innovantes émergent dans toute l\'Afrique, proposant des solutions adaptées aux besoins locaux.

Le mobile money, les prêts numériques, l\'assurance digitale et les services d\'investissement en ligne révolutionnent la manière dont les Africains gèrent leur argent. Des pays comme le Kenya, le Nigéria, l\'Afrique du Sud et le Ghana sont devenus des hubs d\'innovation fintech.

Cette analyse explore les principales tendances du secteur, les acteurs clés, les défis réglementaires et les opportunités futures pour les investisseurs et les entrepreneurs.',
                'author' => 'Jean-Baptiste Touré',
                'authorTitle' => 'Expert en technologie financière',
                'date' => '12 Novembre 2024',
                'readTime' => '6 min',
                'image' => 'fintech-africaine',
                'tags' => ['Fintech', 'Innovation', 'Digital', 'Mobile Money'],
                'relatedArticles' => [
                    [
                        'title' => 'Nouvelles opportunités d\'investissement en Afrique de l\'Ouest',
                        'slug' => 'opportunites-investissement-afrique-ouest',
                        'date' => '15 Novembre 2024'
                    ],
                    [
                        'title' => 'Partenariat stratégique avec la Banque Centrale',
                        'slug' => 'partenariat-banque-centrale',
                        'date' => '8 Novembre 2024'
                    ]
                ]
            ],
            'lancement-plateforme-digitale' => [
                'title' => 'Lancement de notre nouvelle plateforme digitale',
                'category' => 'Actualités',
                'excerpt' => 'Une solution innovante pour simplifier vos opérations financières quotidiennes...',
                'content' => 'Nous sommes ravis d\'annoncer le lancement de notre nouvelle plateforme digitale, conçue pour révolutionner l\'expérience utilisateur dans la gestion financière. Cette nouvelle version intègre les dernières technologies pour offrir une expérience plus intuitive, sécurisée et complète.

La plateforme propose des fonctionnalités innovantes telles que le suivi des dépenses en temps réel, des recommandations d\'investissement personnalisées, des outils de planification budgétaire avancés et une intégration transparente avec les principales institutions financières africaines.

Disponible sur web et mobile, cette nouvelle version représente un pas important dans notre mission de rendre la finance accessible à tous en Afrique.',
                'author' => 'Équipe Afri-Fin',
                'authorTitle' => 'Communication',
                'date' => '10 Novembre 2024',
                'readTime' => '4 min',
                'image' => 'plateforme-digitale',
                'tags' => ['Lancement', 'Digital', 'Innovation', 'Plateforme'],
                'relatedArticles' => [
                    [
                        'title' => 'Nouvelles opportunités d\'investissement en Afrique de l\'Ouest',
                        'slug' => 'opportunites-investissement-afrique-ouest',
                        'date' => '15 Novembre 2024'
                    ],
                    [
                        'title' => 'La fintech africaine en pleine expansion',
                        'slug' => 'fintech-africaine-expansion',
                        'date' => '12 Novembre 2024'
                    ]
                ]
            ]
        ];
        
        $this->article = $articles[$slug] ?? $articles['opportunites-investissement-afrique-ouest'];
    }
    
    public function render()
    {
        return view('livewire.pages.actualite-detail')
            ->extends('layouts.site', ['title' => 'Accueil'])
            ->section('content');
    }
}
