<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin' }} — Africaine des Finances</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @livewireStyles
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .admin-page > main.container { max-width: none; margin: 0; padding: 0; }
        .admin-table thead th { color: #001a61; font-size: 0.7rem; letter-spacing: 0.06em; text-transform: uppercase; font-weight: 700; }
        .admin-table tbody tr { transition: background 0.2s ease; }
        .admin-table tbody tr:hover { background: rgba(240, 243, 255, 0.85); }
        [x-cloak] { display: none !important; }
        .fixed.inset-0 > .relative.bg-card,
        .fixed.inset-0 > .relative.bg-background,
        .fixed.inset-0 > .bg-card,
        .fixed.inset-0 > .bg-background,
        .fixed.inset-0 > .bg-white,
        .fixed.inset-0 .adf-modal-panel {
            background-color: #ffffff !important;
            opacity: 1 !important;
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
        }
        #admin-main { transition: opacity 0.12s ease; }
        html[data-admin-navigating] #admin-main {
            opacity: 0.45;
            pointer-events: none;
        }
        #admin-nav-progress {
            position: fixed; top: 0; left: 0; height: 3px; width: 0; z-index: 99999;
            background: linear-gradient(90deg, #ffbf00, #001a61);
            opacity: 0;
            transition: width 0.25s ease, opacity 0.15s ease;
        }
        html[data-admin-navigating] #admin-nav-progress {
            opacity: 1; width: 78%;
            transition: width 8s cubic-bezier(0.1, 0.4, 0.2, 1), opacity 0.15s ease;
        }
        html[data-admin-navigating='done'] #admin-nav-progress {
            width: 100%; opacity: 0;
            transition: width 0.15s ease, opacity 0.25s ease 0.1s;
        }
        /* Admin : affichage immédiat, sans animation d'entrée */
        .adf-shell-admin .adf-reveal {
            animation: none !important;
            opacity: 1 !important;
            transform: none !important;
        }
        /* Éditeur riche Quill — lisible pour non-devs */
        .adf-rich-editor .ql-toolbar.ql-snow {
            border: none !important;
            border-bottom: 1px solid #e7eeff !important;
            background: #f5f7ff;
            padding: 8px 10px !important;
            font-family: inherit;
        }
        .adf-rich-editor .ql-container.ql-snow {
            border: none !important;
            font-family: inherit;
            font-size: 0.925rem;
        }
        .adf-rich-editor .ql-editor {
            min-height: inherit;
            padding: 14px 16px;
            line-height: 1.55;
            color: #131c2a;
        }
        .adf-rich-editor .ql-editor.ql-blank::before {
            color: #757683;
            font-style: normal;
            left: 16px;
        }
        .adf-rich-editor .ql-snow .ql-stroke { stroke: #001a61; }
        .adf-rich-editor .ql-snow .ql-fill { fill: #001a61; }
        .adf-rich-editor .ql-snow.ql-toolbar button:hover,
        .adf-rich-editor .ql-snow .ql-toolbar button:hover,
        .adf-rich-editor .ql-snow.ql-toolbar button.ql-active,
        .adf-rich-editor .ql-snow .ql-toolbar button.ql-active {
            background: rgba(255, 191, 0, 0.25);
            border-radius: 6px;
        }
        .adf-rich-editor .ql-editor h2 { font-size: 1.35rem; font-weight: 800; color: #001a61; margin: 0.6em 0 0.35em; }
        .adf-rich-editor .ql-editor h3 { font-size: 1.1rem; font-weight: 700; color: #0a2e8c; margin: 0.5em 0 0.3em; }
        .adf-rich-editor .ql-editor ul { list-style: disc; padding-left: 1.4em; }
        .adf-rich-editor .ql-editor ol { list-style: decimal; padding-left: 1.4em; }
        .adf-rich-editor .ql-editor a { color: #0a2e8c; text-decoration: underline; }
        .adf-rich-editor .ql-editor blockquote {
            border-left: 3px solid #ffbf00;
            padding-left: 12px;
            color: #444652;
            margin: 0.5em 0;
        }
    </style>
</head>
<body class="font-sans antialiased adf-shell-admin text-[#131c2a]" x-data="{ sidebarOpen: false }">
    <div id="admin-nav-progress" aria-hidden="true"></div>

    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
        class="fixed inset-0 z-40 bg-[#001a61]/40 backdrop-blur-sm lg:hidden"></div>

    <aside
        class="fixed inset-y-0 left-0 z-50 w-64 adf-glass-dark text-white flex flex-col transition-transform duration-300 ease-soft -translate-x-full lg:translate-x-0"
        :class="sidebarOpen && '!translate-x-0'">
        <div class="px-5 py-5 border-b border-white/10 flex items-center gap-3 shrink-0">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 min-w-0 flex-1">
                <img src="{{ asset('assets/logo.png') }}" alt="Africaine des Finances"
                    class="h-10 w-auto object-contain shrink-0">
                <div class="min-w-0">
                    <p class="font-extrabold text-sm leading-tight truncate">Africaine des Finances</p>
                    <p class="text-[11px] text-white/60">Administration</p>
                </div>
            </a>
            <button type="button" @click="sidebarOpen = false" class="lg:hidden p-1 rounded hover:bg-white/10" aria-label="Fermer">
                <span class="material-symbols-outlined text-xl">close</span>
            </button>
        </div>

        @php
            $navSections = [
                [
                    'label' => null,
                    'items' => [
                        ['route' => 'admin.dashboard', 'match' => 'admin.dashboard', 'label' => 'Tableau de bord', 'icon' => 'dashboard', 'perm' => null],
                    ],
                ],
                [
                    'label' => 'Contenu',
                    'items' => [
                        ['route' => 'admin.events', 'match' => ['admin.events', 'admin.event.*'], 'label' => 'Événements', 'icon' => 'event', 'perm' => 'events.view'],
                        ['route' => 'admin.articles', 'match' => 'admin.articles', 'label' => 'Articles', 'icon' => 'newspaper', 'perm' => 'articles.view'],
                        ['route' => 'admin.formations', 'match' => 'admin.formations*', 'label' => 'Formations', 'icon' => 'school', 'perm' => 'formations.view'],
                    ],
                ],
                [
                    'label' => 'Académie',
                    'items' => [
                        ['route' => 'admin.learners', 'match' => 'admin.learners*', 'label' => 'Apprenants', 'icon' => 'groups', 'perm' => 'formations.view'],
                        ['route' => 'admin.academy.exercises', 'match' => 'admin.academy.exercises', 'label' => 'Exercices', 'icon' => 'grading', 'perm' => 'formations.view'],
                        ['route' => 'admin.academy.questions', 'match' => 'admin.academy.questions', 'label' => 'Questions', 'icon' => 'contact_support', 'perm' => 'formations.view'],
                        ['route' => 'admin.academy.engagement', 'match' => 'admin.academy.engagement', 'label' => 'Engagement', 'icon' => 'insights', 'perm' => 'formations.view'],
                        ['route' => 'admin.academy.formations', 'match' => 'admin.academy.formations', 'label' => 'Analyses', 'icon' => 'analytics', 'perm' => 'formations.view'],
                        ['route' => 'admin.academy.abandon', 'match' => 'admin.academy.abandon', 'label' => 'Abandon', 'icon' => 'trending_down', 'perm' => 'formations.view'],
                        ['route' => 'admin.academy.quiz', 'match' => 'admin.academy.quiz', 'label' => 'Quiz / Examens', 'icon' => 'quiz', 'perm' => 'formations.view'],
                        ['route' => 'admin.academy.videos', 'match' => 'admin.academy.videos', 'label' => 'Vidéos', 'icon' => 'videocam', 'perm' => 'formations.view'],
                    ],
                ],
                [
                    'label' => 'Marchés',
                    'items' => [
                        ['route' => 'admin.stock-data', 'match' => ['admin.stock-data', 'admin.market-advanced'], 'label' => 'Actions BRVM', 'icon' => 'monitoring', 'perm' => 'stock-data.view'],
                        ['route' => 'admin.government-bonds', 'match' => 'admin.government-bonds', 'label' => 'Obligations États', 'icon' => 'account_balance', 'perm' => 'government-bonds.view'],
                        ['route' => 'admin.sgi-sgo', 'match' => ['admin.sgi-sgo', 'admin.order-intents', 'admin.sgi-account-requests', 'admin.sgi-documents'], 'label' => 'SGI / SGO', 'icon' => 'account_balance', 'perm' => 'partners.view'],
                        ['route' => 'admin.partners', 'match' => 'admin.partners', 'label' => 'Partenaires', 'icon' => 'handshake', 'perm' => 'partners.view'],
                    ],
                ],
                [
                    'label' => 'Support',
                    'items' => [
                        ['route' => 'admin.contacts', 'match' => 'admin.contacts', 'label' => 'Messages contact', 'icon' => 'contact_mail', 'perm' => 'contacts.view'],
                        ['route' => 'admin.appointments', 'match' => 'admin.appointments', 'label' => 'Rendez-vous', 'icon' => 'calendar_month', 'perm' => 'appointments.view'],
                        ['route' => 'admin.job-applications', 'match' => 'admin.job-applications', 'label' => 'Candidatures', 'icon' => 'work', 'perm' => 'users.view'],
                        ['route' => 'admin.newsletters', 'match' => 'admin.newsletters', 'label' => 'Newsletters', 'icon' => 'mail', 'perm' => 'newsletters.view'],
                        ['route' => 'admin.transactions', 'match' => 'admin.transactions', 'label' => 'Transactions', 'icon' => 'payments', 'perm' => 'transactions.view'],
                    ],
                ],
                [
                    'label' => 'Système',
                    'items' => [
                        ['route' => 'admin.users', 'match' => 'admin.users', 'label' => 'Utilisateurs', 'icon' => 'group', 'perm' => 'users.view'],
                        ['route' => 'admin.roles', 'match' => 'admin.roles', 'label' => 'Rôles', 'icon' => 'admin_panel_settings', 'perm' => 'roles.view'],
                        ['route' => 'admin.team', 'match' => 'admin.team', 'label' => 'Équipe', 'icon' => 'badge', 'perm' => 'team.view'],
                        ['route' => 'admin.social-links', 'match' => 'admin.social-links', 'label' => 'Réseaux sociaux', 'icon' => 'share', 'perm' => null],
                        ['route' => 'admin.statistics', 'match' => 'admin.statistics', 'label' => 'Statistiques', 'icon' => 'bar_chart', 'perm' => 'statistics.view'],
                        ['route' => 'admin.site-services', 'match' => 'admin.site-services', 'label' => 'Services site', 'icon' => 'widgets', 'perm' => 'team.view'],
                        ['route' => 'admin.api-config', 'match' => 'admin.api-config', 'label' => 'Config API', 'icon' => 'vpn_key', 'perm' => 'users.view'],
                        ['route' => 'admin.settings', 'match' => 'admin.settings', 'label' => 'Paramètres', 'icon' => 'settings', 'perm' => null],
                    ],
                ],
            ];
        @endphp
        <nav class="flex-1 min-h-0 overflow-y-auto overscroll-contain p-3 space-y-4">
            @foreach ($navSections as $section)
                @php
                    $visible = collect($section['items'])->filter(function ($item) {
                        if (! $item['perm']) {
                            return true;
                        }

                        return auth()->user()->can($item['perm'])
                            || auth()->user()->hasRole('super_admin')
                            || auth()->user()->hasRole('admin');
                    });
                @endphp
                @continue($visible->isEmpty())
                <div class="space-y-1">
                    @if ($section['label'])
                        <p class="px-3 pt-1 pb-1 text-[10px] font-bold uppercase tracking-wider text-white/40">{{ $section['label'] }}</p>
                    @endif
                    @foreach ($visible as $item)
                        @php
                            $active = is_array($item['match'])
                                ? request()->routeIs(...$item['match'])
                                : request()->routeIs($item['match']);
                        @endphp
                        <a href="{{ route($item['route']) }}"
                            wire:navigate.hover
                            @click="sidebarOpen = false"
                            @class([
                                'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition',
                                'bg-white/15 text-white' => $active,
                                'text-white/75 hover:bg-white/10 hover:text-white' => ! $active,
                            ])>
                            <span class="material-symbols-outlined text-[20px]">{{ $item['icon'] }}</span>
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </div>
            @endforeach
        </nav>

        <div class="px-4 py-4 border-t border-white/10 text-xs text-white/50 shrink-0 truncate">
            {{ Auth::user()->name }}
        </div>
    </aside>

    <div class="min-h-screen lg:pl-64 flex flex-col">
        <header class="sticky top-0 z-20 adf-glass-nav px-4 lg:px-8 py-3 flex items-center justify-between gap-3">
            <div class="flex items-center gap-2 min-w-0">
                <button type="button" @click="sidebarOpen = true"
                    class="lg:hidden p-2 -ml-1 rounded-xl hover:bg-[#e7eeff]/80 text-[#001a61] transition" aria-label="Menu">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <h1 class="text-lg font-extrabold text-[#001a61] truncate">{{ $title ?? 'Administration' }}</h1>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('home') }}" class="text-sm font-medium text-[#444652] hover:text-[#001a61] px-2 transition">Retour au Site</a>
                <span class="hidden sm:inline text-sm font-semibold text-[#001a61] px-2 truncate max-w-[10rem]">{{ Auth::user()->name }}</span>
                <a href="{{ route('admin.profile') }}" wire:navigate.hover class="text-sm font-semibold text-[#001a61] px-2 transition hover:underline">Profil</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1 text-sm font-bold border border-[#001a61]/30 bg-white/50 backdrop-blur text-[#001a61] px-3 py-1.5 rounded-xl hover:bg-[#e7eeff] transition">
                        <span class="material-symbols-outlined text-base">logout</span>
                        Déconnexion
                    </button>
                </form>
            </div>
        </header>

        <main id="admin-main" class="flex-1 px-4 lg:px-8 py-6">
            @yield('content')
        </main>
    </div>

    @livewireScripts
    @include('partials.sweetalert')
    <script>
        (function () {
            const html = document.documentElement;
            let doneTimer;
            document.addEventListener('livewire:navigate', () => {
                clearTimeout(doneTimer);
                html.dataset.adminNavigating = '1';
            });
            document.addEventListener('livewire:navigated', () => {
                html.dataset.adminNavigating = 'done';
                doneTimer = setTimeout(() => delete html.dataset.adminNavigating, 220);
            });
            document.addEventListener('livewire:navigate-error', () => {
                delete html.dataset.adminNavigating;
            });
        })();
    </script>
    @stack('scripts')
</body>
</html>
