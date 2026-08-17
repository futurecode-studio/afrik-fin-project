@php
    $diasporaUrl = config('services.diaspora_funnel.url');
    $diasporaLabel = config('services.diaspora_funnel.label');

    $expertises = [
        ['icon' => 'handshake', 't' => 'Mise en Relation', 'd' => 'Opérations d’épargne, d’investissement et de levée de fonds sur le marché financier régional (BRVM).'],
        ['icon' => 'functions', 't' => 'Modélisation Financière', 'd' => 'Création de modèles financiers pour la valorisation et l’analyse d’investissements.'],
        ['icon' => 'account_tree', 't' => 'Ingénierie Financière', 'd' => 'Structuration de montages financiers complexes et optimisation fiscale.'],
        ['icon' => 'diversity_3', 't' => 'Inclusion Financière', 'd' => 'Démocratisation de l’accès aux marchés financiers pour tous les acteurs économiques.'],
        ['icon' => 'school', 't' => 'Formation Professionnelle', 'd' => 'Programmes certifiés en finance de marché et gestion de patrimoine.'],
    ];

    $produits = [
        ['icon' => 'candlestick_chart', 't' => 'Actions'],
        ['icon' => 'receipt_long', 't' => 'Obligations'],
        ['icon' => 'pie_chart', 't' => 'FCP / FCTC'],
        ['icon' => 'account_balance', 't' => 'BAT / OAT'],
    ];

    $etapes = [
        ['n' => '1', 't' => 'Ouvrir un Compte Titre', 'd' => 'Souscrivez auprès d’une SGI partenaire recommandée. Fournissez vos pièces d’identité (CNI, photos, IFU) et signez la convention.'],
        ['n' => '2', 't' => 'Alimenter votre Compte', 'd' => 'Effectuez vos versements par chèque, virement bancaire ou Mobile Money (Moov Money, Celtiis Cash, etc.).'],
        ['n' => '3', 't' => 'Gérer vos Investissements', 'd' => 'Gestion directe ou sous mandat. Suivez votre portefeuille via l’application dédiée de votre SGI.'],
    ];

    $raisons = [
        ['icon' => 'public', 't' => 'Expertise Locale', 'd' => 'Une connaissance approfondie des marchés financiers africains et de leurs spécificités.'],
        ['icon' => 'support_agent', 't' => 'Accompagnement Personnalisé', 'd' => 'Des conseillers dédiés pour répondre à vos besoins spécifiques.'],
        ['icon' => 'verified', 't' => 'Cadre Réglementé', 'd' => 'Activité d’apporteur d’affaires agréée AMF-UMOA — transparence et traçabilité.'],
    ];
@endphp

