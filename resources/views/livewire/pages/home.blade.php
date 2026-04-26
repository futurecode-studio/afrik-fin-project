<main class="flex-1">
    <section class="relative w-full flex items-center overflow-hidden" style="height:100vh; min-height:100vh;" 
        x-data="{
            currentSlide: 0,
            paused: false,
            slides: [
                {
                    image: '{{ asset('assets/images/img_1.png') }}',
                    eyebrow: 'Expertise Financière',
                    title: 'Mikwabo, Africaine des Finances',
                    subtitle: 'Accompagnement sur mesure pour investisseurs, entreprises et institutions de la zone UEMOA.',
                    cta: 'Découvrir nos services',
                    link: '{{ route('services') }}',
                    ctaSecondary: 'Nous contacter',
                    linkSecondary: '{{ route('contact') }}'
                },
                {
                    image: '{{ asset('assets/images/img_2.png') }}',
                    eyebrow: 'Bourse & Investissement',
                    title: 'Investissement Stratégique',
                    subtitle: 'Données de marché en temps réel, analyses expertes et conseils personnalisés pour maximiser vos rendements.',
                    cta: 'Commencer à investir',
                    link: '{{ route('investir.actions-brvm') }}',
                    ctaSecondary: 'Voir les cotations',
                    linkSecondary: '{{ route('services-bourse') }}'
                },
                {
                    image: '{{ asset('assets/images/img_3.png') }}',
                    eyebrow: 'Formation Certifiante',
                    title: 'Formation d\'Excellence',
                    subtitle: 'Formations certifiantes en analyse financière, trading et gestion de portefeuille, dispensées par des experts de terrain.',
                    cta: 'Explorer les formations',
                    link: '{{ route('formations') }}',
                    ctaSecondary: 'Voir le programme',
                    linkSecondary: '{{ route('services-formation') }}'
                }
            ],
            init() {
                setInterval(() => {
                    if (!this.paused) this.next();
                }, 5000);
            },
            next() {
                this.currentSlide = (this.currentSlide + 1) % this.slides.length;
            },
            prev() {
                this.currentSlide = (this.currentSlide - 1 + this.slides.length) % this.slides.length;
            },
            pad(n) { return String(n).padStart(2, '0'); }
        }"
        @mouseenter="paused = true" @mouseleave="paused = false">
        
        <!-- Background Slides -->
        <template x-for="(slide, index) in slides" :key="index">
            <div class="absolute inset-0 transition-opacity duration-1000 ease-in-out"
                x-show="currentSlide === index"
                x-transition:enter="transition ease-out duration-1000"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-1000"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0">
                <img :src="slide.image" :alt="slide.title" class="w-full h-full object-cover" style="filter: contrast(1.08) saturate(1.15) brightness(0.97); transform: translateZ(0); image-rendering: -webkit-optimize-contrast;">
                <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-black/40 to-black/20"></div>
            </div>
        </template>

        <!-- Decorative Elements -->
        <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
            <div class="absolute top-20 left-10 w-72 h-72 bg-secondary/20 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-20 right-10 w-96 h-96 bg-accent/20 rounded-full blur-3xl animate-pulse delay-1000"></div>
        </div>

        <!-- Content -->
        <div class="absolute inset-0 z-10 flex items-center" style="padding-top:80px;">
            <div class="container mx-auto px-4 lg:px-16">
            <template x-for="(slide, index) in slides" :key="index">
                <div class="max-w-3xl" x-show="currentSlide === index"
                    x-transition:enter="transition ease-out duration-700 delay-300"
                    x-transition:enter-start="opacity-0 translate-y-8"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-(-8)">
                    
                    <!-- Badge de confiance (pill sombre avec icône dorée) -->
                    <div class="inline-flex items-center gap-2.5 px-5 py-2.5 bg-primary-dark/90 backdrop-blur-sm rounded-full mb-8 border border-secondary/40 shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-secondary shrink-0">
                            <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path>
                            <path d="m9 12 2 2 4-4"></path>
                        </svg>
                        <span class="text-sm md:text-base font-bold text-white tracking-wide whitespace-nowrap">Agréé AMF-UMOA N° AA/2022-03</span>
                    </div>

                    <h1 class="text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-extrabold text-white mb-6 leading-[1.05] tracking-tight drop-shadow-[0_4px_12px_rgba(0,0,0,0.6)]" x-text="slide.title"></h1>
                    
                    <p class="text-lg md:text-xl lg:text-2xl text-white/90 mb-10 leading-relaxed max-w-2xl font-light drop-shadow-[0_2px_6px_rgba(0,0,0,0.5)]" x-text="slide.subtitle"></p>
                    
                    <!-- CTA principal -->
                    <div>
                        <a :href="slide.link">
                            <button class="inline-flex items-center justify-center gap-3 whitespace-nowrap font-bold bg-secondary text-secondary-foreground hover:bg-secondary-light shadow-glow hover:shadow-elegant hover:scale-[1.02] transition-all duration-300 h-14 md:h-16 rounded-xl px-8 md:px-10 text-base md:text-lg group">
                                <span x-text="slide.cta"></span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 group-hover:translate-x-1 transition-transform">
                                    <path d="M5 12h14"></path>
                                    <path d="m12 5 7 7-7 7"></path>
                                </svg>
                            </button>
                        </a>
                    </div>
                </div>
            </template>
            </div>
        </div>

        <!-- Indicateur numérique & progress -->
        <div class="absolute bottom-12 right-6 lg:right-16 z-20 hidden md:flex items-center gap-4 text-white">
            <span class="text-5xl lg:text-6xl font-extrabold tabular-nums" x-text="pad(currentSlide + 1)"></span>
            <div class="w-20 lg:w-28 h-[2px] bg-white/30 relative overflow-hidden">
                <div class="absolute inset-y-0 left-0 bg-secondary transition-all duration-500"
                     :style="'width: ' + ((currentSlide + 1) / slides.length * 100) + '%'"></div>
            </div>
            <span class="text-lg lg:text-xl font-medium text-white/60 tabular-nums" x-text="pad(slides.length)"></span>
        </div>

        <!-- Navigation Buttons -->
        <button @click="prev()" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-12 h-12 rounded-full bg-black/20 backdrop-blur-sm border border-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-all hidden md:flex group">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left w-6 h-6 group-hover:-translate-x-0.5 transition-transform">
                <path d="m15 18-6-6 6-6"/>
            </svg>
        </button>
        <button @click="next()" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-12 h-12 rounded-full bg-black/20 backdrop-blur-sm border border-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-all hidden md:flex group">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right w-6 h-6 group-hover:translate-x-0.5 transition-transform">
                <path d="m9 18 6-6-6-6"/>
            </svg>
        </button>

        <!-- Dots -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-20 flex gap-3">
            <template x-for="(slide, index) in slides" :key="index">
                <button @click="currentSlide = index" 
                    class="h-1.5 rounded-full transition-all duration-300"
                    :class="currentSlide === index ? 'w-8 bg-secondary' : 'w-2 bg-white/50 hover:bg-white/80'">
                </button>
            </template>
        </div>
    </section>
    <section class="py-20 bg-muted/30">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Nos <span
                        class="text-primary">Services</span></h2>
                <p class="text-lg text-muted-foreground max-w-2xl mx-auto">Solutions professionnelles pour
                    investisseurs, traders et entreprises de la zone UEMOA</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <a class="group" href="{{ route('services-formation') }}">
                    <div
                        class="rounded-2xl border bg-card text-card-foreground shadow-sm h-full p-8 hover:shadow-elegant hover:-translate-y-2 transition-all duration-300 border-border hover:border-primary/30 group-hover:bg-accent/5">
                        <div class="flex flex-col items-center text-center space-y-4">
                            <div
                                class="w-24 h-24 rounded-2xl bg-gradient-to-br from-primary to-primary-light p-1 shadow-card group-hover:shadow-glow transition-smooth">
                                <div
                                    class="w-full h-full rounded-xl bg-card flex items-center justify-center">
                                    <img src="{{ asset('assets/elearning-icon.png') }}"
                                        alt="Formation Professionnelle" class="w-16 h-16 object-contain">
                                </div>
                            </div>
                            <h3 class="text-xl font-bold group-hover:text-primary transition-smooth">
                                Formation Professionnelle</h3>
                            <p class="text-muted-foreground leading-relaxed">Programmes certifiants en
                                analyse financière, trading BRVM, gestion de portefeuille et finance
                                d'entreprise. Cours conçus par des experts des marchés africains.</p><span
                                class="text-primary font-medium group-hover:gap-2 flex items-center gap-1 transition-smooth">En
                                savoir plus<span
                                    class="group-hover:translate-x-1 transition-smooth">→</span></span>
                        </div>
                    </div>
                </a>
                <a class="group" href="{{ route('services-bourse') }}">
                    <div
                        class="rounded-2xl border bg-card text-card-foreground shadow-sm h-full p-8 hover:shadow-elegant hover:-translate-y-2 transition-all duration-300 border-border hover:border-primary/30 group-hover:bg-accent/5">
                        <div class="flex flex-col items-center text-center space-y-4">
                            <div
                                class="w-24 h-24 rounded-2xl bg-gradient-to-br from-accent to-accent-light p-1 shadow-card group-hover:shadow-glow transition-smooth">
                                <div
                                    class="w-full h-full rounded-xl bg-card flex items-center justify-center">
                                    <img src="{{ asset('assets/market-data-icon.png') }}"
                                        alt="Données &amp; Cotations BRVM" class="w-16 h-16 object-contain">
                                </div>
                            </div>
                            <h3 class="text-xl font-bold group-hover:text-primary transition-smooth">Données
                                &amp; Cotations BRVM</h3>
                            <p class="text-muted-foreground leading-relaxed">Flux en temps réel des 45
                                valeurs cotées, indices BRVM Composite et BRVM 30, historiques de cours,
                                carnets d'ordres et analyses techniques quotidiennes.</p><span
                                class="text-primary font-medium group-hover:gap-2 flex items-center gap-1 transition-smooth">En
                                savoir plus<span
                                    class="group-hover:translate-x-1 transition-smooth">→</span></span>
                        </div>
                    </div>
                </a><a class="group" href="{{ route('services-conseil') }}">
                    <div
                        class="rounded-2xl border bg-card text-card-foreground shadow-sm h-full p-8 hover:shadow-elegant hover:-translate-y-2 transition-all duration-300 border-border hover:border-primary/30 group-hover:bg-accent/5">
                        <div class="flex flex-col items-center text-center space-y-4">
                            <div
                                class="w-24 h-24 rounded-2xl bg-gradient-to-br from-secondary to-secondary-light p-1 shadow-card group-hover:shadow-glow transition-smooth">
                                <div
                                    class="w-full h-full rounded-xl bg-card flex items-center justify-center">
                                    <img src="{{ asset('assets/consulting-icon.png') }}"
                                        alt="Conseil &amp; Gestion"
                                        class="w-16 h-16 object-contain"></div>
                            </div>
                            <h3 class="text-xl font-bold group-hover:text-primary transition-smooth">Conseil
                                &amp; Gestion</h3>
                            <p class="text-muted-foreground leading-relaxed">Accompagnement personnalisé en
                                stratégie d'investissement, allocation d'actifs, analyse de risque et
                                optimisation fiscale pour particuliers et institutionnels.</p><span
                                class="text-primary font-medium group-hover:gap-2 flex items-center gap-1 transition-smooth">En
                                savoir plus<span
                                    class="group-hover:translate-x-1 transition-smooth">→</span></span>
                        </div>
                    </div>
                </a></div>
        </div>
    </section>
    <section class="py-20 bg-background">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Pourquoi <span class="text-primary">Investir</span> ?</h2>
                <p class="text-lg text-muted-foreground max-w-2xl mx-auto">Préparez votre avenir et réalisez vos projets grâce à l'investissement financier</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-card p-6 rounded-xl border border-border shadow-sm hover:shadow-elegant transition-smooth group">
                    <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center mb-4 group-hover:bg-primary/20 transition-smooth">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-graduation-cap w-6 h-6 text-primary">
                            <path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"></path>
                            <path d="M22 10v6"></path>
                            <path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Éducation & Avenir</h3>
                    <p class="text-sm text-muted-foreground leading-relaxed">Garantissez les meilleures études supérieures et formations professionnelles pour vos enfants.</p>
                </div>
                <div class="bg-card p-6 rounded-xl border border-border shadow-sm hover:shadow-elegant transition-smooth group">
                    <div class="w-12 h-12 bg-secondary/10 rounded-lg flex items-center justify-center mb-4 group-hover:bg-secondary/20 transition-smooth">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-palmtree w-6 h-6 text-secondary">
                            <path d="M13 8c0-2.76-2.46-5-5.5-5S2 5.24 2 8h2l1-1 1 1h4"></path>
                            <path d="M13 7.14A5.82 5.82 0 0 1 16.5 6c3.04 0 5.5 2.24 5.5 5h-3l-1-1-1 1h-3"></path>
                            <path d="M5.89 9.71c-2.15 2.15-2.3 5.47-.35 7.43l4.24-4.25.7-.7.71-.71 2.12-2.12c-1.95-1.96-5.27-1.81-7.42.35z"></path>
                            <path d="M11 15.5c.5 2.5-.17 4.5-1 6.5h4c2-5.5-.5-12-1-14"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Retraite Paisible</h3>
                    <p class="text-sm text-muted-foreground leading-relaxed">Constituez un capital pour vous offrir une retraite confortable et augmenter votre rente.</p>
                </div>
                <div class="bg-card p-6 rounded-xl border border-border shadow-sm hover:shadow-elegant transition-smooth group">
                    <div class="w-12 h-12 bg-accent/10 rounded-lg flex items-center justify-center mb-4 group-hover:bg-accent/20 transition-smooth">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-briefcase w-6 h-6 text-accent">
                            <rect width="20" height="14" x="2" y="6" rx="2" ry="2"></rect>
                            <path d="M16 2v4"></path>
                            <path d="M8 2v4"></path>
                            <path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Entrepreneuriat</h3>
                    <p class="text-sm text-muted-foreground leading-relaxed">Disposez du capital nécessaire pour démarrer votre propre business ou activité.</p>
                </div>
                <div class="bg-card p-6 rounded-xl border border-border shadow-sm hover:shadow-elegant transition-smooth group">
                    <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center mb-4 group-hover:bg-primary/20 transition-smooth">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-home w-6 h-6 text-primary">
                            <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Projets de Vie</h3>
                    <p class="text-sm text-muted-foreground leading-relaxed">Financez vos projets immobiliers, mariages, voyages et autres événements marquants.</p>
                </div>
            </div>
        </div>
    </section>
    <section class="py-20 bg-gradient-hero">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-primary-foreground mb-4">Notre <span
                        class="text-secondary">Impact</span></h2>
                <p class="text-lg text-primary-foreground/90 max-w-2xl mx-auto">Des chiffres qui témoignent
                    de notre engagement et de notre expertise</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    class="rounded-lg border text-card-foreground shadow-sm p-6 bg-primary-foreground/10 backdrop-blur-sm border-primary-foreground/20 hover:bg-primary-foreground/20 transition-smooth group">
                    <div class="flex items-start gap-4">
                        <div
                            class="w-12 h-12 bg-secondary/20 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-secondary/30 transition-smooth">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-users w-6 h-6 text-secondary">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg></div>
                        <div class="flex-1">
                            <p class="text-3xl font-bold text-primary-foreground mb-1">2,500+</p>
                            <p class="text-base font-semibold text-secondary mb-1">Clients satisfaits</p>
                            <p class="text-sm text-primary-foreground/70">Investisseurs et entreprises</p>
                        </div>
                    </div>
                </div>
                <div
                    class="rounded-lg border text-card-foreground shadow-sm p-6 bg-primary-foreground/10 backdrop-blur-sm border-primary-foreground/20 hover:bg-primary-foreground/20 transition-smooth group">
                    <div class="flex items-start gap-4">
                        <div
                            class="w-12 h-12 bg-secondary/20 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-secondary/30 transition-smooth">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-book-open w-6 h-6 text-secondary">
                                <path d="M12 7v14"></path>
                                <path
                                    d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z">
                                </path>
                            </svg></div>
                        <div class="flex-1">
                            <p class="text-3xl font-bold text-primary-foreground mb-1">150+</p>
                            <p class="text-base font-semibold text-secondary mb-1">Formations disponibles
                            </p>
                            <p class="text-sm text-primary-foreground/70">Cours certifiés</p>
                        </div>
                    </div>
                </div>
                <div
                    class="rounded-lg border text-card-foreground shadow-sm p-6 bg-primary-foreground/10 backdrop-blur-sm border-primary-foreground/20 hover:bg-primary-foreground/20 transition-smooth group">
                    <div class="flex items-start gap-4">
                        <div
                            class="w-12 h-12 bg-secondary/20 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-secondary/30 transition-smooth">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-award w-6 h-6 text-secondary">
                                <path
                                    d="m15.477 12.89 1.515 8.526a.5.5 0 0 1-.81.47l-3.58-2.687a1 1 0 0 0-1.197 0l-3.586 2.686a.5.5 0 0 1-.81-.469l1.514-8.526">
                                </path>
                                <circle cx="12" cy="8" r="6"></circle>
                            </svg></div>
                        <div class="flex-1">
                            <p class="text-3xl font-bold text-primary-foreground mb-1">98%</p>
                            <p class="text-base font-semibold text-secondary mb-1">Taux de satisfaction</p>
                            <p class="text-sm text-primary-foreground/70">Note moyenne clients</p>
                        </div>
                    </div>
