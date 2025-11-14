<?php

namespace Database\Seeders;

use App\Models\Formation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FormationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        $formations = [
            [
                'titre' => 'Initiation à la Finance Africaine',
                'description_courte' => 'Découvrez les fondamentaux de la finance et des marchés financiers en Afrique.',
                'description_complete' => '<p>Cette formation vous permettra de comprendre les bases de la finance africaine, les institutions financières, les marchés et les opportunités d\'investissement.</p><p>Vous apprendrez les concepts clés et les tendances actuelles du secteur financier africain.</p>',
                'niveau' => 'debutant',
                'duree' => '4 semaines',
                'prix' => 150000,
                'statut' => 'publie',
                'published_at' => now(),
            ],
            [
                'titre' => 'Finance Islamique et Microfinance',
                'description_courte' => 'Maîtrisez les principes de la finance islamique et de la microfinance.',
                'description_complete' => '<p>Apprenez les fondements de la finance islamique conforme à la Charia et découvrez comment la microfinance transforme les communautés africaines.</p><p>Cette formation couvre les produits financiers islamiques, les institutions de microfinance et les modèles d\'inclusion financière.</p>',
                'niveau' => 'intermediaire',
                'duree' => '6 semaines',
                'prix' => 250000,
                'statut' => 'publie',
                'published_at' => now(),
            ],
            [
                'titre' => 'Analyse Financière Avancée',
                'description_courte' => 'Devenez expert en analyse financière et évaluation d\'entreprises.',
                'description_complete' => '<p>Formation avancée en analyse financière couvrant l\'évaluation d\'entreprises, l\'analyse des états financiers, les ratios financiers et les techniques de valorisation.</p><p>Vous apprendrez à analyser la santé financière des entreprises et à prendre des décisions d\'investissement éclairées.</p>',
                'niveau' => 'avance',
                'duree' => '8 semaines',
                'prix' => 350000,
                'statut' => 'publie',
                'published_at' => now(),
            ],
            [
                'titre' => 'Gestion de Portefeuille et Investissement',
                'description_courte' => 'Apprenez à construire et gérer un portefeuille d\'investissement performant.',
                'description_complete' => '<p>Cette formation vous enseigne les stratégies de gestion de portefeuille, la diversification des investissements, l\'allocation d\'actifs et la gestion des risques.</p><p>Vous découvrirez comment optimiser vos rendements tout en maîtrisant les risques.</p>',
                'niveau' => 'intermediaire',
                'duree' => '6 semaines',
                'prix' => 280000,
                'statut' => 'publie',
                'published_at' => now(),
            ],
            [
                'titre' => 'Fintech et Innovation Financière en Afrique',
                'description_courte' => 'Explorez les technologies financières qui transforment l\'Afrique.',
                'description_complete' => '<p>Découvrez les innovations fintech qui révolutionnent le secteur financier africain : mobile money, blockchain, cryptomonnaies, API bancaires et néobanques.</p><p>Cette formation vous prépare aux métiers de demain dans la finance digitale.</p>',
                'niveau' => 'intermediaire',
                'duree' => '5 semaines',
                'prix' => 200000,
                'statut' => 'publie',
                'published_at' => now(),
            ],
            [
                'titre' => 'Marchés Boursiers Africains',
                'description_courte' => 'Maîtrisez le trading et l\'investissement en bourse en Afrique.',
                'description_complete' => '<p>Formation complète sur les bourses africaines : BRVM, NSE, JSE et autres places boursières du continent.</p><p>Apprenez à trader les actions, les obligations et les produits dérivés sur les marchés africains.</p>',
                'niveau' => 'avance',
                'duree' => '10 semaines',
                'prix' => 400000,
                'statut' => 'publie',
                'published_at' => now(),
            ],
            [
                'titre' => 'Finance d\'Entreprise et Levée de Fonds',
                'description_courte' => 'Apprenez à financer et développer votre entreprise.',
                'description_complete' => '<p>Formation pratique sur la finance d\'entreprise : business plan, levée de fonds, capital-risque, business angels et financement participatif.</p><p>Idéal pour les entrepreneurs et les gestionnaires d\'entreprise.</p>',
                'niveau' => 'intermediaire',
                'duree' => '7 semaines',
                'prix' => 300000,
                'statut' => 'publie',
                'published_at' => now(),
            ],
            [
                'titre' => 'Comptabilité et Fiscalité Africaine',
                'description_courte' => 'Maîtrisez les normes comptables et fiscales en Afrique.',
                'description_complete' => '<p>Formation complète sur les systèmes comptables africains (SYSCOHADA), la fiscalité des entreprises et des particuliers.</p><p>Vous apprendrez à tenir une comptabilité conforme et à optimiser la fiscalité.</p>',
                'niveau' => 'debutant',
                'duree' => '6 semaines',
                'prix' => 180000,
                'statut' => 'brouillon',
            ],
        ];

        foreach ($formations as $formation) {
            Formation::create([
                'titre' => $formation['titre'],
                'slug' => Str::slug($formation['titre']),
                'description_courte' => $formation['description_courte'],
                'description_complete' => $formation['description_complete'],
                'niveau' => $formation['niveau'],
                'duree' => $formation['duree'],
                'prix' => $formation['prix'],
                'image_url' => 'https://via.placeholder.com/800x400?text=' . urlencode($formation['titre']),
                'statut' => $formation['statut'],
                'published_at' => $formation['published_at'] ?? null,
                'user_id' => $user->id,
            ]);
        }
    }
}
