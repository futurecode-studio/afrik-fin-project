<div class="bg-[#f9f9ff] text-[#131c2a]">
    {{-- Hero --}}
    <section class="relative bg-[#001a61] text-white overflow-hidden">
        <div class="absolute inset-0 opacity-30 pointer-events-none"
            style="background: radial-gradient(800px 400px at 90% 10%, rgba(255,191,0,.35), transparent 55%), radial-gradient(600px 300px at 10% 90%, rgba(10,46,140,.5), transparent 50%);"></div>
        <div class="relative max-w-[1100px] mx-auto px-5 lg:px-8 pt-14 pb-12 lg:pt-20 lg:pb-16">
            <p class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-[#ffbf00]">Guide Complet de la Bourse</p>
            <h1 class="mt-3 text-3xl md:text-5xl font-extrabold tracking-tight max-w-3xl leading-tight">
                Tout ce que vous devez savoir sur la BRVM et l’investissement en bourse
            </h1>
            <p class="mt-4 text-white/80 text-lg max-w-2xl leading-relaxed">
                Que vous soyez débutant ou investisseur confirmé, ce guide vous accompagne dans votre parcours
                d’investissement sur le marché boursier de l’UEMOA — avec Africaine des Finances
                (agrément AMF-UMOA AA/2022-03).
            </p>
            <div class="mt-8 grid sm:grid-cols-3 gap-3">
                <a href="{{ route('formations') }}" class="rounded-xl bg-white/10 hover:bg-white/15 border border-white/20 p-4 transition">
                    <span class="material-symbols-outlined text-[#ffbf00]">school</span>
                    <p class="font-bold mt-2">Formations</p>
                    <p class="text-sm text-white/70 mt-1">Apprenez les bases et l’analyse de marché</p>
                </a>
                <a href="{{ route('outils.interets-composes') }}" class="rounded-xl bg-white/10 hover:bg-white/15 border border-white/20 p-4 transition">
                    <span class="material-symbols-outlined text-[#ffbf00]">calculate</span>
                    <p class="font-bold mt-2">Outils investisseurs</p>
                    <p class="text-sm text-white/70 mt-1">Calculateurs et profil investisseur</p>
                </a>
                <a href="{{ route('ouverture-compte-sgi') }}" class="rounded-xl bg-[#ffbf00] text-[#261a00] hover:brightness-95 p-4 transition">
                    <span class="material-symbols-outlined">account_balance_wallet</span>
                    <p class="font-extrabold mt-2">Ouvrir un compte</p>
                    <p class="text-sm opacity-80 mt-1">Première étape pour investir via une SGI</p>
                </a>
            </div>
        </div>
    </section>

    {{-- Sommaire sticky --}}
    <nav class="sticky top-[7.5rem] z-20 border-b border-[#c5c5d4] bg-white/90 backdrop-blur">
        <div class="max-w-[1100px] mx-auto px-5 lg:px-8 overflow-x-auto">
            <div class="flex gap-1 min-w-max py-2 text-sm">
                @foreach ([
                    ['#bourse', 'Qu’est-ce que la bourse'],
                    ['#brvm', 'La BRVM'],
                    ['#actions', 'Actions'],
                    ['#obligations', 'Obligations'],
                    ['#investir', 'Comment investir'],
                    ['#conseils', 'Conseils'],
                    ['#glossaire', 'Glossaire'],
                ] as $link)
                    <a href="{{ $link[0] }}" class="px-3 py-2 rounded-lg text-[#444652] hover:bg-[#e7eeff] hover:text-[#001a61] font-medium whitespace-nowrap">{{ $link[1] }}</a>
                @endforeach
            </div>
        </div>
    </nav>

    <div class="max-w-[1100px] mx-auto px-5 lg:px-8 py-12 lg:py-16 space-y-20">

        {{-- Qu'est-ce que la bourse --}}
        <section id="bourse" class="scroll-mt-40">
            <h2 class="text-2xl md:text-3xl font-extrabold text-[#001a61]">Qu’est-ce que la Bourse ?</h2>
            <p class="mt-4 text-[#444652] text-lg leading-relaxed max-w-3xl">
                La bourse est un marché financier organisé où s’échangent des valeurs mobilières (actions, obligations, etc.).
                C’est un lieu de rencontre entre les entreprises qui ont besoin de financement et les investisseurs qui souhaitent placer leur argent.
            </p>
            <div class="mt-8 grid md:grid-cols-2 gap-6">
                <div class="bg-white border border-[#c5c5d4] rounded-2xl p-6">
                    <span class="material-symbols-outlined text-[#001a61] text-3xl">business</span>
                    <h3 class="mt-3 text-xl font-bold text-[#001a61]">Pour les entreprises</h3>
                    <p class="mt-2 text-[#444652] leading-relaxed">
                        La bourse permet aux entreprises de lever des capitaux pour financer leur croissance,
                        leurs projets d’expansion ou leurs investissements.
                    </p>
                </div>
                <div class="bg-white border border-[#c5c5d4] rounded-2xl p-6">
                    <span class="material-symbols-outlined text-[#001a61] text-3xl">trending_up</span>
                    <h3 class="mt-3 text-xl font-bold text-[#001a61]">Pour les investisseurs</h3>
                    <p class="mt-2 text-[#444652] leading-relaxed">
                        La bourse offre aux particuliers et institutions la possibilité d’investir dans des entreprises
                        et de participer à leur développement tout en générant des revenus.
                    </p>
                </div>
            </div>
            <div class="mt-6 rounded-2xl border border-[#ffbf00]/40 bg-[#fff8e6] p-5 md:p-6">
                <p class="text-xs font-extrabold uppercase tracking-wider text-[#001a61]">Le saviez-vous ?</p>
                <p class="mt-2 text-[#444652] leading-relaxed">
                    La première bourse moderne a été créée à Amsterdam en 1602 avec la Compagnie néerlandaise des Indes orientales.
                    Aujourd’hui, les bourses sont présentes sur tous les continents et jouent un rôle crucial dans l’économie mondiale.
                </p>
            </div>
        </section>

        {{-- BRVM --}}
        <section id="brvm" class="scroll-mt-40">
            <h2 class="text-2xl md:text-3xl font-extrabold text-[#001a61]">La BRVM : Bourse Régionale des Valeurs Mobilières</h2>
            <p class="mt-4 text-[#444652] text-lg leading-relaxed max-w-3xl">
                La BRVM est la bourse commune aux 8 pays de l’Union Économique et Monétaire Ouest-Africaine (UEMOA).
            </p>

            <h3 class="mt-8 text-lg font-bold text-[#001a61]">Les 8 pays membres de l’UEMOA</h3>
            <div class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-3">
                @foreach (['Bénin', 'Burkina Faso', 'Côte d’Ivoire', 'Guinée-Bissau', 'Mali', 'Niger', 'Sénégal', 'Togo'] as $pays)
                    <div class="bg-white border border-[#c5c5d4] rounded-xl px-4 py-3 text-sm font-semibold text-[#001a61] text-center">
                        {{ $pays }}
                    </div>
                @endforeach
            </div>

            <h3 class="mt-10 text-lg font-bold text-[#001a61]">Caractéristiques de la BRVM</h3>
            <div class="mt-4 grid sm:grid-cols-2 gap-4">
                @foreach ([
                    ['icon' => 'location_on', 't' => 'Siège social', 'd' => 'Abidjan, Côte d’Ivoire — la BRVM centralise les transactions boursières de la zone UEMOA depuis 1998.'],
                    ['icon' => 'public', 't' => 'Marché unique', 'd' => 'Un marché intégré permettant aux entreprises des 8 pays d’accéder à un bassin d’investisseurs plus large.'],
                    ['icon' => 'gavel', 't' => 'Régulation', 'd' => 'Supervisée par l’Autorité des Marchés Financiers de l’UMOA (AMF-UMOA), qui garantit la sécurité et la transparence des transactions.'],
                    ['icon' => 'monitoring', 't' => 'Indices boursiers', 'd' => 'BRVM Composite et BRVM 30 mesurent la performance globale du marché et des valeurs les plus actives.'],
                ] as $item)
                    <div class="bg-white border border-[#c5c5d4] rounded-2xl p-5 flex gap-4">
                        <span class="material-symbols-outlined text-[#001a61] shrink-0">{{ $item['icon'] }}</span>
                        <div>
                            <h4 class="font-bold text-[#001a61]">{{ $item['t'] }}</h4>
                            <p class="mt-1 text-sm text-[#444652] leading-relaxed">{{ $item['d'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 rounded-2xl bg-[#001a61] text-white p-5 md:p-6">
                <p class="text-xs font-extrabold uppercase tracking-wider text-[#ffbf00]">Important</p>
                <p class="mt-2 leading-relaxed text-white/90">
                    La BRVM fonctionne en Francs CFA (XOF). Les séances de cotation ont lieu du lundi au vendredi,
                    généralement de 8h30 à 15h30 (heure d’Abidjan). Consultez le
                    <a href="{{ route('marches.calendrier') }}" class="underline font-bold text-[#ffbf00]">calendrier financier</a>
                    pour les jours fériés et événements.
                </p>
            </div>
        </section>

        {{-- Actions --}}
        <section id="actions" class="scroll-mt-40">
            <h2 class="text-2xl md:text-3xl font-extrabold text-[#001a61]">Les Actions : devenir actionnaire</h2>
            <p class="mt-4 text-[#444652] text-lg leading-relaxed max-w-3xl">
                Une action représente une part du capital d’une entreprise. En achetant des actions, vous devenez
                actionnaire et propriétaire d’une partie de l’entreprise.
            </p>

            <h3 class="mt-8 text-lg font-bold text-[#001a61]">Avantages de détenir des actions</h3>
            <div class="mt-4 grid md:grid-cols-3 gap-4">
                @foreach ([
                    ['icon' => 'payments', 't' => 'Plus-values', 'd' => 'Profitez de l’augmentation du cours. Achat à 1 000 FCFA, revente à 1 500 FCFA : plus-value de 500 FCFA par action.'],
                    ['icon' => 'redeem', 't' => 'Dividendes', 'd' => 'Recevez une part des bénéfices distribuée aux actionnaires, généralement en espèces, selon la politique de l’entreprise.'],
                    ['icon' => 'how_to_vote', 't' => 'Droit de vote', 'd' => 'Participez aux décisions importantes lors des assemblées générales, proportionnellement au nombre d’actions détenues.'],
                ] as $item)
                    <div class="bg-white border border-[#c5c5d4] rounded-2xl p-5">
                        <span class="material-symbols-outlined text-[#001a61]">{{ $item['icon'] }}</span>
                        <h4 class="mt-2 font-bold text-[#001a61]">{{ $item['t'] }}</h4>
                        <p class="mt-2 text-sm text-[#444652] leading-relaxed">{{ $item['d'] }}</p>
                    </div>
                @endforeach
            </div>

            <h3 class="mt-10 text-lg font-bold text-[#001a61]">Comment fonctionne le prix d’une action ?</h3>
            <ul class="mt-4 space-y-2 text-[#444652]">
                <li class="flex gap-2"><span class="material-symbols-outlined text-[#001a61] text-[20px] shrink-0">check_circle</span> <span><strong>Forte demande</strong> : si beaucoup d’investisseurs veulent acheter, le prix monte.</span></li>
                <li class="flex gap-2"><span class="material-symbols-outlined text-[#001a61] text-[20px] shrink-0">check_circle</span> <span><strong>Forte offre</strong> : si beaucoup d’actionnaires veulent vendre, le prix baisse.</span></li>
                <li class="flex gap-2"><span class="material-symbols-outlined text-[#001a61] text-[20px] shrink-0">check_circle</span> <span><strong>Résultats de l’entreprise</strong> : de bons résultats financiers attirent les acheteurs.</span></li>
                <li class="flex gap-2"><span class="material-symbols-outlined text-[#001a61] text-[20px] shrink-0">check_circle</span> <span><strong>Actualités</strong> : événements politiques, économiques ou sectoriels influencent les cours.</span></li>
            </ul>

            <div class="mt-6 rounded-2xl border border-[#c5c5d4] bg-white p-5 md:p-6">
                <p class="text-xs font-extrabold uppercase tracking-wider text-[#0a2e8c]">Exemple concret</p>
                <p class="mt-2 text-[#444652] leading-relaxed">
                    Vous achetez 100 actions à 10 000 FCFA l’unité. Investissement : <strong>1 000 000 FCFA</strong>.
                    Si le cours monte à 12 000 FCFA, votre portefeuille vaut <strong>1 200 000 FCFA</strong>
                    (plus-value potentielle de 200 000 FCFA, soit 20 %), en plus d’éventuels dividendes.
                </p>
                <a href="{{ route('marches.cotations') }}" class="inline-flex items-center gap-1 mt-4 text-sm font-bold text-[#001a61] hover:underline">
                    Voir les cotations BRVM <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
            </div>
        </section>

        {{-- Obligations --}}
        <section id="obligations" class="scroll-mt-40">
            <h2 class="text-2xl md:text-3xl font-extrabold text-[#001a61]">Les Obligations : prêter à une entreprise ou un État</h2>
            <p class="mt-4 text-[#444652] text-lg leading-relaxed max-w-3xl">
                Une obligation est un titre de créance. En l’achetant, vous prêtez de l’argent à une entreprise ou un État
                qui s’engage à vous rembourser avec des intérêts.
            </p>

            <div class="mt-8 grid sm:grid-cols-2 gap-4">
                @foreach ([
                    ['icon' => 'shield', 't' => 'Sécurité', 'd' => 'Souvent moins risquées que les actions : en cas de difficultés, les obligataires sont en principe remboursés avant les actionnaires.'],
                    ['icon' => 'event_repeat', 't' => 'Revenus fixes', 'd' => 'Vous recevez des intérêts (coupons) à des dates prédéfinies, généralement annuellement ou semestriellement.'],
                    ['icon' => 'hourglass_bottom', 't' => 'Maturité', 'd' => 'Durée de vie définie (5, 10, 15 ans…). À l’échéance, vous récupérez en principe le capital (valeur nominale).'],
                    ['icon' => 'account_balance', 't' => 'Émetteurs', 'd' => 'États (bons du Trésor), entreprises publiques ou privées qui financent leurs projets sans diluer leur capital.'],
                ] as $item)
                    <div class="bg-white border border-[#c5c5d4] rounded-2xl p-5 flex gap-4">
                        <span class="material-symbols-outlined text-[#001a61] shrink-0">{{ $item['icon'] }}</span>
                        <div>
                            <h4 class="font-bold text-[#001a61]">{{ $item['t'] }}</h4>
                            <p class="mt-1 text-sm text-[#444652] leading-relaxed">{{ $item['d'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 rounded-2xl border border-[#c5c5d4] bg-white p-5 md:p-6">
                <p class="text-xs font-extrabold uppercase tracking-wider text-[#0a2e8c]">Exemple d’obligation</p>
                <ul class="mt-3 space-y-1 text-sm text-[#444652]">
                    <li><strong>Émetteur</strong> : État membre UEMOA</li>
                    <li><strong>Valeur nominale</strong> : 10 000 FCFA</li>
                    <li><strong>Taux</strong> : 6 % par an</li>
                    <li><strong>Durée</strong> : 5 ans</li>
                    <li><strong>Revenu annuel</strong> : 600 FCFA par obligation</li>
                    <li><strong>Total sur 5 ans</strong> : 3 000 FCFA d’intérêts + 10 000 FCFA de capital = 13 000 FCFA</li>
                </ul>
                <a href="{{ route('marches.obligations') }}" class="inline-flex items-center gap-1 mt-4 text-sm font-bold text-[#001a61] hover:underline">
                    Explorer le marché obligataire <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
            </div>

            <h3 class="mt-10 text-lg font-bold text-[#001a61]">Actions vs Obligations</h3>
            <div class="mt-4 overflow-x-auto rounded-2xl border border-[#c5c5d4] bg-white">
                <table class="w-full text-sm min-w-[560px]">
                    <thead class="bg-[#f0f3ff] text-xs uppercase text-[#757683]">
                        <tr>
                            <th class="text-left px-4 py-3 font-semibold">Critère</th>
                            <th class="text-left px-4 py-3 font-semibold">Actions</th>
                            <th class="text-left px-4 py-3 font-semibold">Obligations</th>
                        </tr>
                    </thead>
                    <tbody class="text-[#444652]">
                        @foreach ([
                            ['Nature', 'Part de propriété', 'Titre de créance'],
                            ['Risque', 'Élevé', 'Faible à modéré'],
                            ['Rendement', 'Variable (dividendes + plus-values)', 'Fixe (intérêts)'],
                            ['Durée', 'Indéterminée', 'Déterminée (échéance)'],
                            ['Droit de vote', 'Oui', 'Non'],
                        ] as $row)
                            <tr class="border-t border-[#e7eeff]">
                                <td class="px-4 py-3 font-semibold text-[#001a61]">{{ $row[0] }}</td>
                                <td class="px-4 py-3">{{ $row[1] }}</td>
                                <td class="px-4 py-3">{{ $row[2] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        {{-- Comment investir --}}
        <section id="investir" class="scroll-mt-40">
            <h2 class="text-2xl md:text-3xl font-extrabold text-[#001a61]">Comment investir en bourse ?</h2>
            <p class="mt-2 text-[#444652]">Les étapes pour commencer avec Africaine des Finances</p>

            <div class="mt-8 space-y-5">
                @foreach ([
                    [
                        'n' => '1',
                        't' => 'Ouvrir un compte-titres',
                        'd' => 'Contactez une Société de Gestion et d’Intermédiation (SGI) agréée. Africaine des Finances, apporteur d’affaires agréé AMF-UMOA (AA/2022-03), vous oriente et vous accompagne dans cette démarche.',
                        'cta' => 'Demander une ouverture',
                        'route' => 'ouverture-compte-sgi',
                    ],
                    [
                        'n' => '2',
                        't' => 'Découvrez votre profil investisseur',
                        'd' => 'Évaluez votre tolérance au risque, vos objectifs et votre horizon : prudent (obligations / grandes valeurs), équilibré (mix), ou dynamique (actions à potentiel).',
                        'cta' => 'Évaluer mon profil',
                        'route' => 'investir.profil-test',
                    ],
                    [
                        'n' => '3',
                        't' => 'Analyser les valeurs',
                        'd' => 'Utilisez cotations, fiches titres, indices et analyses pour comprendre les entreprises avant d’investir.',
                        'cta' => 'Voir les marchés',
                        'route' => 'marches.index',
                    ],
                    [
                        'n' => '4',
                        't' => 'Passer votre intention d’ordre',
                        'd' => 'Sur la plateforme, enregistrez une intention d’ordre relayée vers une SGI agréée. Africaine des Finances n’exécute pas les ordres elle-même.',
                        'cta' => 'Carnet d’ordres',
                        'route' => 'marches.carnet',
                    ],
                    [
                        'n' => '5',
                        't' => 'Suivre votre portefeuille',
                        'd' => 'Consultez l’évolution des cours, les actualités et ajustez votre stratégie avec l’appui de nos formations et conseillers.',
                        'cta' => 'Espace client',
                        'route' => 'connexion',
                    ],
                ] as $step)
                    <article class="bg-white border border-[#c5c5d4] rounded-2xl p-5 md:p-6 flex flex-col md:flex-row md:items-center gap-5">
                        <div class="shrink-0 w-12 h-12 rounded-full bg-[#001a61] text-white font-extrabold flex items-center justify-center text-lg">
                            {{ $step['n'] }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-bold text-[#001a61]">{{ $step['t'] }}</h3>
                            <p class="mt-1 text-sm text-[#444652] leading-relaxed">{{ $step['d'] }}</p>
                        </div>
                        <a href="{{ route($step['route']) }}"
                            class="shrink-0 inline-flex justify-center px-4 py-2.5 rounded-xl bg-[#001a61] text-white text-sm font-bold hover:bg-[#0a2e8c] transition">
                            {{ $step['cta'] }}
                        </a>
                    </article>
                @endforeach
            </div>
        </section>

        {{-- Conseils --}}
        <section id="conseils" class="scroll-mt-40">
            <h2 class="text-2xl md:text-3xl font-extrabold text-[#001a61]">Conseils pour bien investir</h2>
            <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ([
                    ['icon' => 'diversity_3', 't' => 'Diversifiez', 'd' => 'Répartissez entre secteurs (banques, télécoms, agro, industrie…) et types de titres (actions, obligations, FCP).'],
                    ['icon' => 'schedule', 't' => 'Investissez long terme', 'd' => 'La bourse récompense la patience. Un horizon de 5 ans ou plus lisse les variations et profite de la croissance.'],
                    ['icon' => 'menu_book', 't' => 'Formez-vous', 'd' => 'Suivez l’actualité, les résultats et les indicateurs. Plus vous êtes informé, meilleures sont vos décisions.'],
                    ['icon' => 'savings', 't' => 'N’investissez que le superflu', 'd' => 'Ne placez jamais l’argent nécessaire au quotidien. Gardez une épargne de sécurité avant d’investir.'],
                    ['icon' => 'psychology', 't' => 'Évitez l’émotion', 'd' => 'Ne vendez pas en panique, n’achetez pas dans l’euphorie. Tenez-vous à votre stratégie et à l’analyse.'],
                    ['icon' => 'warning', 't' => 'Méfiez-vous des promesses', 'd' => 'Aucun rendement élevé n’est sans risque. Privilégiez les valeurs cotées BRVM et les intermédiaires agréés.'],
                ] as $tip)
                    <div class="bg-white border border-[#c5c5d4] rounded-2xl p-5">
                        <span class="material-symbols-outlined text-[#001a61]">{{ $tip['icon'] }}</span>
                        <h3 class="mt-2 font-bold text-[#001a61]">{{ $tip['t'] }}</h3>
                        <p class="mt-2 text-sm text-[#444652] leading-relaxed">{{ $tip['d'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Glossaire --}}
        <section id="glossaire" class="scroll-mt-40">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3">
                <h2 class="text-2xl md:text-3xl font-extrabold text-[#001a61]">Glossaire des termes boursiers</h2>
                <a href="{{ route('glossaire') }}" class="text-sm font-bold text-[#001a61] hover:underline">Glossaire complet →</a>
            </div>
            <div class="mt-6 grid sm:grid-cols-2 gap-3">
                @foreach ([
                    ['Capitalisation boursière', 'Valeur totale d’une entreprise en bourse (nombre d’actions × prix).'],
                    ['Dividende', 'Part des bénéfices distribuée aux actionnaires.'],
                    ['PER', 'Ratio cours / bénéfice : mesure si une action paraît chère ou bon marché.'],
                    ['Liquidité', 'Facilité à acheter ou vendre un titre rapidement sans impacter son prix.'],
                    ['Volatilité', 'Amplitude des variations du cours d’un titre.'],
                    ['Ordre à cours limité', 'Ordre d’achat ou de vente à un prix maximum ou minimum défini.'],
                    ['Ordre au marché', 'Ordre exécuté immédiatement au meilleur prix disponible.'],
                    ['Plus-value', 'Gain réalisé en vendant un titre plus cher que son prix d’achat.'],
                    ['Moins-value', 'Perte réalisée en vendant un titre moins cher que son prix d’achat.'],
                    ['Indice boursier', 'Indicateur de performance d’un marché ou d’un panier de valeurs.'],
                    ['SGI', 'Société de Gestion et d’Intermédiation — intermédiaire agréé pour les ordres et comptes-titres.'],
                    ['AMF-UMOA', 'Autorité des Marchés Financiers de l’UEMOA — régulateur régional du marché et de la BRVM.'],
                ] as $term)
                    <div class="bg-white border border-[#c5c5d4] rounded-xl px-4 py-3">
                        <p class="font-bold text-[#001a61] text-sm">{{ $term[0] }}</p>
                        <p class="text-xs text-[#444652] mt-1 leading-relaxed">{{ $term[1] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Parcours CTA --}}
        <section class="rounded-3xl bg-[#001a61] text-white p-8 md:p-12">
            <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-[#ffbf00]">Parcours Africaine des Finances</p>
            <h2 class="mt-3 text-2xl md:text-3xl font-extrabold max-w-2xl">
                Votre parcours d’investissement, étape par étape
            </h2>
            <p class="mt-3 text-white/75 max-w-xl">
                Suivez ces étapes simples pour commencer à investir en toute confiance, avec l’appui d’un apporteur d’affaires agréé.
            </p>
            <div class="mt-8 grid md:grid-cols-3 gap-4">
                <div class="rounded-2xl bg-white/10 border border-white/15 p-5">
                    <p class="text-[#ffbf00] font-extrabold text-sm">1 · Apprenez</p>
                    <p class="mt-2 text-sm text-white/80">Formations, guide et outils pour comprendre la BRVM.</p>
                </div>
                <div class="rounded-2xl bg-white/10 border border-white/15 p-5">
                    <p class="text-[#ffbf00] font-extrabold text-sm">2 · Ouvrez votre compte</p>
                    <p class="mt-2 text-sm text-white/80">Compte-titres existant chez une SGI, ou demande d’ouverture via ADF.</p>
                </div>
                <div class="rounded-2xl bg-white/10 border border-white/15 p-5">
                    <p class="text-[#ffbf00] font-extrabold text-sm">3 · Investissez</p>
                    <p class="mt-2 text-sm text-white/80">Intentions d’ordres, suivi des marchés et accompagnement conseil.</p>
                </div>
            </div>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('formations') }}" class="inline-flex items-center gap-2 bg-[#ffbf00] text-[#261a00] font-extrabold px-5 py-3 rounded-xl hover:brightness-95 transition">
                    Voir les formations
                </a>
                <a href="{{ route('investir.profil-test') }}" class="inline-flex items-center gap-2 bg-white/10 border border-white/25 font-bold px-5 py-3 rounded-xl hover:bg-white/15 transition">
                    Évaluer mon profil
                </a>
                <a href="{{ route('ouverture-compte-sgi') }}" class="inline-flex items-center gap-2 bg-white text-[#001a61] font-bold px-5 py-3 rounded-xl hover:bg-[#e7eeff] transition">
                    Ouvrir un compte-titres
                </a>
            </div>
            <p class="mt-6 text-sm text-white/60">
                Besoin d’aide ? <a href="{{ route('contact') }}" class="text-[#ffbf00] font-bold underline">Contactez notre équipe</a>
            </p>
        </section>
    </div>
</div>
