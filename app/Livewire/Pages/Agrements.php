<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class Agrements extends Component
{
    public function render()
    {
        $items = [
            [
                'title' => 'Agréments AMF-UMOA',
                'ref' => 'AA/2022-03',
                'body' => 'Cadre d’agrément pour les activités de formation et d’accompagnement financier dans l’espace UMOA.',
            ],
            [
                'title' => 'Conformité marchés régionaux',
                'ref' => 'BRVM / CREPMF',
                'body' => 'Alignement des contenus pédagogiques et des outils d’information marché sur les standards régionaux.',
            ],
            [
                'title' => 'Certification des parcours',
                'ref' => 'CERT-AF',
                'body' => 'Délivrance de certificats vérifiables après validation des critères de réussite des formations.',
            ],
        ];

        return view('livewire.pages.agrements', compact('items'))
            ->extends('layouts.site', ['title' => 'Agréments & conformité'])
            ->section('content');
    }
}
