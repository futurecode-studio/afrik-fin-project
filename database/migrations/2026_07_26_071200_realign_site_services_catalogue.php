<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('site_services')) {
            return;
        }

        $now = now();

        $updates = [
            'formations-e-learning' => [
                'title' => 'Formations E-Learning',
                'price_label' => 'À partir de 29 000 FCFA',
                'cta_label' => 'Voir les formations',
                'cta_url' => '/formations',
                'excerpt' => 'Accédez à notre catalogue de formations certifiées conçues par des experts du secteur financier africain.',
                'features' => json_encode([
                    'Plus de 150 cours disponibles',
                    'Certificats reconnus',
                    'Accès illimité à vie',
                    'Support pédagogique 24/7',
                    'Mises à jour régulières',
                ], JSON_UNESCAPED_UNICODE),
            ],
            'donnees-boursieres-brvm' => [
                'title' => 'Données Boursières BRVM',
                'price_label' => 'À partir de 15 000 FCFA/mois',
                'cta_label' => 'Voir les marchés',
                'cta_url' => '/marches',
                'excerpt' => 'Suivez en temps réel l’évolution des marchés avec nos outils d’analyse professionnels.',
                'features' => json_encode([
                    'Données en temps réel',
                    'Historiques complets',
                    'Alertes personnalisées',
                    'Graphiques interactifs',
                    'Rapports d’analyse',
                ], JSON_UNESCAPED_UNICODE),
            ],
            'conseil-financier' => [
                'title' => 'Conseil en Investissement',
                'price_label' => 'Sur devis',
                'cta_label' => 'Prendre contact',
                'cta_url' => '/contact',
                'excerpt' => 'Bénéficiez de l’expertise de nos conseillers pour développer votre stratégie d’investissement.',
                'features' => json_encode([
                    'Consultation personnalisée',
                    'Analyse de portefeuille',
                    'Recommandations d’experts',
                    'Suivi régulier',
                    'Stratégies sur mesure',
                ], JSON_UNESCAPED_UNICODE),
            ],
            'analyses-de-marche' => [
                'title' => 'Analyses de Marché',
                'price_label' => 'À partir de 25 000 FCFA/mois',
                'cta_label' => 'Lire les analyses',
                'cta_url' => '/actualites',
                'excerpt' => 'Recevez des analyses approfondies des tendances et opportunités des marchés africains.',
                'features' => json_encode([
                    'Rapports hebdomadaires',
                    'Analyses sectorielles',
                    'Prévisions économiques',
                    'Études de marché',
                    'Recommandations d’achat/vente',
                ], JSON_UNESCAPED_UNICODE),
            ],
            'evenements-webinaires' => [
                'title' => 'Événements & Webinaires',
                'price_label' => 'Gratuit pour membres',
                'cta_label' => 'Voir le calendrier',
                'cta_url' => '/evenements',
                'excerpt' => 'Participez à nos conférences, séminaires et webinaires animés par des experts.',
                'features' => json_encode([
                    'Webinaires mensuels',
                    'Conférences annuelles',
                    'Ateliers pratiques',
                    'Networking professionnel',
                    'Replays disponibles',
                ], JSON_UNESCAPED_UNICODE),
            ],
        ];

        foreach ($updates as $slug => $data) {
            DB::table('site_services')->where('slug', $slug)->update(array_merge($data, [
                'updated_at' => $now,
            ]));
        }

        // Alias éventuel si le slug a été renommé côté admin
        DB::table('site_services')
            ->where('title', 'like', 'Conseil%')
            ->where('slug', '!=', 'conseil-financier')
            ->limit(1)
            ->update(array_merge($updates['conseil-financier'], ['updated_at' => $now]));

        $pubExists = DB::table('site_services')->where('slug', 'publications-recherches')->exists();
        if (! $pubExists) {
            $maxOrder = (int) DB::table('site_services')->max('order');
            DB::table('site_services')->insert([
                'title' => 'Publications & Recherches',
                'slug' => 'publications-recherches',
                'icon' => 'menu_book',
                'subtitle' => null,
                'excerpt' => 'Accédez à nos études, rapports et publications sur les marchés financiers africains.',
                'content' => null,
                'features' => json_encode([
                    'Études sectorielles',
                    'Rapports annuels',
                    'Livres blancs',
                    'Guides pratiques',
                    'Veille réglementaire',
                ], JSON_UNESCAPED_UNICODE),
                'price_label' => 'Gratuit avec abonnement',
                'duration_label' => null,
                'image_url' => null,
                'cta_label' => 'Voir la bibliothèque',
                'cta_url' => '/marches/bibliotheque',
                'is_active' => true,
                'is_featured' => false,
                'order' => $maxOrder + 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // Placer Publications avant Événements (ordre catalogue ancien)
        $orders = [
            'formations-e-learning' => 1,
            'donnees-boursieres-brvm' => 2,
            'conseil-financier' => 3,
            'analyses-de-marche' => 4,
            'publications-recherches' => 5,
            'evenements-webinaires' => 6,
        ];
        foreach ($orders as $slug => $order) {
            DB::table('site_services')->where('slug', $slug)->update(['order' => $order, 'updated_at' => $now]);
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('site_services')) {
            return;
        }

        DB::table('site_services')->where('slug', 'publications-recherches')->delete();
    }
};
