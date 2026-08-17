<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Cours' }} — Africaine des Finances</title>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; vertical-align: middle; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased adf-shell-client text-[#131c2a]">
    <header class="sticky top-0 z-40 adf-glass-nav">
        <div class="max-w-[1440px] mx-auto px-4 lg:px-6 h-14 flex items-center justify-between gap-4">
            <div class="flex items-center gap-4 min-w-0">
                <a href="{{ route('client.formations') }}" class="flex items-center gap-2 shrink-0 group">
                    <span class="material-symbols-outlined text-[#001a61] text-2xl transition group-hover:scale-110">school</span>
                    <span class="font-extrabold text-[#001a61] hidden sm:inline">Africaine des Finances</span>
                </a>
                <span class="text-[#c5c5d4] hidden sm:inline">|</span>
                <a href="{{ route('client.formations') }}" class="text-sm font-bold text-[#001a61] border-b-2 border-[#ffbf00] pb-0.5">Mes formations</a>
            </div>
            <div class="flex items-center gap-2 sm:gap-3">
                <a href="{{ route('client.formations') }}" class="text-sm font-medium text-[#444652] hover:text-[#001a61] hidden sm:inline transition">Mes formations</a>
                <a href="{{ route('support.ticket') }}" class="p-2 rounded-xl hover:bg-[#e7eeff]/80 transition" title="Support">
                    <span class="material-symbols-outlined text-[#001a61]">help</span>
                </a>
                <span class="text-sm font-semibold text-[#001a61] truncate max-w-[8rem]">{{ Auth::user()->name }}</span>
            </div>
        </div>
    </header>

    <main class="adf-reveal">
        @yield('content')
    </main>

    @livewireScripts
    @include('partials.sweetalert')
    @stack('scripts')
</body>
</html>
