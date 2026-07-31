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
                <a href="{{ config('services.diaspora_funnel.url') }}" target="_blank" rel="noopener noreferrer"
                    class="bg-[#ffbf00] text-[#261a00] font-extrabold px-8 py-4 rounded-lg hover:brightness-95 transition-all flex items-center gap-2">
                    {{ config('services.diaspora_funnel.label') }}
                    <span class="material-symbols-outlined text-[20px]">open_in_new</span>
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

    {{-- Guide Complet de la Bourse — mise en avant --}}
    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto py-12 lg:py-16">
        <div class="relative overflow-hidden rounded-3xl bg-[#001a61] text-white">
            <div class="absolute inset-0 opacity-40 pointer-events-none"
                style="background: radial-gradient(700px 320px at 100% 0%, rgba(255,191,0,.4), transparent 55%);"></div>
            <div class="relative grid lg:grid-cols-12 gap-8 p-8 md:p-12 items-center">
                <div class="lg:col-span-8">
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-[#ffbf00]">Nouveau · Éducation financière</p>
                    <h2 class="mt-3 text-3xl md:text-4xl font-extrabold tracking-tight max-w-2xl leading-tight">
                        Guide Complet de la Bourse
                    </h2>
                    <p class="mt-4 text-white/80 text-lg max-w-2xl leading-relaxed">
                        BRVM, actions, obligations, FCP… — tout ce qu’il faut savoir
                        pour démarrer sur le marché de l’UEMOA.
                    </p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('guide-bourse') }}"
                            class="inline-flex items-center gap-2 bg-[#ffbf00] text-[#261a00] font-extrabold px-6 py-3 rounded-xl hover:brightness-95 transition">
                            Lire le guide
                            <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                        </a>
                        <a href="{{ route('investir.profil-test') }}"
                            class="inline-flex items-center gap-2 border border-white/30 font-bold px-6 py-3 rounded-xl hover:bg-white/10 transition">
                            Lancer le diagnostic
                        </a>
                    </div>
                </div>
                <div class="lg:col-span-4 grid grid-cols-2 gap-3">
                    @foreach ([
                        ['icon' => 'account_balance', 'label' => 'La BRVM'],
                        ['icon' => 'candlestick_chart', 'label' => 'Actions'],
                        ['icon' => 'receipt_long', 'label' => 'Obligations'],
                        ['icon' => 'menu_book', 'label' => 'Glossaire'],
                    ] as $chip)
                        <div class="rounded-2xl bg-white/10 border border-white/15 px-4 py-4 text-center">
                            <span class="material-symbols-outlined text-[#ffbf00]">{{ $chip['icon'] }}</span>
                            <p class="mt-1 text-sm font-bold">{{ $chip['label'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Actualités mises en avant --}}
    @if($featuredArticle || ($latestArticles ?? collect())->isNotEmpty())
    <section class="py-16 lg:py-20 px-5 lg:px-16 max-w-[1280px] mx-auto">
        <div class="flex flex-col md:flex-row justify-between md:items-end gap-4 mb-8">
            <div>
                <span class="text-[#001a61] text-sm font-medium uppercase tracking-wider">À la une</span>
                <h2 class="text-2xl md:text-3xl font-bold text-[#001a61] mt-2">Actualités & analyses</h2>
                <p class="text-[#444652] mt-2 max-w-xl">Les décryptages marché UEMOA / BRVM sélectionnés par nos équipes.</p>
            </div>
            <a href="{{ route('actualites') }}" class="text-[#001a61] font-bold flex items-center gap-2">
                Toutes les actualités <span class="material-symbols-outlined">arrow_forward</span>
            </a>
        </div>

        <div class="grid lg:grid-cols-12 gap-6">
            @if($featuredArticle)
                @php
                    $heroImg = $featuredArticle->image_url ?: asset('assets/images/stitch/hero.jpg');
                    if ($featuredArticle->image_url && !str_starts_with($featuredArticle->image_url, 'http') && !str_contains($featuredArticle->image_url, '/storage/')) {
                        $heroImg = asset('storage/'.$featuredArticle->image_url);
                    }
                @endphp
                <a href="{{ route('actualite-detail', $featuredArticle->slug) }}"
                    class="lg:col-span-7 group relative overflow-hidden rounded-2xl border border-[#c5c5d4] bg-[#001a61] min-h-[320px] lg:min-h-[420px] block">
                    <img src="{{ $heroImg }}" alt="{{ $featuredArticle->titre }}"
                        class="absolute inset-0 w-full h-full object-cover opacity-70 group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#001a61] via-[#001a61]/50 to-transparent"></div>
                    <div class="absolute inset-x-0 bottom-0 p-6 lg:p-8 text-white">
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            <span class="text-[10px] font-extrabold uppercase tracking-wide px-2 py-1 rounded bg-[#ffbf00] text-[#001a61]">À la une</span>
                            @if($featuredArticle->categorie)
                                <span class="text-xs opacity-90">{{ $featuredArticle->categorie }}</span>
                            @endif
                            @if($featuredArticle->published_at)
                                <span class="text-xs opacity-70">{{ $featuredArticle->published_at->format('d/m/Y') }}</span>
                            @endif
                        </div>
                        <h3 class="text-2xl lg:text-3xl font-extrabold leading-tight group-hover:text-[#ffbf00] transition-colors">
                            {{ $featuredArticle->titre }}
                        </h3>
                        @if($featuredArticle->extrait)
                            <p class="mt-3 text-sm lg:text-base text-white/85 line-clamp-2 max-w-2xl">{{ plain_text($featuredArticle->extrait, 160) }}</p>
                        @endif
                    </div>
                </a>
            @endif

            <div class="@if($featuredArticle) lg:col-span-5 @else lg:col-span-12 @endif flex flex-col gap-3">
                @forelse($latestArticles as $article)
                    <a href="{{ route('actualite-detail', $article->slug) }}"
                        class="flex gap-4 p-4 rounded-xl border border-[#c5c5d4] bg-white hover:border-[#001a61]/40 hover:shadow-md transition-all group">
                        @php
                            $thumb = null;
                            if ($article->image_url) {
                                $thumb = str_starts_with($article->image_url, 'http') || str_contains($article->image_url, '/storage/')
                                    ? $article->image_url
                                    : asset('storage/'.$article->image_url);
                            }
                        @endphp
                        @if($thumb)
                            <img src="{{ $thumb }}" alt="" class="w-20 h-20 rounded-lg object-cover shrink-0 border border-[#c5c5d4]">
                        @else
                            <div class="w-20 h-20 rounded-lg bg-[#eef3ff] shrink-0 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[#001a61]">newspaper</span>
                            </div>
                        @endif
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 text-[11px] text-[#757683] mb-1">
                                @if($article->categorie)<span class="font-bold text-[#001a61]">{{ $article->categorie }}</span>@endif
                                @if($article->published_at)<span>{{ $article->published_at->format('d/m/Y') }}</span>@endif
                            </div>
                            <h4 class="font-bold text-[#001a61] leading-snug group-hover:underline line-clamp-2">{{ $article->titre }}</h4>
                            @if($article->extrait)
                                <p class="text-xs text-[#444652] mt-1 line-clamp-2">{{ plain_text($article->extrait, 100) }}</p>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="p-6 rounded-xl border border-dashed border-[#c5c5d4] text-[#757683] text-sm">
                        D’autres analyses seront bientôt publiées.
                    </div>
                @endforelse
            </div>
        </div>
    </section>
    @endif

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
                <a href="{{ route('ouverture-compte-sgi') }}" class="text-[#001a61] font-bold flex items-center gap-2 group-hover:gap-4 transition-all">
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
                    <p class="text-[#444652] mt-2 text-sm">Indice Composite, volumes et variations — mise en avant graphique.</p>
                </div>
                <a href="{{ route('marches.cotations') }}" class="text-[#001a61] font-bold flex items-center gap-2">
                    Voir tous les cours <span class="material-symbols-outlined">chevron_right</span>
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <div class="lg:col-span-8 bg-white border border-[#c5c5d4] rounded-2xl p-6 lg:p-8">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-6">
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
                                @endif
                            </p>
                            @if ($compositeLatest)
                                <p class="text-xs text-[#757683] mt-1">
                                    Historique {{ count($chartLabels) }} séances · {{ $compositeLatest->snapshot_date->format('d/m/Y') }}
                                </p>
                            @endif
                        </div>
                        <a href="{{ route('marches.indices') }}" class="text-sm font-bold text-[#001a61] hover:underline">
                            Voir les indices →
                        </a>
                    </div>

                    @if (!empty($chartLabels) && !empty($chartValues))
                        <div class="w-full min-w-0" style="position: relative; height: 288px;">
                            <canvas id="homeBrvmChart" style="width:100%;height:100%;display:block;" aria-label="Graphique BRVM Composite"></canvas>
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
                        <div class="space-y-3">
                            @forelse ($topGainers->take(5) as $stock)
                                <div class="flex justify-between items-center gap-2">
                                    <div class="min-w-0">
                                        <span class="font-bold text-[#001a61]">{{ $stock->symbol }}</span>
                                        <p class="text-[10px] text-[#757683] truncate">{{ $stock->company_name }}</p>
                                    </div>
                                    <span class="text-green-600 font-semibold text-sm shrink-0">
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
                        <div class="space-y-3">
                            @forelse ($topLosers->take(5) as $stock)
                                <div class="flex justify-between items-center gap-2">
                                    <div class="min-w-0">
                                        <span class="font-bold text-[#001a61]">{{ $stock->symbol }}</span>
                                        <p class="text-[10px] text-[#757683] truncate">{{ $stock->company_name }}</p>
                                    </div>
                                    <span class="text-red-600 font-semibold text-sm shrink-0">
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

            @if(($volumeLeaders ?? collect())->isNotEmpty())
            <div class="mt-6 w-full bg-white border border-[#c5c5d4] rounded-2xl p-6 lg:p-8">
                <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-3 mb-6">
                    <h4 class="text-lg font-bold text-[#001a61]">Volumes — titres les plus échangés</h4>
                    <a href="{{ route('investir.actions-brvm') }}" class="text-sm font-bold text-[#001a61] hover:underline">Marché actions →</a>
                </div>
                <div class="w-full min-w-0" style="position: relative; height: 280px;">
                    <canvas id="homeVolumeChart" style="width:100%;height:100%;display:block;" aria-label="Volumes BRVM"></canvas>
                </div>
            </div>
            @endif
        </div>
    </section>

    @include('partials.home-marketmap')

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
                    <p class="text-[#444652] group-hover:text-white/80 mb-4">{{ plain_text($service->excerpt, 160) }}</p>
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
                            <p class="text-[#444652] text-sm mb-4 line-clamp-2">{{ plain_text($formation->description_courte, 140) }}</p>
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

    {{-- Avis clients — carrousel immersif --}}
    @php
        $testimonials = [
            [
                'quote' => "Grâce aux formations de Africaine des Finances, j'ai pu diversifier mon portefeuille et générer des rendements constants. L'équipe est professionnelle et toujours disponible.",
                'name' => 'Koffi Mensah',
                'role' => 'Investisseur privé',
                'city' => "Abidjan, Côte d'Ivoire",
                'seed' => 'Koffi',
                'tag' => 'Formations',
            ],
            [
                'quote' => "Les analyses de marché BRVM sont précises et m'ont aidée à prendre des décisions stratégiques pour mon entreprise. Un service incontournable pour tout professionnel de la finance.",
                'name' => 'Aminata Diallo',
                'role' => 'Directrice Financière',
                'city' => 'Dakar, Sénégal',
                'seed' => 'Aminata',
                'tag' => 'Analyses',
            ],
            [
                'quote' => "Le service de conseil en investissement m'a permis d'optimiser mes placements. Les conseillers sont compétents et comprennent parfaitement les spécificités du marché africain.",
                'name' => 'Jean-Pierre Kouassi',
                'role' => 'Entrepreneur',
                'city' => 'Lomé, Togo',
                'seed' => 'Jean',
                'tag' => 'Conseil',
            ],
            [
                'quote' => "La plateforme est claire, les cotations BRVM sont à jour, et le suivi personnalisé change vraiment la donne pour un investisseur particulier comme moi.",
                'name' => 'Fatou Bamba',
                'role' => 'Cadre bancaire',
                'city' => 'Abidjan, Côte d\'Ivoire',
                'seed' => 'Fatou',
                'tag' => 'Marchés',
            ],
            [
                'quote' => "J’ai commencé sans expérience boursière. En quelques mois, j’ai compris les indices, les volumes et les opportunités UEMOA grâce à leur pédagogie.",
                'name' => 'Ibrahim Sow',
                'role' => 'Jeune investisseur',
                'city' => 'Ouagadougou, Burkina Faso',
                'seed' => 'Ibrahim',
                'tag' => 'Académie',
            ],
        ];
    @endphp

    <section
        class="relative overflow-hidden bg-[#001a61] text-white py-16 lg:py-24"
        x-data="homeAvis(@js($testimonials))"
        @mouseenter="pause()"
        @mouseleave="resume()"
    >
        <div class="pointer-events-none absolute inset-0" aria-hidden="true">
            <div class="absolute -top-24 -left-16 w-80 h-80 rounded-full bg-[#ffbf00]/15 blur-3xl adf-orb"></div>
            <div class="absolute -bottom-28 -right-10 w-96 h-96 rounded-full bg-[#0a2e8c]/80 blur-3xl" style="animation-delay:1.5s"></div>
            <div class="absolute inset-0 opacity-[0.07]" style="background-image:radial-gradient(circle at 1px 1px,#fff 1px,transparent 0);background-size:28px 28px;"></div>
        </div>

        <div class="relative max-w-[1100px] mx-auto px-5 lg:px-16">
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-10 lg:mb-14">
                <div>
                    <p class="text-[#ffbf00] text-xs font-extrabold uppercase tracking-[0.2em]">Témoignages</p>
                    <h2 class="mt-2 text-3xl md:text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight">
                        La confiance se lit<br class="hidden sm:block">
                        <span class="text-[#ffbf00]">dans leurs mots.</span>
                    </h2>
                </div>
                <div class="flex items-center gap-4 shrink-0">
                    <div class="text-right">
                        <p class="text-2xl font-extrabold tabular-nums leading-none">4,9<span class="text-[#ffbf00]">/5</span></p>
                        <div class="flex justify-end gap-0.5 mt-1 text-[#ffbf00]">
                            @for ($i = 0; $i < 5; $i++)
                                <span class="material-symbols-outlined text-[16px]" style="font-variation-settings:'FILL' 1;">star</span>
                            @endfor
                        </div>
                        <p class="text-[11px] text-white/60 mt-1">Satisfaction clients</p>
                    </div>
                    <div class="hidden sm:block w-px h-12 bg-white/20"></div>
                    <p class="hidden sm:block text-sm text-white/70 max-w-[10rem] leading-snug">Des parcours réels sur la BRVM & l’UEMOA.</p>
                </div>
            </div>

            <div class="relative min-h-[280px] md:min-h-[300px]">
                <span class="material-symbols-outlined absolute -top-2 left-0 text-[72px] md:text-[96px] text-[#ffbf00]/25 select-none leading-none" aria-hidden="true">format_quote</span>

                <template x-for="(t, i) in items" :key="t.seed">
                    <div
                        x-show="active === i"
                        x-transition:enter="transition ease-out duration-500"
                        x-transition:enter-start="opacity-0 translate-y-4"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-300 absolute inset-0"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-3"
                        class="relative pl-2 md:pl-4"
                    >
                        <span
                            class="inline-flex items-center rounded-full border border-[#ffbf00]/40 bg-[#ffbf00]/10 text-[#ffbf00] text-[10px] font-extrabold uppercase tracking-wider px-2.5 py-1 mb-5"
                            x-text="t.tag"
                        ></span>
                        <blockquote class="text-xl md:text-2xl lg:text-[1.75rem] lg:leading-snug font-medium text-white/95 max-w-3xl">
                            <span class="text-[#ffbf00]">“</span><span x-text="t.quote"></span><span class="text-[#ffbf00]">”</span>
                        </blockquote>

                        <div class="mt-8 flex flex-wrap items-center gap-4">
                            <img
                                :src="'https://api.dicebear.com/7.x/avataaars/svg?seed=' + encodeURIComponent(t.seed)"
                                :alt="t.name"
                                class="w-14 h-14 rounded-full bg-[#0a2e8c] ring-2 ring-[#ffbf00] object-cover"
                            >
                            <div>
                                <p class="font-extrabold text-lg leading-tight" x-text="t.name"></p>
                                <p class="text-sm text-white/70" x-text="t.role + ' · ' + t.city"></p>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="mt-10 flex flex-col sm:flex-row sm:items-center justify-between gap-5 border-t border-white/15 pt-6">
                <div class="flex items-center gap-2 overflow-x-auto pb-1" style="-ms-overflow-style:none;scrollbar-width:none;">
                    <template x-for="(t, i) in items" :key="'nav-'+t.seed">
                        <button
                            type="button"
                            @click="go(i)"
                            class="shrink-0 group flex items-center gap-2 rounded-full border px-1.5 py-1.5 pr-3 transition-all"
                            :class="active === i
                                ? 'bg-white text-[#001a61] border-white shadow-lg scale-105'
                                : 'bg-white/5 border-white/20 text-white/80 hover:bg-white/10'"
                            :aria-pressed="active === i"
                        >
                            <img
                                :src="'https://api.dicebear.com/7.x/avataaars/svg?seed=' + encodeURIComponent(t.seed)"
                                :alt="t.name"
                                class="w-9 h-9 rounded-full bg-[#0a2e8c] object-cover"
                                :class="active === i ? 'ring-2 ring-[#ffbf00]' : 'opacity-80 group-hover:opacity-100'"
                            >
                            <span class="text-xs font-bold whitespace-nowrap hidden sm:inline" x-text="t.name.split(' ')[0]"></span>
                        </button>
                    </template>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <button type="button" @click="prev()"
                        class="w-10 h-10 rounded-full border border-white/25 hover:bg-white/10 transition-colors flex items-center justify-center"
                        aria-label="Avis précédent">
                        <span class="material-symbols-outlined">chevron_left</span>
                    </button>
                    <p class="text-xs font-bold tabular-nums text-white/60 w-10 text-center">
                        <span x-text="active + 1"></span>/<span x-text="items.length"></span>
                    </p>
                    <button type="button" @click="next()"
                        class="w-10 h-10 rounded-full border border-white/25 hover:bg-white/10 transition-colors flex items-center justify-center"
                        aria-label="Avis suivant">
                        <span class="material-symbols-outlined">chevron_right</span>
                    </button>
                </div>
            </div>

            {{-- Barre de progression autoplay --}}
            <div class="mt-5 h-0.5 w-full bg-white/10 rounded-full overflow-hidden">
                <div class="h-full bg-[#ffbf00] origin-left"
                    :style="`width:${progress}%`; transition: width 80ms linear;"></div>
            </div>
        </div>
    </section>

    @once
    <script>
    window.homeAvis = function (items) {
        return {
            items: items || [],
            active: 0,
            progress: 0,
            timer: null,
            tick: null,
            paused: false,
            duration: 6500,

            init() {
                this.resume();
            },

            go(i) {
                this.active = i;
                this.progress = 0;
                this.resume();
            },

            next() {
                this.active = (this.active + 1) % this.items.length;
                this.progress = 0;
            },

            prev() {
                this.active = (this.active - 1 + this.items.length) % this.items.length;
                this.progress = 0;
            },

            pause() {
                this.paused = true;
                this.clearTimers();
            },

            resume() {
                this.paused = false;
                this.clearTimers();
                const step = 80;
                const inc = (step / this.duration) * 100;
                this.tick = setInterval(() => {
                    if (this.paused) return;
                    this.progress = Math.min(100, this.progress + inc);
                    if (this.progress >= 100) {
                        this.next();
                    }
                }, step);
            },

            clearTimers() {
                if (this.tick) clearInterval(this.tick);
                this.tick = null;
            },

            destroy() {
                this.clearTimers();
            }
        };
    };
    </script>
    @endonce

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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {
    const labels = @json($chartLabels ?? []);
    const values = @json($chartValues ?? []);
    const volumeLeaders = @json(collect($volumeLeaders ?? [])->values()->all());

    let homeLineChart = null;
    let homeVolumeChart = null;

    function destroyChart(instance) {
        if (instance) {
            try { instance.destroy(); } catch (_) {}
        }
        return null;
    }

    function initHomeCharts() {
        if (typeof Chart === 'undefined') return;

        const lineCtx = document.getElementById('homeBrvmChart');
        if (lineCtx && labels.length && values.length) {
            homeLineChart = destroyChart(homeLineChart);
            const parent = lineCtx.parentElement;
            if (parent) {
                lineCtx.style.width = '100%';
                lineCtx.style.height = '100%';
                lineCtx.removeAttribute('width');
                lineCtx.removeAttribute('height');
            }
            const up = values.length > 1 ? values[values.length - 1] >= values[0] : true;
            homeLineChart = new Chart(lineCtx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label: 'BRVM Composite',
                        data: values,
                        borderColor: '#001a61',
                        backgroundColor: (ctx) => {
                            const {chart} = ctx;
                            const {ctx: c, chartArea} = chart;
                            if (!chartArea) return 'rgba(0,26,97,0.12)';
                            const g = c.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
                            g.addColorStop(0, up ? 'rgba(0,26,97,0.28)' : 'rgba(220,38,38,0.18)');
                            g.addColorStop(1, 'rgba(0,26,97,0)');
                            return g;
                        },
                        borderWidth: 2.5,
                        pointRadius: 0,
                        pointHoverRadius: 4,
                        pointHoverBackgroundColor: '#ffbf00',
                        tension: 0.35,
                        fill: true,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    resizeDelay: 50,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#001a61',
                            titleFont: { weight: 'bold' },
                            callbacks: {
                                label: (item) => ' ' + Number(item.raw).toLocaleString('fr-FR', { minimumFractionDigits: 2 }) + ' pts'
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: '#757683', maxRotation: 0, autoSkip: true, maxTicksLimit: 6 }
                        },
                        y: {
                            grid: { color: 'rgba(197,197,212,0.45)' },
                            ticks: { color: '#757683' }
                        }
                    }
                }
            });
        }

        const volCtx = document.getElementById('homeVolumeChart');
        if (volCtx && volumeLeaders.length) {
            homeVolumeChart = destroyChart(homeVolumeChart);
            volCtx.style.width = '100%';
            volCtx.style.height = '100%';
            volCtx.removeAttribute('width');
            volCtx.removeAttribute('height');

            homeVolumeChart = new Chart(volCtx, {
                type: 'bar',
                data: {
                    labels: volumeLeaders.map(s => s.symbol),
                    datasets: [{
                        label: 'Volume',
                        data: volumeLeaders.map(s => s.volume),
                        backgroundColor: volumeLeaders.map(s => s.variation >= 0 ? 'rgba(0,26,97,0.85)' : 'rgba(220,38,38,0.7)'),
                        borderRadius: 8,
                        maxBarThickness: 72,
                        categoryPercentage: 0.7,
                        barPercentage: 0.85,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    resizeDelay: 50,
                    layout: { padding: { top: 8, right: 8, bottom: 4, left: 4 } },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#001a61',
                            callbacks: {
                                afterLabel: (item) => {
                                    const s = volumeLeaders[item.dataIndex];
                                    return [
                                        'Cours: ' + Number(s.price).toLocaleString('fr-FR') + ' FCFA',
                                        'Var: ' + (s.variation >= 0 ? '+' : '') + Number(s.variation).toFixed(2) + '%'
                                    ];
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: '#001a61', font: { weight: 'bold', size: 12 } }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(197,197,212,0.45)' },
                            ticks: {
                                color: '#757683',
                                callback: (v) => Number(v).toLocaleString('fr-FR')
                            }
                        }
                    }
                }
            });

            requestAnimationFrame(() => {
                if (homeVolumeChart) homeVolumeChart.resize();
                if (homeLineChart) homeLineChart.resize();
            });
        }
    }

    function boot() {
        // Laisser le layout CSS s’appliquer avant de mesurer la largeur
        requestAnimationFrame(() => setTimeout(initHomeCharts, 30));
    }

    document.addEventListener('DOMContentLoaded', boot);
    document.addEventListener('livewire:navigated', boot);
    window.addEventListener('resize', () => {
        if (homeVolumeChart) homeVolumeChart.resize();
        if (homeLineChart) homeLineChart.resize();
    });
})();
</script>
@endpush
