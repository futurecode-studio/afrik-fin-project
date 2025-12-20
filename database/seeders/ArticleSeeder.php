<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\User;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'contact@africainedesfinances.com')->first();
        
        if (!$admin) {
            $this->command->warn('Admin user not found. Please run UserSeeder first.');
            return;
        }

        $articles = [
            [
                'titre' => 'Introduction à la Bourse BRVM',
                'slug' => 'introduction-bourse-brvm',
                'extrait' => 'Découvrez les fondamentaux de la Bourse Régionale des Valeurs Mobilières',
                'contenu' => '<p>La <strong>BRVM</strong> (Bourse Régionale des Valeurs Mobilières) est la bourse commune aux huit pays de l\'Union Économique et Monétaire Ouest Africaine (UEMOA).</p><p>Elle offre de nombreuses opportunités d\'investissement pour les particuliers et les entreprises.</p>',
                'categorie' => 'Bourse',
                'statut' => 'publie',
                'published_at' => now()->subDays(5),
            ],
            [
                'titre' => 'Les 10 règles d\'or de l\'investissement',
                'slug' => '10-regles-or-investissement',
                'extrait' => 'Apprenez les principes fondamentaux pour réussir vos investissements',
                'contenu' => '<p>Voici les <strong>10 règles essentielles</strong> que tout investisseur devrait connaître :</p><ul><li>Diversifiez votre portefeuille</li><li>Investissez sur le long terme</li><li>Faites vos propres recherches</li><li>Ne suivez pas aveuglément la foule</li><li>Contrôlez vos émotions</li></ul>',
                'categorie' => 'Conseil',
                'statut' => 'publie',
                'published_at' => now()->subDays(3),
            ],
            [
                'titre' => 'Comprendre les obligations d\'État',
                'slug' => 'comprendre-obligations-etat',
                'extrait' => 'Un guide complet sur les obligations souveraines et leur fonctionnement',
                'contenu' => '<p>Les <strong>obligations d\'État</strong> sont des titres de créance émis par les gouvernements pour financer leurs activités.</p><p>Elles représentent un investissement généralement considéré comme sûr et stable.</p>',
                'categorie' => 'Finance',
                'statut' => 'publie',
                'published_at' => now()->subDay(),
            ],
            [
                'titre' => 'Nouvelle formation : Analyse Technique Avancée',
                'slug' => 'nouvelle-formation-analyse-technique',
                'extrait' => 'Inscrivez-vous à notre formation sur l\'analyse technique des marchés',
                'contenu' => '<p>Nous sommes heureux d\'annoncer le lancement de notre nouvelle formation sur l\'<strong>analyse technique avancée</strong>.</p><p>Cette formation vous apprendra à lire les graphiques, identifier les tendances et prendre de meilleures décisions d\'investissement.</p>',
                'categorie' => 'Formation',
                'statut' => 'brouillon',
                'published_at' => null,
            ],
            [
                'titre' => 'L\'impact de l\'inflation sur vos investissements',
                'slug' => 'impact-inflation-investissements',
                'extrait' => 'Comment protéger votre patrimoine face à l\'inflation',
                'contenu' => '<p>L\'<strong>inflation</strong> érode le pouvoir d\'achat de votre argent au fil du temps.</p><p>Il est crucial de comprendre comment adapter votre stratégie d\'investissement pour préserver et faire croître votre patrimoine malgré l\'inflation.</p>',
                'categorie' => 'Économie',
                'statut' => 'publie',
                'published_at' => now()->subDays(7),
            ],
        ];

        foreach ($articles as $articleData) {
            Article::create(array_merge($articleData, ['user_id' => $admin->id]));
        }

        $this->command->info('5 articles créés avec succès !');
    }
}
