{{-- Footer simplifié --}}
@php
    $socialLinks = cache()->remember('social_links_active', 600, fn () =>
        \App\Models\SocialLink::where('is_active', true)->orderBy('order')->get()
    );
    $diasporaUrl = config('services.diaspora_funnel.url');
    $diasporaLabel = config('services.diaspora_funnel.label');
@endphp

<footer class="relative mt-auto border-t border-white/40 text-[#131c2a] overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-[#dae3f6]/90 via-[#e8eefc]/85 to-[#f0f4ff]/90 backdrop-blur-xl"></div>
    <div class="absolute -top-24 -right-16 w-64 h-64 rounded-full bg-[#ffbf00]/15 blur-3xl pointer-events-none"></div>

    <div class="relative px-5 lg:px-16 max-w-[1280px] mx-auto pt-14 lg:pt-16 pb-10">
        <div class="grid gap-10 lg:grid-cols-[1.6fr_1fr]">
            <div class="space-y-5">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('assets/logo.jpg') }}" alt="Africaine des Finances" class="h-10 w-auto object-contain">
                    <span class="text-xl font-bold text-[#001a61]">Africaine des Finances</span>
                </div>

                <p class="text-sm md:text-base text-[#444652] max-w-md leading-relaxed">
                    Éducation financière, accompagnement à l'investissement et mise en relation avec l'écosystème agréé AMF-UMOA.
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

            <div class="space-y-4">
                <h5 class="font-bold text-[#001a61]">Contact</h5>
                <div class="space-y-3 text-sm text-[#444652]">
                    <p><span class="font-semibold text-[#001a61]">Email :</span> <a href="mailto:contact@africainedesfinances.com" class="hover:text-[#001a61]">contact@africainedesfinances.com</a></p>
                    <p><span class="font-semibold text-[#001a61]">Téléphone :</span> +229 01 44 21 82 09</p>
                    <p><span class="font-semibold text-[#001a61]">Adresse :</span> Fidjrossè Hlazounto, Cotonou</p>
                    <p><span class="font-semibold text-[#001a61]">Agrément :</span> AMF-UMOA AA/2022-03</p>
                </div>

                <a href="{{ $diasporaUrl }}" target="_blank" rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 bg-[#001a61] text-white font-bold px-4 py-2.5 rounded-xl hover:bg-[#0a2e8c] transition text-sm">
                    {{ $diasporaLabel }}
                </a>
            </div>
        </div>

        <div class="border-t border-[#c5c5d4] mt-10 pt-5 flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
            <p class="text-sm text-[#757683]">© {{ date('Y') }} Africaine des Finances. Tous droits réservés.</p>
            <div class="flex flex-wrap gap-4 text-sm">
                <a href="{{ route('home') }}" class="text-[#444652] hover:text-[#001a61] transition">Accueil</a>
                <a href="{{ route('contact') }}" class="text-[#444652] hover:text-[#001a61] transition">Contact</a>
                <a href="{{ route('legal.show', 'mentions-legales') }}" class="text-[#444652] hover:text-[#001a61] transition">Mentions légales</a>
                <a href="{{ route('legal.show', 'politique-de-confidentialite') }}" class="text-[#444652] hover:text-[#001a61] transition">Confidentialité</a>
                <a href="{{ route('support.ticket') }}" class="text-[#444652] hover:text-[#001a61] transition">Support</a>
            </div>
        </div>
    </div>
</footer>
