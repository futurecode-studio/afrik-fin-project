<!DOCTYPE html>
<html lang="fr">

<head>
    <link rel="stylesheet" href="{{ asset('assets/css/index.css') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Africaine des Finances' }}</title>
    <meta name="description"
        content="Plateforme financière africaine de référence. Formations e-learning, données boursières BRVM en temps réel, analyses de marché et conseil en investissement.">
    <meta name="author" content="Africaine des Finances">
    <meta name="keywords"
        content="finance africaine, BRVM, bourse, formation financière, e-learning, investissement, conseil financier, Afrique de l'Ouest, UEMOA">

    <meta property="og:title" content="Africaine des Finances - Formation, Bourse BRVM &amp; Conseil Financier">
    <meta property="og:description"
        content="Plateforme financière africaine de référence. Formations e-learning, données boursières BRVM en temps réel, analyses de marché et conseil en investissement.">
    <meta property="og:type" content="website">

    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @livewireStyles

    <style>
        [x-cloak] { display: none !important; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
    </style>
</head>

<body class="font-sans antialiased text-[#131c2a] overflow-x-hidden">
    <div id="root" class="adf-shell-public relative isolate overflow-x-hidden">
        <div class="pointer-events-none absolute inset-0 overflow-hidden" aria-hidden="true">
            <div class="adf-orb w-72 h-72 bg-[#ffbf00]/30 top-24 -left-20"></div>
            <div class="adf-orb w-96 h-96 bg-[#0a2e8c]/20 bottom-10 -right-24" style="animation-delay: 2s"></div>
        </div>

        <div class="relative z-10 min-h-screen flex flex-col overflow-x-hidden">
            @include('partials.navbar')

            <main class="flex-1 adf-reveal min-w-0">
                @yield('content')
            </main>

            @include('partials.footer')
        </div>
    </div>

    @include('partials.jeudi-popup')

    @livewireScripts
    @include('partials.sweetalert')
    @stack('scripts')
</body>

</html>
