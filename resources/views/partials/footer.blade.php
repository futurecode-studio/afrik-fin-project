{{-- Footer simplifié --}}
@php
    $socialLinks = cache()->remember('social_links_active', 600, fn () =>
        \App\Models\SocialLink::where('is_active', true)->orderBy('order')->get()
    );
    $diasporaUrl = config('services.diaspora_funnel.url');
    $diasporaLabel = config('services.diaspora_funnel.label');
@endphp

<footer class="relative mt-auto bg-gradient-to-br from-[#001a61] via-[#001a61] to-[#0a2e8c] text-white overflow-hidden border-t border-white/10">
    <div class="absolute -top-24 -right-16 w-64 h-64 rounded-full bg-[#ffbf00]/10 blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-1/4 w-96 h-96 rounded-full bg-[#0a2e8c]/40 blur-3xl pointer-events-none"></div>

    <div class="relative px-5 lg:px-16 max-w-[1280px] mx-auto pt-14 lg:pt-16 pb-10">
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-10">
            <div class="space-y-5">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('assets/logo.jpg') }}" alt="Africaine des Finances" class="h-10 w-auto object-contain rounded">
                    <span class="text-xl font-bold text-white">Africaine des Finances</span>
                </div>

                <p class="text-sm md:text-base text-white/80 max-w-md leading-relaxed">
                    Éducation financière, accompagnement à l'investissement et mise en relation avec l'écosystème agréé AMF-UMOA.
                </p>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('services') }}" class="inline-flex items-center rounded-full border border-white/25 bg-white/10 px-4 py-2 text-sm font-semibold text-white hover:bg-white/20 hover:border-white/40 transition">Services</a>
                    <a href="{{ route('formations') }}" class="inline-flex items-center rounded-full border border-white/25 bg-white/10 px-4 py-2 text-sm font-semibold text-white hover:bg-white/20 hover:border-white/40 transition">Formations</a>
                    <a href="{{ route('contact') }}" class="inline-flex items-center rounded-full border border-white/25 bg-white/10 px-4 py-2 text-sm font-semibold text-white hover:bg-white/20 hover:border-white/40 transition">Contact</a>
                </div>

                @if ($socialLinks->isNotEmpty())
                    <div class="flex flex-wrap gap-3 pt-1">
                        @foreach ($socialLinks as $socialLink)
                            <a href="{{ $socialLink->url }}" target="_blank" rel="noopener noreferrer"
                                title="{{ $socialLink->getPlatformLabel() }}"
                                class="w-10 h-10 rounded-full border border-white/25 flex items-center justify-center text-white hover:bg-[#ffbf00] hover:text-[#261a00] hover:border-[#ffbf00] transition-all">
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

            <div>
                <h5 class="font-bold text-[#ffbf00] mb-4">Navigation</h5>
                <ul class="space-y-3 text-sm">
                    <li><a class="text-white/80 hover:text-[#ffbf00] transition" href="{{ route('home') }}">Accueil</a></li>
                    <li><a class="text-white/80 hover:text-[#ffbf00] transition" href="{{ route('services') }}">Services</a></li>
                    <li><a class="text-white/80 hover:text-[#ffbf00] transition" href="{{ route('formations') }}">Formations</a></li>
                    <li><a class="text-white/80 hover:text-[#ffbf00] transition" href="{{ route('events-list') }}">Événements</a></li>
                    <li><a class="text-white/80 hover:text-[#ffbf00] transition" href="{{ route('actualites') }}">Actualités</a></li>
                    <li><a class="text-white/80 hover:text-[#ffbf00] transition" href="{{ route('team') }}">À propos</a></li>
                    <li><a class="text-white/80 hover:text-[#ffbf00] transition" href="{{ route('contact') }}">Contact</a></li>
                </ul>
            </div>

            <div class="space-y-4">
                <h5 class="font-bold text-[#ffbf00]">Outils</h5>
                <ul class="space-y-3 text-sm">
                    <li><a class="text-white/80 hover:text-[#ffbf00] transition" href="{{ route('guide-bourse') }}">Guide</a></li>
                    <li><a class="text-white/80 hover:text-[#ffbf00] transition" href="{{ route('outils.interets-composes') }}">Simulateur intérêts composés</a></li>
                </ul>
            </div>

            <div class="space-y-4">
                <h5 class="font-bold text-[#ffbf00]">Contact</h5>
                <div class="space-y-3 text-sm text-white/80">
                    <p><span class="font-semibold text-white">Email :</span> <a href="mailto:contact@africainedesfinances.com" class="hover:text-[#ffbf00] transition">contact@africainedesfinances.com</a></p>
                    <p><span class="font-semibold text-white">Téléphone :</span> +229 01 44 21 82 09 / +229 01 66 55 51 21</p>
                    <p><span class="font-semibold text-white">Adresse :</span> Fidjrossè Hlazounto, Cotonou</p>
                    <p><span class="font-semibold text-white">Agrément :</span> AMF-UMOA AA/2022-03</p>
                </div>

                <a href="{{ $diasporaUrl }}" target="_blank" rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 bg-[#ffbf00] text-[#261a00] font-bold px-4 py-2.5 rounded-xl hover:brightness-105 transition text-sm">
                    {{ $diasporaLabel }}
                </a>
            </div>
        </div>

        <div class="border-t border-white/15 mt-10 pt-5 flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
            <p class="text-sm text-white/60">© {{ date('Y') }} Africaine des Finances. Tous droits réservés.</p>
            <div class="flex flex-wrap gap-4 text-sm">
                <a href="{{ route('home') }}" class="text-white/80 hover:text-[#ffbf00] transition">Accueil</a>
                <a href="{{ route('legal.show', 'mentions-legales') }}" class="text-white/80 hover:text-[#ffbf00] transition">Mentions légales</a>
                <a href="{{ route('legal.show', 'politique-de-confidentialite') }}" class="text-white/80 hover:text-[#ffbf00] transition">Confidentialité</a>
            </div>
        </div>
    </div>
</footer>
