<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class ServiceDetail extends Component
{
    public $service;
    
    public function mount($slug = null)
    {
        // Données de démonstration pour le service
        $services = [
            'conseil-financier' => [
                'title' => 'Conseil Financier',
                'description' => 'Expertise personnalisée pour optimiser vos décisions financières',
                'fullDescription' => 'Notre service de conseil financier vous accompagne dans toutes vos décisions importantes. Nos experts analysent votre situation personnelle ou professionnelle pour vous proposer des stratégies sur mesure.',
                'features' => [
                    'Analyse patrimoniale complète',
                    'Stratégies d\'optimisation fiscale',
                    'Planification de retraite',
                    'Conseil en investissement',
                    'Accompagnement personnalisé'
                ],
                'price' => 'À partir de 50 000 XOF/mois',
                'duration' => 'Contrat mensuel sans engagement',
                'image' => 'conseil-financier'
            ],
            'gestion-patrimoine' => [
                'title' => 'Gestion de Patrimoine',
                'description' => 'Stratégies sur mesure pour faire fructifier votre capital',
                'fullDescription' => 'Confiez-nous la gestion de votre patrimoine pour une croissance sereine et optimisée. Notre approche combine expertise financière et connaissance des marchés africains.',
                'features' => [
                    'Gestion diversifiée des actifs',
                    'Suivi performance en temps réel',
                    'Rapports mensuels détaillés',
                    'Stratégies adaptées à votre profil',
                    'Accès à des investissements exclusifs'
                ],
                'price' => '1.5% du capital géré/an',
                'duration' => 'Gestion à long terme',
                'image' => 'gestion-patrimoine'
            ],
            'investissement' => [
                'title' => 'Investissement',
                'description' => 'Opportunités d\'investissement adaptées au marché africain',
                'fullDescription' => 'Accédez à des opportunités d\'investissement sélectionnées par nos experts pour leur potentiel de croissance sur les marchés africains.',
                'features' => [
                    'Actions des entreprises africaines prometteuses',
                    'Obligations souveraines et corporatives',
                    'Fonds d\'investissement thématiques',
                    'Produits structurés',
                    'Conseil en allocation d\'actifs'
                ],
                'price' => 'Commission de 0.5% par transaction',
                'duration' => 'Investissement flexible',
                'image' => 'investissement'
            ]
        ];
        
        $this->service = $services[$slug] ?? $services['conseil-financier'];
    }
    
    public function render()
    {
        return view('livewire.pages.service-detail')
            ->extends('layouts.site', ['title' => 'Accueil'])
            ->section('content');
    }
}