<div class="relative text-[#131c2a] min-h-screen overflow-x-hidden">
    {{-- HERO — identité + agrément --}}
    <section class="relative min-h-[78vh] flex items-end overflow-hidden bg-[#001a61]">
        <div class="absolute inset-0" aria-hidden="true">
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_20%_20%,rgba(255,191,0,0.28),transparent_55%),radial-gradient(ellipse_at_90%_10%,rgba(10,46,140,0.9),transparent_50%),linear-gradient(160deg,#001a61_0%,#0a2e8c_55%,#001038_100%)]"></div>
            <div class="absolute inset-0 opacity-[0.12]" style="background-image:linear-gradient(rgba(255,255,255,.08) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.08) 1px,transparent 1px);background-size:56px 56px;"></div>
            <div class="adf-orb w-[28rem] h-[28rem] bg-[#ffbf00]/25 -bottom-24 -right-16 adf-float"></div>
        </div>

        <div class="relative z-10 w-full max-w-[1280px] mx-auto px-5 lg:px-16 pt-24 pb-16 lg:pb-20">
            <div class="max-w-3xl adf-reveal">
                <p class="inline-flex items-center gap-2 text-[11px] font-extrabold uppercase tracking-[0.2em] text-[#ffbf00] mb-4">
                    <span class="material-symbols-outlined text-[16px]">verified_user</span>
                    Africaine des Finances
                </p>
                <h1 class="text-4xl md:text-5xl lg:text-[3.75rem] lg:leading-[1.08] font-extrabold text-white tracking-tight">
                    Nos Services
                </h1>
                <p class="mt-4 text-lg md:text-xl text-white/85 max-w-2xl leading-relaxed">
                    Apporteur d’affaires agréé par l’AMF-UMOA sur le marché financier régional —
                    formation, données BRVM, conseil et mise en relation avec l’écosystème agréé.
                </p>
                <div class="mt-5 inline-flex items-center gap-2 rounded-xl border border-[#ffbf00]/40 bg-[#ffbf00]/10 px-4 py-2.5 text-sm font-bold text-[#ffbf00]">
                    <span class="material-symbols-outlined text-[18px]">badge</span>
                    Agrément N° AA/2022-03
                </div>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="#catalogue" class="adf-btn-gold inline-flex items-center gap-2 px-7 py-3.5 text-base shadow-lg shadow-[#ffbf00]/20">
                        Voir le catalogue
                        <span class="material-symbols-outlined text-[20px]">south</span>
                    </a>
                    <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-xl border-2 border-white/40 text-white font-extrabold hover:bg-white hover:text-[#001a61] transition">
                        Nous contacter
                    </a>
                    <a href="{{ $diasporaUrl }}" target="_blank" rel="noopener noreferrer"
                        class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl adf-glass text-white font-bold hover:bg-white/20 transition">
                        {{ $diasporaLabel }}
                        <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Pitch institutionnel — sous les CTA, sans chevauchement --}}
    <section class="relative z-20 px-5 lg:px-16 max-w-[1280px] mx-auto mt-8 md:mt-10">
        <div class="adf-glass-strong rounded-2xl p-6 md:p-8 border border-white/60 shadow-lg adf-scroll" data-adf-reveal>
            <div class="grid md:grid-cols-3 gap-6 md:gap-8">
                @foreach ([
                    ['icon' => 'school', 't' => 'Former', 'd' => 'Parcours e-learning concrets pour lire les marchés et agir.'],
                    ['icon' => 'candlestick_chart', 't' => 'Informer', 'd' => 'Cotations, indices et analyses BRVM pour décider à froid.'],
                    ['icon' => 'handshake', 't' => 'Orienter', 'd' => 'Conseil et mise en relation avec l’écosystème agréé (SGI / SGO).'],
                ] as $i => $pillar)
                    <div class="flex gap-4 {{ $i ? 'md:border-l md:border-[#c5c5d4]/70 md:pl-8' : '' }}">
                        <span class="material-symbols-outlined text-[#001a61] text-3xl shrink-0">{{ $pillar['icon'] }}</span>
                        <div>
                            <h2 class="font-extrabold text-[#001a61] text-lg">{{ $pillar['t'] }}</h2>
                            <p class="text-sm text-[#444652] mt-1 leading-relaxed">{{ $pillar['d'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Catalogue --}}
    <section id="catalogue" class="scroll-mt-28 max-w-[1280px] mx-auto px-5 lg:px-16 py-16 lg:py-20">
        <div class="max-w-2xl adf-scroll" data-adf-reveal>
            <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-[#0a2e8c]">Catalogue</p>
            <h2 class="mt-2 text-3xl md:text-4xl font-extrabold text-[#001a61] tracking-tight">Nos offres</h2>
            <p class="mt-3 text-[#444652] text-lg">Des services concrets — du premier pas à l’accompagnement suivi.</p>
        </div>

        @if ($services->isEmpty())
            <div class="mt-10 adf-glass-strong rounded-2xl p-12 text-center border border-dashed border-[#c5c5d4]">
                <p class="text-[#444652]">Aucun service publié pour le moment.</p>
            </div>
        @else
            <div class="mt-10 grid md:grid-cols-2 gap-5 lg:gap-6">
                @foreach ($services as $index => $service)
                    @php
                        $delay = 'adf-scroll-delay-'.(($index % 4) + 1);
                        $ctaHref = $service->cta_url ?: route('service-detail', $service->slug);
                        $isExternal = $service->cta_url && Str::startsWith($service->cta_url, ['http://', 'https://'])
                            && ! Str::contains($service->cta_url, parse_url(config('app.url'), PHP_URL_HOST) ?: 'localhost');
                    @endphp
                    <article
                        class="group relative adf-glass-strong rounded-3xl border border-white/70 overflow-hidden adf-scroll {{ $delay }} hover:border-[#001a61]/40 transition duration-300"
                        data-adf-reveal
                        style="box-shadow: var(--adf-shadow)">
                        <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-[#001a61] via-[#0a2e8c] to-[#ffbf00] opacity-80"></div>
                        <div class="p-6 lg:p-8 flex flex-col h-full">
                            <div class="flex items-start justify-between gap-4">
                                <span class="inline-flex w-14 h-14 rounded-2xl bg-[#001a61] text-[#ffbf00] items-center justify-center shadow-lg shadow-[#001a61]/20 group-hover:scale-105 transition">
                                    <span class="material-symbols-outlined text-[28px]">{{ $service->icon ?: 'handshake' }}</span>
                                </span>
                            </div>

                            <h3 class="mt-5 text-2xl font-extrabold text-[#001a61] leading-snug">
                                <a href="{{ route('service-detail', $service->slug) }}" class="hover:underline decoration-[#ffbf00] underline-offset-4">
                                    {{ $service->title }}
                                </a>
                            </h3>
                            @if ($service->subtitle)
                                <p class="mt-1 text-sm font-semibold text-[#0a2e8c]">{{ $service->subtitle }}</p>
                            @endif
                            <p class="mt-3 text-[#444652] leading-relaxed">{{ plain_text($service->excerpt, 200) }}</p>

                            <div class="mt-6 flex flex-wrap items-center gap-3">
                                <a href="{{ $ctaHref }}"
                                    @if ($isExternal) target="_blank" rel="noopener noreferrer" @endif
                                    class="adf-btn-gold inline-flex items-center gap-2 px-5 py-3 text-sm">
                                    {{ $service->cta_label ?: 'En savoir plus' }}
                                    <span class="material-symbols-outlined text-[18px]">{{ $isExternal ? 'open_in_new' : 'arrow_forward' }}</span>
                                </a>
                                <a href="{{ route('service-detail', $service->slug) }}"
                                    class="inline-flex items-center gap-1 text-sm font-bold text-[#001a61] hover:gap-2 transition-all">
                                    Détails
                                    <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    {{-- Services agréés AMF-UMOA --}}
    <section id="expertises" class="scroll-mt-28 bg-[#001a61] text-white py-16 lg:py-20">
        <div class="max-w-[1280px] mx-auto px-5 lg:px-16">
            <div class="max-w-3xl adf-scroll" data-adf-reveal>
                <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-[#ffbf00]">Services agréés AMF-UMOA</p>
                <h2 class="mt-2 text-3xl md:text-4xl font-extrabold tracking-tight">Nos expertises</h2>
                <p class="mt-3 text-white/75 text-lg leading-relaxed">
                    Services autorisés par notre agrément en qualité d’apporteur d’affaires sur le marché financier régional (BRVM).
                </p>
            </div>
            <div class="mt-10 grid sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-5">
                @foreach ($expertises as $i => $item)
                    <div class="adf-glass-dark rounded-2xl border border-white/15 p-5 lg:p-6 adf-scroll adf-scroll-delay-{{ ($i % 5) + 1 }}" data-adf-reveal>
                        <span class="material-symbols-outlined text-[#ffbf00] text-3xl">{{ $item['icon'] }}</span>
                        <h3 class="mt-3 text-lg font-extrabold">{{ $item['t'] }}</h3>
                        <p class="mt-2 text-sm text-white/70 leading-relaxed">{{ $item['d'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Épargne & investissement --}}
    <section class="max-w-[1280px] mx-auto px-5 lg:px-16 py-16 lg:py-20">
        <div class="grid lg:grid-cols-2 gap-10 lg:gap-14 items-start">
            <div class="adf-scroll" data-adf-reveal>
                <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-[#0a2e8c]">Épargne &amp; investissement</p>
                <h2 class="mt-2 text-3xl md:text-4xl font-extrabold text-[#001a61] tracking-tight">Ouvrir un compte titre</h2>
                <p class="mt-3 text-[#444652] text-lg leading-relaxed">
                    Épargnez à votre rythme selon votre capacité — actions, obligations et OPCVM via nos partenaires SGI agréés.
                </p>
                <div class="mt-6 grid grid-cols-2 gap-3">
                    @foreach ($produits as $p)
                        <div class="rounded-2xl border border-[#c5c5d4] bg-white px-4 py-4 flex items-center gap-3">
                            <span class="material-symbols-outlined text-[#001a61] text-2xl">{{ $p['icon'] }}</span>
                            <span class="font-bold text-[#001a61]">{{ $p['t'] }}</span>
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('ouverture-compte-sgi') }}" class="mt-6 inline-flex items-center gap-2 font-bold text-[#001a61] hover:underline">
                    Demander l’ouverture d’un compte
                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                </a>
                <a href="{{ route('partenaires') }}" class="mt-3 ml-0 sm:ml-4 inline-flex items-center gap-2 text-sm font-semibold text-[#444652] hover:text-[#001a61] hover:underline">
                    Voir les partenaires SGI / SGO
                </a>
            </div>

            <div class="adf-scroll adf-scroll-delay-2" data-adf-reveal>
                <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-[#0a2e8c]">Comment épargner ?</p>
                <h2 class="mt-2 text-2xl md:text-3xl font-extrabold text-[#001a61]">Un processus simple et sécurisé</h2>
                <ol class="mt-6 space-y-5">
                    @foreach ($etapes as $etape)
                        <li class="flex gap-4">
                            <span class="shrink-0 w-10 h-10 rounded-full bg-[#001a61] text-[#ffbf00] font-extrabold flex items-center justify-center">{{ $etape['n'] }}</span>
                            <div>
                                <h3 class="font-extrabold text-[#001a61]">{{ $etape['t'] }}</h3>
                                <p class="mt-1 text-sm text-[#444652] leading-relaxed">{{ $etape['d'] }}</p>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>

        <div class="mt-12 rounded-2xl border border-[#ffbf00]/40 bg-gradient-to-br from-[#fff8e6] to-white p-6 md:p-8 adf-scroll" data-adf-reveal>
            <div class="flex flex-col md:flex-row md:items-start gap-4">
                <span class="material-symbols-outlined text-[#001a61] text-4xl shrink-0">security</span>
                <div>
                    <h3 class="text-xl font-extrabold text-[#001a61]">Sécurité &amp; régulation</h3>
                    <p class="mt-2 text-[#444652] leading-relaxed max-w-3xl">
                        Votre épargne est sécurisée : l’activité est strictement réglementée par l’AMF-UMOA.
                        Fiscalité avantageuse possible sur plus-values et intérêts selon le cadre en vigueur — renseignez-vous auprès de votre SGI.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Pourquoi ADF --}}
    <section class="bg-[#f0f3ff] py-16 lg:py-20">
        <div class="max-w-[1280px] mx-auto px-5 lg:px-16">
            <div class="max-w-2xl adf-scroll" data-adf-reveal>
                <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-[#0a2e8c]">Confiance</p>
                <h2 class="mt-2 text-3xl md:text-4xl font-extrabold text-[#001a61]">Pourquoi Africaine des Finances ?</h2>
            </div>
            <div class="mt-10 grid md:grid-cols-3 gap-5">
                @foreach ($raisons as $i => $r)
                    <div class="bg-white rounded-2xl border border-[#c5c5d4] p-6 adf-scroll adf-scroll-delay-{{ $i + 1 }}" data-adf-reveal>
                        <span class="material-symbols-outlined text-[#001a61] text-3xl">{{ $r['icon'] }}</span>
                        <h3 class="mt-3 text-lg font-extrabold text-[#001a61]">{{ $r['t'] }}</h3>
                        <p class="mt-2 text-sm text-[#444652] leading-relaxed">{{ $r['d'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA final --}}
    <section class="relative px-5 lg:px-16 max-w-[1280px] mx-auto py-16 lg:py-20">
        <div class="relative overflow-hidden rounded-[2rem] bg-[#001a61] text-white adf-scroll" data-adf-reveal>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_85%_20%,rgba(255,191,0,0.35),transparent_45%),radial-gradient(circle_at_10%_80%,rgba(10,46,140,0.8),transparent_50%)]" aria-hidden="true"></div>
            <div class="relative p-8 md:p-12 lg:p-14 grid lg:grid-cols-[1.4fr_1fr] gap-8 items-center">
                <div>
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-[#ffbf00]">Prêt à démarrer ?</p>
                    <h2 class="mt-3 text-3xl md:text-4xl font-extrabold leading-tight">
                        Contactez-nous pour discuter de vos besoins.
                    </h2>
                    <p class="mt-4 text-white/80 text-lg max-w-xl">
                        Un conseiller ADF vous oriente vers la bonne offre — formation, marchés, conseil ou mise en relation SGI.
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row lg:flex-col gap-3">
                    <a href="{{ route('contact') }}" class="adf-btn-gold text-center px-6 py-4 text-base inline-flex items-center justify-center gap-2">
                        Nous contacter
                        <span class="material-symbols-outlined">mail</span>
                    </a>
                    <a href="{{ route('formations') }}"
                        class="text-center px-6 py-4 rounded-xl border-2 border-white/35 font-extrabold hover:bg-white hover:text-[#001a61] transition inline-flex items-center justify-center gap-2">
                        Voir les formations
                    </a>
                    <a href="{{ $diasporaUrl }}" target="_blank" rel="noopener noreferrer"
                        class="text-center px-6 py-4 rounded-xl adf-glass font-bold hover:bg-white/20 transition inline-flex items-center justify-center gap-2">
                        {{ $diasporaLabel }}
                        <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const nodes = document.querySelectorAll('[data-adf-reveal]');
    if (!nodes.length) return;
    if (!('IntersectionObserver' in window)) {
        nodes.forEach((el) => el.classList.add('is-in'));
        return;
    }
    const io = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-in');
                io.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
    nodes.forEach((el) => io.observe(el));
});
</script>
@endpush