</div>
            </div>
        </div>
    </section>
    <section class="py-20">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center mb-12">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">Dernières <span
                            class="text-primary">Actualités</span></h2>
                    <p class="text-lg text-muted-foreground">Restez informé des tendances et analyses du
                        marché</p>
                </div><a href="/actualites"><button
                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 border-2 border-primary bg-background text-primary hover:bg-primary hover:text-primary-foreground transition-smooth h-14 rounded-lg px-10 text-base mt-4 md:mt-0 group">Voir
                        toutes les actualités<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-arrow-right w-4 h-4 group-hover:translate-x-1 transition-smooth">
                            <path d="M5 12h14"></path>
                            <path d="m12 5 7 7-7 7"></path>
                        </svg></button></a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div
                    class="rounded-lg border bg-card text-card-foreground shadow-sm overflow-hidden border-border hover:border-primary/30 hover:shadow-elegant transition-smooth group">
                    <a href="/actualites/1">
                        <div class="relative h-48 overflow-hidden"><img
                                src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&amp;h=500&amp;fit=crop"
                                alt="Analyse BRVM : Performance du secteur bancaire en 2024"
                                class="w-full h-full object-cover group-hover:scale-105 transition-smooth duration-500">
                            <div class="absolute top-4 left-4"><span
                                    class="px-3 py-1 bg-secondary text-secondary-foreground text-xs font-semibold rounded-full shadow-glow">Analyse</span>
                            </div>
                        </div>
                    </a>
                    <div class="p-6 space-y-3">
                        <div class="flex items-center gap-2 text-sm text-muted-foreground"><svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-calendar w-4 h-4">
                                <path d="M8 2v4"></path>
                                <path d="M16 2v4"></path>
                                <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                                <path d="M3 10h18"></path>
                            </svg><span>15 Janvier 2025</span></div><a href="/actualites/1">
                            <h3
                                class="text-xl font-bold group-hover:text-primary transition-smooth line-clamp-2">
                                Analyse BRVM : Performance du secteur bancaire en 2024</h3>
                        </a>
                        <p class="text-muted-foreground line-clamp-3 leading-relaxed">Le secteur bancaire de
                            la BRVM affiche une croissance de 12% avec des dividendes attractifs. Analyse
                            détaillée des opportunités pour 2025...</p><a
                            class="inline-flex items-center gap-1 text-primary font-medium group-hover:gap-2 transition-smooth"
                            href="/actualites/1">Lire la suite<svg xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-arrow-right w-4 h-4">
                                <path d="M5 12h14"></path>
                                <path d="m12 5 7 7-7 7"></path>
                            </svg></a>
                    </div>
                </div>
                <div
                    class="rounded-lg border bg-card text-card-foreground shadow-sm overflow-hidden border-border hover:border-primary/30 hover:shadow-elegant transition-smooth group">
                    <a href="/actualites/2">
                        <div class="relative h-48 overflow-hidden"><img
                                src="https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=800&amp;h=500&amp;fit=crop"
                                alt="Indices BRVM : Le BRVM Composite franchit les 250 points"
                                class="w-full h-full object-cover group-hover:scale-105 transition-smooth duration-500">
                            <div class="absolute top-4 left-4"><span
                                    class="px-3 py-1 bg-secondary text-secondary-foreground text-xs font-semibold rounded-full shadow-glow">Bourse</span>
                            </div>
                        </div>
                    </a>
                    <div class="p-6 space-y-3">
                        <div class="flex items-center gap-2 text-sm text-muted-foreground"><svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-calendar w-4 h-4">
                                <path d="M8 2v4"></path>
                                <path d="M16 2v4"></path>
                                <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                                <path d="M3 10h18"></path>
                            </svg><span>12 Janvier 2025</span></div><a href="/actualites/2">
                            <h3
                                class="text-xl font-bold group-hover:text-primary transition-smooth line-clamp-2">
                                Indices BRVM : Le BRVM Composite franchit les 250 points</h3>
                        </a>
                        <p class="text-muted-foreground line-clamp-3 leading-relaxed">La bourse régionale
                            connaît une dynamique positive portée par les valeurs de télécommunications et
                            bancaires. Point sur les perspectives...</p><a
                            class="inline-flex items-center gap-1 text-primary font-medium group-hover:gap-2 transition-smooth"
                            href="/actualites/2">Lire la suite<svg xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-arrow-right w-4 h-4">
                                <path d="M5 12h14"></path>
                                <path d="m12 5 7 7-7 7"></path>
                            </svg></a>
                    </div>
                </div>
                <div
                    class="rounded-lg border bg-card text-card-foreground shadow-sm overflow-hidden border-border hover:border-primary/30 hover:shadow-elegant transition-smooth group">
                    <a href="/actualites/3">
                        <div class="relative h-48 overflow-hidden"><img
                                src="https://images.unsplash.com/photo-1434626881859-194d67b2b86f?w=800&amp;h=500&amp;fit=crop"
                                alt="Formation : Comprendre l'analyse fondamentale sur la BRVM"
                                class="w-full h-full object-cover group-hover:scale-105 transition-smooth duration-500">
                            <div class="absolute top-4 left-4"><span
                                    class="px-3 py-1 bg-secondary text-secondary-foreground text-xs font-semibold rounded-full shadow-glow">Formation</span>
                            </div>
                        </div>
                    </a>
                    <div class="p-6 space-y-3">
                        <div class="flex items-center gap-2 text-sm text-muted-foreground"><svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-calendar w-4 h-4">
                                <path d="M8 2v4"></path>
                                <path d="M16 2v4"></path>
                                <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                                <path d="M3 10h18"></path>
                            </svg><span>10 Janvier 2025</span></div><a href="/actualites/3">
                            <h3
                                class="text-xl font-bold group-hover:text-primary transition-smooth line-clamp-2">
                                Formation : Comprendre l'analyse fondamentale sur la BRVM</h3>
                        </a>
                        <p class="text-muted-foreground line-clamp-3 leading-relaxed">Apprenez à analyser
                            les états financiers des sociétés cotées, calculer les ratios clés et identifier
                            les opportunités d'investissement...</p><a
                            class="inline-flex items-center gap-1 text-primary font-medium group-hover:gap-2 transition-smooth"
                            href="/actualites/3">Lire la suite<svg xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-arrow-right w-4 h-4">
                                <path d="M5 12h14"></path>
                                <path d="m12 5 7 7-7 7"></path>
                            </svg></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @if($partnersByType->isNotEmpty())
    <section class="py-16 border-y border-border bg-muted/30">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <p class="text-sm font-semibold text-muted-foreground uppercase tracking-wider mb-2">Ils nous font confiance</p>
                <h2 class="text-2xl md:text-3xl font-bold">Nos <span class="text-primary">Partenaires Officiels</span></h2>
            </div>

            @php
                $typeLabels = \App\Models\Partner::TYPES;
                $orderedTypes = array_keys($typeLabels);
                $activeTypes = array_filter($orderedTypes, fn($k) => $partnersByType->has($k));
                $typeCount = count($activeTypes);
            @endphp

            <div class="max-w-6xl mx-auto space-y-12">
                @foreach($orderedTypes as $typeKey)
                    @if($partnersByType->has($typeKey))
                        @php $group = $partnersByType->get($typeKey); @endphp
                        <div>
                            @if($typeCount > 1)
                            <h3 class="text-base font-semibold text-center mb-6 text-secondary flex items-center justify-center gap-3">
                                <span class="flex-1 h-px bg-border max-w-[120px]"></span>
                                {{ $typeLabels[$typeKey] }}
                                <span class="flex-1 h-px bg-border max-w-[120px]"></span>
                            </h3>
                            @endif
                            <div class="flex flex-wrap justify-center gap-4">
                                @foreach($group as $partner)
                                    <a href="{{ $partner->website ?: '#' }}" {{ $partner->website ? 'target="_blank" rel="noopener noreferrer"' : '' }}
                                        class="group bg-card border border-border rounded-xl shadow-sm hover:shadow-elegant hover:border-primary/40 hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center overflow-hidden"
                                        style="width: 160px; height: 80px; padding: 12px;">
                                        @if($partner->logo)
                                            <img src="{{ asset('storage/' . $partner->logo) }}" alt="{{ $partner->nom }}"
                                                class="max-w-full max-h-full w-auto h-auto object-contain block"
                                                style="max-height: 56px; max-width: 130px;">
                                        @else
                                            <span class="font-semibold text-xs text-center text-foreground group-hover:text-primary transition-colors leading-snug px-2">{{ $partner->nom }}</span>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="text-center mt-16">
                <a href="{{ route('partenaires') }}" class="inline-flex items-center gap-2 text-sm font-medium text-primary hover:text-primary-light transition-colors">
                    Voir tous nos partenaires
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </section>
    @endif
    <section class="py-20 bg-muted/30">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Ce que disent nos <span
                        class="text-primary">clients</span></h2>
                <p class="text-lg text-muted-foreground max-w-2xl mx-auto">Découvrez les témoignages de ceux
                    qui nous font confiance</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div
                    class="rounded-lg border bg-card text-card-foreground shadow-sm p-6 border-border hover:border-primary/30 hover:shadow-elegant transition-smooth">
                    <div class="flex items-center gap-1 mb-4"><svg xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-star w-5 h-5 fill-secondary text-secondary">
                            <path
                                d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z">
                            </path>
                        </svg><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-star w-5 h-5 fill-secondary text-secondary">
                            <path
                                d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z">
                            </path>
                        </svg><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-star w-5 h-5 fill-secondary text-secondary">
                            <path
                                d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z">
                            </path>
                        </svg><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-star w-5 h-5 fill-secondary text-secondary">
                            <path
                                d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z">
                            </path>
                        </svg><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-star w-5 h-5 fill-secondary text-secondary">
                            <path
                                d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z">
                            </path>
                        </svg></div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-quote w-10 h-10 text-primary/20 mb-4">
                        <path
                            d="M16 3a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2 1 1 0 0 1 1 1v1a2 2 0 0 1-2 2 1 1 0 0 0-1 1v2a1 1 0 0 0 1 1 6 6 0 0 0 6-6V5a2 2 0 0 0-2-2z">
                        </path>
                        <path
                            d="M5 3a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2 1 1 0 0 1 1 1v1a2 2 0 0 1-2 2 1 1 0 0 0-1 1v2a1 1 0 0 0 1 1 6 6 0 0 0 6-6V5a2 2 0 0 0-2-2z">
                        </path>
                    </svg>
                    <p class="text-foreground/90 mb-6 leading-relaxed italic">"Grâce aux formations de
                        Africaine des Finances, j'ai pu diversifier mon portefeuille et générer des
                        rendements constants. L'équipe est professionnelle et toujours disponible."</p>
                    <div class="flex items-center gap-3 pt-4 border-t border-border"><img
                            src="https://api.dicebear.com/7.x/avataaars/svg?seed=Koffi" alt="Koffi Mensah"
                            class="w-12 h-12 rounded-full bg-muted">
                        <div>
                            <p class="font-semibold">Koffi Mensah</p>
                            <p class="text-sm text-muted-foreground">Investisseur privé</p>
                            <p class="text-xs text-muted-foreground">Abidjan, Côte d'Ivoire</p>
                        </div>
                    </div>
                </div>
                <div
                    class="rounded-lg border bg-card text-card-foreground shadow-sm p-6 border-border hover:border-primary/30 hover:shadow-elegant transition-smooth">
                    <div class="flex items-center gap-1 mb-4"><svg xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-star w-5 h-5 fill-secondary text-secondary">
                            <path
                                d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z">
                            </path>
                        </svg><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-star w-5 h-5 fill-secondary text-secondary">
                            <path
                                d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z">
                            </path>
                        </svg><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-star w-5 h-5 fill-secondary text-secondary">
                            <path
                                d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z">
                            </path>
                        </svg><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-star w-5 h-5 fill-secondary text-secondary">
                            <path
                                d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z">
                            </path>
                        </svg><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-star w-5 h-5 fill-secondary text-secondary">
                            <path
                                d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z">
                            </path>
                        </svg></div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-quote w-10 h-10 text-primary/20 mb-4">
                        <path
                            d="M16 3a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2 1 1 0 0 1 1 1v1a2 2 0 0 1-2 2 1 1 0 0 0-1 1v2a1 1 0 0 0 1 1 6 6 0 0 0 6-6V5a2 2 0 0 0-2-2z">
                        </path>
                        <path
                            d="M5 3a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2 1 1 0 0 1 1 1v1a2 2 0 0 1-2 2 1 1 0 0 0-1 1v2a1 1 0 0 0 1 1 6 6 0 0 0 6-6V5a2 2 0 0 0-2-2z">
                        </path>
                    </svg>
                    <p class="text-foreground/90 mb-6 leading-relaxed italic">"Les analyses de marché BRVM
                        sont précises et m'ont aidée à prendre des décisions stratégiques pour mon
                        entreprise. Un service incontournable pour tout professionnel de la finance."</p>
                    <div class="flex items-center gap-3 pt-4 border-t border-border"><img
                            src="https://api.dicebear.com/7.x/avataaars/svg?seed=Aminata"
                            alt="Aminata Diallo" class="w-12 h-12 rounded-full bg-muted">
                        <div>
                            <p class="font-semibold">Aminata Diallo</p>
                            <p class="text-sm text-muted-foreground">Directrice Financière</p>
                            <p class="text-xs text-muted-foreground">Dakar, Sénégal</p>
                        </div>
                    </div>
                </div>
                <div
                    class="rounded-lg border bg-card text-card-foreground shadow-sm p-6 border-border hover:border-primary/30 hover:shadow-elegant transition-smooth">
                    <div class="flex items-center gap-1 mb-4"><svg xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-star w-5 h-5 fill-secondary text-secondary">
                            <path
                                d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z">
                            </path>
                        </svg><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-star w-5 h-5 fill-secondary text-secondary">
                            <path
                                d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z">
                            </path>
                        </svg><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-star w-5 h-5 fill-secondary text-secondary">
                            <path
                                d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z">
                            </path>
                        </svg><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-star w-5 h-5 fill-secondary text-secondary">
                            <path
                                d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z">
                            </path>
                        </svg><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-star w-5 h-5 fill-secondary text-secondary">
                            <path
                                d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z">
                            </path>
                        </svg></div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-quote w-10 h-10 text-primary/20 mb-4">
                        <path
                            d="M16 3a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2 1 1 0 0 1 1 1v1a2 2 0 0 1-2 2 1 1 0 0 0-1 1v2a1 1 0 0 0 1 1 6 6 0 0 0 6-6V5a2 2 0 0 0-2-2z">
                        </path>
                        <path
                            d="M5 3a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2 1 1 0 0 1 1 1v1a2 2 0 0 1-2 2 1 1 0 0 0-1 1v2a1 1 0 0 0 1 1 6 6 0 0 0 6-6V5a2 2 0 0 0-2-2z">
                        </path>
                    </svg>
                    <p class="text-foreground/90 mb-6 leading-relaxed italic">"Le service de conseil en
                        investissement m'a permis d'optimiser mes placements. Les conseillers sont
                        compétents et comprennent parfaitement les spécificités du marché africain."</p>
                    <div class="flex items-center gap-3 pt-4 border-t border-border"><img
                            src="https://api.dicebear.com/7.x/avataaars/svg?seed=Jean"
                            alt="Jean-Pierre Kouassi" class="w-12 h-12 rounded-full bg-muted">
                        <div>
                            <p class="font-semibold">Jean-Pierre Kouassi</p>
                            <p class="text-sm text-muted-foreground">Entrepreneur</p>
                            <p class="text-xs text-muted-foreground">Lomé, Togo</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">Questions <span
                            class="text-primary">Fréquentes</span></h2>
                    <p class="text-lg text-muted-foreground">Trouvez rapidement des réponses à vos questions
                    </p>
                </div>
                <div class="space-y-4" id="faq-accordion">
                    <div class="border border-border rounded-lg px-6 hover:border-primary/30 transition-smooth accordion-item">
                        <button type="button" class="flex w-full items-center justify-between py-4 text-left font-semibold hover:text-primary accordion-trigger">
                            Comment investir sur la BRVM avec Africaine des Finances ?
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down h-4 w-4 shrink-0 transition-transform duration-200 accordion-icon">
                                <path d="m6 9 6 6 6-6"></path>
                            </svg>
                        </button>
                        <div class="accordion-content overflow-hidden transition-all duration-300 max-h-0">
                            <div class="pb-4 text-muted-foreground leading-relaxed">
                                Pour investir sur la BRVM avec nous, il vous suffit de créer un compte et de suivre nos formations gratuites pour comprendre les bases. Ensuite, nos conseillers vous accompagnent dans la sélection des meilleures opportunités d'investissement selon votre profil de risque. Nous proposons également un service de gestion de portefeuille pour optimiser vos placements.
                            </div>
                        </div>
                    </div>

                    <div class="border border-border rounded-lg px-6 hover:border-primary/30 transition-smooth accordion-item">
                        <button type="button" class="flex w-full items-center justify-between py-4 text-left font-semibold hover:text-primary accordion-trigger">
                            Quelles sont les 45 valeurs cotées à la BRVM que vous couvrez ?
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down h-4 w-4 shrink-0 transition-transform duration-200 accordion-icon">
                                <path d="m6 9 6 6 6-6"></path>
                            </svg>
                        </button>
                        <div class="accordion-content overflow-hidden transition-all duration-300 max-h-0">
                            <div class="pb-4 text-muted-foreground leading-relaxed">
                                Nous suivons l'ensemble des 45 valeurs cotées à la BRVM, réparties dans différents secteurs : banques (BOA, Ecobank, SGCI...), télécoms (Orange CI, Sonatel...), industrie, agriculture, distribution et services. Chaque jour, nous publions des analyses détaillées sur les valeurs les plus actives et les opportunités d'investissement. Nos rapports incluent les données financières, les ratios clés et nos recommandations d'achat ou de vente.
                            </div>
                        </div>
                    </div>

                    <div class="border border-border rounded-lg px-6 hover:border-primary/30 transition-smooth accordion-item">
                        <button type="button" class="flex w-full items-center justify-between py-4 text-left font-semibold hover:text-primary accordion-trigger">
                            Vos formations sont-elles reconnues professionnellement ?
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down h-4 w-4 shrink-0 transition-transform duration-200 accordion-icon">
                                <path d="m6 9 6 6 6-6"></path>
                            </svg>
                        </button>
                        <div class="accordion-content overflow-hidden transition-all duration-300 max-h-0">
                            <div class="pb-4 text-muted-foreground leading-relaxed">
                                Oui, toutes nos formations sont certifiées et reconnues par les institutions financières de la zone UEMOA. Nos formateurs sont des experts agréés avec plus de 15 ans d'expérience dans la finance africaine. À l'issue de chaque formation, vous recevez un certificat attestant de vos compétences, valorisable dans votre parcours professionnel. Nos programmes sont également éligibles au CPF dans certains pays.
                            </div>
                        </div>
                    </div>

                    <div class="border border-border rounded-lg px-6 hover:border-primary/30 transition-smooth accordion-item">
                        <button type="button" class="flex w-full items-center justify-between py-4 text-left font-semibold hover:text-primary accordion-trigger">
                            Quels sont vos tarifs pour le conseil en investissement ?
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down h-4 w-4 shrink-0 transition-transform duration-200 accordion-icon">
                                <path d="m6 9 6 6 6-6"></path>
                            </svg>
                        </button>
                        <div class="accordion-content overflow-hidden transition-all duration-300 max-h-0">
                            <div class="pb-4 text-muted-foreground leading-relaxed">
                                Nos tarifs varient selon le type de service : consultation ponctuelle (50 000 FCFA), abonnement mensuel avec analyses quotidiennes (75 000 FCFA/mois), ou gestion de portefeuille (1% de l'actif sous gestion par an avec un minimum de 5 millions FCFA). La première consultation d'évaluation est gratuite et sans engagement. Nous proposons également des forfaits sur mesure pour les entreprises et investisseurs institutionnels.
                            </div>
                        </div>
                    </div>

                    <div class="border border-border rounded-lg px-6 hover:border-primary/30 transition-smooth accordion-item">
                        <button type="button" class="flex w-full items-center justify-between py-4 text-left font-semibold hover:text-primary accordion-trigger">
                            Comment accédez-vous aux données BRVM en temps réel ?
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down h-4 w-4 shrink-0 transition-transform duration-200 accordion-icon">
                                <path d="m6 9 6 6 6-6"></path>
                            </svg>
                        </button>
                        <div class="accordion-content overflow-hidden transition-all duration-300 max-h-0">
                            <div class="pb-4 text-muted-foreground leading-relaxed">
                                Nous sommes partenaire officiel de la BRVM et disposons d'un accès direct à leur flux de données en temps réel. Cela nous permet de vous fournir les cotations, volumes et transactions instantanément. Notre plateforme affiche les cours avec un délai de moins de 5 secondes. Vous pouvez suivre l'évolution du marché via notre site web ou notre application mobile disponible sur iOS et Android.
                            </div>
                        </div>
                    </div>

                    <div class="border border-border rounded-lg px-6 hover:border-primary/30 transition-smooth accordion-item">
                        <button type="button" class="flex w-full items-center justify-between py-4 text-left font-semibold hover:text-primary accordion-trigger">
                            Accompagnez-vous les investisseurs institutionnels ?
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down h-4 w-4 shrink-0 transition-transform duration-200 accordion-icon">
                                <path d="m6 9 6 6 6-6"></path>
                            </svg>
                        </button>
                        <div class="accordion-content overflow-hidden transition-all duration-300 max-h-0">
                            <div class="pb-4 text-muted-foreground leading-relaxed">
                                Absolument ! Nous accompagnons de nombreux investisseurs institutionnels (fonds de pension, compagnies d'assurance, family offices) dans leurs stratégies d'allocation d'actifs en Afrique de l'Ouest. Nous proposons des analyses sectorielles approfondies, du conseil en gouvernance d'entreprise et un accès privilégié à des opportunités d'investissement pré-IPO. Notre équipe dédiée aux clients institutionnels garantit un service personnalisé et confidentiel.
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const accordionItems = document.querySelectorAll('.accordion-item');
                        
                        // Initialiser tous les accordéons en état fermé
                        accordionItems.forEach(item => {
                            const content = item.querySelector('.accordion-content');
                            const icon = item.querySelector('.accordion-icon');
                            content.style.maxHeight = '0px';
                            icon.style.transform = 'rotate(0deg)';
                        });
                        
                        // Gérer les clics sur les accordéons
                        accordionItems.forEach(item => {
                            const trigger = item.querySelector('.accordion-trigger');
                            const content = item.querySelector('.accordion-content');
                            const icon = item.querySelector('.accordion-icon');
                            
                            trigger.addEventListener('click', function() {
                                const isOpen = content.style.maxHeight && content.style.maxHeight !== '0px';
                                
                                // Fermer tous les autres accordéons
                                accordionItems.forEach(otherItem => {
                                    const otherContent = otherItem.querySelector('.accordion-content');
                                    const otherIcon = otherItem.querySelector('.accordion-icon');
                                    otherContent.style.maxHeight = '0px';
                                    otherIcon.style.transform = 'rotate(0deg)';
                                });
                                
                                // Ouvrir ou fermer l'accordéon actuel
                                if (!isOpen) {
                                    content.style.maxHeight = content.scrollHeight + 'px';
                                    icon.style.transform = 'rotate(180deg)';
                                } else {
                                    content.style.maxHeight = '0px';
                                    icon.style.transform = 'rotate(0deg)';
                                }
                            });
                        });
                    });
                </script>
            </div>
        </div>
    </section>
    <!-- <section class="py-20 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-hero">
            <div
                class="absolute top-10 right-10 w-96 h-96 bg-secondary/20 rounded-full blur-3xl animate-pulse">
            </div>
            <div
                class="absolute bottom-10 left-10 w-72 h-72 bg-accent/20 rounded-full blur-3xl animate-pulse delay-1000">
            </div>
        </div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center space-y-8">
                <div
                    class="inline-flex items-center gap-2 px-4 py-2 bg-secondary/20 backdrop-blur-sm rounded-full border border-secondary/30">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-sparkles w-4 h-4 text-secondary">
                        <path
                            d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z">
                        </path>
                        <path d="M20 3v4"></path>
                        <path d="M22 5h-4"></path>
                        <path d="M4 17v2"></path>
                        <path d="M5 18H3"></path>
                    </svg><span class="text-sm font-medium text-primary-foreground">Rejoignez notre
                        communauté</span></div>
                <h2 class="text-3xl md:text-5xl font-bold text-primary-foreground leading-tight">Développez
                    votre expertise des <span class="text-secondary">marchés africains</span></h2>
                <p class="text-lg md:text-xl text-primary-foreground/90 max-w-2xl mx-auto leading-relaxed">
                    Formations professionnelles, données BRVM en temps réel, analyses quotidiennes et
                    conseil en investissement. Tout ce dont vous avez besoin pour réussir sur les marchés de
                    la zone UEMOA.</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center pt-4"><a
                        href="/formations"><button
                            class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 bg-secondary text-secondary-foreground hover:bg-secondary-light hover:shadow-elegant transition-smooth h-14 rounded-lg px-10 text-base group shadow-glow">Commencer
                            gratuitement<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-arrow-right w-5 h-5 group-hover:translate-x-1 transition-smooth">
                                <path d="M5 12h14"></path>
                                <path d="m12 5 7 7-7 7"></path>
                            </svg></button></a><a href="/contact"><button
                            class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 border-2 bg-background transition-smooth h-14 rounded-lg px-10 text-base border-primary-foreground/30 text-primary-foreground hover:bg-primary-foreground hover:text-primary">Prendre
                            rendez-vous</button></a></div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 pt-12">
                    <div class="text-center">
                        <p class="text-4xl font-bold text-secondary mb-2">100%</p>
                        <p class="text-sm text-primary-foreground/80">En ligne</p>
                    </div>
                    <div class="text-center">
                        <p class="text-4xl font-bold text-secondary mb-2">24/7</p>
                        <p class="text-sm text-primary-foreground/80">Support</p>
                    </div>
                    <div class="text-center">
                        <p class="text-4xl font-bold text-secondary mb-2">Expert</p>
                        <p class="text-sm text-primary-foreground/80">Formateurs</p>
                    </div>
                    <div class="text-center">
                        <p class="text-4xl font-bold text-secondary mb-2">Certifié</p>
                        <p class="text-sm text-primary-foreground/80">Formations</p>
                    </div>
                </div>
            </div>
        </div>
    </section> -->
</main>