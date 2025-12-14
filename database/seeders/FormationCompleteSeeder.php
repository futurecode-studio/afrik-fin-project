<?php

namespace Database\Seeders;

use App\Models\Formation;
use App\Models\FormationModule;
use App\Models\ModuleLesson;
use App\Models\ModuleQuiz;
use App\Models\QuizQuestion;
use App\Models\QuizAnswer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FormationCompleteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer le premier utilisateur
        $admin = User::first();
        if (!$admin) {
            $admin = User::create([
                'name' => 'Admin',
                'email' => 'admin@afrifin.com',
                'password' => bcrypt('password'),
            ]);
        }

        // Créer la formation
        $formation = Formation::create([
            'user_id' => $admin->id,
            'titre' => 'Formation Complète en Finance et Investissement BRVM',
            'slug' => 'formation-complete-finance-investissement-brvm',
            'description_courte' => '<p>Maîtrisez les fondamentaux de la finance et apprenez à investir intelligemment sur la BRVM. Cette formation complète vous guide pas à pas vers l\'autonomie financière.</p>',
            'description_complete' => '<h2>À propos de cette formation</h2>
<p>Cette formation complète vous permettra de comprendre les mécanismes des marchés financiers africains, en particulier la Bourse Régionale des Valeurs Mobilières (BRVM).</p>
<h3>Ce que vous allez apprendre :</h3>
<ul>
<li>Les fondamentaux de la finance personnelle</li>
<li>Comment analyser une action cotée en bourse</li>
<li>Les stratégies d\'investissement adaptées à la BRVM</li>
<li>La gestion de portefeuille et la diversification</li>
<li>L\'analyse technique et fondamentale</li>
</ul>
<h3>Pour qui est cette formation ?</h3>
<p>Cette formation s\'adresse aux débutants comme aux investisseurs intermédiaires souhaitant approfondir leurs connaissances sur les marchés financiers africains.</p>',
            'image_url' => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=800',
            'niveau' => 'debutant',
            'duree' => '8 semaines',
            'prix' => 75000,
            'is_free' => false,
            'statut' => 'publie',
            'published_at' => now(),
        ]);

        // ==========================================
        // MODULE 1 : Introduction à la Finance
        // ==========================================
        $module1 = FormationModule::create([
            'formation_id' => $formation->id,
            'titre' => 'Introduction à la Finance Personnelle',
            'slug' => 'introduction-finance-personnelle',
            'description' => 'Découvrez les bases de la gestion financière personnelle et apprenez à établir un budget efficace.',
            'ordre' => 1,
            'duree_estimee' => '2 heures',
            'is_active' => true,
        ]);

        // Leçons du Module 1
        ModuleLesson::create([
            'formation_module_id' => $module1->id,
            'titre' => 'Qu\'est-ce que la finance personnelle ?',
            'slug' => 'quest-ce-que-la-finance-personnelle',
            'description' => 'Introduction aux concepts fondamentaux de la gestion de vos finances.',
            'contenu' => '<h2>La Finance Personnelle : Définition</h2>
<p>La finance personnelle englobe toutes les décisions et activités financières d\'un individu ou d\'un ménage, incluant :</p>
<ul>
<li><strong>La budgétisation</strong> : Planifier ses revenus et dépenses</li>
<li><strong>L\'épargne</strong> : Mettre de l\'argent de côté pour le futur</li>
<li><strong>L\'investissement</strong> : Faire fructifier son capital</li>
<li><strong>La gestion des dettes</strong> : Contrôler et rembourser ses emprunts</li>
<li><strong>La protection</strong> : Assurances et prévoyance</li>
</ul>
<h3>Pourquoi est-ce important ?</h3>
<p>Une bonne gestion de vos finances personnelles vous permet de :</p>
<ol>
<li>Atteindre vos objectifs de vie (maison, études des enfants, retraite)</li>
<li>Faire face aux imprévus sans stress</li>
<li>Construire un patrimoine durable</li>
<li>Gagner en liberté et en sérénité</li>
</ol>',
            'type' => 'texte',
            'ordre' => 1,
            'duree_estimee' => '15 min',
            'is_active' => true,
        ]);

        ModuleLesson::create([
            'formation_module_id' => $module1->id,
            'titre' => 'Établir son budget mensuel',
            'slug' => 'etablir-son-budget-mensuel',
            'description' => 'Apprenez à créer et suivre un budget adapté à votre situation.',
            'contenu' => '<h2>La Méthode 50/30/20</h2>
<p>Une règle simple pour gérer votre budget :</p>
<ul>
<li><strong>50%</strong> pour les besoins essentiels (loyer, nourriture, transport)</li>
<li><strong>30%</strong> pour les envies (loisirs, sorties, shopping)</li>
<li><strong>20%</strong> pour l\'épargne et le remboursement des dettes</li>
</ul>
<h3>Étapes pour créer votre budget</h3>
<ol>
<li>Listez tous vos revenus mensuels</li>
<li>Identifiez vos dépenses fixes</li>
<li>Estimez vos dépenses variables</li>
<li>Définissez vos objectifs d\'épargne</li>
<li>Suivez vos dépenses chaque semaine</li>
</ol>',
            'type' => 'texte',
            'ordre' => 2,
            'duree_estimee' => '20 min',
            'is_active' => true,
        ]);

        ModuleLesson::create([
            'formation_module_id' => $module1->id,
            'titre' => 'L\'importance de l\'épargne',
            'slug' => 'importance-de-lepargne',
            'description' => 'Comprendre pourquoi et comment épargner efficacement.',
            'contenu' => '<h2>Pourquoi Épargner ?</h2>
<p>L\'épargne est le fondement de toute stratégie financière saine.</p>
<h3>Les différents types d\'épargne</h3>
<ul>
<li><strong>Épargne de précaution</strong> : 3 à 6 mois de dépenses pour les urgences</li>
<li><strong>Épargne projet</strong> : Pour des objectifs à moyen terme</li>
<li><strong>Épargne retraite</strong> : Pour préparer vos vieux jours</li>
<li><strong>Épargne investissement</strong> : Pour faire fructifier votre capital</li>
</ul>',
            'video_url' => 'https://www.youtube.com/watch?v=example1',
            'type' => 'mixte',
            'ordre' => 3,
            'duree_estimee' => '25 min',
            'is_active' => true,
        ]);

        // Quiz du Module 1
        $quiz1 = ModuleQuiz::create([
            'formation_module_id' => $module1->id,
            'titre' => 'Quiz : Fondamentaux de la Finance Personnelle',
            'description' => 'Testez vos connaissances sur les bases de la finance personnelle.',
            'duree_minutes' => 10,
            'score_minimum' => 70,
            'tentatives_max' => 3,
            'is_active' => true,
            'afficher_corrections' => true,
        ]);

        // Questions du Quiz 1
        $q1 = QuizQuestion::create([
            'module_quiz_id' => $quiz1->id,
            'question' => 'Selon la règle 50/30/20, quel pourcentage de vos revenus devrait être consacré à l\'épargne ?',
            'type' => 'choix_unique',
            'explication' => 'La règle 50/30/20 recommande de consacrer 20% de vos revenus à l\'épargne et au remboursement des dettes.',
            'points' => 1,
            'ordre' => 1,
            'is_active' => true,
        ]);
        QuizAnswer::create(['quiz_question_id' => $q1->id, 'reponse' => '10%', 'is_correct' => false, 'ordre' => 1]);
        QuizAnswer::create(['quiz_question_id' => $q1->id, 'reponse' => '20%', 'is_correct' => true, 'ordre' => 2]);
        QuizAnswer::create(['quiz_question_id' => $q1->id, 'reponse' => '30%', 'is_correct' => false, 'ordre' => 3]);
        QuizAnswer::create(['quiz_question_id' => $q1->id, 'reponse' => '50%', 'is_correct' => false, 'ordre' => 4]);

        $q2 = QuizQuestion::create([
            'module_quiz_id' => $quiz1->id,
            'question' => 'Combien de mois de dépenses devrait représenter votre épargne de précaution ?',
            'type' => 'choix_unique',
            'explication' => 'Il est recommandé d\'avoir 3 à 6 mois de dépenses en épargne de précaution pour faire face aux imprévus.',
            'points' => 1,
            'ordre' => 2,
            'is_active' => true,
        ]);
        QuizAnswer::create(['quiz_question_id' => $q2->id, 'reponse' => '1 mois', 'is_correct' => false, 'ordre' => 1]);
        QuizAnswer::create(['quiz_question_id' => $q2->id, 'reponse' => '3 à 6 mois', 'is_correct' => true, 'ordre' => 2]);
        QuizAnswer::create(['quiz_question_id' => $q2->id, 'reponse' => '12 mois', 'is_correct' => false, 'ordre' => 3]);
        QuizAnswer::create(['quiz_question_id' => $q2->id, 'reponse' => '24 mois', 'is_correct' => false, 'ordre' => 4]);

        $q3 = QuizQuestion::create([
            'module_quiz_id' => $quiz1->id,
            'question' => 'La finance personnelle inclut la gestion des dettes.',
            'type' => 'vrai_faux',
            'explication' => 'Vrai ! La gestion des dettes fait partie intégrante de la finance personnelle.',
            'points' => 1,
            'ordre' => 3,
            'is_active' => true,
        ]);
        QuizAnswer::create(['quiz_question_id' => $q3->id, 'reponse' => 'Vrai', 'is_correct' => true, 'ordre' => 1]);
        QuizAnswer::create(['quiz_question_id' => $q3->id, 'reponse' => 'Faux', 'is_correct' => false, 'ordre' => 2]);

        // ==========================================
        // MODULE 2 : Introduction à la Bourse
        // ==========================================
        $module2 = FormationModule::create([
            'formation_id' => $formation->id,
            'titre' => 'Comprendre la Bourse et les Marchés Financiers',
            'slug' => 'comprendre-bourse-marches-financiers',
            'description' => 'Découvrez le fonctionnement des marchés boursiers et les différents instruments financiers.',
            'ordre' => 2,
            'duree_estimee' => '3 heures',
            'is_active' => true,
        ]);

        // Leçons du Module 2
        ModuleLesson::create([
            'formation_module_id' => $module2->id,
            'titre' => 'Qu\'est-ce qu\'une bourse ?',
            'slug' => 'quest-ce-quune-bourse',
            'description' => 'Comprendre le rôle et le fonctionnement d\'une place boursière.',
            'contenu' => '<h2>La Bourse : Un Marché Organisé</h2>
<p>Une bourse est un marché réglementé où s\'échangent des titres financiers (actions, obligations, etc.).</p>
<h3>Les acteurs de la bourse</h3>
<ul>
<li><strong>Les émetteurs</strong> : Entreprises qui lèvent des capitaux</li>
<li><strong>Les investisseurs</strong> : Particuliers et institutionnels qui achètent des titres</li>
<li><strong>Les intermédiaires</strong> : Courtiers et banques qui facilitent les transactions</li>
<li><strong>Le régulateur</strong> : Autorité qui supervise le marché</li>
</ul>
<h3>Pourquoi investir en bourse ?</h3>
<ol>
<li>Potentiel de rendement supérieur à l\'épargne classique</li>
<li>Participation à l\'économie réelle</li>
<li>Diversification du patrimoine</li>
<li>Revenus passifs (dividendes)</li>
</ol>',
            'type' => 'texte',
            'ordre' => 1,
            'duree_estimee' => '20 min',
            'is_active' => true,
        ]);

        ModuleLesson::create([
            'formation_module_id' => $module2->id,
            'titre' => 'La BRVM : Bourse Régionale des Valeurs Mobilières',
            'slug' => 'brvm-bourse-regionale-valeurs-mobilieres',
            'description' => 'Tout savoir sur la bourse commune aux 8 pays de l\'UEMOA.',
            'contenu' => '<h2>Présentation de la BRVM</h2>
<p>La BRVM est la bourse commune aux 8 pays de l\'Union Économique et Monétaire Ouest Africaine (UEMOA) :</p>
<ul>
<li>Bénin</li>
<li>Burkina Faso</li>
<li>Côte d\'Ivoire</li>
<li>Guinée-Bissau</li>
<li>Mali</li>
<li>Niger</li>
<li>Sénégal</li>
<li>Togo</li>
</ul>
<h3>Caractéristiques de la BRVM</h3>
<ul>
<li><strong>Siège</strong> : Abidjan, Côte d\'Ivoire</li>
<li><strong>Création</strong> : 1998</li>
<li><strong>Monnaie</strong> : Franc CFA (XOF)</li>
<li><strong>Indices</strong> : BRVM Composite, BRVM 10</li>
<li><strong>Sociétés cotées</strong> : Plus de 45 entreprises</li>
</ul>',
            'type' => 'texte',
            'ordre' => 2,
            'duree_estimee' => '25 min',
            'is_active' => true,
        ]);

        ModuleLesson::create([
            'formation_module_id' => $module2->id,
            'titre' => 'Les différents types d\'actions',
            'slug' => 'differents-types-actions',
            'description' => 'Comprendre les catégories d\'actions et leurs caractéristiques.',
            'contenu' => '<h2>Classification des Actions</h2>
<h3>Par secteur d\'activité</h3>
<ul>
<li><strong>Banques et Finances</strong> : SGBCI, BOA, Ecobank...</li>
<li><strong>Industrie</strong> : Solibra, Nestlé CI, Unilever...</li>
<li><strong>Distribution</strong> : CFAO, Total CI...</li>
<li><strong>Agriculture</strong> : PALM CI, SOGB...</li>
<li><strong>Services publics</strong> : CIE, SODECI, Sonatel...</li>
</ul>
<h3>Par capitalisation</h3>
<ul>
<li><strong>Large caps</strong> : Grandes entreprises (Sonatel, Orange CI)</li>
<li><strong>Mid caps</strong> : Entreprises de taille moyenne</li>
<li><strong>Small caps</strong> : Petites entreprises</li>
</ul>',
            'type' => 'texte',
            'ordre' => 3,
            'duree_estimee' => '20 min',
            'is_active' => true,
        ]);

        ModuleLesson::create([
            'formation_module_id' => $module2->id,
            'titre' => 'Comment passer un ordre en bourse',
            'slug' => 'comment-passer-ordre-bourse',
            'description' => 'Guide pratique pour acheter et vendre des actions.',
            'video_url' => 'https://www.youtube.com/watch?v=example2',
            'type' => 'video',
            'ordre' => 4,
            'duree_estimee' => '30 min',
            'is_active' => true,
        ]);

        // Quiz du Module 2
        $quiz2 = ModuleQuiz::create([
            'formation_module_id' => $module2->id,
            'titre' => 'Quiz : La Bourse et la BRVM',
            'description' => 'Vérifiez vos connaissances sur les marchés boursiers et la BRVM.',
            'duree_minutes' => 15,
            'score_minimum' => 70,
            'tentatives_max' => 3,
            'is_active' => true,
            'afficher_corrections' => true,
        ]);

        // Questions du Quiz 2
        $q4 = QuizQuestion::create([
            'module_quiz_id' => $quiz2->id,
            'question' => 'Combien de pays font partie de la zone BRVM ?',
            'type' => 'choix_unique',
            'explication' => 'La BRVM regroupe les 8 pays de l\'UEMOA.',
            'points' => 1,
            'ordre' => 1,
            'is_active' => true,
        ]);
        QuizAnswer::create(['quiz_question_id' => $q4->id, 'reponse' => '5 pays', 'is_correct' => false, 'ordre' => 1]);
        QuizAnswer::create(['quiz_question_id' => $q4->id, 'reponse' => '8 pays', 'is_correct' => true, 'ordre' => 2]);
        QuizAnswer::create(['quiz_question_id' => $q4->id, 'reponse' => '10 pays', 'is_correct' => false, 'ordre' => 3]);
        QuizAnswer::create(['quiz_question_id' => $q4->id, 'reponse' => '15 pays', 'is_correct' => false, 'ordre' => 4]);

        $q5 = QuizQuestion::create([
            'module_quiz_id' => $quiz2->id,
            'question' => 'Où se trouve le siège de la BRVM ?',
            'type' => 'choix_unique',
            'explication' => 'Le siège de la BRVM est situé à Abidjan, en Côte d\'Ivoire.',
            'points' => 1,
            'ordre' => 2,
            'is_active' => true,
        ]);
        QuizAnswer::create(['quiz_question_id' => $q5->id, 'reponse' => 'Dakar', 'is_correct' => false, 'ordre' => 1]);
        QuizAnswer::create(['quiz_question_id' => $q5->id, 'reponse' => 'Abidjan', 'is_correct' => true, 'ordre' => 2]);
        QuizAnswer::create(['quiz_question_id' => $q5->id, 'reponse' => 'Lomé', 'is_correct' => false, 'ordre' => 3]);
        QuizAnswer::create(['quiz_question_id' => $q5->id, 'reponse' => 'Cotonou', 'is_correct' => false, 'ordre' => 4]);

        $q6 = QuizQuestion::create([
            'module_quiz_id' => $quiz2->id,
            'question' => 'Quels sont les principaux indices de la BRVM ?',
            'type' => 'choix_multiple',
            'explication' => 'Les deux principaux indices de la BRVM sont le BRVM Composite et le BRVM 10.',
            'points' => 2,
            'ordre' => 3,
            'is_active' => true,
        ]);
        QuizAnswer::create(['quiz_question_id' => $q6->id, 'reponse' => 'BRVM Composite', 'is_correct' => true, 'ordre' => 1]);
        QuizAnswer::create(['quiz_question_id' => $q6->id, 'reponse' => 'BRVM 10', 'is_correct' => true, 'ordre' => 2]);
        QuizAnswer::create(['quiz_question_id' => $q6->id, 'reponse' => 'CAC 40', 'is_correct' => false, 'ordre' => 3]);
        QuizAnswer::create(['quiz_question_id' => $q6->id, 'reponse' => 'S&P 500', 'is_correct' => false, 'ordre' => 4]);

        $q7 = QuizQuestion::create([
            'module_quiz_id' => $quiz2->id,
            'question' => 'La BRVM a été créée en 1998.',
            'type' => 'vrai_faux',
            'explication' => 'Vrai ! La BRVM a été créée en 1998.',
            'points' => 1,
            'ordre' => 4,
            'is_active' => true,
        ]);
        QuizAnswer::create(['quiz_question_id' => $q7->id, 'reponse' => 'Vrai', 'is_correct' => true, 'ordre' => 1]);
        QuizAnswer::create(['quiz_question_id' => $q7->id, 'reponse' => 'Faux', 'is_correct' => false, 'ordre' => 2]);

        // ==========================================
        // MODULE 3 : Analyse Fondamentale
        // ==========================================
        $module3 = FormationModule::create([
            'formation_id' => $formation->id,
            'titre' => 'L\'Analyse Fondamentale',
            'slug' => 'analyse-fondamentale',
            'description' => 'Apprenez à évaluer la valeur intrinsèque d\'une entreprise à travers ses fondamentaux.',
            'ordre' => 3,
            'duree_estimee' => '4 heures',
            'is_active' => true,
        ]);

        // Leçons du Module 3
        ModuleLesson::create([
            'formation_module_id' => $module3->id,
            'titre' => 'Introduction à l\'analyse fondamentale',
            'slug' => 'introduction-analyse-fondamentale',
            'description' => 'Comprendre les principes de base de l\'analyse fondamentale.',
            'contenu' => '<h2>Qu\'est-ce que l\'Analyse Fondamentale ?</h2>
<p>L\'analyse fondamentale est une méthode d\'évaluation qui vise à déterminer la valeur intrinsèque d\'une action en analysant :</p>
<ul>
<li>Les états financiers de l\'entreprise</li>
<li>Son secteur d\'activité</li>
<li>L\'environnement économique</li>
<li>La qualité de la direction</li>
</ul>
<h3>Objectif</h3>
<p>Identifier les actions sous-évaluées (à acheter) ou surévaluées (à vendre).</p>',
            'type' => 'texte',
            'ordre' => 1,
            'duree_estimee' => '20 min',
            'is_active' => true,
        ]);

        ModuleLesson::create([
            'formation_module_id' => $module3->id,
            'titre' => 'Lire un bilan comptable',
            'slug' => 'lire-bilan-comptable',
            'description' => 'Décrypter les informations clés d\'un bilan.',
            'contenu' => '<h2>Le Bilan : Photo de l\'Entreprise</h2>
<p>Le bilan présente la situation patrimoniale de l\'entreprise à un instant T.</p>
<h3>L\'Actif (ce que possède l\'entreprise)</h3>
<ul>
<li><strong>Actif immobilisé</strong> : Terrains, bâtiments, équipements</li>
<li><strong>Actif circulant</strong> : Stocks, créances clients, trésorerie</li>
</ul>
<h3>Le Passif (ce que doit l\'entreprise)</h3>
<ul>
<li><strong>Capitaux propres</strong> : Capital social, réserves, résultat</li>
<li><strong>Dettes</strong> : Emprunts, dettes fournisseurs</li>
</ul>',
            'type' => 'texte',
            'ordre' => 2,
            'duree_estimee' => '30 min',
            'is_active' => true,
        ]);

        ModuleLesson::create([
            'formation_module_id' => $module3->id,
            'titre' => 'Les ratios financiers essentiels',
            'slug' => 'ratios-financiers-essentiels',
            'description' => 'Maîtriser les ratios clés pour évaluer une entreprise.',
            'contenu' => '<h2>Les Ratios Incontournables</h2>
<h3>Ratios de valorisation</h3>
<ul>
<li><strong>PER (Price Earning Ratio)</strong> = Cours / BPA</li>
<li><strong>P/B (Price to Book)</strong> = Cours / Valeur comptable par action</li>
<li><strong>Rendement du dividende</strong> = Dividende / Cours</li>
</ul>
<h3>Ratios de rentabilité</h3>
<ul>
<li><strong>ROE (Return on Equity)</strong> = Résultat net / Capitaux propres</li>
<li><strong>ROA (Return on Assets)</strong> = Résultat net / Total actif</li>
<li><strong>Marge nette</strong> = Résultat net / Chiffre d\'affaires</li>
</ul>
<h3>Ratios de solvabilité</h3>
<ul>
<li><strong>Ratio d\'endettement</strong> = Dettes / Capitaux propres</li>
<li><strong>Ratio de liquidité</strong> = Actif circulant / Passif circulant</li>
</ul>',
            'type' => 'texte',
            'ordre' => 3,
            'duree_estimee' => '45 min',
            'is_active' => true,
        ]);

        // Quiz du Module 3
        $quiz3 = ModuleQuiz::create([
            'formation_module_id' => $module3->id,
            'titre' => 'Quiz : Analyse Fondamentale',
            'description' => 'Testez vos connaissances sur l\'analyse fondamentale.',
            'duree_minutes' => 15,
            'score_minimum' => 70,
            'tentatives_max' => 3,
            'is_active' => true,
            'afficher_corrections' => true,
        ]);

        $q8 = QuizQuestion::create([
            'module_quiz_id' => $quiz3->id,
            'question' => 'Que signifie PER ?',
            'type' => 'choix_unique',
            'explication' => 'PER signifie Price Earning Ratio, soit le rapport entre le cours de l\'action et le bénéfice par action.',
            'points' => 1,
            'ordre' => 1,
            'is_active' => true,
        ]);
        QuizAnswer::create(['quiz_question_id' => $q8->id, 'reponse' => 'Price Earning Ratio', 'is_correct' => true, 'ordre' => 1]);
        QuizAnswer::create(['quiz_question_id' => $q8->id, 'reponse' => 'Profit Evaluation Rate', 'is_correct' => false, 'ordre' => 2]);
        QuizAnswer::create(['quiz_question_id' => $q8->id, 'reponse' => 'Performance Economic Return', 'is_correct' => false, 'ordre' => 3]);

        $q9 = QuizQuestion::create([
            'module_quiz_id' => $quiz3->id,
            'question' => 'Un PER élevé indique généralement que l\'action est sous-évaluée.',
            'type' => 'vrai_faux',
            'explication' => 'Faux ! Un PER élevé indique généralement que l\'action est surévaluée ou que les investisseurs anticipent une forte croissance.',
            'points' => 1,
            'ordre' => 2,
            'is_active' => true,
        ]);
        QuizAnswer::create(['quiz_question_id' => $q9->id, 'reponse' => 'Vrai', 'is_correct' => false, 'ordre' => 1]);
        QuizAnswer::create(['quiz_question_id' => $q9->id, 'reponse' => 'Faux', 'is_correct' => true, 'ordre' => 2]);

        $this->command->info('Formation complète créée avec succès !');
        $this->command->info("- Formation : {$formation->titre}");
        $this->command->info("- 3 Modules créés");
        $this->command->info("- 10 Leçons créées");
        $this->command->info("- 3 Quiz avec 9 questions au total");
    }
}
