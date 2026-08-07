{{-- TopAppBar + glass nav --}}
<div x-data="{ mobileOpen: false }" class="sticky top-0 z-50">
    <header class="bg-[#001a61] text-white border-b border-white/10">
        <div class="flex justify-between items-center w-full px-5 lg:px-16 h-9 text-[11px] lg:text-[12px]">
            <div class="flex items-center gap-2 shrink-0">
                <span class="material-symbols-outlined text-[15px] text-[#ffbf00]">verified_user</span>
                <span class="font-bold tracking-wide hidden sm:inline">AMF-UMOA AA/2022-03</span>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <button type="button" class="hover:text-[#ffbf00] transition-colors flex items-center gap-1" aria-label="Langue">
                    <span class="material-symbols-outlined text-[15px]">language</span>
                    <span class="font-medium">FR</span>
                </button>
                <a href="mailto:contact@africainedesfinances.com" class="hover:text-[#ffbf00] transition-colors" aria-label="Email">
                    <span class="material-symbols-outlined text-[15px]">mail</span>
                </a>
                <div class="relative" x-data="{ phoneOpen: false }" @keydown.escape.window="phoneOpen = false">
                    <button type="button" @click="phoneOpen = !phoneOpen"
                        class="hover:text-[#ffbf00] transition-colors flex items-center"
                        :aria-expanded="phoneOpen.toString()"
                        aria-label="Téléphone"
                        aria-haspopup="true">
                        <span class="material-symbols-outlined text-[15px]">call</span>
                    </button>
                    <div x-show="phoneOpen" x-cloak @click.outside="phoneOpen = false"
                        x-transition.opacity.duration.150ms
                        class="absolute right-0 top-full mt-2 z-[60] w-64 rounded-xl bg-white text-[#131c2a] shadow-lg border border-[#c5c5d4] p-3 text-left">
                        <p class="text-[10px] font-bold uppercase tracking-wide text-[#757683] mb-2">Nous appeler</p>
                        <div class="space-y-1.5">
                            <a href="tel:+2290144218209" class="block rounded-lg px-2.5 py-2 hover:bg-[#e7eeff] transition">
                                <span class="block text-[11px] text-[#757683]">Principal</span>
                                <span class="text-sm font-bold text-[#001a61]">+229 01 44 21 82 09</span>
                            </a>
                            <a href="tel:+2290166555121" class="block rounded-lg px-2.5 py-2 hover:bg-[#e7eeff] transition">
                                <span class="block text-[11px] text-[#757683]">Mobile</span>
                                <span class="text-sm font-bold text-[#001a61]">+229 01 66 55 51 21</span>
                            </a>
                            <a href="tel:+2290148718851" class="block rounded-lg px-2.5 py-2 hover:bg-[#e7eeff] transition">
                                <span class="block text-[11px] text-[#757683]">Ligne 2</span>
                                <span class="text-sm font-bold text-[#001a61]">+229 01 48 71 88 51</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('partials.nav-market-strip')

        @if(($navHeadlines ?? collect())->isNotEmpty())
            <div class="bg-[#0a2e8c] border-t border-white/10">
                <div class="flex items-stretch max-w-[1280px] mx-auto px-0 lg:px-16">
                    <a href="{{ route('actualites') }}"
                        class="shrink-0 flex items-center gap-1.5 px-4 bg-[#ffbf00] text-[#001a61] text-[11px] font-extrabold uppercase tracking-wide">
                        <span class="material-symbols-outlined text-[16px]">campaign</span>
                        <span class="hidden sm:inline">Actualités</span>
                    </a>
                    <div class="flex-1 overflow-hidden min-w-0 py-2">
                        <div class="adf-ticker-wrap">
                            <div class="adf-ticker flex gap-10 items-center whitespace-nowrap text-[12px]">
                                @foreach($navHeadlines as $headline)
                                    <a href="{{ route('actualite-detail', $headline->slug) }}"
                                        class="inline-flex items-center gap-2 hover:text-[#ffbf00] transition-colors">
                                        @if($headline->is_featured)
                                            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-[#ffbf00]/20 text-[#ffbf00]">À la une</span>
                                        @elseif($headline->categorie)
                                            <span class="text-[10px] uppercase opacity-70">{{ $headline->categorie }}</span>
                                        @endif
                                        <span class="font-medium">{{ $headline->titre }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </header>

    <nav class="adf-glass-nav w-full">
        <div class="flex justify-between items-center w-full px-5 lg:px-16 max-w-[1280px] mx-auto h-20">
            <a href="{{ route('home') }}" class="flex items-center gap-2 group shrink-0">
                <img src="{{ asset('assets/logo.png') }}" alt="Africaine des Finances"
                    class="h-10 w-auto object-contain transition duration-300 group-hover:scale-105 group-hover:drop-shadow-md" />
                <span class="text-xl lg:text-2xl font-extrabold text-[#001a61] leading-tight hidden sm:block">
                    Africaine des Finances
                </span>
            </a>

            <div class="hidden lg:flex items-center gap-8">
                <a href="{{ route('search') }}"
                    class="text-base font-medium transition-colors duration-200 pb-1 {{ request()->routeIs('search') ? 'text-[#001a61] border-b-2 border-[#001a61] font-bold' : 'text-[#444652] hover:text-[#001a61]' }}"
                    title="Recherche" aria-label="Recherche">
                    <span class="material-symbols-outlined text-[22px] align-middle">search</span>
                </a>
                <a href="{{ route('marches.index') }}"
                    class="text-base font-medium transition-colors duration-200 pb-1 {{ request()->routeIs('marches.*') ? 'text-[#001a61] border-b-2 border-[#001a61] font-bold' : 'text-[#444652] hover:text-[#001a61]' }}">
                    Marchés
                </a>

                <a href="{{ route('services') }}"
                    class="text-base font-medium transition-colors duration-200 pb-1 {{ request()->routeIs('services', 'services*', 'service-detail', 'services.*') ? 'text-[#001a61] border-b-2 border-[#001a61] font-bold' : 'text-[#444652] hover:text-[#001a61]' }}">
                    Services
                </a>

                <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <button type="button"
                        class="text-base font-medium transition-colors duration-200 pb-1 flex items-center gap-1 {{ request()->routeIs('investir.*', 'marches.*', 'guide-bourse') ? 'text-[#001a61] font-bold' : 'text-[#444652] hover:text-[#001a61]' }}">
                        Investir
                        <span class="material-symbols-outlined text-[18px] transition-transform"
                            :class="open ? 'rotate-180' : ''">expand_more</span>
                    </button>
                    <div x-show="open" x-cloak
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        class="absolute left-0 mt-3 w-72 adf-glass-strong rounded-2xl overflow-hidden z-50 shadow-glass-lg">
                        <a href="{{ route('investir.hub') }}"
                            class="block px-4 py-3 text-sm text-[#131c2a] hover:bg-[#e7eeff]/80 transition">Investir — Hub</a>
                        <a href="{{ route('guide-bourse') }}"
                            class="block px-4 py-3 text-sm font-bold text-[#001a61] hover:bg-[#ffbf00]/25 transition border-b border-[#c5c5d4]/60">
                            Guide Complet de la Bourse
                            <span class="block text-[11px] font-medium text-[#757683]">BRVM · actions · obligations · glossaire</span>
                        </a>
                        <a href="{{ route('ouverture-compte-sgi') }}"
                            class="block px-4 py-3 text-sm font-bold text-[#001a61] hover:bg-[#ffbf00]/25 transition border-b border-[#c5c5d4]/60">
                            Ouvrir un compte titre
                            <span class="block text-[11px] font-medium text-[#757683]">SGI · FCP · accompagnement ADF</span>
                        </a>
                        <a href="{{ config('services.diaspora_funnel.url') }}" target="_blank" rel="noopener noreferrer"
                            class="block px-4 py-3 text-sm font-bold text-[#001a61] hover:bg-[#ffbf00]/25 transition border-b border-[#c5c5d4]/60">
                            {{ config('services.diaspora_funnel.label') }}
                            <span class="block text-[11px] font-medium text-[#757683]">Tunnel diaspora · diagnostic gratuit</span>
                        </a>
                        <a href="{{ route('investir.comment') }}"
                            class="block px-4 py-3 text-sm text-[#131c2a] hover:bg-[#e7eeff] transition">Comment investir</a>
                        <a href="{{ route('investir.profil-test') }}"
                            class="block px-4 py-3 text-sm text-[#131c2a] hover:bg-[#e7eeff] transition">Diagnostic</a>
                        <a href="{{ route('investir.opcvm') }}"
                            class="block px-4 py-3 text-sm text-[#131c2a] hover:bg-[#e7eeff] transition">FCP / OPCVM</a>
                        <a href="{{ route('marches.index') }}"
                            class="block px-4 py-3 text-sm text-[#131c2a] hover:bg-[#e7eeff] transition">Marchés financiers</a>
                        <a href="{{ route('marches.cotations') }}"
                            class="block px-4 py-3 text-sm text-[#131c2a] hover:bg-[#e7eeff] transition">Cotations Actions</a>
                        <a href="{{ route('marches.carte') }}"
                            class="block px-4 py-3 text-sm text-[#131c2a] hover:bg-[#e7eeff] transition">Carte du marché</a>
                        <a href="{{ route('marches.calendrier') }}"
                            class="block px-4 py-3 text-sm text-[#131c2a] hover:bg-[#e7eeff] transition">Calendrier financier</a>
                        <a href="{{ route('investir.partenaires') }}"
                            class="block px-4 py-3 text-sm text-[#131c2a] hover:bg-[#e7eeff] transition">Partenaires agréés</a>
                    </div>
                </div>

                <a href="{{ route('formations') }}"
                    class="text-base font-medium transition-colors duration-200 pb-1 {{ request()->routeIs('formations*', 'formation-detail') ? 'text-[#001a61] border-b-2 border-[#001a61] font-bold' : 'text-[#444652] hover:text-[#001a61]' }}">
                    Formations
                </a>
                <a href="{{ route('events-list') }}"
                    class="text-base font-medium transition-colors duration-200 pb-1 {{ request()->routeIs('events-list', 'event-detail', 'event.*') ? 'text-[#001a61] border-b-2 border-[#001a61] font-bold' : 'text-[#444652] hover:text-[#001a61]' }}">
                    Événements
                </a>
                <a href="{{ route('actualites') }}"
                    class="text-base font-medium transition-colors duration-200 pb-1 {{ request()->routeIs('actualites*', 'actualite-detail') ? 'text-[#001a61] border-b-2 border-[#001a61] font-bold' : 'text-[#444652] hover:text-[#001a61]' }}">
                    Actualités
                </a>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('panier') }}" class="relative p-2 rounded hover:bg-[#e7eeff] transition" aria-label="Panier">
                    <span class="material-symbols-outlined text-[26px] text-[#001a61]">shopping_cart</span>
                    @php $cartCount = app(\App\Services\FormationCartService::class)->count(); @endphp
                    @if ($cartCount > 0)
                        <span class="absolute -top-0.5 -right-0.5 min-w-[1.1rem] h-[1.1rem] px-1 rounded-full bg-[#ffbf00] text-[#001a61] text-[10px] font-bold flex items-center justify-center">{{ $cartCount }}</span>
                    @endif
                </a>
                <div class="hidden lg:flex items-center gap-3">
                    @auth
                        <a href="{{ Auth::user()->canAccessAdminPanel() ? route('admin.dashboard') : route('client.dashboard') }}"
                            class="text-[#444652] font-medium hover:text-[#001a61] transition-colors text-base px-3 py-2">
                            {{ Auth::user()->canAccessAdminPanel() ? 'Administration' : 'Mon Espace' }}
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="text-[#444652] font-medium hover:text-[#001a61] transition-colors text-base px-3 py-2">
                                Déconnexion
                            </button>
                        </form>
                    @else
                        <a href="{{ route('connexion') }}"
                            class="text-[#444652] font-medium hover:text-[#001a61] transition-colors text-base px-4 py-2">
                            Connexion
                        </a>
                        <a href="{{ config('services.diaspora_funnel.url') }}" target="_blank" rel="noopener noreferrer"
                            class="adf-btn-primary text-base whitespace-nowrap px-5 py-3">
                            {{ config('services.diaspora_funnel.label') }}
                        </a>
                    @endauth
                </div>

                <button type="button" class="lg:hidden p-2 rounded hover:bg-[#e7eeff] transition"
                    @click="mobileOpen = !mobileOpen" :aria-expanded="mobileOpen.toString()"
                    aria-label="Menu">
                    <span class="material-symbols-outlined text-[28px] text-[#001a61]"
                        x-text="mobileOpen ? 'close' : 'menu'"></span>
                </button>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div x-show="mobileOpen" x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="lg:hidden border-t border-[#c5c5d4]/60 adf-glass-strong px-5 py-4 space-y-1">
            <a href="{{ route('search') }}" class="block py-3 text-[#131c2a] font-medium">Recherche</a>
            <a href="{{ route('aide') }}" class="block py-3 text-[#131c2a] font-medium">Aide</a>
            <a href="{{ route('investir.hub') }}" class="block py-3 text-[#131c2a] font-medium">Investir</a>
            <a href="{{ route('guide-bourse') }}" class="block py-3 pl-4 font-bold text-[#001a61]">Guide Complet de la Bourse</a>
            <a href="{{ route('ouverture-compte-sgi') }}" class="block py-3 pl-4 font-bold text-[#001a61]">Ouvrir un compte titre</a>
            <a href="{{ config('services.diaspora_funnel.url') }}" target="_blank" rel="noopener noreferrer"
                class="block py-3 pl-4 font-bold text-[#001a61]">{{ config('services.diaspora_funnel.label') }}</a>
            <a href="{{ route('investir.opcvm') }}" class="block py-3 pl-4 text-[#444652]">FCP / OPCVM</a>
            <a href="{{ route('marches.index') }}" class="block py-3 text-[#131c2a] font-medium">Marchés</a>
            <a href="{{ route('marches.cotations') }}" class="block py-3 pl-4 text-[#444652]">Cotations</a>
            <a href="{{ route('marches.carte') }}" class="block py-3 pl-4 text-[#444652]">Carte du marché</a>
            <a href="{{ route('marches.calendrier') }}" class="block py-3 pl-4 text-[#444652]">Calendrier</a>
            <a href="{{ route('investir.partenaires') }}" class="block py-3 pl-4 text-[#444652]">Partenaires</a>
            <a href="{{ route('services') }}" class="block py-3 text-[#131c2a] font-medium">Services</a>
            <a href="{{ route('formations') }}" class="block py-3 text-[#131c2a] font-medium">Formations</a>
            <a href="{{ route('events-list') }}" class="block py-3 text-[#131c2a] font-medium">Événements</a>
            <a href="{{ route('actualites') }}" class="block py-3 text-[#131c2a] font-medium">Actualités</a>
            <a href="{{ route('contact') }}" class="block py-3 text-[#131c2a] font-medium">Contact</a>
            <div class="pt-3 border-t border-[#c5c5d4] space-y-2">
                @auth
                    <a href="{{ Auth::user()->canAccessAdminPanel() ? route('admin.dashboard') : route('client.dashboard') }}"
                        class="block py-3 font-medium text-[#001a61]">
                        {{ Auth::user()->canAccessAdminPanel() ? 'Administration' : 'Mon Espace' }}
                    </a>
                @else
                    <a href="{{ route('connexion') }}" class="block py-3 font-medium text-[#444652]">Connexion</a>
                    <a href="{{ config('services.diaspora_funnel.url') }}" target="_blank" rel="noopener noreferrer"
                        class="block text-center bg-[#0a2e8c] text-white font-bold px-5 py-3 rounded">
                        {{ config('services.diaspora_funnel.label') }}
                    </a>
                @endauth
            </div>
        </div>
    </nav>
</div>
