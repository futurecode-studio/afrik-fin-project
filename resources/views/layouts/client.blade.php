<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Mon Espace' }} — Africaine des Finances</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased adf-shell-client text-[#131c2a]" x-data="{ sidebarOpen: false }">
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
        class="fixed inset-0 z-40 bg-[#001a61]/35 backdrop-blur-sm lg:hidden"></div>

    <aside
        class="fixed inset-y-0 left-0 z-50 w-64 adf-glass-strong flex flex-col transition-transform duration-300 ease-soft -translate-x-full lg:translate-x-0 border-r border-white/50"
        :class="sidebarOpen && '!translate-x-0'">
        <div class="px-5 py-5 border-b border-[#c5c5d4]/50 flex items-center gap-3 shrink-0">
            <a href="{{ route('client.my-events') }}" class="flex items-center gap-3 min-w-0 flex-1">
                <img src="{{ asset('assets/logo.jpg') }}" alt="Africaine des Finances"
                    class="h-10 w-auto object-contain shrink-0">
                <div class="min-w-0">
                    <p class="text-sm font-extrabold text-[#001a61] leading-tight truncate">Africaine des Finances</p>
                    <p class="text-[11px] text-[#757683]">Espace client</p>
                </div>
            </a>
            <button type="button" @click="sidebarOpen = false" class="lg:hidden p-1 rounded hover:bg-[#e7eeff]" aria-label="Fermer">
                <span class="material-symbols-outlined text-[#001a61]">close</span>
            </button>
        </div>

        @php
            $nav = [
                ['route' => 'client.dashboard', 'match' => 'client.dashboard', 'label' => 'Accueil', 'icon' => 'dashboard'],
                ['route' => 'client.my-events', 'match' => ['client.my-events', 'client.event.ticket'], 'label' => 'Webinaires', 'icon' => 'live_tv'],
                ['route' => 'client.formations', 'match' => ['client.formations', 'client.formation', 'client.formation.*', 'client.quiz.*', 'client.exam.*'], 'label' => 'Formations', 'icon' => 'school'],
                ['route' => 'client.ordres', 'match' => 'client.ordres', 'label' => 'Souscriptions', 'icon' => 'contract_edit'],
                ['route' => 'client.ask-instructor', 'match' => 'client.ask-instructor', 'label' => 'Formateur', 'icon' => 'contact_support'],
                ['route' => 'client.certificates', 'match' => 'client.certificates*', 'label' => 'Certificat', 'icon' => 'workspace_premium'],
            ];
        @endphp
        <nav class="flex-1 min-h-0 overflow-y-auto overscroll-contain p-3 space-y-0.5">
            @foreach ($nav as $item)
                @php
                    $active = is_array($item['match'])
                        ? request()->routeIs(...$item['match'])
                        : request()->routeIs($item['match']);
                @endphp
                <a href="{{ route($item['route']) }}"
                    @click="sidebarOpen = false"
                    @class([
                        'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition',
                        'bg-[#001a61] text-white shadow-md shadow-[#001a61]/20' => $active,
                        'text-[#444652] hover:bg-[#e7eeff]/80 hover:text-[#001a61]' => ! $active,
                    ])>
                    <span class="material-symbols-outlined text-[20px]">{{ $item['icon'] }}</span>
                    <span class="flex-1 truncate">{{ $item['label'] }}</span>
                    @if (! empty($item['soon']))
                        <span @class([
                            'text-[9px] font-extrabold uppercase tracking-wide px-1.5 py-0.5 rounded',
                            'bg-[#ffbf00] text-[#261a00]' => $active,
                            'bg-[#e7eeff] text-[#001a61]' => ! $active,
                        ])>Bientôt</span>
                    @endif
                </a>
            @endforeach
        </nav>

        <div class="px-4 py-4 border-t border-[#c5c5d4]/50 shrink-0 space-y-2">
            <p class="text-xs text-[#757683] truncate">{{ Auth::user()->name }}</p>
            <a href="{{ route('home') }}" class="block text-xs font-semibold text-[#001a61] hover:underline">Retour au site</a>
        </div>
    </aside>

    <div class="min-h-screen lg:pl-64 flex flex-col">
        <header class="sticky top-0 z-30 adf-glass-nav px-4 lg:px-8 py-3 flex items-center justify-between gap-3">
            <div class="flex items-center gap-2 min-w-0">
                <button type="button" @click="sidebarOpen = true"
                    class="lg:hidden p-2 -ml-1 rounded-xl hover:bg-[#e7eeff]/80 text-[#001a61] transition" aria-label="Menu">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <h1 class="text-lg font-extrabold text-[#001a61] truncate">{{ $title ?? 'Mon Espace' }}</h1>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('client.profile') }}" class="hidden sm:inline text-sm font-semibold text-[#001a61] truncate max-w-[9rem]">{{ Auth::user()->name }}</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1 text-sm font-bold border border-[#001a61]/30 bg-white/50 backdrop-blur text-[#001a61] px-3 py-1.5 rounded-xl hover:bg-[#e7eeff] transition">
                        <span class="material-symbols-outlined text-base">logout</span>
                        <span class="hidden sm:inline">Déconnexion</span>
                    </button>
                </form>
            </div>
        </header>

        <main class="flex-1 px-4 lg:px-8 py-8 max-w-[1280px] w-full adf-reveal">
            @php
                $nextClientWebinar = \App\Models\Event::query()
                    ->whereIn('status', ['published', 'ongoing'])
                    ->where('starts_at', '>=', now())
                    ->where(function ($query) {
                        $query->whereIn('event_type', ['online', 'hybrid'])
                            ->orWhere('category', 'like', '%web%')
                            ->orWhere('title', 'like', '%web%');
                    })
                    ->orderBy('starts_at')
                    ->first();
            @endphp
            @if ($nextClientWebinar)
                <a href="{{ route('event-detail', $nextClientWebinar->slug) }}"
                    class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3 rounded-xl border border-[#c5c5d4] bg-white px-4 py-3 hover:border-[#001a61] transition">
                    <span class="inline-flex items-center gap-2 text-sm text-[#444652]">
                        <span class="material-symbols-outlined text-[#001a61]">live_tv</span>
                        <span>
                            Prochain webinaire :
                            <strong class="text-[#001a61]">{{ $nextClientWebinar->title }}</strong>
                        </span>
                    </span>
                    <span class="text-xs font-bold text-[#0a2e8c]">{{ $nextClientWebinar->starts_at?->format('d/m/Y H:i') }}</span>
                </a>
            @endif
            @yield('content')
        </main>
    </div>

    @livewireScripts
    @include('partials.sweetalert')
    @stack('scripts')
</body>
</html>
