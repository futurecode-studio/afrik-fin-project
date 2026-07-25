<div class="bg-[#f9f9ff] min-h-[70vh]">
    <section class="max-w-[1000px] mx-auto px-5 lg:px-8 py-14 lg:py-20">
        <p class="text-xs font-semibold tracking-widest uppercase text-[#0a2e8c]">Ressources & éducation</p>
        <h1 class="text-4xl lg:text-5xl font-extrabold text-[#001a61] mt-2">Glossaire Financier</h1>
        <p class="text-[#444652] mt-3 max-w-2xl">Les termes essentiels des marchés UEMOA / BRVM, expliqués simplement.</p>

        <div class="mt-8 flex flex-col lg:flex-row gap-4 lg:items-center">
            <div class="relative flex-1 max-w-xl">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#757683]">search</span>
                <input wire:model.live.debounce.300ms="search" type="search" placeholder="Rechercher un terme…"
                    class="w-full pl-11 pr-4 py-3 rounded-xl border border-[#c5c5d4] bg-white focus:border-[#001a61] outline-none">
            </div>
            <div class="flex flex-wrap gap-2">
                <button type="button" wire:click="$set('letter','all')" @class(['px-3 py-1.5 rounded-lg text-sm font-bold border', $letter==='all'?'bg-[#001a61] text-white border-[#001a61]':'bg-white border-[#c5c5d4] text-[#001a61]'])>Tous</button>
                @foreach ($letters as $L)
                    <button type="button" wire:click="$set('letter','{{ $L }}')" @class(['px-3 py-1.5 rounded-lg text-sm font-bold border', $letter===$L?'bg-[#001a61] text-white border-[#001a61]':'bg-white border-[#c5c5d4] text-[#001a61]'])>{{ $L }}</button>
                @endforeach
            </div>
        </div>

        <div class="mt-10 grid sm:grid-cols-2 gap-4">
            @forelse ($filtered as $term)
                <article class="bg-white border border-[#c5c5d4] rounded-xl p-5">
                    <h2 class="font-bold text-[#001a61] text-lg">{{ $term['term'] }}</h2>
                    <p class="text-sm text-[#444652] mt-2 leading-relaxed">{{ $term['def'] }}</p>
                </article>
            @empty
                <p class="text-[#757683] col-span-2">Aucun terme trouvé.</p>
            @endforelse
        </div>

        <div class="mt-12 rounded-xl border border-[#c5c5d4] bg-[#001a61] text-white p-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <p class="font-extrabold text-xl">Restez informé de l’actualité financière</p>
                <p class="text-white/70 text-sm mt-1">Analyses BRVM et ressources pédagogiques.</p>
            </div>
            <a href="{{ route('newsletter') }}" class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-[#ffbf00] text-[#261a00] font-bold">S’inscrire</a>
        </div>
    </section>
</div>
