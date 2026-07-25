<div class="bg-[#f9f9ff] text-[#131c2a] overflow-x-hidden">
    {{-- Hero --}}
    <section class="relative pt-16 lg:pt-20 pb-16 lg:pb-20 px-5 lg:px-16 max-w-[1280px] mx-auto grid lg:grid-cols-2 gap-8 items-center">
        <div class="flex flex-col gap-4">
            <div class="bg-[#dfe8fc] text-[#001a61] inline-flex items-center px-3 py-1 rounded-full gap-2 w-fit">
                <span class="material-symbols-outlined text-[18px]">verified_user</span>
                <span class="text-sm font-bold tracking-wide">Agrément AMF-UMOA AA/2022-03</span>
            </div>
            <h1 class="text-4xl md:text-5xl lg:text-[48px] lg:leading-[56px] font-extrabold text-[#001a61] tracking-tight">
                Comprendre la finance. <br>
                Investir avec stratégie. <br>
                <span class="text-[#ffbf00]">Construire l’avenir.</span>
            </h1>
            <p class="text-lg leading-7 text-[#444652] max-w-xl">
                Accédez aux marchés financiers de l'UEMOA avec l'expertise d'une institution régulée.
                Nous transformons la complexité boursière en opportunités de croissance durable pour votre patrimoine.
            </p>
            <div class="flex flex-wrap gap-4 mt-4">
                <a href="{{ route('investir.actions-brvm') }}"
                    class="bg-[#001a61] text-white font-bold px-8 py-4 rounded-lg hover:bg-[#0a2e8c] transition-all flex items-center gap-2">
                    Commencer à investir
                    <span class="material-symbols-outlined">trending_up</span>
                </a>
                <a href="{{ route('marches.cotations') }}"
                    class="border border-[#001a61] text-[#001a61] font-bold px-8 py-4 rounded-lg hover:bg-[#e7eeff] transition-all">
                    Découvrir les marchés
                </a>
            </div>
        </div>

        <div class="relative h-[420px] lg:h-[600px] w-full hidden lg:block">
            <div class="absolute inset-0 bg-[#0a2e8c]/5 rounded-[2rem] transform rotate-3 scale-95"></div>
            <div class="absolute inset-0 overflow-hidden rounded-[2rem] border border-[#c5c5d4] shadow-xl">
                <img src="{{ asset('assets/images/stitch/hero.jpg') }}"
                    alt="Investisseur devant des données de marché BRVM"
                    class="w-full h-full object-cover">
                @if ($topGainers->isNotEmpty())
                    @php $lead = $topGainers->first(); @endphp
                    <div class="absolute top-10 right-10 bg-white/70 backdrop-blur-md border border-white/50 p-4 rounded-xl shadow-lg">
                        <p class="text-sm font-medium tracking-wide text-[#444652]">{{ $lead->symbol }}</p>
                        <p class="text-base font-semibold text-[#001a61]">
                            {{ number_format((float) $lead->current_price, 0, ',', ' ') }} FCFA
                            <span class="text-green-600 text-xs">
                                +{{ number_format((float) $lead->variation_percent, 2) }}%
                            </span>
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- Ticker marchés --}}
    <section class="bg-[#001a61] text-white py-4 overflow-hidden">
        <div class="adf-ticker-wrap">
            <div class="adf-ticker flex gap-16 items-center whitespace-nowrap">
                @forelse ($tickerStocks as $stock)
                    <div class="flex items-center gap-4">
                        <span class="uppercase opacity-70 text-sm font-medium tracking-wide">{{ $stock->symbol }}:</span>
                        <span class="font-semibold">
                            {{ number_format((float) $stock->current_price, 0, ',', ' ') }}
                            @if ($stock->variation_percent >= 0)
                                <span class="text-green-400">▲ {{ number_format((float) $stock->variation_percent, 2) }}%</span>
                            @else
                                <span class="text-red-400">▼ {{ number_format(abs((float) $stock->variation_percent), 2) }}%</span>
                            @endif
                        </span>
                    </div>
                @empty
                    <div class="flex items-center gap-4 px-8">
                        <span class="uppercase opacity-70 text-sm">Marché BRVM</span>
                        <span class="font-semibold">Données en cours de chargement</span>
                    </div>
                @endforelse
                <div class="flex items-center gap-4">
                    <span class="uppercase opacity-70 text-sm font-medium tracking-wide">Volume:</span>
                    <span class="font-semibold">{{ number_format($totalVolume, 0, ',', ' ') }} titres</span>
                </div>
                @if ($topGainers->isNotEmpty())
                    <div class="flex items-center gap-4">
                        <span class="uppercase opacity-70 text-sm font-medium tracking-wide">Top Winner:</span>
                        <span class="text-green-400 font-bold">
                            {{ $topGainers->first()->symbol }} (+{{ number_format((float) $topGainers->first()->variation_percent, 2) }}%)
                        </span>
                    </div>
                @endif
                @if ($topLosers->isNotEmpty())
                    <div class="flex items-center gap-4">
                        <span class="uppercase opacity-70 text-sm font-medium tracking-wide">Top Loser:</span>
                        <span class="text-red-400 font-bold">
                            {{ $topLosers->first()->symbol }} ({{ number_format((float) $topLosers->first()->variation_percent, 2) }}%)
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- Apprendre / Comprendre / Investir --}}
    <section class="py-16 lg:py-20 px-5 lg:px-16 max-w-[1280px] mx-auto">
        <div class="grid md:grid-cols-3 gap-6">
            <div class="bg-[#f9f9ff] border border-[#c5c5d4] p-8 rounded-xl hover:shadow-lg transition-all group">
                <div class="w-12 h-12 bg-[#dce1ff] rounded-lg flex items-center justify-center mb-6 group-hover:bg-[#ffbf00] transition-colors">
                    <span class="material-symbols-outlined text-[#001a61]">school</span>
                </div>
                <h3 class="text-xl font-bold text-[#001a61] mb-4">Apprendre</h3>
                <p class="text-[#444652] mb-6">Formez-vous aux bases de l'investissement boursier avec nos experts dédiés au marché UEMOA.</p>
                <a href="{{ route('formations') }}" class="text-[#001a61] font-bold flex items-center gap-2 group-hover:gap-4 transition-all">
                    En savoir plus <span class="material-symbols-outlined">arrow_forward</span>
                </a>
            </div>
            <div class="bg-[#f9f9ff] border border-[#c5c5d4] p-8 rounded-xl hover:shadow-lg transition-all group">
                <div class="w-12 h-12 bg-[#dce1ff] rounded-lg flex items-center justify-center mb-6 group-hover:bg-[#ffbf00] transition-colors">
                    <span class="material-symbols-outlined text-[#001a61]">insights</span>
                </div>
                <h3 class="text-xl font-bold text-[#001a61] mb-4">Comprendre</h3>
                <p class="text-[#444652] mb-6">Décryptez les tendances du marché grâce à nos analyses techniques et macro-économiques quotidiennes.</p>
                <a href="{{ route('actualites') }}" class="text-[#001a61] font-bold flex items-center gap-2 group-hover:gap-4 transition-all">
                    Accéder aux analyses <span class="material-symbols-outlined">arrow_forward</span>
                </a>
            </div>
            <div class="bg-[#f9f9ff] border border-[#c5c5d4] p-8 rounded-xl hover:shadow-lg transition-all group">
                <div class="w-12 h-12 bg-[#dce1ff] rounded-lg flex items-center justify-center mb-6 group-hover:bg-[#ffbf00] transition-colors">
                    <span class="material-symbols-outlined text-[#001a61]">account_balance_wallet</span>
                </div>
                <h3 class="text-xl font-bold text-[#001a61] mb-4">Investir</h3>
                <p class="text-[#444652] mb-6">Passez à l'action avec une plateforme sécurisée et un accompagnement personnalisé par nos conseillers.</p>
                <a href="{{ route('investir.actions-brvm') }}" class="text-[#001a61] font-bold flex items-center gap-2 group-hover:gap-4 transition-all">
                    Ouvrir un compte <span class="material-symbols-outlined">arrow_forward</span>
                </a>
            </div>
        </div>
    </section>

    {{-- Coup d'œil marché --}}
    <section class="py-16 lg:py-20 bg-[#f0f3ff] px-5 lg:px-16">
        <div class="max-w-[1280px] mx-auto">
            <div class="flex flex-col md:flex-row justify-between md:items-end gap-4 mb-8">
                <div>
                    <span class="text-[#001a61] text-sm font-medium uppercase tracking-wider">Données marché</span>
                    <h2 class="text-2xl md:text-3xl font-bold text-[#001a61] mt-2">Coup d'œil sur le Marché BRVM</h2>
                </div>
                <a href="{{ route('marches.cotations') }}" class="text-[#001a61] font-bold flex items-center gap-2">
                    Voir tous les cours <span class="material-symbols-outlined">chevron_right</span>
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <div class="lg:col-span-8 bg-white border border-[#c5c5d4] rounded-2xl p-6 lg:p-8">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-8">
                        <div>
                            <h4 class="text-xl font-bold text-[#001a61]">BRVM Composite</h4>
                            <p class="text-2xl text-[#131c2a] mt-1 font-semibold">
                                @if ($compositeLatest)
                                    {{ number_format((float) $compositeLatest->value, 2, ',', ' ') }}
                                    <span class="text-sm font-bold ml-2 {{ $compositeLatest->variation_percent >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $compositeLatest->variation_percent >= 0 ? '+' : '' }}{{ number_format((float) $compositeLatest->variation_percent, 2) }}%
                                    </span>
                                @else
                                    {{ $tickerStocks->count() }} titres suivis
                                    @if ($topGainers->isNotEmpty())
                                        <span class="text-green-600 text-sm font-bold ml-2">
                                            Top +{{ number_format((float) $topGainers->first()->variation_percent, 2) }}%
                                        </span>
                                    @endif
                                @endif
                            </p>
                            @if ($compositeLatest)
                                <p class="text-xs text-[#757683] mt-1">
                                    Historique réel · {{ $compositeLatest->snapshot_date->format('d/m/Y') }}
                                    · {{ $tickerStocks->count() }} titres suivis
                                </p>
                            @endif
                        </div>
                        <a href="{{ route('marches.indices') }}" class="text-sm font-bold text-[#001a61] hover:underline">
                            Voir les indices →
                        </a>
                    </div>

                    @if ($chartPoints->isNotEmpty())
                        <div class="h-64 flex items-end gap-1.5">
                            @foreach ($chartPoints as $point)
                                <div class="flex-1 flex flex-col justify-end items-center group relative h-full">
                                    <div class="absolute -top-1 opacity-0 group-hover:opacity-100 transition text-[10px] font-semibold text-[#001a61] whitespace-nowrap bg-white border border-[#c5c5d4] rounded px-1.5 py-0.5 shadow-sm z-10">
                                        {{ number_format($point['value'], 2, ',', ' ') }}
                                        ({{ $point['variation'] >= 0 ? '+' : '' }}{{ number_format($point['variation'], 2) }}%)
                                    </div>
                                    <div class="w-full bg-[#001a61]/25 group-hover:bg-[#001a61] transition-all rounded-t-sm"
                                        style="height: {{ $point['height'] }}%"
                                        title="{{ $point['date'] }} : {{ number_format($point['value'], 2) }}"></div>
                                </div>
                            @endforeach
                        </div>
                        <div class="flex justify-between mt-3 text-[10px] md:text-xs font-medium text-[#757683]">
                            <span>{{ $chartPoints->first()['date'] }}</span>
                            <span>{{ $chartPoints->get((int) floor(($chartPoints->count() - 1) / 2))['date'] ?? '' }}</span>
                            <span>{{ $chartPoints->last()['date'] }}</span>
                        </div>
                    @else
                        <div class="h-64 flex items-center justify-center text-[#757683] text-sm border border-dashed border-[#c5c5d4] rounded-xl">
                            Historique d'indice indisponible pour le moment
                        </div>
                    @endif
                </div>

                <div class="lg:col-span-4 flex flex-col gap-6">
                    <div class="bg-white border border-[#c5c5d4] rounded-2xl p-6 flex-1">
                        <h4 class="text-sm font-medium text-[#757683] uppercase mb-4 tracking-wide">Top Hausse</h4>
                        <div class="space-y-4">
                            @forelse ($topGainers as $stock)
                                <div class="flex justify-between items-center">
                                    <span class="font-bold">{{ $stock->symbol }}</span>
                                    <span class="text-green-600 font-semibold">
                                        +{{ number_format((float) $stock->variation_percent, 2) }}%
                                    </span>
                                </div>
                            @empty
                                <p class="text-sm text-[#444652]">Aucune donnée disponible</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="bg-white border border-[#c5c5d4] rounded-2xl p-6 flex-1">
                        <h4 class="text-sm font-medium text-[#757683] uppercase mb-4 tracking-wide">Top Baisse</h4>
                        <div class="space-y-4">
                            @forelse ($topLosers as $stock)
                                <div class="flex justify-between items-center">
                                    <span class="font-bold">{{ $stock->symbol }}</span>
                                    <span class="text-red-600 font-semibold">
                                        {{ number_format((float) $stock->variation_percent, 2) }}%
                                    </span>
                                </div>
                            @empty
                                <p class="text-sm text-[#444652]">Aucune donnée disponible</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Services Africaine des Finances --}}
    <section class="py-16 lg:py-20 px-5 lg:px-16 max-w-[1280px] mx-auto text-center">
        <div class="mb-8">
            <span class="text-[#001a61] text-sm font-medium uppercase tracking-wider">Offre</span>
            <h2 class="text-2xl md:text-3xl font-bold text-[#001a61] mt-2">Nos Services</h2>
            <p class="text-[#444652] max-w-2xl mx-auto mt-3">
                Apporteur d'affaires agréé AMF-UMOA — formations, marchés, conseil et analyses pour la zone UEMOA.
            </p>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
            @forelse (($siteServices ?? collect()) as $service)
                <a href="{{ route('service-detail', $service->slug) }}"
                    class="p-8 border border-[#c5c5d4] rounded-xl text-left bg-[#f9f9ff] hover:bg-[#001a61] group transition-all block">
                    <span class="material-symbols-outlined text-[#001a61] group-hover:text-white text-4xl mb-4">{{ $service->icon ?: 'handshake' }}</span>
                    <h3 class="text-xl font-bold text-[#001a61] group-hover:text-white mb-2">{{ $service->title }}</h3>
                    <p class="text-[#444652] group-hover:text-white/80 mb-4">{{ $service->excerpt }}</p>
                    <span class="inline-flex items-center gap-1 text-sm font-bold text-[#001a61] group-hover:text-[#ffbf00]">
                        {{ $service->cta_label ?: 'En savoir plus' }}
                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </span>
                </a>
            @empty
                <p class="col-span-full text-[#757683]">Services bientôt disponibles.</p>
            @endforelse
        </div>
        <div class="mt-10">
            <a href="{{ route('services') }}"
                class="inline-flex items-center gap-2 border border-[#001a61] text-[#001a61] font-bold px-6 py-3 rounded-lg hover:bg-[#001a61] hover:text-white transition-all">
                Voir tous les services
                <span class="material-symbols-outlined">chevron_right</span>
            </a>
        </div>
    </section>

    {{-- Parcours investisseur --}}
    <section class="py-16 lg:py-20 bg-[#001a61] text-white px-5 lg:px-16 overflow-hidden">
        <div class="max-w-[1280px] mx-auto">
            <div class="text-center mb-16 lg:mb-20">
                <h2 class="text-3xl md:text-4xl font-extrabold mb-4">Votre parcours vers l'indépendance financière</h2>
                <p class="opacity-80 max-w-2xl mx-auto">5 étapes simples pour transformer votre épargne en capital productif.</p>
            </div>
            <div class="relative flex flex-col md:flex-row justify-between gap-10 items-start">
                <div class="absolute top-8 left-0 w-full h-[2px] bg-[#ffbf00]/30 hidden md:block z-0"></div>
                @php
                    $steps = [
                        ['n' => '1', 'title' => 'Ouverture', 'text' => 'Créez votre compte en quelques minutes.', 'accent' => true],
                        ['n' => '2', 'title' => 'Diagnostic', 'text' => 'Définissez votre profil de risque avec un conseiller.'],
                        ['n' => '3', 'title' => 'Alimentation', 'text' => 'Effectuez votre premier dépôt sécurisé.'],
                        ['n' => '4', 'title' => 'Allocation', 'text' => 'Choisissez vos titres (Actions/Obligations).'],
                        ['n' => '5', 'title' => 'Suivi', 'text' => 'Gérez vos investissements via votre espace.'],
                    ];
                @endphp
                @foreach ($steps as $step)
                    <div class="z-10 flex flex-col items-center text-center max-w-[200px] mx-auto md:mx-0">
                        <div @class([
                            'w-16 h-16 rounded-full flex items-center justify-center font-bold text-xl mb-6',
                            'bg-[#ffbf00] text-[#001a61] shadow-lg' => !empty($step['accent']),
                            'bg-[#e7eeff] text-[#001a61]' => empty($step['accent']),
                        ])>{{ $step['n'] }}</div>
                        <h4 class="font-bold mb-2">{{ $step['title'] }}</h4>
                        <p class="text-sm opacity-70">{{ $step['text'] }}</p>
                    </div>
                @endforeach
            </div>
            <div class="text-center mt-12">
                <a href="{{ route('inscription') }}"
                    class="inline-flex items-center gap-2 bg-[#ffbf00] text-[#001a61] font-bold px-8 py-4 rounded-lg hover:bg-[#fbbc00] transition-all">
                    Créer mon compte
                    <span class="material-symbols-outlined">arrow_forward</span>
                </a>
            </div>
        </div>
    </section>

    {{-- Formations populaires --}}
    <section class="py-16 lg:py-20 px-5 lg:px-16 max-w-[1280px] mx-auto">
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-8">
            <h2 class="text-2xl md:text-3xl font-bold text-[#001a61]">Formations Populaires</h2>
            <a href="{{ route('formations') }}"
                class="border border-[#757683] text-[#131c2a] font-medium px-6 py-2 rounded hover:bg-[#dae3f6] transition-all text-center">
                Toutes les formations
            </a>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
                $fallbackCourses = [
                    [
                        'image' => asset('assets/images/stitch/course-1.jpg'),
                        'niveau' => 'Débutant',
                        'titre' => 'Les bases de la BRVM',
                        'desc' => 'Comprendre comment fonctionne la bourse régionale d\'Afrique de l\'Ouest.',
                        'prix' => '25.000 FCFA',
                        'link' => route('formations'),
                        'badge' => 'gold',
                    ],
                    [
                        'image' => asset('assets/images/stitch/course-2.jpg'),
                        'niveau' => 'Intermédiaire',
                        'titre' => 'Analyse Technique Avancée',
                        'desc' => 'Apprenez à lire les chandeliers japonais et les indicateurs RSI/MACD.',
                        'prix' => 'Sur devis',
                        'link' => route('formations'),
                        'badge' => 'navy',
                    ],
                    [
                        'image' => asset('assets/images/stitch/course-3.jpg'),
                        'niveau' => 'Expert',
                        'titre' => 'Gestion de Portefeuille',
                        'desc' => 'Stratégies de diversification et optimisation de vos revenus.',
                        'prix' => '50.000 FCFA',
                        'link' => route('formations'),
                        'badge' => 'dark',
                    ],
                ];
            @endphp

            @if ($formations->isNotEmpty())
                @foreach ($formations as $i => $formation)
                    @php
                        $img = $formation->image_url
                            ? (str_starts_with($formation->image_url, 'http') ? $formation->image_url : asset('storage/' . ltrim($formation->image_url, '/')))
                            : ($fallbackCourses[$i]['image'] ?? asset('assets/images/stitch/course-1.jpg'));
                        $price = $formation->isFree()
                            ? 'Gratuit'
                            : number_format((float) $formation->prix, 0, ',', ' ') . ' FCFA';
                    @endphp
                    <a href="{{ route('formation-detail', $formation->slug) }}"
                        class="overflow-hidden border border-[#c5c5d4] rounded-xl group bg-[#f9f9ff] block hover:shadow-lg transition-all">
                        <div class="h-48 overflow-hidden relative">
                            <img src="{{ $img }}" alt="{{ $formation->titre }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-all duration-500">
                            @if ($formation->niveau)
                                <div class="absolute top-4 left-4 bg-[#ffbf00] text-[#001a61] text-[10px] font-bold px-2 py-1 rounded uppercase">
                                    {{ $formation->niveau }}
                                </div>
                            @endif
                        </div>
                        <div class="p-6">
                            <h4 class="text-xl font-bold text-[#001a61] mb-2">{{ $formation->titre }}</h4>
                            <p class="text-[#444652] text-sm mb-4 line-clamp-2">{{ $formation->description_courte }}</p>
                            <div class="flex justify-between items-center pt-4 border-t border-[#c5c5d4]">
                                <span class="text-[#001a61] font-bold">{{ $price }}</span>
                                <span class="material-symbols-outlined text-[#001a61]">shopping_cart</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            @else
                @foreach ($fallbackCourses as $course)
                    <a href="{{ $course['link'] }}"
                        class="overflow-hidden border border-[#c5c5d4] rounded-xl group bg-[#f9f9ff] block hover:shadow-lg transition-all">
                        <div class="h-48 overflow-hidden relative">
                            <img src="{{ $course['image'] }}" alt="{{ $course['titre'] }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-all duration-500">
                            <div @class([
                                'absolute top-4 left-4 text-[10px] font-bold px-2 py-1 rounded uppercase',
                                'bg-[#ffbf00] text-[#001a61]' => $course['badge'] === 'gold',
                                'bg-[#001a61] text-white' => $course['badge'] === 'navy',
                                'bg-[#213371] text-[#8d9ee3]' => $course['badge'] === 'dark',
                            ])>{{ $course['niveau'] }}</div>
                        </div>
                        <div class="p-6">
                            <h4 class="text-xl font-bold text-[#001a61] mb-2">{{ $course['titre'] }}</h4>
                            <p class="text-[#444652] text-sm mb-4">{{ $course['desc'] }}</p>
                            <div class="flex justify-between items-center pt-4 border-t border-[#c5c5d4]">
                                <span class="text-[#001a61] font-bold">{{ $course['prix'] }}</span>
                                <span class="material-symbols-outlined text-[#001a61]">shopping_cart</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            @endif
        </div>
    </section>

    {{-- Avis clients --}}
    <section class="py-16 lg:py-20 px-5 lg:px-16 max-w-[1280px] mx-auto">
        <div class="text-center mb-12">
            <span class="text-[#001a61] text-sm font-medium uppercase tracking-wider">Témoignages</span>
            <h2 class="text-2xl md:text-3xl font-bold text-[#001a61] mt-2">
                Ce que disent nos <span class="text-[#ffbf00]">clients</span>
            </h2>
            <p class="text-lg text-[#444652] max-w-2xl mx-auto mt-3">
                Découvrez les témoignages de ceux qui nous font confiance
            </p>
        </div>

        @php
            $testimonials = [
                [
                    'quote' => "Grâce aux formations de Africaine des Finances, j'ai pu diversifier mon portefeuille et générer des rendements constants. L'équipe est professionnelle et toujours disponible.",
                    'name' => 'Koffi Mensah',
                    'role' => 'Investisseur privé',
                    'city' => "Abidjan, Côte d'Ivoire",
                    'seed' => 'Koffi',
                ],
                [
                    'quote' => "Les analyses de marché BRVM sont précises et m'ont aidée à prendre des décisions stratégiques pour mon entreprise. Un service incontournable pour tout professionnel de la finance.",
                    'name' => 'Aminata Diallo',
                    'role' => 'Directrice Financière',
                    'city' => 'Dakar, Sénégal',
                    'seed' => 'Aminata',
                ],
                [
                    'quote' => "Le service de conseil en investissement m'a permis d'optimiser mes placements. Les conseillers sont compétents et comprennent parfaitement les spécificités du marché africain.",
                    'name' => 'Jean-Pierre Kouassi',
                    'role' => 'Entrepreneur',
                    'city' => 'Lomé, Togo',
                    'seed' => 'Jean',
                ],
            ];
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach ($testimonials as $testimonial)
                <article class="bg-[#f9f9ff] border border-[#c5c5d4] rounded-xl p-6 hover:shadow-lg hover:border-[#001a61]/30 transition-all flex flex-col">
                    <div class="flex items-center gap-1 mb-4 text-[#ffbf00]">
                        @for ($i = 0; $i < 5; $i++)
                            <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 20;">star</span>
                        @endfor
                    </div>
                    <span class="material-symbols-outlined text-[#001a61]/20 text-4xl mb-3">format_quote</span>
                    <p class="text-[#131c2a]/90 mb-6 leading-relaxed italic flex-1">
                        "{{ $testimonial['quote'] }}"
                    </p>
                    <div class="flex items-center gap-3 pt-4 border-t border-[#c5c5d4]">
                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode($testimonial['seed']) }}"
                            alt="{{ $testimonial['name'] }}"
                            class="w-12 h-12 rounded-full bg-[#e7eeff] object-cover">
                        <div>
                            <p class="font-semibold text-[#001a61]">{{ $testimonial['name'] }}</p>
                            <p class="text-sm text-[#444652]">{{ $testimonial['role'] }}</p>
                            <p class="text-xs text-[#757683]">{{ $testimonial['city'] }}</p>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    {{-- Partenaires --}}
    <section class="py-16 bg-[#f0f3ff] grayscale opacity-80 hover:grayscale-0 hover:opacity-100 transition-all duration-700">
        <div class="max-w-[1280px] mx-auto px-5 lg:px-16 flex flex-wrap justify-center md:justify-between items-center gap-10">
            @if ($partners->isNotEmpty())
                @foreach ($partners->take(8) as $partner)
                    @if ($partner->logo_url)
                        <a href="{{ $partner->website ?: route('partenaires') }}" target="_blank" rel="noopener noreferrer"
                            class="flex items-center justify-center h-12">
                            <img src="{{ $partner->logo_url }}" alt="{{ $partner->nom }}" class="max-h-12 object-contain">
                        </a>
                    @else
                        <div class="font-extrabold text-xl md:text-2xl tracking-tighter text-[#757683]">{{ $partner->nom }}</div>
                    @endif
                @endforeach
            @else
                <div class="font-extrabold text-2xl tracking-tighter text-[#757683]">SGI CI</div>
                <div class="font-extrabold text-2xl tracking-tighter text-[#757683]">SGO PARTNER</div>
                <div class="font-extrabold text-2xl tracking-tighter text-[#757683]">AMF-UMOA</div>
                <div class="font-extrabold text-2xl tracking-tighter text-[#757683]">BRVM</div>
                <div class="font-extrabold text-2xl tracking-tighter text-[#757683]">DC/BR</div>
            @endif
        </div>
    </section>
</div>
