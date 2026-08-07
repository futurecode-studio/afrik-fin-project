{{-- Stitch footer + données de l'ancien footer --}}
@php
    $socialLinks = cache()->remember('social_links_active', 600, fn () =>
        \App\Models\SocialLink::where('is_active', true)->orderBy('order')->get()
    );
@endphp

<footer class="relative mt-auto border-t border-white/40 text-[#131c2a] overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-[#dae3f6]/90 via-[#e8eefc]/85 to-[#f0f4ff]/90 backdrop-blur-xl"></div>
    <div class="absolute -top-24 -right-16 w-64 h-64 rounded-full bg-[#ffbf00]/15 blur-3xl pointer-events-none"></div>
    <div class="relative px-5 lg:px-16 max-w-[1280px] mx-auto pt-16 lg:pt-20 pb-10">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-10">
            {{-- Brand --}}
            <div class="lg:col-span-4 space-y-4">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('assets/logo.jpg') }}" alt="Africaine des Finances" class="h-10 w-auto object-contain">
                    <span class="text-xl font-bold text-[#001a61]">Africaine des Finances</span>
                </div>
                <p class="text-base text-[#444652] max-w-sm leading-relaxed">
                    Votre partenaire de confiance pour l'éducation financière, les analyses de marché
                    et les services de conseil en Afrique. Régulé par l'AMF-UMOA.
                </p>
                @if ($socialLinks->isNotEmpty())
                    <div class="flex flex-wrap gap-3 pt-1">
                        @foreach ($socialLinks as $socialLink)
                            <a href="{{ $socialLink->url }}" target="_blank" rel="noopener noreferrer"
                                title="{{ $socialLink->getPlatformLabel() }}"
                                class="w-10 h-10 rounded-full border border-[#c5c5d4] flex items-center justify-center text-[#001a61] hover:bg-[#001a61] hover:text-white hover:border-[#001a61] transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    {!! $socialLink->getIconHtml() !!}
                                </svg>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Liens rapides --}}
            <div class="lg:col-span-2">
                <h5 class="font-bold text-[#001a61] mb-6">Liens Rapides</h5>
                <ul class="space-y-3">
                    <li><a class="text-[#444652] hover:text-[#001a61] hover:underline transition-all text-sm font-medium" href="{{ route('home') }}">Accueil</a></li>
                    <li><a class="text-[#444652] hover:text-[#001a61] hover:underline transition-all text-sm font-medium" href="{{ route('guide-bourse') }}">Guide Complet de la Bourse</a></li>
                    <li><a class="text-[#444652] hover:text-[#001a61] hover:underline transition-all text-sm font-medium" href="{{ route('ouverture-compte-sgi') }}">Ouvrir un compte titre</a></li>
                    <li><a class="text-[#444652] hover:text-[#001a61] hover:underline transition-all text-sm font-medium" href="{{ route('formations') }}">Formations</a></li>
                    <li><a class="text-[#444652] hover:text-[#001a61] hover:underline transition-all text-sm font-medium" href="{{ route('events-list') }}">Événements</a></li>
                    <li><a class="text-[#444652] hover:text-[#001a61] hover:underline transition-all text-sm font-medium" href="{{ route('investir.actions-brvm') }}">Investir — Actions BRVM</a></li>
                    <li><a class="text-[#444652] hover:text-[#001a61] hover:underline transition-all text-sm font-medium" href="{{ route('partenaires') }}">Partenaires</a></li>
                    <li><a class="text-[#444652] hover:text-[#001a61] hover:underline transition-all text-sm font-medium" href="{{ route('team') }}">Équipe</a></li>
                </ul>
            </div>

            {{-- Services --}}
            <div class="lg:col-span-2">
                <h5 class="font-bold text-[#001a61] mb-6">Services</h5>
                <ul class="space-y-3">
                    <li><a class="text-[#444652] hover:text-[#001a61] hover:underline transition-all text-sm font-medium" href="{{ route('services-formation') }}">E-Learning Financier</a></li>
                    <li><a class="text-[#444652] hover:text-[#001a61] hover:underline transition-all text-sm font-medium" href="{{ route('actualites') }}">Analyses de Marché</a></li>
                    <li><a class="text-[#444652] hover:text-[#001a61] hover:underline transition-all text-sm font-medium" href="{{ route('services-conseil') }}">Conseil en Investissement</a></li>
                    <li><a class="text-[#444652] hover:text-[#001a61] hover:underline transition-all text-sm font-medium" href="{{ route('services-bourse') }}">Données Boursières</a></li>
                    <li><a class="text-[#444652] hover:text-[#001a61] hover:underline transition-all text-sm font-medium" href="{{ route('faq') }}">FAQ</a></li>
                    <li><a class="text-[#444652] hover:text-[#001a61] hover:underline transition-all text-sm font-medium" href="{{ route('glossaire') }}">Glossaire</a></li>
                    <li><a class="text-[#444652] hover:text-[#001a61] hover:underline transition-all text-sm font-medium" href="{{ route('outils.frais') }}">Frais & fiscalité</a></li>
                    <li><a class="text-[#444652] hover:text-[#001a61] hover:underline transition-all text-sm font-medium" href="{{ route('services.mandat') }}">Gestion sous mandat</a></li>
                    <li><a class="text-[#444652] hover:text-[#001a61] hover:underline transition-all text-sm font-medium" href="{{ route('services.institutionnel') }}">Institutionnel</a></li>
                    <li><a class="text-[#444652] hover:text-[#001a61] hover:underline transition-all text-sm font-medium" href="{{ route('marches.screener') }}">Screener</a></li>
                    <li><a class="text-[#444652] hover:text-[#001a61] hover:underline transition-all text-sm font-medium" href="{{ route('marches.bibliotheque') }}">Bibliothèque</a></li>
                    <li><a class="text-[#444652] hover:text-[#001a61] hover:underline transition-all text-sm font-medium" href="{{ route('marches.produits-structures') }}">Produits structurés</a></li>
                    <li><a class="text-[#444652] hover:text-[#001a61] hover:underline transition-all text-sm font-medium" href="{{ route('marches.analyse-pro') }}">Graphique Pro</a></li>
                    <li><a class="text-[#444652] hover:text-[#001a61] hover:underline transition-all text-sm font-medium" href="{{ route('mise-en-relation') }}">Mise en relation</a></li>
                    <li><a class="text-[#444652] hover:text-[#001a61] hover:underline transition-all text-sm font-medium" href="{{ route('contact') }}">Contact</a></li>
                </ul>
            </div>

            {{-- Newsletter --}}
            <div class="lg:col-span-4 space-y-4">
                <h5 class="font-bold text-[#001a61] mb-2">Newsletter</h5>
                <p class="text-sm text-[#444652]">Restez informé des dernières actualités financières</p>
                <div class="adf-footer-newsletter">
                    @livewire('newsletter-subscribe')
                </div>
            </div>
        </div>

        {{-- Contact coords (ancien footer) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12 pt-10 border-t border-[#c5c5d4]">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-[#001a61]/10 rounded-full flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[#001a61] text-[20px]">mail</span>
                </div>
                <div>
                    <p class="text-xs text-[#757683]">Email</p>
                    <a href="mailto:contact@africainedesfinances.com" class="text-sm font-medium text-[#131c2a] hover:text-[#001a61]">
                        contact@africainedesfinances.com
                    </a>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-[#001a61]/10 rounded-full flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[#001a61] text-[20px]">call</span>
                </div>
                <div>
                    <p class="text-xs text-[#757683]">Téléphone</p>
                    <p class="text-sm font-medium">+229 01 44 21 82 09 / 01 66 55 51 21</p>
                    <p class="text-xs text-[#757683] mt-1">+229 01 48 71 88 51</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 bg-[#001a61]/10 rounded-full flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[#001a61] text-[20px]">location_on</span>
                </div>
                <div>
                    <p class="text-xs text-[#757683]">Adresse</p>
                    <p class="text-sm font-medium">Fidjrossè Hlazounto, lot 3990, parcelle g</p>
                    <p class="text-xs text-[#757683] mt-1">Fin des pavés du CEG l’Entente</p>
                    <p class="text-xs text-[#757683] mt-0.5">Cotonou, Bénin</p>
                </div>
            </div>
        </div>

        {{-- Légal + agrément (ancien footer) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
            <div class="bg-white/50 rounded-lg p-4 border border-[#c5c5d4] hover:border-[#001a61]/30 transition-all">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-[#001a61]/10 rounded-lg flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[#001a61]">description</span>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-[#001a61] mb-3 text-base">Informations légales</p>
                        <div class="space-y-2">
                            <p class="flex flex-wrap items-center gap-2">
                                <span class="text-[#757683] text-xs">N° RCCM:</span>
                                <span class="font-bold text-sm">RB/COT/21 B 31296</span>
                            </p>
                            <p class="flex flex-wrap items-center gap-2">
                                <span class="text-[#757683] text-xs">N° IFU:</span>
                                <span class="font-bold text-sm">3202113721309</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white/50 rounded-lg p-4 border border-[#c5c5d4] hover:border-[#001a61]/30 transition-all">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-[#001a61]/10 rounded-lg flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[#001a61]">verified_user</span>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-[#001a61] mb-3 text-base">Agrément officiel</p>
                        <div class="space-y-2">
                            <p class="flex flex-wrap items-center gap-2">
                                <span class="text-[#757683] text-xs">N° AA/2022-03</span>
                                <span class="font-bold text-sm">Décision N°CREFMPF/22/143</span>
                            </p>
                            <p class="flex flex-wrap items-center gap-2">
                                <span class="text-[#757683] text-xs">Autorité</span>
                                <span class="font-bold text-sm">AMF-UMOA (ex CREPMF)</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="relative px-5 lg:px-16 max-w-[1280px] mx-auto py-6 border-t border-[#c5c5d4]/70 flex flex-col md:flex-row justify-between items-center text-[#444652] text-sm gap-4">
        <p>© {{ date('Y') }} <span class="font-semibold text-[#001a61]">Africaine des Finances</span>. Tous droits réservés. Agréé AMF-UMOA AA/2022-03.</p>
        <div class="flex flex-wrap gap-4 md:gap-6 justify-center">
            <a href="{{ route('search') }}" class="hover:text-[#001a61] transition-colors">Recherche</a>
            <a href="{{ route('aide') }}" class="hover:text-[#001a61] transition-colors">Aide</a>
            <a href="{{ route('agrements') }}" class="hover:text-[#001a61] transition-colors">Agréments</a>
            <a href="{{ route('legal.show', 'mentions-legales') }}" class="hover:text-[#001a61] transition-colors">Mentions</a>
            <a href="{{ route('legal.show', 'confidentialite') }}" class="hover:text-[#001a61] transition-colors">Confidentialité</a>
            <a href="{{ route('legal.show', 'cgu') }}" class="hover:text-[#001a61] transition-colors">CGU</a>
            <a href="{{ route('newsletter') }}" class="hover:text-[#001a61] transition-colors">Newsletter</a>
            <a href="{{ route('contact') }}" class="hover:text-[#001a61] transition-colors">Contact</a>
        </div>
    </div>
</footer>
