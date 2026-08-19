<div class="bg-[#f9f9ff] text-[#131c2a] overflow-x-hidden">
    {{-- 1. Hero carrousel 4 slides --}}
    @php
        $heroSlides = [
            [
                'image' => asset('assets/images/hero-01-actionnaire.png'),
                'imagePosition' => 'object-cover object-[center_20%] lg:object-right',
                'overlay' => 'light',
                'title' => 'Devenez actionnaire en 2 jours',
                'text' => 'Découvrez les opportunités de la BRVM avec un accompagnement clair.',
            ],
            [
                'image' => asset('assets/images/hero-02-epargne.jpg'),
                'imagePosition' => 'object-cover',
                'overlay' => 'default',
                'title' => 'Faites fructifier votre épargne grâce aux opportunités de la BRVM',
                'text' => 'Actions, obligations et FCP : construisez une stratégie adaptée à votre profil.',
            ],
            [
                'image' => asset('assets/images/hero-03-patrimoine.jpg'),
                'imagePosition' => 'object-cover',
                'overlay' => 'default',
                'title' => 'Investissez aujourd’hui pour bâtir votre patrimoine de demain',
                'text' => 'Une approche pédagogique pour transformer l’épargne en capital productif.',
            ],
            [
                'image' => asset('assets/images/hero-04-equipe.jpg'),
                'imagePosition' => 'object-cover',
                'overlay' => 'default',
                'title' => 'Votre allié financier d’opportunité et de solution',
                'text' => 'Africaine des Finances — apporteur d’affaires agréé AMF-UMOA.',
            ],
        ];
    @endphp

    <section
        class="relative min-h-[78vh] lg:min-h-[88vh] overflow-hidden"
        x-data="{
            slides: {{ count($heroSlides) }},
            active: 0,
            timer: null,
            init() {
                this.timer = setInterval(() => { this.active = (this.active + 1) % this.slides }, 6500);
            },
            go(i) { this.active = i; },
            next() { this.active = (this.active + 1) % this.slides; },
            prev() { this.active = (this.active - 1 + this.slides) % this.slides; }
        }"
    >
        @foreach ($heroSlides as $i => $slide)
            <div
                x-show="active === {{ $i }}"
                x-transition:enter="transition ease-out duration-700"
                x-transition:enter-start="opacity-0 scale-105"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-500 absolute inset-0"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="absolute inset-0"
                @if ($i !== 0) x-cloak @endif
            >
                <img src="{{ $slide['image'] }}" alt="" class="absolute inset-0 w-full h-full {{ $slide['imagePosition'] ?? 'object-cover' }}">
                <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
                    @if (($slide['overlay'] ?? 'default') === 'light')
                        <div class="absolute inset-0 bg-black/25"></div>
                        <div class="absolute inset-0 bg-gradient-to-r from-[#001a61]/82 via-[#001a61]/45 to-transparent"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-[#001a61]/55 via-transparent to-[#001a61]/20"></div>
                    @else
                        <div class="absolute inset-0 bg-black/45"></div>
                        <div class="absolute inset-0 bg-gradient-to-r from-[#001a61]/88 via-[#001a61]/62 to-[#001a61]/50"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-[#001a61]/80 via-transparent to-[#001a61]/35"></div>
                    @endif
                </div>
            </div>
        @endforeach

        <div class="relative z-10 max-w-[1280px] mx-auto px-5 lg:px-16 min-h-[78vh] lg:min-h-[88vh] flex flex-col pt-28 lg:pt-36 pb-16 lg:pb-20">
            <div class="bg-[#ffbf00] text-[#261a00] inline-flex items-center px-3 py-1 rounded-full gap-2 w-fit mb-8 lg:mb-10">
                <span class="material-symbols-outlined text-[18px]">verified_user</span>
                <span class="text-sm font-bold tracking-wide">Agrément AMF-UMOA AA/2022-03</span>
            </div>

            @foreach ($heroSlides as $i => $slide)
                <div x-show="active === {{ $i }}" @if ($i !== 0) x-cloak @endif class="max-w-3xl">
                    <h1 class="text-4xl md:text-5xl lg:text-[52px] lg:leading-[1.1] font-extrabold text-white tracking-tight [text-shadow:0_2px_24px_rgba(0,26,97,0.65)]">
                        {{ $slide['title'] }}
                    </h1>
                    <p class="mt-5 text-lg text-white/90 max-w-xl leading-relaxed [text-shadow:0_1px_12px_rgba(0,26,97,0.7)]">{{ $slide['text'] }}</p>
                </div>
            @endforeach

            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('formations') }}"
                    class="bg-[#ffbf00] text-[#261a00] font-extrabold px-7 py-3.5 rounded-lg hover:brightness-95 transition-all inline-flex items-center gap-2">
                    Voir les formations
                    <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                </a>
                <a href="{{ config('services.diaspora_funnel.url') }}" target="_blank" rel="noopener noreferrer"
                    class="border border-white/40 text-white font-bold px-7 py-3.5 rounded-lg hover:bg-white/10 transition-all inline-flex items-center gap-2">
                    {{ config('services.diaspora_funnel.label') }}
                </a>
            </div>

            <div class="mt-12 flex items-center gap-4">
                <button type="button" @click="prev()" class="w-10 h-10 rounded-full border border-white/30 text-white hover:bg-white/10 flex items-center justify-center" aria-label="Slide précédent">
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>
                <div class="flex gap-2">
                    @foreach ($heroSlides as $i => $slide)
                        <button type="button" @click="go({{ $i }})"
                            class="h-1.5 rounded-full transition-all"
                            :class="active === {{ $i }} ? 'w-8 bg-[#ffbf00]' : 'w-3 bg-white/40'"
                            aria-label="Slide {{ $i + 1 }}"></button>
                    @endforeach
                </div>
                <button type="button" @click="next()" class="w-10 h-10 rounded-full border border-white/30 text-white hover:bg-white/10 flex items-center justify-center" aria-label="Slide suivant">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
            </div>
        </div>
    </section>

    {{-- 2. Simulateur + CTA souscrire / compte FCP --}}
    <section class="py-16 lg:py-20 bg-[#f0f3ff] px-5 lg:px-16" id="simulateur">
        <div class="max-w-[1280px] mx-auto"
            x-data="{
                capital: 1000000,
                versement: 50000,
                annees: 10,
                type: 'Actions',
                rates: { Actions: 13, Obligations: 6, Diversifies: 8 },
                get rate() { return this.rates[this.type] || 7; },
                get months() { return Math.max(0, this.annees) * 12; },
                get invested() { return Math.max(0, this.capital) + Math.max(0, this.versement) * this.months; },
                get future() {
                    const r = this.rate / 100 / 12;
                    let v = Math.max(0, this.capital);
                    const m = Math.max(0, this.versement);
                    for (let i = 0; i < this.months; i++) { v = (v + m) * (1 + r); }
                    return Math.round(v);
                },
                get gain() { return Math.max(0, this.future - this.invested); },
                fmt(n) { return Number(n).toLocaleString('fr-FR'); }
            }">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
                <div>
                    <span class="text-[#001a61] text-sm font-medium uppercase tracking-wider">Outils</span>
                    <h2 class="text-2xl md:text-3xl font-bold text-[#001a61] mt-2">Simulateur de croissance</h2>
                    <p class="text-[#444652] mt-2 max-w-xl">Projection indicative — hors frais et fiscalité.</p>
                </div>
                <a href="{{ route('outils.interets-composes') }}" class="text-[#001a61] font-bold inline-flex items-center gap-1">
                    Version complète <span class="material-symbols-outlined">arrow_forward</span>
                </a>
            </div>

            <div class="grid lg:grid-cols-2 gap-6">
                <div class="bg-white border border-[#c5c5d4] rounded-2xl p-6 space-y-5">
                    <div>
                        <label class="block text-xs font-semibold tracking-wider text-[#757683] mb-2">Simulez votre investissement parmi une sélection de nos FCP : Actions 13% prévisionnel, Obligations 6% et Diversifiés 8%.</label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <button type="button" @click="type = 'Actions'"
                                :class="type === 'Actions' ? 'bg-[#001a61] text-white' : 'bg-[#f9f9ff] text-[#001a61] border border-[#c5c5d4]'"
                                class="rounded-lg px-3 py-2.5 text-sm font-bold transition text-left">
                                <span class="block">FCP Actions</span>
                                <span class="block text-xs font-semibold mt-0.5 opacity-90">13 % prévisionnel</span>
                            </button>
                            <button type="button" @click="type = 'Obligations'"
                                :class="type === 'Obligations' ? 'bg-[#001a61] text-white' : 'bg-[#f9f9ff] text-[#001a61] border border-[#c5c5d4]'"
                                class="rounded-lg px-3 py-2.5 text-sm font-bold transition text-left">
                                <span class="block">FCP Obligations</span>
                                <span class="block text-xs font-semibold mt-0.5 opacity-90">6 %</span>
                            </button>
                            <button type="button" @click="type = 'Diversifies'"
                                :class="type === 'Diversifies' ? 'bg-[#001a61] text-white' : 'bg-[#f9f9ff] text-[#001a61] border border-[#c5c5d4]'"
                                class="rounded-lg px-3 py-2.5 text-sm font-bold transition text-left">
                                <span class="block">FCP Diversifiés</span>
                                <span class="block text-xs font-semibold mt-0.5 opacity-90">8 %</span>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold tracking-wider uppercase text-[#757683] mb-1">Capital initial (FCFA)</label>
                        <input type="number" x-model.number="capital" min="0" class="w-full rounded-lg border border-[#c5c5d4] px-3 py-2.5 bg-[#f9f9ff] focus:border-[#001a61] outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold tracking-wider uppercase text-[#757683] mb-1">Versement mensuel (FCFA)</label>
                        <input type="number" x-model.number="versement" min="0" class="w-full rounded-lg border border-[#c5c5d4] px-3 py-2.5 bg-[#f9f9ff] focus:border-[#001a61] outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold tracking-wider uppercase text-[#757683] mb-1">Durée (années)</label>
                        <input type="number" x-model.number="annees" min="0" max="50" class="w-full rounded-lg border border-[#c5c5d4] px-3 py-2.5 bg-[#f9f9ff] focus:border-[#001a61] outline-none">
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="bg-[#001a61] text-white rounded-2xl p-6">
                        <p class="text-sm text-white/70">Capital estimé à terme</p>
                        <p class="text-3xl font-extrabold mt-1"><span x-text="fmt(future)"></span> <span class="text-base font-semibold">FCFA</span></p>
                        <p class="mt-2 text-xs text-white/60">Taux prévisionnel <span x-text="rate"></span> % / an · hors frais et fiscalité</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white border border-[#c5c5d4] rounded-xl p-5">
                            <p class="text-xs text-[#757683]">Montant investi</p>
                            <p class="text-xl font-bold text-[#001a61] mt-1" x-text="fmt(invested)"></p>
                        </div>
                        <div class="bg-white border border-[#c5c5d4] rounded-xl p-5">
                            <p class="text-xs text-[#757683]">Intérêts estimés</p>
                            <p class="text-xl font-bold text-[#0a2e8c] mt-1" x-text="fmt(gain)"></p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-3 pt-2">
                        <a href="{{ route('inscription') }}"
                            class="inline-flex items-center gap-2 bg-[#ffbf00] text-[#261a00] font-extrabold px-5 py-3 rounded-xl hover:brightness-95 transition">
                            S’inscrire
                            <span class="material-symbols-outlined text-[18px]">person_add</span>
                        </a>
                        <a href="{{ route('mise-en-relation') }}"
                            class="inline-flex items-center gap-2 bg-[#001a61] text-white font-bold px-5 py-3 rounded-xl hover:bg-[#0a2e8c] transition">
                            Être accompagné
                        </a>
                    </div>
                    <p class="text-xs text-[#757683]">Simulation pédagogique. Les performances passées ne préjugent pas des performances futures.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- 4. Qui sommes-nous — vision, mission, équipe, ce que nous faisons --}}
    <section class="py-16 lg:py-20 bg-white px-5 lg:px-16" id="qui-sommes-nous">
        <div class="max-w-[1280px] mx-auto">
            <div class="mb-10">
                <span class="text-[#001a61] text-sm font-medium uppercase tracking-wider">Qui sommes-nous</span>
                <h2 class="text-2xl md:text-3xl font-bold text-[#001a61] mt-2">Africaine des Finances</h2>
                <p class="text-[#444652] mt-2 max-w-2xl">Cabinet de conseils et d’ingénierie financière, apporteur d’affaires agréé AMF-UMOA sur le marché BRVM.</p>
            </div>

            <div class="grid md:grid-cols-2 gap-6 mb-10">
                <article class="rounded-2xl border border-[#c5c5d4] bg-[#f9f9ff] p-6 lg:p-8">
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-[#0a2e8c]">Vision</p>
                    <h3 class="mt-2 text-xl font-extrabold text-[#001a61]">Une finance accessible et pédagogique</h3>
                    <p class="mt-3 text-[#444652] leading-relaxed">
                        Rapprocher les épargnants du marché financier régional pour bâtir un patrimoine durable, avec clarté et responsabilité.
                    </p>
                </article>
                <article class="rounded-2xl border border-[#c5c5d4] bg-[#f9f9ff] p-6 lg:p-8">
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-[#0a2e8c]">Mission</p>
                    <h3 class="mt-2 text-xl font-extrabold text-[#001a61]">Vulgariser et accompagner</h3>
                    <p class="mt-3 text-[#444652] leading-relaxed">
                        Vulgariser l’investissement sur le marché financier régional et accompagner particuliers, entreprises, institutions et associations dans leurs projets d’épargne et de valorisation de patrimoine.
                    </p>
                </article>
            </div>

            <div class="mb-12">
                <h3 class="text-lg font-extrabold text-[#001a61] mb-5">Ce que nous faisons</h3>
                <div class="grid md:grid-cols-3 gap-5">
                    @foreach ($pillars as $pillar)
                        <article class="rounded-2xl border border-[#c5c5d4] bg-[#f9f9ff] p-6">
                            <span class="material-symbols-outlined text-3xl text-[#ffbf00]">{{ $pillar['icon'] }}</span>
                            <h4 class="mt-3 font-extrabold text-[#001a61]">{{ $pillar['title'] }}</h4>
                            <p class="mt-2 text-sm text-[#444652] leading-relaxed">{{ $pillar['text'] }}</p>
                        </article>
                    @endforeach
                </div>
            </div>

            <div class="mb-12 rounded-2xl border border-[#c5c5d4] bg-[#f9f9ff] p-6">
                <h3 class="text-lg font-extrabold text-[#001a61]">Valeurs</h3>
                <p class="mt-2 text-sm text-[#444652]">Une approche simple : pédagogie, transparence et proximité.</p>
                <div class="mt-4 flex flex-wrap gap-2.5">
                    @foreach (collect($values)->take(3) as $value)
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-[#c5c5d4] bg-white px-3 py-1.5 text-xs font-semibold text-[#001a61]">
                            <span class="material-symbols-outlined text-[16px] text-[#ffbf00]">{{ $value['icon'] }}</span>
                            {{ $value['title'] }}
                        </span>
                    @endforeach
                </div>
            </div>

            <div>
                <h3 class="text-lg font-extrabold text-[#001a61] mb-5">L’équipe</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
                    @foreach ($teamPreview as $member)
                        <article class="bg-[#f9f9ff] border border-[#c5c5d4] rounded-xl overflow-hidden">
                            <div class="h-32 sm:h-36 md:h-32 lg:h-40 xl:h-44 bg-[#e7eeff] overflow-hidden">
                                <img src="{{ asset($member['image']) }}" alt="{{ $member['name'] }}" class="w-full h-full object-cover object-top">
                            </div>
                            <div class="p-3 md:p-3.5">
                                <p class="font-bold text-[#001a61] text-xs sm:text-sm leading-snug">{{ $member['name'] }}</p>
                                <p class="text-[11px] sm:text-xs text-[#757683] mt-0.5">{{ $member['role'] }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
                <div class="mt-6 md:mt-8">
                    <a href="{{ route('team') }}"
                        class="inline-flex items-center gap-2 bg-[#001a61] text-white font-bold px-5 py-2.5 md:px-6 md:py-3 rounded-xl hover:bg-[#0a2e8c] transition text-sm md:text-base">
                        Nous découvrir
                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- 5. Apprendre — Webinaire (hebdo gratuit) + Formation --}}
    <section class="py-16 lg:py-20 px-5 lg:px-16 max-w-[1280px] mx-auto" id="apprendre">
        <div class="mb-8">
            <span class="text-[#001a61] text-sm font-medium uppercase tracking-wider">Apprendre</span>
            <h2 class="text-2xl md:text-3xl font-bold text-[#001a61] mt-2">Webinaire & formation</h2>
            <p class="text-[#444652] mt-2 max-w-xl">Deux portes d’entrée : une rencontre hebdomadaire gratuite, puis des parcours pour aller plus loin.</p>
        </div>

        <div class="grid lg:grid-cols-2 gap-6 mb-8">
            <div class="rounded-3xl bg-[#001a61] text-white p-7 lg:p-8 flex flex-col">
                <p class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-[#ffbf00]">Hebdomadaire · Gratuit</p>
                <h3 class="mt-3 text-2xl font-extrabold">Webinaire</h3>
                <p class="mt-3 text-white/80 leading-relaxed flex-1">
                    Une mini-formation chaque semaine pour toucher le marché, comprendre une thématique et oser la prochaine étape.
                </p>
                <a href="{{ route('events-list', ['filterFormat' => 'online']) }}"
                    class="mt-6 inline-flex items-center gap-2 bg-[#ffbf00] text-[#261a00] font-extrabold px-5 py-3 rounded-xl hover:brightness-95 transition w-fit">
                    Rejoindre le prochain webinaire
                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                </a>
            </div>

            <div class="rounded-3xl border border-[#c5c5d4] bg-white p-7 lg:p-8 flex flex-col">
                <p class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-[#0a2e8c]">Parcours structurés</p>
                <h3 class="mt-3 text-2xl font-extrabold text-[#001a61]">Formation</h3>
                <p class="mt-3 text-[#444652] leading-relaxed flex-1">
                    Des modules ciblés pour approfondir : BRVM, actions, obligations, FCP — à votre rythme.
                </p>
                @if ($formations->isNotEmpty())
                    <div class="mt-5 space-y-3">
                        @foreach ($formations->take(2) as $formation)
                            <a href="{{ route('formation-detail', $formation->slug) }}" class="block rounded-xl border border-[#c5c5d4] bg-[#f9f9ff] px-4 py-3 hover:border-[#001a61]/40 transition">
                                <p class="font-bold text-[#001a61] line-clamp-1">{{ $formation->titre }}</p>
                                <p class="text-xs text-[#757683] mt-0.5 line-clamp-1">{{ plain_text($formation->description_courte, 80) }}</p>
                            </a>
                        @endforeach
                    </div>
                @endif
                <a href="{{ route('formations') }}"
                    class="mt-6 inline-flex items-center gap-2 bg-[#001a61] text-white font-bold px-5 py-3 rounded-xl hover:bg-[#0a2e8c] transition w-fit">
                    Voir les formations
                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                </a>
            </div>
        </div>
    </section>

    {{-- 6. Événements --}}
    <section class="py-16 lg:py-20 bg-[#f0f3ff] px-5 lg:px-16" id="evenements">
        <div class="max-w-[1280px] mx-auto">
            <div class="mb-10">
                <span class="text-[#0a2e8c] text-sm font-extrabold uppercase tracking-[0.18em]">Événements</span>
                <h2 class="text-2xl md:text-3xl font-extrabold text-[#001a61] mt-2">Des rencontres qui font avancer le marché financier régional</h2>
                <p class="text-[#444652] mt-2 max-w-2xl">Découvrez nos événements, rencontres et initiatives dédiés à l'information, à la formation et au développement des acteurs du marché financier.</p>
            </div>

            {{-- 6a. Événements réalisés --}}
            @if ($pastEvents->isNotEmpty())
                <div class="mb-12">
                    <h3 class="text-lg font-extrabold text-[#001a61] mb-5 inline-flex items-center gap-2">
                        <span class="material-symbols-outlined text-[#ffbf00]">photo_camera</span>
                        Événements réalisés
                    </h3>

                    @foreach ($pastEvents as $pastEvent)
                        @php
                            $pastImg = $pastEvent->featured_image
                                ? (str_starts_with($pastEvent->featured_image, 'http') ? $pastEvent->featured_image : (str_starts_with($pastEvent->featured_image, 'assets/') ? asset($pastEvent->featured_image) : asset('storage/'.$pastEvent->featured_image)))
                                : ($pastEvent->galleries->first() ? asset($pastEvent->galleries->first()->image_path) : null);
                        @endphp
                        <article class="rounded-2xl border border-[#c5c5d4] bg-white overflow-hidden">
                            <div class="grid lg:grid-cols-5">
                                <div class="lg:col-span-2 h-64 lg:h-auto min-h-[260px] bg-[#e7eeff] relative overflow-hidden">
                                    @if ($pastImg)
                                        <img src="{{ $pastImg }}" alt="{{ $pastEvent->title }}" class="absolute inset-0 w-full h-full object-cover">
                                    @else
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <span class="material-symbols-outlined text-7xl text-[#001a61]/30">photo_camera</span>
                                        </div>
                                    @endif
                                    <span class="absolute top-4 left-4 px-3 py-1.5 rounded-lg bg-[#001a61] text-white text-xs font-extrabold uppercase tracking-wide">
                                        Terminé
                                    </span>
                                </div>
                                <div class="lg:col-span-3 p-6 lg:p-8 flex flex-col justify-center">
                                    <h4 class="text-xl lg:text-2xl font-extrabold text-[#001a61]">{{ $pastEvent->title }}</h4>
                                    <div class="mt-3 flex flex-wrap gap-4 text-sm text-[#757683]">
                                        <span class="inline-flex items-center gap-1.5">
                                            <span class="material-symbols-outlined text-base text-[#001a61]">calendar_month</span>
                                            {{ optional($pastEvent->starts_at)->translatedFormat('j F Y') }}
                                        </span>
                                        @if ($pastEvent->city)
                                            <span class="inline-flex items-center gap-1.5">
                                                <span class="material-symbols-outlined text-base text-[#001a61]">location_on</span>
                                                {{ $pastEvent->city }}{{ $pastEvent->country ? ', '.$pastEvent->country : ''}}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="mt-3 text-[#444652] text-sm leading-relaxed line-clamp-3">{{ plain_text($pastEvent->description, 250) }}</p>
                                    <a href="{{ route('event-detail', $pastEvent->slug) }}" class="mt-4 inline-flex items-center gap-2 text-sm font-bold text-[#001a61] hover:underline w-fit">
                                        Voir les photos
                                        <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                                    </a>
                                </div>
                            </div>

                            {{-- Galerie visible uniquement sur /evenements, pas sur l'accueil --}}
                        </article>
                    @endforeach
                </div>
            @endif

            {{-- 6b. Prochains événements --}}
            <div>
                <h3 class="text-lg font-extrabold text-[#001a61] mb-5 inline-flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#ffbf00]">event</span>
                    Prochains événements
                </h3>

                @forelse ($upcomingEvents as $event)
                    <article class="rounded-2xl bg-[#001a61] text-white overflow-hidden">
                        <div class="grid lg:grid-cols-2">
                            @if ($event->featured_image)
                                <div class="min-h-[280px] lg:min-h-full bg-[#0a2e8c]">
                                    <img src="{{ str_starts_with($event->featured_image, 'http') ? $event->featured_image : (str_starts_with($event->featured_image, 'assets/') ? asset($event->featured_image) : asset('storage/'.$event->featured_image)) }}"
                                        alt="{{ $event->title }}" class="w-full h-full object-cover min-h-[280px] lg:min-h-[400px]">
                                </div>
                            @endif
                            <div class="p-7 lg:p-10 flex flex-col justify-center {{ $event->featured_image ? ''  : 'lg:col-span-2' }}">
                                <p class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-[#ffbf00]">
                                    {{ $event->category ?: 'Événement phare' }}
                                </p>
                                <h4 class="mt-3 text-2xl md:text-3xl font-extrabold leading-tight">{{ $event->title }}</h4>
                                <div class="mt-4 flex flex-wrap gap-4 text-sm text-white/70">
                                    <span class="inline-flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-base text-[#ffbf00]">calendar_month</span>
                                        {{ optional($event->starts_at)->translatedFormat('l j F Y') }}
                                    </span>
                                    @if ($event->city || $event->location_name)
                                        <span class="inline-flex items-center gap-1.5">
                                            <span class="material-symbols-outlined text-base text-[#ffbf00]">location_on</span>
                                            {{ $event->location_name ?? $event->city }}
                                        </span>
                                    @endif
                                </div>
                                <p class="mt-4 text-white/80 leading-relaxed text-base flex-1">
                                    {{ plain_text($event->description, 220) }}
                                </p>
                                <a href="{{ route('event-detail', $event->slug) }}"
                                    class="mt-6 inline-flex items-center gap-2 bg-[#ffbf00] text-[#261a00] font-extrabold px-6 py-3.5 rounded-xl hover:brightness-95 transition w-fit text-base">
                                    S'inscrire à l'événement
                                    <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-xl border border-dashed border-[#c5c5d4] p-8 text-center text-[#757683] text-sm bg-white">
                        Aucun événement programmé pour le moment.
                    </div>
                @endforelse
            </div>

            <div class="mt-8 text-center">
                <a href="{{ route('events-list') }}" class="inline-flex items-center gap-2 text-[#001a61] font-bold hover:underline">
                    Voir tous les événements
                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                </a>
            </div>
        </div>
    </section>

    {{-- 7. Partenaires --}}
    <section class="py-16 lg:py-20 bg-white px-5 lg:px-16 max-w-[1280px] mx-auto overflow-hidden">
        <div class="text-center mb-10">
            <span class="text-[#001a61] text-sm font-medium uppercase tracking-wider">Partenaires</span>
            <h2 class="text-2xl md:text-3xl font-bold text-[#001a61] mt-2">Un écosystème agréé</h2>
        </div>
        @include('partials.partner-logos', ['partners' => $partners, 'linkToList' => true])
        <div class="mt-8 text-center">
            <a href="{{ route('partenaires') }}" class="text-[#001a61] font-bold inline-flex items-center gap-1">
                Tous les partenaires <span class="material-symbols-outlined">arrow_forward</span>
            </a>
        </div>
    </section>

    {{-- 8. Actions BRVM — ticker + graphique Composite + top hausse --}}
    <section class="py-16 lg:py-20 px-5 lg:px-16 max-w-[1280px] mx-auto" id="actions">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
            <div>
                <span class="text-[#001a61] text-sm font-medium uppercase tracking-wider">Actions BRVM</span>
                <h2 class="text-2xl md:text-3xl font-bold text-[#001a61] mt-2">
                    {{ $stockCount ?: 47 }} sociétés cotées
                </h2>
                <p class="text-[#444652] mt-2 max-w-xl">Découvrez chaque titre, suivez l’évolution du marché, puis passez à l’action.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="#actions-marche"
                    class="border border-[#001a61] text-[#001a61] font-bold px-5 py-2.5 rounded-lg hover:bg-[#e7eeff] transition inline-flex items-center gap-2">
                    Découvrir
                    <span class="material-symbols-outlined text-[18px]">search</span>
                </a>
                <a href="{{ route('mise-en-relation') }}"
                    class="bg-[#001a61] text-white font-bold px-5 py-2.5 rounded-lg hover:bg-[#0a2e8c] transition inline-flex items-center gap-2">
                    Créer un compte-titres
                    <span class="material-symbols-outlined text-[18px]">account_balance</span>
                </a>
            </div>
        </div>

        <div class="bg-[#001a61] text-white py-3 overflow-hidden rounded-xl mb-6">
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
                        <div class="px-8 text-sm opacity-80">Marché BRVM — données en cours de chargement</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6" id="actions-marche">
            <div class="lg:col-span-8 bg-[#f0f3ff] border border-[#c5c5d4] rounded-2xl p-6 lg:p-8">
                <div class="flex flex-col sm:flex-row justify-between sm:items-start gap-4 mb-6">
                    <div>
                        <h4 class="text-xl font-bold text-[#001a61]">BRVM Composite</h4>
                        <p class="text-2xl text-[#131c2a] mt-1 font-semibold">
                            @if ($compositeLatest)
                                {{ number_format((float) $compositeLatest->value, 2, ',', ' ') }}
                                <span class="text-sm font-bold ml-2 {{ $compositeLatest->variation_percent >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $compositeLatest->variation_percent >= 0 ? '+' : '' }}{{ number_format((float) $compositeLatest->variation_percent, 2) }}%
                                </span>
                            @else
                                Indice en cours de chargement
                            @endif
                        </p>
                    </div>
                </div>
                @if (! empty($chartLabels) && ! empty($chartValues))
                    <div class="w-full min-w-0" style="position: relative; height: 260px;">
                        <canvas id="homeBrvmChart" style="width:100%;height:100%;display:block;" aria-label="Graphique BRVM Composite"></canvas>
                    </div>
                @else
                    <div class="h-52 flex items-center justify-center text-[#757683] text-sm border border-dashed border-[#c5c5d4] rounded-xl bg-white/60">
                        Historique d’indice indisponible pour le moment
                    </div>
                @endif
            </div>
            <div class="lg:col-span-4">
                <div class="bg-[#f0f3ff] border border-[#c5c5d4] rounded-2xl p-6 h-full">
                    <h4 class="text-sm font-medium text-[#757683] uppercase mb-4 tracking-wide">Top hausse</h4>
                    <div class="space-y-4">
                        @forelse ($topGainers as $stock)
                            <div class="flex justify-between items-center gap-2">
                                <span class="font-bold text-[#001a61]">{{ $stock->symbol }}</span>
                                <span class="text-green-600 font-semibold text-sm">+{{ number_format((float) $stock->variation_percent, 2) }}%</span>
                            </div>
                        @empty
                            <p class="text-sm text-[#444652]">Aucune donnée</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 10. Actualités (légères) --}}
    @if ($featuredArticle || ($latestArticles ?? collect())->isNotEmpty())
    <section class="py-16 lg:py-20 bg-[#f0f3ff] px-5 lg:px-16 max-w-[1280px] mx-auto">
        <div class="flex flex-col md:flex-row justify-between md:items-end gap-4 mb-8">
            <div>
                <span class="text-[#001a61] text-xs font-semibold uppercase tracking-[0.22em]">Pour aller plus loin</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-[#001a61] mt-2">Actualités</h2>
            </div>
            <a href="{{ route('actualites') }}" class="text-[#001a61] font-bold flex items-center gap-2">
                Toutes les actualités <span class="material-symbols-outlined">arrow_forward</span>
            </a>
        </div>
        <div class="grid md:grid-cols-3 gap-5">
            @foreach (($featuredArticle ? collect([$featuredArticle])->merge($latestArticles) : $latestArticles)->take(3) as $article)
                <a href="{{ route('actualite-detail', $article->slug) }}"
                    class="rounded-3xl border border-[#c5c5d4] bg-white p-6 md:p-7 min-h-[220px] flex flex-col justify-between hover:border-[#001a61]/30 hover:shadow-lg transition group">
                    <div>
                        <p class="text-[11px] font-bold text-[#0a2e8c] uppercase tracking-[0.18em]">{{ $article->categorie ?: 'Analyse' }}</p>
                        <h3 class="mt-4 text-xl md:text-2xl font-extrabold leading-snug text-[#001a61] group-hover:underline line-clamp-3">{{ $article->titre }}</h3>
                    </div>
                    <span class="mt-6 inline-flex items-center gap-2 text-sm font-bold text-[#001a61]">
                        Lire l’actualité
                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </span>
                </a>
            @endforeach
        </div>
    </section>
    @endif
</div>

@push('scripts')
<script>
(function () {
    const labels = @json($chartLabels ?? []);
    const values = @json($chartValues ?? []);
    let homeLineChart = null;

    function initHomeCharts() {
        if (typeof Chart === 'undefined') return;
        const lineCtx = document.getElementById('homeBrvmChart');
        if (!lineCtx || !labels.length || !values.length) return;
        if (homeLineChart) { try { homeLineChart.destroy(); } catch (_) {} }
        const up = values.length > 1 ? values[values.length - 1] >= values[0] : true;
        homeLineChart = new Chart(lineCtx, {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    label: 'BRVM Composite',
                    data: values,
                    borderColor: up ? '#16a34a' : '#dc2626',
                    backgroundColor: up ? 'rgba(22,163,74,0.12)' : 'rgba(220,38,38,0.12)',
                    borderWidth: 2.5,
                    pointRadius: 0,
                    pointHoverRadius: 4,
                    tension: 0.35,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#757683', maxTicksLimit: 6 } },
                    y: { grid: { color: 'rgba(197,197,212,0.45)' }, ticks: { color: '#757683' } }
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => requestAnimationFrame(() => setTimeout(initHomeCharts, 30)));
    document.addEventListener('livewire:navigated', () => requestAnimationFrame(() => setTimeout(initHomeCharts, 30)));
})();
</script>
@endpush
