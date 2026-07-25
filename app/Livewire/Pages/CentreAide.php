<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class CentreAide extends Component
{
    public string $q = '';

    public function render()
    {
        $topics = collect([
            ['cat' => 'Compte', 'q' => 'Comment créer un compte ?', 'a' => 'Utilisez la page d’inscription, validez votre e-mail puis connectez-vous à l’espace client.'],
            ['cat' => 'Formations', 'q' => 'Comment accéder à mes cours ?', 'a' => 'Depuis Mes formations, cliquez Continuer / Commencer. La progression et les verrous de modules s’appliquent automatiquement.'],
            ['cat' => 'Formations', 'q' => 'Où trouver mon certificat ?', 'a' => 'Dans Certificats (espace client). Un QR permet de vérifier l’authenticité sur le site public.'],
            ['cat' => 'Paiement', 'q' => 'Quels moyens de paiement ?', 'a' => 'Les paiements passent par les passerelles configurées (ex. Kkiapay / Fedapay) selon l’offre.'],
            ['cat' => 'Marchés', 'q' => 'Les données marché sont-elles un conseil ?', 'a' => 'Non. Ce sont des informations et outils pédagogiques — voir l’avertissement investissement.'],
            ['cat' => 'Support', 'q' => 'Comment contacter le support ?', 'a' => 'Via Contact, FAQ, ou ouverture d’un ticket support.'],
        ]);

        if ($this->q !== '') {
            $term = mb_strtolower($this->q);
            $topics = $topics->filter(fn ($t) => str_contains(mb_strtolower($t['q'].' '.$t['a'].' '.$t['cat']), $term));
        }

        return view('livewire.pages.centre-aide', ['topics' => $topics->values()])
            ->extends('layouts.site', ['title' => 'Centre d\'aide'])
            ->section('content');
    }
}
