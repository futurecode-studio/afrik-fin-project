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
                            <div
                                class="w-10 h-10 bg-gradient-primary rounded-lg flex items-center justify-center shadow-elegant group-hover:shadow-glow transition-smooth">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="lucide lucide-trending-up w-6 h-6 text-primary-foreground">
                                    <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                    <polyline points="16 7 22 7 22 13"></polyline>
                                </svg></div><span
                                class="text-xl font-bold bg-gradient-primary bg-clip-text text-transparent">Africaine
                                des Finances</span>
                        </a>
                        <div class="hidden md:flex items-center gap-1">
                            <a class="px-4 py-2 rounded-md font-medium transition-smooth bg-primary text-primary-foreground shadow-elegant"
                                href="{{ route('home') }}">Accueil</a>
                            <a class="px-4 py-2 rounded-md font-medium transition-smooth text-foreground hover:bg-muted hover:text-primary"
                                href="{{ route('services') }}">Services</a>
                            <a class="px-4 py-2 rounded-md font-medium transition-smooth text-foreground hover:bg-muted hover:text-primary"
                                href="{{ route('formations') }}">Formations</a>
                            <a class="px-4 py-2 rounded-md font-medium transition-smooth text-foreground hover:bg-muted hover:text-primary"
                                href="{{ route('bourse') }}">Bourse BRVM</a>
                            <a class="px-4 py-2 rounded-md font-medium transition-smooth text-foreground hover:bg-muted hover:text-primary"
                                href="{{ route('actualites') }}">Actualités</a>
                            <a class="px-4 py-2 rounded-md font-medium transition-smooth text-foreground hover:bg-muted hover:text-primary"
                                href="{{ route('about') }}">À Propos</a>
                            <!-- <a class="px-4 py-2 rounded-md font-medium transition-smooth text-foreground hover:bg-muted hover:text-primary"
                                href="{{ route('newsletter') }}">Newsletter</a> -->
                            <a class="px-4 py-2 rounded-md font-medium transition-smooth text-foreground hover:bg-muted hover:text-primary"
                                href="{{ route('contact') }}">Contact</a>
                        </div>
                        @if (Route::has('login'))
                            @auth
                                <div class="hidden md:flex items-center gap-2">
                                    <a href="{{ route('dashboard') }}">
                                        <button
                                            class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm ring-offset-background 
                                            transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 
                                            disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 
                                            bg-gradient-hero text-primary-foreground border-2 border-secondary/30 hover:border-secondary shadow-elegant hover:shadow-glow 
                                            transition-smooth font-semibold h-11 px-6 py-3">
                                            Dashboard
                                        </button>
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm ring-offset-background 
                                            transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 
                                            disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 
                                            bg-destructive text-destructive-foreground hover:bg-destructive/90 shadow-elegant hover:shadow-glow 
                                            transition-smooth font-semibold h-11 px-6 py-3">
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
                                            disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 
                                            bg-gradient-hero text-primary-foreground border-2 border-secondary/30 hover:border-secondary shadow-elegant hover:shadow-glow 
                                            transition-smooth font-semibold h-11 px-6 py-3">
                                            Se Connecter
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
            
            <footer class="bg-gradient-hero text-primary-foreground">
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
                            <div class="flex gap-3"><a href="#"
                                    class="w-9 h-9 bg-primary-foreground/10 hover:bg-secondary rounded-full flex items-center justify-center transition-smooth hover:shadow-glow"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-facebook w-4 h-4">
                                        <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z">
                                        </path>
                                    </svg></a><a href="#"
                                    class="w-9 h-9 bg-primary-foreground/10 hover:bg-secondary rounded-full flex items-center justify-center transition-smooth hover:shadow-glow"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-twitter w-4 h-4">
                                        <path
                                            d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z">
                                        </path>
                                    </svg></a><a href="#"
                                    class="w-9 h-9 bg-primary-foreground/10 hover:bg-secondary rounded-full flex items-center justify-center transition-smooth hover:shadow-glow"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-linkedin w-4 h-4">
                                        <path
                                            d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z">
                                        </path>
                                        <rect width="4" height="12" x="2" y="9"></rect>
                                        <circle cx="4" cy="4" r="2"></circle>
                                    </svg></a></div>
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
                                        href="{{ route('bourse') }}">Bourse BRVM</a>
                                </li>
                                <li>
                                    <a class="text-sm text-primary-foreground/80 hover:text-secondary transition-smooth"
                                        href="{{ route('newsletter') }}">Newsletter</a>
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
                                <p class="text-sm font-medium">contact@financeafricaine.com</p>
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
                                <p class="text-sm font-medium">+229 XX XX XX XX</p>
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
                                <p class="text-sm font-medium">Cotonou, Bénin</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8 pt-8 border-t border-primary-foreground/20 text-center">
                        <p class="text-sm text-primary-foreground/60">© 2025 Africaine des Finances. Tous droits
                            réservés.</p>
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