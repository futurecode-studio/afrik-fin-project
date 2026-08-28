<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\User;

class ArticleCompleteSeeder extends Seeder
{
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

        $articles = [
            // Article 1 : Présentation de la BRVM
            [
                'titre' => 'Comprendre la BRVM : La Bourse Régionale des Valeurs Mobilières',
                'slug' => 'comprendre-brvm-bourse-regionale-valeurs-mobilieres',
                'extrait' => 'Découvrez le fonctionnement de la BRVM, la bourse commune aux 8 pays de l\'UEMOA, ses indices et son rôle dans l\'économie ouest-africaine.',
                'contenu' => '<p class="text-lg mb-6">La Bourse Régionale des Valeurs Mobilières (BRVM) est une institution financière unique en son genre : c\'est la seule bourse au monde à être partagée par plusieurs pays souverains.</p>

<h2 class="text-2xl font-bold mt-8 mb-4">Qu\'est-ce que la BRVM ?</h2>
<p class="mb-4">Créée le <strong>18 décembre 1996</strong> et opérationnelle depuis le <strong>16 septembre 1998</strong>, la BRVM est la bourse commune aux huit pays membres de l\'Union Économique et Monétaire Ouest Africaine (UEMOA) :</p>
<ul class="list-disc pl-6 mb-4">
<li>Bénin</li>
<li>Burkina Faso</li>
<li>Côte d\'Ivoire</li>
<li>Guinée-Bissau</li>
<li>Mali</li>
<li>Niger</li>
<li>Sénégal</li>
<li>Togo</li>
</ul>

<p class="mb-4">Son siège social est situé à <strong>Abidjan, en Côte d\'Ivoire</strong>. La BRVM est régulée par l\'Autorité des Marchés Financiers de l\'UMOA (AMF-UMOA).</p>

<h2 class="text-2xl font-bold mt-8 mb-4">Les indices de la BRVM</h2>
<p class="mb-4">La BRVM dispose de deux indices principaux :</p>
<ul class="list-disc pl-6 mb-4">
<li><strong>BRVM Composite</strong> : Indice global regroupant toutes les valeurs cotées</li>
<li><strong>BRVM 10</strong> : Indice des 10 valeurs les plus actives du marché</li>
</ul>

<h2 class="text-2xl font-bold mt-8 mb-4">Les sociétés cotées</h2>
<p class="mb-4">La BRVM compte actuellement <strong>46 sociétés cotées</strong> réparties dans différents secteurs :</p>
<ul class="list-disc pl-6 mb-4">
<li><strong>Finance</strong> : Banques (SGBCI, BOA, Ecobank, Coris Bank...) et assurances</li>
<li><strong>Industrie</strong> : Nestlé CI, Solibra, Filtisac...</li>
<li><strong>Services publics</strong> : Sonatel, Orange CI, CIE, SODECI...</li>
<li><strong>Distribution</strong> : Total Énergies CI, Vivo Energy...</li>
<li><strong>Agriculture</strong> : PALM CI, SOGB, SAPH...</li>
<li><strong>Transport</strong> : Bolloré Transport & Logistics...</li>
</ul>

<h2 class="text-2xl font-bold mt-8 mb-4">Comment investir sur la BRVM ?</h2>
<p class="mb-4">Pour investir sur la BRVM, vous devez :</p>
<ol class="list-decimal pl-6 mb-4">
<li>Ouvrir un compte-titres auprès d\'une <strong>Société de Gestion et d\'Intermédiation (SGI)</strong> agréée</li>
<li>Effectuer un dépôt initial</li>
<li>Passer vos ordres d\'achat ou de vente via votre SGI</li>
</ol>

<p class="mb-4">Les SGI sont présentes dans chacun des 8 pays de l\'UEMOA. Parmi les plus connues : CGF Bourse, Hudson & Cie, Impaxis Securities, BOA Capital...</p>

<h2 class="text-2xl font-bold mt-8 mb-4">Horaires de cotation</h2>
<p class="mb-4">La BRVM fonctionne du lundi au vendredi avec les horaires suivants (heure GMT) :</p>
<ul class="list-disc pl-6 mb-4">
<li><strong>Pré-ouverture</strong> : 9h00 - 10h00</li>
<li><strong>Séance de cotation</strong> : 10h00 - 15h00</li>
</ul>

<h2 class="text-2xl font-bold mt-8 mb-4">Avantages de la BRVM</h2>
<ul class="list-disc pl-6 mb-4">
<li>Monnaie unique (Franc CFA) éliminant le risque de change intra-zone</li>
<li>Cadre réglementaire harmonisé</li>
<li>Accès à 8 économies via une seule plateforme</li>
<li>Fiscalité avantageuse sur les plus-values dans plusieurs pays</li>
</ul>

<p class="mb-4"><em>Sources : BRVM (brvm.org), AMF-UMOA, Banque Centrale des États de l\'Afrique de l\'Ouest (BCEAO)</em></p>',
                'image_url' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=1200&h=600&fit=crop',
                'categorie' => 'Éducation',
                'statut' => 'publie',
                'published_at' => '2025-01-15 09:00:00',
            ],

            // Article 2 : Les principales sociétés cotées à la BRVM
            [
                'titre' => 'Les principales sociétés cotées à la BRVM en 2024',
                'slug' => 'principales-societes-cotees-brvm-2024',
                'extrait' => 'Présentation des plus grandes capitalisations boursières de la BRVM et de leurs activités dans la zone UEMOA.',
                'contenu' => '<p class="text-lg mb-6">La BRVM compte 46 sociétés cotées représentant une capitalisation boursière totale d\'environ 8 000 milliards de FCFA. Découvrez les principales valeurs du marché.</p>

<h2 class="text-2xl font-bold mt-8 mb-4">Secteur des Télécommunications</h2>

<h3 class="text-xl font-semibold mt-6 mb-3">Sonatel (SNTS)</h3>
<p class="mb-4">Sonatel est la plus grande capitalisation de la BRVM. Filiale du groupe Orange, elle opère au Sénégal, Mali, Guinée, Guinée-Bissau et Sierra Leone sous la marque Orange. L\'entreprise emploie plus de 5 000 personnes et dessert plus de 40 millions de clients.</p>

<h3 class="text-xl font-semibold mt-6 mb-3">Orange Côte d\'Ivoire (ORAC)</h3>
<p class="mb-4">Leader des télécommunications en Côte d\'Ivoire avec plus de 20 millions d\'abonnés. L\'entreprise propose des services de téléphonie mobile, internet fixe et mobile, et Orange Money.</p>

<h2 class="text-2xl font-bold mt-8 mb-4">Secteur Bancaire</h2>

<h3 class="text-xl font-semibold mt-6 mb-3">Ecobank Transnational Incorporated (ETIT)</h3>
<p class="mb-4">Ecobank est la banque panafricaine par excellence, présente dans 33 pays africains. Son siège est à Lomé (Togo). C\'est l\'une des plus grandes banques du continent avec des actifs dépassant 25 milliards de dollars.</p>

<h3 class="text-xl font-semibold mt-6 mb-3">Société Générale Côte d\'Ivoire (SGBC)</h3>
<p class="mb-4">Filiale du groupe Société Générale, SGBCI est l\'une des principales banques de Côte d\'Ivoire avec un réseau de plus de 70 agences. Elle propose des services aux particuliers, professionnels et entreprises.</p>

<h3 class="text-xl font-semibold mt-6 mb-3">Bank of Africa (BOA)</h3>
<p class="mb-4">Le groupe BOA, filiale de BMCE Bank of Africa (Maroc), est présent dans plusieurs pays de l\'UEMOA : Bénin, Burkina Faso, Côte d\'Ivoire, Mali, Niger et Sénégal.</p>

<h3 class="text-xl font-semibold mt-6 mb-3">Coris Bank International (CBIBF)</h3>
<p class="mb-4">Banque d\'origine burkinabè, Coris Bank s\'est développée dans plusieurs pays de la sous-région. Elle est reconnue pour son dynamisme et sa croissance rapide.</p>

<h2 class="text-2xl font-bold mt-8 mb-4">Secteur Industriel</h2>

<h3 class="text-xl font-semibold mt-6 mb-3">Nestlé Côte d\'Ivoire (NTLC)</h3>
<p class="mb-4">Filiale du groupe suisse Nestlé, l\'entreprise produit et commercialise des produits alimentaires (Nescafé, Maggi, Nido) en Afrique de l\'Ouest.</p>

<h3 class="text-xl font-semibold mt-6 mb-3">Solibra (SLBC)</h3>
<p class="mb-4">Société de Limonaderies et Brasseries d\'Afrique, filiale du groupe Castel. Solibra produit et distribue des bières (Flag, Castel) et boissons gazeuses en Côte d\'Ivoire.</p>

<h2 class="text-2xl font-bold mt-8 mb-4">Secteur Distribution</h2>

<h3 class="text-xl font-semibold mt-6 mb-3">TotalEnergies Marketing Côte d\'Ivoire (TTLC)</h3>
<p class="mb-4">Filiale de TotalEnergies, elle exploite un réseau de stations-service et distribue des carburants, lubrifiants et gaz en Côte d\'Ivoire.</p>

<h3 class="text-xl font-semibold mt-6 mb-3">Vivo Energy Côte d\'Ivoire (SHEC)</h3>
<p class="mb-4">Distributeur de produits pétroliers sous la marque Shell. Vivo Energy est présent dans 23 pays africains.</p>

<h2 class="text-2xl font-bold mt-8 mb-4">Secteur Services Publics</h2>

<h3 class="text-xl font-semibold mt-6 mb-3">CIE - Compagnie Ivoirienne d\'Électricité (CIEC)</h3>
<p class="mb-4">Concessionnaire de la distribution d\'électricité en Côte d\'Ivoire, la CIE dessert plus de 2 millions de clients.</p>

<h3 class="text-xl font-semibold mt-6 mb-3">SODECI (SDCC)</h3>
<p class="mb-4">Société de Distribution d\'Eau de la Côte d\'Ivoire, elle assure la production et la distribution d\'eau potable dans le pays.</p>

<p class="mb-4"><em>Sources : BRVM (brvm.org), rapports annuels des sociétés cotées</em></p>',
                'image_url' => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=1200&h=600&fit=crop',
                'categorie' => 'Bourse',
                'statut' => 'publie',
                'published_at' => '2025-01-12 10:30:00',
            ],

            // Article 3 : Comment ouvrir un compte-titres à la BRVM
            [
                'titre' => 'Comment ouvrir un compte-titres à la BRVM : Guide pratique',
                'slug' => 'comment-ouvrir-compte-titres-brvm-guide-pratique',
                'extrait' => 'Les étapes concrètes pour ouvrir un compte-titres et commencer à investir sur la Bourse Régionale des Valeurs Mobilières.',
                'contenu' => '<p class="text-lg mb-6">Pour investir sur la BRVM, vous devez obligatoirement passer par une Société de Gestion et d\'Intermédiation (SGI) agréée par l\'AMF-UMOA. Voici le guide complet.</p>

<h2 class="text-2xl font-bold mt-8 mb-4">Qu\'est-ce qu\'une SGI ?</h2>
<p class="mb-4">Les Sociétés de Gestion et d\'Intermédiation (SGI) sont les seuls intermédiaires habilités à exécuter des ordres de bourse sur la BRVM. Elles sont agréées et supervisées par l\'<strong>Autorité des Marchés Financiers de l\'UMOA (AMF-UMOA)</strong>.</p>

<p class="mb-4">Il existe actuellement <strong>25 SGI agréées</strong> réparties dans les 8 pays de l\'UEMOA.</p>

<h2 class="text-2xl font-bold mt-8 mb-4">Liste des principales SGI par pays</h2>

<h3 class="text-xl font-semibold mt-6 mb-3">Bénin</h3>
<ul class="list-disc pl-6 mb-4">
<li>UCA</li>
<li>Africabourse</li>
<li>SGI Bénin</li>
<li>BFS</li>
<li>AGI</li>
<li>BOA</li>
</ul>

<h3 class="text-xl font-semibold mt-6 mb-3">Autres pays</h3>
<p class="mb-4">Des SGI sont également présentes en Côte d\'Ivoire (CGF Bourse, Hudson &amp; Cie, NSIA Finance, Atlantique Finance, BNI Finances), au Sénégal (CGF Bourse Sénégal, Impaxis Securities), au Burkina Faso (Coris Bourse, Fidelis Finance), au Mali, au Niger et au Togo.</p>

<h2 class="text-2xl font-bold mt-8 mb-4">Documents requis pour l\'ouverture de compte</h2>
<p class="mb-4">Pour les personnes physiques :</p>
<ul class="list-disc pl-6 mb-4">
<li>Pièce d\'identité valide (CNI, passeport)</li>
<li>Justificatif de domicile de moins de 3 mois</li>
<li>Formulaire d\'ouverture de compte rempli et signé</li>
<li>Spécimen de signature</li>
<li>Photo d\'identité</li>
</ul>

<h2 class="text-2xl font-bold mt-8 mb-4">Les frais à prévoir</h2>
<p class="mb-4">Les frais varient selon les SGI mais comprennent généralement :</p>
<ul class="list-disc pl-6 mb-4">
<li><strong>Frais d\'ouverture de compte</strong> : Gratuit chez la plupart des SGI ; 11 000 FCFA uniquement chez Africabourse</li>
<li><strong>Commission de courtage</strong> : Entre 0,5% et 1,5% du montant de la transaction</li>
<li><strong>Droits de garde</strong> : Frais annuels de conservation des titres (0,2% à 0,5% de la valeur du portefeuille)</li>
<li><strong>Frais de la BRVM</strong> : 0,1% sur chaque transaction</li>
</ul>

<h2 class="text-2xl font-bold mt-8 mb-4">Montant minimum pour investir</h2>
<p class="mb-4">Il n\'y a pas de montant minimum légal pour investir sur la BRVM. Cependant :</p>
<ul class="list-disc pl-6 mb-4">
<li>Certaines SGI exigent un dépôt initial minimum (souvent 100 000 à 500 000 FCFA)</li>
<li>Le prix d\'une action varie de quelques centaines à plusieurs milliers de FCFA</li>
<li>Il est recommandé de commencer avec au moins 200 000 FCFA pour pouvoir diversifier</li>
</ul>

<h2 class="text-2xl font-bold mt-8 mb-4">Processus d\'ouverture de compte</h2>
<ol class="list-decimal pl-6 mb-4">
<li>Choisir une SGI (comparer les frais et services)</li>
<li>Prendre rendez-vous ou se rendre en agence</li>
<li>Remplir le formulaire d\'ouverture de compte</li>
<li>Fournir les documents requis</li>
<li>Effectuer le dépôt initial</li>
<li>Recevoir vos identifiants de connexion (si plateforme en ligne)</li>
<li>Commencer à passer vos ordres</li>
</ol>

<p class="mb-4"><em>Sources : AMF-UMOA (amf-umoa.org), BRVM (brvm.org)</em></p>',
                'image_url' => 'https://images.unsplash.com/photo-1434626881859-194d67b2b86f?w=1200&h=600&fit=crop',
                'categorie' => 'Formation',
                'statut' => 'publie',
                'published_at' => '2025-01-10 08:00:00',
            ],

            // Article 4 : L'UEMOA et le Franc CFA
            [
                'titre' => 'L\'UEMOA et le Franc CFA : Comprendre l\'environnement monétaire',
                'slug' => 'uemoa-franc-cfa-environnement-monetaire',
                'extrait' => 'Présentation de l\'Union Économique et Monétaire Ouest Africaine et du fonctionnement du Franc CFA.',
                'contenu' => '<p class="text-lg mb-6">L\'UEMOA est une organisation régionale qui regroupe 8 pays d\'Afrique de l\'Ouest partageant une monnaie commune : le Franc CFA (XOF).</p>

<h2 class="text-2xl font-bold mt-8 mb-4">Qu\'est-ce que l\'UEMOA ?</h2>
<p class="mb-4">L\'<strong>Union Économique et Monétaire Ouest Africaine (UEMOA)</strong> a été créée le <strong>10 janvier 1994</strong> par le Traité de Dakar. Elle succède à l\'Union Monétaire Ouest Africaine (UMOA) créée en 1962.</p>

<p class="mb-4">Les 8 États membres sont :</p>
<ul class="list-disc pl-6 mb-4">
<li><strong>Bénin</strong> - Capitale économique : Cotonou</li>
<li><strong>Burkina Faso</strong> - Capitale : Ouagadougou</li>
<li><strong>Côte d\'Ivoire</strong> - Capitale économique : Abidjan (plus grande économie de la zone)</li>
<li><strong>Guinée-Bissau</strong> - Capitale : Bissau (membre depuis 1997)</li>
<li><strong>Mali</strong> - Capitale : Bamako</li>
<li><strong>Niger</strong> - Capitale : Niamey</li>
<li><strong>Sénégal</strong> - Capitale : Dakar (siège de la BCEAO)</li>
<li><strong>Togo</strong> - Capitale : Lomé</li>
</ul>

<h2 class="text-2xl font-bold mt-8 mb-4">Le Franc CFA (XOF)</h2>
<p class="mb-4">Le <strong>Franc de la Communauté Financière Africaine</strong> est la monnaie commune des 8 pays de l\'UEMOA. Caractéristiques principales :</p>
<ul class="list-disc pl-6 mb-4">
<li><strong>Code ISO</strong> : XOF</li>
<li><strong>Parité fixe</strong> : 1 EUR = 655,957 XOF (depuis le 1er janvier 1999)</li>
<li><strong>Émetteur</strong> : Banque Centrale des États de l\'Afrique de l\'Ouest (BCEAO)</li>
<li><strong>Garantie</strong> : Convertibilité garantie par le Trésor français</li>
</ul>

<h2 class="text-2xl font-bold mt-8 mb-4">La BCEAO</h2>
<p class="mb-4">La <strong>Banque Centrale des États de l\'Afrique de l\'Ouest</strong> est l\'institut d\'émission commun aux 8 pays. Son siège est à <strong>Dakar (Sénégal)</strong>.</p>

<p class="mb-4">Ses missions principales :</p>
<ul class="list-disc pl-6 mb-4">
<li>Définir et mettre en œuvre la politique monétaire</li>
<li>Émettre les billets et pièces en Franc CFA</li>
<li>Gérer les réserves de change</li>
<li>Superviser les établissements de crédit</li>
<li>Assurer la stabilité du système financier</li>
</ul>

<h2 class="text-2xl font-bold mt-8 mb-4">Avantages pour les investisseurs</h2>
<p class="mb-4">L\'environnement UEMOA offre plusieurs avantages :</p>
<ul class="list-disc pl-6 mb-4">
<li><strong>Stabilité monétaire</strong> : La parité fixe avec l\'euro réduit le risque de change</li>
<li><strong>Libre circulation des capitaux</strong> : Pas de contrôle des changes au sein de la zone</li>
<li><strong>Marché unifié</strong> : Accès à 8 économies via une seule monnaie</li>
<li><strong>Inflation maîtrisée</strong> : Objectif de la BCEAO de maintenir l\'inflation sous 3%</li>
</ul>

<h2 class="text-2xl font-bold mt-8 mb-4">Données économiques clés (2023)</h2>
<ul class="list-disc pl-6 mb-4">
<li><strong>Population totale</strong> : Environ 140 millions d\'habitants</li>
<li><strong>PIB combiné</strong> : Environ 200 milliards USD</li>
<li><strong>Croissance moyenne</strong> : 5-6% par an</li>
<li><strong>Première économie</strong> : Côte d\'Ivoire (environ 40% du PIB de la zone)</li>
</ul>

<p class="mb-4"><em>Sources : BCEAO (bceao.int), UEMOA (uemoa.int), Banque Mondiale</em></p>',
                'image_url' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=1200&h=600&fit=crop',
                'categorie' => 'Économie',
                'statut' => 'publie',
                'published_at' => '2025-01-08 14:00:00',
            ],

            // Article 5 : Les dividendes à la BRVM
            [
                'titre' => 'Les dividendes à la BRVM : Fonctionnement et calendrier',
                'slug' => 'dividendes-brvm-fonctionnement-calendrier',
                'extrait' => 'Comment fonctionnent les dividendes sur la BRVM ? Dates clés, modalités de paiement et fiscalité.',
                'contenu' => '<p class="text-lg mb-6">Les dividendes représentent une part importante du rendement des actions cotées à la BRVM. Voici tout ce que vous devez savoir sur leur fonctionnement.</p>

<h2 class="text-2xl font-bold mt-8 mb-4">Qu\'est-ce qu\'un dividende ?</h2>
<p class="mb-4">Le dividende est la part des bénéfices qu\'une entreprise distribue à ses actionnaires. Sur la BRVM, de nombreuses sociétés cotées versent des dividendes réguliers, ce qui en fait un marché attractif pour les investisseurs recherchant des revenus.</p>

<h2 class="text-2xl font-bold mt-8 mb-4">Le calendrier des dividendes</h2>
<p class="mb-4">Le processus de distribution des dividendes suit plusieurs étapes :</p>

<h3 class="text-xl font-semibold mt-6 mb-3">1. Assemblée Générale</h3>
<p class="mb-4">L\'Assemblée Générale des actionnaires approuve les comptes et décide du montant du dividende. Elle se tient généralement entre <strong>mars et juin</strong> pour les comptes de l\'année précédente.</p>

<h3 class="text-xl font-semibold mt-6 mb-3">2. Date de détachement</h3>
<p class="mb-4">C\'est la date à partir de laquelle l\'action est cotée sans le droit au dividende. Pour avoir droit au dividende, vous devez détenir l\'action <strong>avant cette date</strong>.</p>

<h3 class="text-xl font-semibold mt-6 mb-3">3. Date de paiement</h3>
<p class="mb-4">Le dividende est versé sur votre compte-titres, généralement quelques jours après la date de détachement.</p>

<h2 class="text-2xl font-bold mt-8 mb-4">Fiscalité des dividendes</h2>
<p class="mb-4">Les dividendes sont soumis à une <strong>retenue à la source</strong> qui varie selon le pays :</p>
<ul class="list-disc pl-6 mb-4">
<li><strong>Côte d\'Ivoire</strong> : 15% (Impôt sur le Revenu des Valeurs Mobilières - IRVM)</li>
<li><strong>Sénégal</strong> : 10%</li>
<li><strong>Burkina Faso</strong> : 12,5%</li>
<li><strong>Mali, Bénin, Niger, Guinée-Bissau</strong> : 15%</li>
<li><strong>Togo</strong> : 13%</li>
</ul>

<p class="mb-4">Cette retenue est généralement <strong>libératoire</strong>, ce qui signifie que vous n\'avez pas d\'impôt supplémentaire à payer sur ces revenus.</p>

<h2 class="text-2xl font-bold mt-8 mb-4">Sociétés à dividendes élevés</h2>
<p class="mb-4">Historiquement, certaines sociétés de la BRVM sont reconnues pour leurs dividendes généreux :</p>
<ul class="list-disc pl-6 mb-4">
<li><strong>Sonatel (SNTS)</strong> : Dividende régulier, rendement historique de 6-8%</li>
<li><strong>Orange Côte d\'Ivoire (ORAC)</strong> : Politique de distribution stable</li>
<li><strong>TotalEnergies CI (TTLC)</strong> : Rendement attractif</li>
<li><strong>Banques (SGBC, BOA)</strong> : Dividendes réguliers quand les résultats le permettent</li>
</ul>

<h2 class="text-2xl font-bold mt-8 mb-4">Comment suivre les dividendes ?</h2>
<p class="mb-4">Pour rester informé des distributions de dividendes :</p>
<ul class="list-disc pl-6 mb-4">
<li>Consultez le site officiel de la BRVM (brvm.org)</li>
<li>Suivez les communiqués des sociétés cotées</li>
<li>Demandez à votre SGI de vous informer</li>
<li>Consultez les rapports annuels des entreprises</li>
</ul>

<h2 class="text-2xl font-bold mt-8 mb-4">Stratégie de dividendes</h2>
<p class="mb-4">Pour construire un portefeuille orienté dividendes :</p>
<ul class="list-disc pl-6 mb-4">
<li>Privilégiez les sociétés avec un historique de distribution régulier</li>
<li>Vérifiez le taux de distribution (payout ratio) : un ratio trop élevé peut être risqué</li>
<li>Diversifiez entre plusieurs secteurs</li>
<li>Réinvestissez vos dividendes pour profiter des intérêts composés</li>
</ul>

<p class="mb-4"><em>Sources : BRVM (brvm.org), rapports annuels des sociétés, codes fiscaux nationaux</em></p>',
                'image_url' => 'https://images.unsplash.com/photo-1563986768609-322da13575f3?w=1200&h=600&fit=crop',
                'categorie' => 'Formation',
                'statut' => 'publie',
                'published_at' => '2025-01-05 11:00:00',
            ],

            // Article 6 : Glossaire des termes boursiers
            [
                'titre' => 'Glossaire des termes boursiers : Le vocabulaire de la BRVM',
                'slug' => 'glossaire-termes-boursiers-vocabulaire-brvm',
                'extrait' => 'Les définitions essentielles pour comprendre le langage des marchés financiers et de la BRVM.',
                'contenu' => '<p class="text-lg mb-6">Pour bien investir, il faut d\'abord comprendre le vocabulaire. Voici les termes essentiels utilisés sur la BRVM et les marchés financiers.</p>

<h2 class="text-2xl font-bold mt-8 mb-4">A - B</h2>

<p class="mb-4"><strong>Action</strong> : Titre de propriété représentant une part du capital d\'une entreprise. L\'actionnaire devient copropriétaire de la société.</p>

<p class="mb-4"><strong>BRVM</strong> : Bourse Régionale des Valeurs Mobilières. Marché boursier commun aux 8 pays de l\'UEMOA, basé à Abidjan.</p>

<p class="mb-4"><strong>BRVM 10</strong> : Indice regroupant les 10 valeurs les plus actives de la BRVM, pondéré par la capitalisation flottante.</p>

<p class="mb-4"><strong>BRVM Composite</strong> : Indice global regroupant toutes les valeurs cotées à la BRVM.</p>

<h2 class="text-2xl font-bold mt-8 mb-4">C - D</h2>

<p class="mb-4"><strong>Capitalisation boursière</strong> : Valeur totale d\'une entreprise en bourse = Nombre d\'actions × Cours de l\'action.</p>

<p class="mb-4"><strong>Cours</strong> : Prix auquel s\'échange une action sur le marché.</p>

<p class="mb-4"><strong>AMF-UMOA</strong> : Autorité des Marchés Financiers de l\'UMOA. Autorité de régulation du marché financier régional.</p>

<p class="mb-4"><strong>Dividende</strong> : Part des bénéfices distribuée aux actionnaires.</p>

<p class="mb-4"><strong>DC/BR</strong> : Dépositaire Central / Banque de Règlement. Organisme qui assure la conservation des titres et le règlement des transactions.</p>

<h2 class="text-2xl font-bold mt-8 mb-4">I - L</h2>

<p class="mb-4"><strong>Indice boursier</strong> : Indicateur mesurant l\'évolution d\'un ensemble de valeurs. La BRVM dispose du BRVM Composite et du BRVM 10.</p>

<p class="mb-4"><strong>Introduction en bourse (IPO)</strong> : Première mise en vente des actions d\'une entreprise sur le marché boursier.</p>

<p class="mb-4"><strong>Liquidité</strong> : Facilité avec laquelle un titre peut être acheté ou vendu sans impact significatif sur son cours.</p>

<h2 class="text-2xl font-bold mt-8 mb-4">O - P</h2>

<p class="mb-4"><strong>Obligation</strong> : Titre de créance représentant un emprunt. L\'obligataire prête de l\'argent et reçoit des intérêts.</p>

<p class="mb-4"><strong>Ordre de bourse</strong> : Instruction donnée à votre SGI pour acheter ou vendre des titres.</p>

<p class="mb-4"><strong>PER (Price Earning Ratio)</strong> : Ratio cours/bénéfice. Indique combien d\'années de bénéfices sont nécessaires pour rembourser le prix de l\'action.</p>

<p class="mb-4"><strong>Plus-value</strong> : Gain réalisé lors de la vente d\'un titre à un prix supérieur au prix d\'achat.</p>

<p class="mb-4"><strong>Portefeuille</strong> : Ensemble des titres détenus par un investisseur.</p>

<h2 class="text-2xl font-bold mt-8 mb-4">R - S</h2>

<p class="mb-4"><strong>Rendement</strong> : Rapport entre le dividende et le cours de l\'action, exprimé en pourcentage.</p>

<p class="mb-4"><strong>SGI</strong> : Société de Gestion et d\'Intermédiation. Intermédiaire agréé pour exécuter les ordres de bourse sur la BRVM.</p>

<p class="mb-4"><strong>Séance de cotation</strong> : Période pendant laquelle les transactions sont possibles sur la BRVM (10h00 - 15h00 GMT).</p>

<h2 class="text-2xl font-bold mt-8 mb-4">T - V</h2>

<p class="mb-4"><strong>Titre</strong> : Terme générique désignant les actions, obligations et autres valeurs mobilières.</p>

<p class="mb-4"><strong>Valeur mobilière</strong> : Titre financier négociable sur un marché (actions, obligations, OPCVM...).</p>

<p class="mb-4"><strong>Volume</strong> : Nombre de titres échangés pendant une période donnée. Un volume élevé indique une forte activité.</p>

<h2 class="text-2xl font-bold mt-8 mb-4">Sigles des sociétés cotées</h2>
<p class="mb-4">Chaque société cotée à la BRVM est identifiée par un code :</p>
<ul class="list-disc pl-6 mb-4">
<li><strong>SNTS</strong> : Sonatel</li>
<li><strong>ORAC</strong> : Orange Côte d\'Ivoire</li>
<li><strong>SGBC</strong> : Société Générale Côte d\'Ivoire</li>
<li><strong>ETIT</strong> : Ecobank Transnational Incorporated</li>
<li><strong>TTLC</strong> : TotalEnergies Marketing Côte d\'Ivoire</li>
<li><strong>SLBC</strong> : Solibra</li>
<li><strong>NTLC</strong> : Nestlé Côte d\'Ivoire</li>
</ul>

<p class="mb-4"><em>Sources : BRVM (brvm.org), AMF-UMOA (amf-umoa.org)</em></p>',
                'image_url' => 'https://images.unsplash.com/photo-1579621970563-ebec7560ff3e?w=1200&h=600&fit=crop',
                'categorie' => 'Éducation',
                'statut' => 'publie',
                'published_at' => '2025-01-03 09:30:00',
            ],
        ];

        // Supprimer les anciens articles
        Article::truncate();

        foreach ($articles as $articleData) {
            Article::create(array_merge($articleData, ['user_id' => $admin->id]));
        }

        $this->command->info('6 articles complets créés avec succès !');
    }
}
