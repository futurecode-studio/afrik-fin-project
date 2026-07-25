<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class Faq extends Component
{
    public string $search = '';
    public string $category = 'all';

    public function render()
    {
        $categories = [
            'inscriptions' => ['label' => 'Inscriptions', 'icon' => 'person_add', 'desc' => 'Compte, KYC et documents.'],
            'formations' => ['label' => 'Formations', 'icon' => 'school', 'desc' => 'Accès cours et certificats.'],
            'investissements' => ['label' => 'Investissements', 'icon' => 'account_balance_wallet', 'desc' => 'Portefeuille et ordres.'],
            'securite' => ['label' => 'Sécurité', 'icon' => 'verified_user', 'desc' => 'Protection des données.'],
        ];

        $items = [
            ['cat' => 'investissements', 'q' => 'Comment puis-je retirer mes fonds vers une banque locale ?', 'a' => 'Les retraits se font via votre SGI / SGO agréée. Africaine des Finances facilite la mise en relation ; l’exécution et le règlement sont réalisés par le partenaire régulé AMF-UMOA.'],
            ['cat' => 'securite', 'q' => 'Quelles sont les garanties de sécurité sur mes titres ?', 'a' => 'Vos titres sont déposés chez un dépositaire central / teneur de compte agréé. Nous ne détenons pas vos actifs : la plateforme oriente et accompagne.'],
            ['cat' => 'formations', 'q' => 'Comment accéder aux rapports d’analyses hebdomadaires ?', 'a' => 'Connectez-vous à votre espace client puis ouvrez Actualités. Les abonnés newsletter reçoivent aussi un résumé par e-mail.'],
            ['cat' => 'inscriptions', 'q' => 'Est-il possible d’ouvrir un compte pour un mineur ?', 'a' => 'Un compte mineur nécessite un représentant légal. Contactez le support pour la procédure et la liste des pièces.'],
            ['cat' => 'formations', 'q' => 'Comment obtenir mon certificat de formation ?', 'a' => 'Après validation des modules et du quiz, le certificat est disponible dans Espace client → Certificats.'],
            ['cat' => 'investissements', 'q' => 'Comment investir sur la BRVM via la plateforme ?', 'a' => 'Passez le test profil, choisissez un partenaire agréé, puis demandez une mise en relation. Les ordres sont exécutés par la SGI.'],
            ['cat' => 'inscriptions', 'q' => 'J’ai oublié mon mot de passe, que faire ?', 'a' => 'Utilisez « Mot de passe oublié » sur la page de connexion. Si le mail n’arrive pas, ouvrez un ticket support.'],
            ['cat' => 'securite', 'q' => 'Comment signaler une activité suspecte ?', 'a' => 'Ouvrez un ticket Priorité Haute (catégorie Sécurité) ou écrivez à african.finances@gmail.com.'],
        ];

        $q = mb_strtolower(trim($this->search));
        $filtered = collect($items)->filter(function ($item) use ($q) {
            if ($this->category !== 'all' && $item['cat'] !== $this->category) {
                return false;
            }
            if ($q === '') {
                return true;
            }

            return str_contains(mb_strtolower($item['q']), $q) || str_contains(mb_strtolower($item['a']), $q);
        })->values();

        return view('livewire.pages.faq', compact('categories', 'filtered'))
            ->extends('layouts.site', ['title' => 'Foire aux Questions — Africaine des Finances'])
            ->section('content');
    }
}
