<div class="bg-[#f9f9ff] text-[#131c2a] min-h-screen">
    @include('partials.investir-nav')

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pt-12 pb-6">
        <p class="text-sm font-semibold tracking-widest uppercase text-[#0a2e8c] mb-3">Réseau</p>
        <h1 class="text-3xl md:text-4xl font-extrabold text-[#001a61]">Partenaires agréés</h1>
        <p class="mt-3 text-[#444652] max-w-2xl">SGI et SGO pour exécuter vos ordres et souscrire aux OPCVM sur l’espace UEMOA.</p>
    </section>

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pb-6">
        <div class="bg-white border border-[#c5c5d4] rounded-xl p-4 flex flex-col md:flex-row gap-3">
            <input type="search" wire:model.live.debounce.300ms="search" placeholder="Rechercher un partenaire…"
                class="flex-1 rounded-lg border-[#c5c5d4] focus:border-[#001a61] focus:ring-[#001a61]">
            <select wire:model.live="type" class="rounded-lg border-[#c5c5d4] focus:border-[#001a61] focus:ring-[#001a61]">
                <option value="">Tous les types</option>
                @foreach ($types as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </section>

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pb-20">
        @forelse ($partners as $partner)
            <article class="bg-white border border-[#c5c5d4] rounded-xl p-5 mb-4 flex flex-col sm:flex-row gap-4 items-start">
                <div class="w-16 h-16 rounded-lg bg-white border border-[#c5c5d4] flex items-center justify-center overflow-hidden shrink-0 p-1">
                    @if ($partner->logo_url)
                        <img src="{{ $partner->logo_url }}" alt="{{ $partner->nom }}" class="w-full h-full object-contain">
                    @else
                        <span class="material-symbols-outlined text-[#001a61] text-3xl">business</span>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-bold text-[#001a61] text-lg">
                        @if ($partner->getKey())
                            <a href="{{ route('partenaires.show', $partner->id) }}" class="hover:underline">{{ $partner->nom }}</a>
                        @else
                            {{ $partner->nom }}
                        @endif
                    </h3>
                    @if ($partner->description)
                        <p class="text-sm text-[#444652] mt-1">{{ plain_text($partner->description, 160) }}</p>
                    @endif
                    <div class="mt-3 flex flex-wrap gap-x-4 gap-y-1 text-sm text-[#757683]">
                        @if ($partner->contact)<span>{{ $partner->contact }}</span>@endif
                        @if ($partner->email)<a href="mailto:{{ $partner->email }}" class="text-[#001a61] hover:underline">{{ $partner->email }}</a>@endif
                    </div>
                </div>
                <div class="flex flex-col gap-2 shrink-0">
                    @if ($partner->getKey())
                        <a href="{{ route('partenaires.show', $partner->id) }}"
                            class="text-sm font-bold text-white bg-[#001a61] px-4 py-2 rounded hover:bg-[#051c5b] transition text-center">Voir la fiche</a>
                    @endif
                    @if ($partner->website)
                        <a href="{{ $partner->website }}" target="_blank" rel="noopener"
                            class="text-sm font-bold text-[#001a61] border border-[#001a61] px-4 py-2 rounded hover:bg-[#e7eeff] transition text-center">Site web</a>
                    @endif
                </div>
            </article>
        @empty
            <div class="bg-white border border-dashed border-[#c5c5d4] rounded-xl p-10 text-center">
                <span class="material-symbols-outlined text-5xl text-[#c5c5d4]">handshake</span>
                <h2 class="text-xl font-bold text-[#001a61] mt-4">Aucun partenaire publié pour le moment</h2>
                <p class="text-[#444652] mt-2 max-w-md mx-auto">La liste des SGI / SGO sera disponible dès validation administrative. En attendant, contactez-nous pour une orientation.</p>
                <a href="{{ route('contact') }}" class="inline-block mt-6 bg-[#001a61] text-white font-bold px-6 py-3 rounded">Nous contacter</a>
            </div>
        @endforelse
    </section>
</div>
