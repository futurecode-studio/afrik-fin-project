<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Africaine des Finances - Formation, Bourse BRVM &amp; Conseil Financier</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('assets/css/admin-dashboard.css') }}">
    <script src="{{ asset('assets/js/admin-dashboard.js') }}" defer></script>
    <meta name="description"
        content="Plateforme financière africaine de référence. Formations e-learning, données boursières BRVM en temps réel, analyses de marché et conseil en investissement.">
    <meta name="author" content="Africaine des Finances">
    <meta name="keywords"
        content="finance africaine, BRVM, bourse, formation financière, e-learning, investissement, conseil financier, Afrique de l'Ouest, UEMOA">

    <meta property="og:title" content="Africaine des Finances - Formation, Bourse BRVM &amp; Conseil Financier">
    <meta property="og:description"
        content="Plateforme financière africaine de référence. Formations e-learning, données boursières BRVM en temps réel, analyses de marché et conseil en investissement.">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

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
        <div class="min-h-screen bg-gradient-to-br from-background via-background to-muted/20">
            <header class="border-b bg-card/50 backdrop-blur-sm sticky top-0 z-10">
                <div class="container mx-auto px-4 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-layout-dashboard h-6 w-6 text-primary">
                                <rect width="7" height="9" x="3" y="3" rx="1"></rect>
                                <rect width="7" height="5" x="14" y="3" rx="1"></rect>
                                <rect width="7" height="9" x="14" y="12" rx="1"></rect>
                                <rect width="7" height="5" x="3" y="16" rx="1"></rect>
                            </svg>
                            <h1 class="text-2xl font-bold">Tableau de Bord Admin</h1>
                        </a>
                    </div>
                    <div class="flex items-center gap-3">
                        <!-- Bouton Retour Dashboard -->
                        @if(!request()->routeIs('admin.dashboard'))
                            <a href="{{ route('admin.dashboard') }}"
                                class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 rounded-md px-4 text-xs">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="h-4 w-4">
                                    <path d="m12 19-7-7 7-7"></path>
                                    <path d="M19 12H5"></path>
                                </svg>
                                Dashboard
                            </a>
                        @endif
                        
                        <!-- Bouton Retour au Site -->
                        <a href="{{ route('home') }}"
                            class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 rounded-md px-4 text-xs">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="h-4 w-4">
                                <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                            Retour au Site
                        </a>
                        
                        <a href="{{ route('admin.profile') }}" class="text-sm text-muted-foreground hover:text-primary transition-colors">
                            {{ Auth::user()->name }}
                        </a>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 border-2 border-primary bg-background text-primary hover:bg-primary hover:text-primary-foreground transition-smooth h-9 rounded-md px-4 text-xs">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-log-out h-4 w-4 mr-2">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                    <polyline points="16 17 21 12 16 7"></polyline>
                                    <line x1="21" x2="9" y1="12" y2="12"></line>
                                </svg>Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </header>
            
            @yield('content')
            
        </div>
    </div>
    
    <!-- Livewire Scripts -->
    @livewireScripts

    @stack('scripts')
</body>

</html>