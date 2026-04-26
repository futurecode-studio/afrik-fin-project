<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="../assets/css/index.css">
    <script src="../assets/js/index.js" defer></script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Africaine des Finances</title>
    <meta name="description"
        content="Plateforme financière africaine de référence. Formations e-learning, données boursières BRVM en temps réel, analyses de marché et conseil en investissement.">
    <meta name="author" content="Africaine des Finances">
    <meta name="keywords"
        content="finance africaine, BRVM, bourse, formation financière, e-learning, investissement, conseil financier, Afrique de l'Ouest, UEMOA">

    <meta property="og:title" content="Africaine des Finances - Formation, Bourse BRVM &amp; Conseil Financier">
    <meta property="og:description"
        content="Plateforme financière africaine de référence. Formations e-learning, données boursières BRVM en temps réel, analyses de marché et conseil en investissement.">
    <meta property="og:type" content="website">

    <!-- Vite (Tailwind + JS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Livewire Styles --> 
    @livewireStyles
</head>

<body>
    <div id="root">
        <div role="region" aria-label="Notifications (F8)" tabindex="-1" style="pointer-events: none;">
            <ol tabindex="-1"
                class="fixed top-0 z-[100] flex max-h-screen w-full flex-col-reverse p-4 sm:bottom-0 sm:right-0 sm:top-auto sm:flex-col md:max-w-[420px]">
            </ol>
        </div>
        <section aria-label="Notifications alt+T" tabindex="-1" aria-live="polite" aria-relevant="additions text"
            aria-atomic="false"></section>
        <div class="min-h-screen flex flex-col">
            <nav
                class="fixed top-0 left-0 right-0 z-50 bg-background/95 backdrop-blur-sm border-b border-border shadow-card">
                <div class="container mx-auto px-4">
                    <div class="flex items-center justify-between h-20"><a class="flex items-center gap-2 group"
                            href="{{ route('home') }}">
                            <img 
                                src="{{ asset('assets/logo.png') }}" 
                                alt="Africaine des Finances" 
                                class="h-12 w-auto object-contain transition-smooth group-hover:scale-105"
                            />
                            <span
                                class="text-xl font-bold bg-gradient-primary bg-clip-text text-transparent">Africaine des Finances
                            </span>
                        </a>
                        <div class="hidden md:flex items-center gap-1">
                            <a class="px-3 py-2 rounded-md text-sm font-medium transition-smooth whitespace-nowrap {{ request()->routeIs('home') ? 'bg-primary text-primary-foreground shadow-elegant' : 'text-foreground hover:bg-muted hover:text-primary' }}"
                                href="{{ route('home') }}">Accueil</a>
                            <a class="px-3 py-2 rounded-md text-sm font-medium transition-smooth whitespace-nowrap {{ request()->routeIs('services*') ? 'bg-primary text-primary-foreground shadow-elegant' : 'text-foreground hover:bg-muted hover:text-primary' }}"
                                href="{{ route('services') }}">Services</a>
                            <a class="px-3 py-2 rounded-md text-sm font-medium transition-smooth whitespace-nowrap {{ request()->routeIs('formations*', 'formation-detail') ? 'bg-primary text-primary-foreground shadow-elegant' : 'text-foreground hover:bg-muted hover:text-primary' }}"
                                href="{{ route('formations') }}">Formations</a>
                            
                            <!-- Menu Investir avec dropdown -->
                            <div class="relative group" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                                <button class="px-3 py-2 rounded-md text-sm font-medium transition-smooth whitespace-nowrap flex items-center gap-1 {{ request()->routeIs('investir.*') ? 'bg-primary text-primary-foreground shadow-elegant' : 'text-foreground hover:bg-muted hover:text-primary' }}">
                                    Investir
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="transition-transform" :class="open ? 'rotate-180' : ''">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </button>
                                <div x-show="open" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 transform scale-95"
                                     x-transition:enter-end="opacity-100 transform scale-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 transform scale-100"
                                     x-transition:leave-end="opacity-0 transform scale-95"
                                     class="absolute left-0 mt-2 w-64 bg-card border border-border rounded-lg shadow-elegant overflow-hidden z-50"
                                     style="display: none;">
                                    <a href="{{ route('investir.actions-brvm') }}" class="block px-4 py-3 text-sm hover:bg-muted transition-smooth {{ request()->routeIs('investir.actions-brvm') ? 'bg-primary/10 text-primary font-semibold' : 'text-foreground' }}">
                                        <div class="flex items-center gap-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary">
                                                <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                                <polyline points="16 7 22 7 22 13"></polyline>
                                            </svg>
                                            <div>
                                                <p class="font-medium">Actions BRVM</p>
                                                <p class="text-xs text-muted-foreground">Marché boursier régional</p>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="{{ route('investir.obligations') }}" class="block px-4 py-3 text-sm hover:bg-muted transition-smooth {{ request()->routeIs('investir.obligations') ? 'bg-primary/10 text-primary font-semibold' : 'text-foreground' }}">
                                        <div class="flex items-center gap-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-secondary">
                                                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                            </svg>
                                            <div>
                                                <p class="font-medium">Obligations d'États</p>
                                                <p class="text-xs text-muted-foreground">Titres souverains UEMOA</p>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="{{ route('investir.fcp') }}" class="block px-4 py-3 text-sm hover:bg-muted transition-smooth {{ request()->routeIs('investir.fcp') ? 'bg-primary/10 text-primary font-semibold' : 'text-foreground' }}">
                                        <div class="flex items-center gap-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-accent">
                                                <path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path>
                                                <path d="M22 12A10 10 0 0 0 12 2v10z"></path>
                                            </svg>
                                            <div>
                                                <p class="font-medium">Fonds Communs de Placement</p>
                                                <p class="text-xs text-muted-foreground">FCP diversifiés</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            
                            <a class="px-3 py-2 rounded-md text-sm font-medium transition-smooth whitespace-nowrap {{ request()->routeIs('actualites*', 'actualite-detail') ? 'bg-primary text-primary-foreground shadow-elegant' : 'text-foreground hover:bg-muted hover:text-primary' }}"
                                href="{{ route('actualites') }}">Actualités</a>
                            <a class="px-3 py-2 rounded-md text-sm font-medium transition-smooth whitespace-nowrap {{ request()->routeIs('about') ? 'bg-primary text-primary-foreground shadow-elegant' : 'text-foreground hover:bg-muted hover:text-primary' }}"
                                href="{{ route('about') }}">Qui sommes-nous ?</a>
                            <a class="px-3 py-2 rounded-md text-sm font-medium transition-smooth whitespace-nowrap {{ request()->routeIs('carriere') ? 'bg-primary text-primary-foreground shadow-elegant' : 'text-foreground hover:bg-muted hover:text-primary' }}"
                                href="{{ route('carriere') }}">Carrière</a>
                            <a class="px-3 py-2 rounded-md text-sm font-medium transition-smooth whitespace-nowrap {{ request()->routeIs('contact') ? 'bg-primary text-primary-foreground shadow-elegant' : 'text-foreground hover:bg-muted hover:text-primary' }}"
                                href="{{ route('contact') }}">Contact</a>
                        </div>
                        @if (Route::has('login'))
                            @auth
                                <div class="hidden md:flex items-center gap-2">
                                    <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : route('client.dashboard') }}">
                                        <button
                                            class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm ring-offset-background 
                                            transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 
                                            disabled:pointer-events-none disabled:opacity-50
                                            bg-gradient-hero text-primary-foreground border-2 border-secondary/30 hover:border-secondary shadow-elegant hover:shadow-glow 
                                            transition-smooth font-semibold h-9 px-4">
                                            {{ Auth::user()->isAdmin() ? 'Administration' : 'Mon Espace' }}
                                        </button>
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm ring-offset-background 
                                            transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 
                                            disabled:pointer-events-none disabled:opacity-50
                                            bg-destructive text-destructive-foreground hover:bg-destructive/90 shadow-elegant hover:shadow-glow 
                                            transition-smooth font-semibold h-9 px-4">
                                            Déconnexion
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="hidden md:flex items-center gap-2">
                                    <a href="{{ route('connexion') }}">
                                        <button
                                            class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm ring-offset-background 
                                            transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 
                                            disabled:pointer-events-none disabled:opacity-50
                                            bg-gradient-hero text-primary-foreground border-2 border-secondary/30 hover:border-secondary shadow-elegant hover:shadow-glow 
                                            transition-smooth font-semibold h-9 px-4">
                                            Connexion
                                        </button>
                                    </a>
                                </div>
                            @endauth
                        @endif
                        <button
                            class="md:hidden p-2 rounded-md hover:bg-muted transition-smooth"
                            aria-label="Toggle menu"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-menu w-6 h-6">
                                <line x1="4" x2="20" y1="12" y2="12"></line>
                                <line x1="4" x2="20" y1="6" y2="6"></line>
                                <line x1="4" x2="20" y1="18" y2="18"></line>
                            </svg></button>
                    </div>
                </div>
            </nav>
            
            @yield("content")
            
            <footer class="text-primary-foreground" style="background: linear-gradient(135deg, #071F5A 0%, #0A2E8C 100%);">
                <div class="container mx-auto px-4 py-12">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <div class="space-y-4">
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-10 h-10 bg-secondary rounded-lg flex items-center justify-center shadow-glow">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-trending-up w-6 h-6 text-primary">
                                        <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                        <polyline points="16 7 22 7 22 13"></polyline>
                                    </svg></div><span class="text-xl font-bold">Africaine des Finances</span>
                            </div>
                            <p class="text-sm text-primary-foreground/80 leading-relaxed">Votre partenaire de confiance
                                pour l'éducation financière, les analyses de marché et les services de conseil en
                                Afrique.</p>
                            @php
                                $socialLinks = cache()->remember('social_links_active', 600, fn() =>
                                    \App\Models\SocialLink::where('is_active', true)->orderBy('order')->get()
                                );
                            @endphp
                            @if($socialLinks->isNotEmpty())
                            <div class="flex flex-wrap gap-3">
                                @foreach($socialLinks as $socialLink)
                                <a href="{{ $socialLink->url }}" target="_blank" rel="noopener noreferrer"
                                    title="{{ $socialLink->getPlatformLabel() }}"
                                    class="w-9 h-9 bg-primary-foreground/10 hover:bg-secondary rounded-full flex items-center justify-center transition-smooth hover:shadow-glow">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="w-4 h-4">
                                        {!! $socialLink->getIconHtml() !!}
                                    </svg>
                                </a>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-secondary">Liens Rapides</h3>
                            <ul class="space-y-2">
                                <li>
                                    <a class="text-sm text-primary-foreground/80 hover:text-secondary transition-smooth"
                                        href="{{ route('home') }}">Accueil</a>
                                </li>
                                <!-- <li>
                                    <a class="text-sm text-primary-foreground/80 hover:text-secondary transition-smooth"
                                        href="{{ route('actualites') }}">Actualités</a>
                                </li> -->
                                <li>
                                    <a class="text-sm text-primary-foreground/80 hover:text-secondary transition-smooth"
                                        href="{{ route('formations') }}">Formations</a>
                                </li>
                                <li>
                                    <a class="text-sm text-primary-foreground/80 hover:text-secondary transition-smooth"
                                        href="{{ route('investir.actions-brvm') }}">Investir - Actions BRVM</a>
                                </li>
                                <li>
                                    <a class="text-sm text-primary-foreground/80 hover:text-secondary transition-smooth"
                                        href="{{ route('newsletter') }}">Newsletter</a>
                                </li>
                                <li>
                                    <a class="text-sm text-primary-foreground/80 hover:text-secondary transition-smooth"
                                        href="{{ route('partenaires') }}">Partenaires</a>
                                </li>
                                <li>
                                    <a class="text-sm text-primary-foreground/80 hover:text-secondary transition-smooth"
                                        href="{{ route('team') }}">Équipe</a>
                                </li>
                            </ul>
                        </div>
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-secondary">Services</h3>
                            <ul class="space-y-2">
                                <li class="text-sm text-primary-foreground/80">E-Learning Financier</li>
                                <li class="text-sm text-primary-foreground/80">Analyses de Marché</li>
                                <li class="text-sm text-primary-foreground/80">Conseil en Investissement</li>
                                <li class="text-sm text-primary-foreground/80">Données Boursières</li>
                            </ul>
                        </div>
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-secondary">Newsletter</h3>
                            <p class="text-sm text-primary-foreground/80">Restez informé des dernières actualités
                                financières</p>
                            @livewire('newsletter-subscribe')
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8 pt-8 border-t border-primary-foreground/20">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-secondary/20 rounded-full flex items-center justify-center"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-mail w-5 h-5 text-secondary">
                                    <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                                    <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                                </svg></div>
                            <div>
                                <p class="text-xs text-primary-foreground/60">Email</p>
                                <p class="text-sm font-medium">african.finances@gmail.com</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-secondary/20 rounded-full flex items-center justify-center"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-phone w-5 h-5 text-secondary">
                                    <path
                                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                    </path>
                                </svg></div>
                            <div>
                                <p class="text-xs text-primary-foreground/60">Téléphone</p>
                                <p class="text-sm font-medium">+229 01 44 21 78 89 / 01 66 55 51 21</p>
                                <p class="text-xs text-primary-foreground/60 mt-1">+229 01 44 21 82 09 / 01 48 71 88 51</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-secondary/20 rounded-full flex items-center justify-center"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-map-pin w-5 h-5 text-secondary">
                                    <path
                                        d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0">
                                    </path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg></div>
                            <div>
                                <p class="text-xs text-primary-foreground/60">Adresse</p>
                                <p class="text-sm font-medium">Cot Agla c/3881</p>
                                <p class="text-xs text-primary-foreground/60 mt-1">Cotonou, Bénin</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8 pt-8 border-t border-primary-foreground/20">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Informations légales -->
                            <div class="bg-primary-foreground/5 backdrop-blur-sm rounded-lg p-4 border border-primary-foreground/10 hover:border-secondary/30 transition-smooth">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 bg-secondary/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="lucide lucide-file-text w-5 h-5 text-secondary">
                                            <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                                            <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                                            <path d="M10 9H8"></path>
                                            <path d="M16 13H8"></path>
                                            <path d="M16 17H8"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-secondary mb-3 text-base">Informations légales</p>
                                        <div class="space-y-2">
                                            <p class="flex items-center gap-2">
                                                <span class="text-primary-foreground/70 text-xs">N° RCCM:</span>
                                                <span class="font-bold text-sm text-primary-foreground">RB/COT/21 B 31296</span>
                                            </p>
                                            <p class="flex items-center gap-2">
                                                <span class="text-primary-foreground/70 text-xs">N° IFU:</span>
                                                <span class="font-bold text-sm text-primary-foreground">3202113721309</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Agrément -->
                            <div class="bg-primary-foreground/5 backdrop-blur-sm rounded-lg p-4 border border-primary-foreground/10 hover:border-secondary/30 transition-smooth">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 bg-secondary/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="lucide lucide-shield-check w-5 h-5 text-secondary">
                                            <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path>
                                            <path d="m9 12 2 2 4-4"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-secondary mb-3 text-base">Agrément officiel</p>
                                        <div class="space-y-2">
                                            <p class="flex items-center gap-2">
                                                <span class="text-primary-foreground/70 text-xs">N° AA/2022-03</span>
                                                <span class="font-bold text-sm text-primary-foreground">Décision N°CREFMPF/22/143</span>
                                            </p>
                                            <p class="flex items-center gap-2">
                                                <span class="text-primary-foreground/70 text-xs">AMF-UMOA (ex CREPMF)</span>
                                                <span class="font-bold text-sm text-primary-foreground">AMF-UMOA (ex CREPMF)</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Copyright -->
                        <div class="text-center pt-4 border-t border-primary-foreground/10">
                            <p class="text-sm text-primary-foreground/60">
                                © 2025 <span class="font-semibold text-secondary">Africaine des Finances</span>. Tous droits réservés.
                            </p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script type="module" src="/src/main.tsx"></script>

    <!-- Livewire Scripts -->
    @livewireScripts

    @stack('scripts')

</body>

</html>