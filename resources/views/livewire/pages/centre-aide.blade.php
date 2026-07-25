<div class="max-w-3xl mx-auto px-4 py-16">
    <h1 class="text-3xl md:text-4xl font-extrabold text-[#001a61]">Centre d’aide</h1>
    <p class="text-[#444652] mt-2">Réponses rapides sur le compte, les formations et le support.</p>

    <input type="search" wire:model.live.debounce.250ms="q" placeholder="Rechercher une question…"
        class="mt-8 w-full rounded-xl border border-[#c5c5d4] px-4 py-3">

    <div class="mt-8 space-y-3">
        @forelse ($topics as $t)
            <details class="adf-card-static p-4 group">
                <summary class="cursor-pointer list-none flex items-start justify-between gap-3">
                    <div>
                        <p class="text-[11px] font-bold uppercase text-[#0a2e8c]">{{ $t['cat'] }}</p>
                        <p class="font-bold text-[#001a61] mt-0.5">{{ $t['q'] }}</p>
                    </div>
                    <span class="material-symbols-outlined text-[#757683] group-open:rotate-180 transition">expand_more</span>
                </summary>
                <p class="mt-3 text-sm text-[#444652]">{{ $t['a'] }}</p>
            </details>
        @empty
            <p class="text-[#757683]">Aucun résultat.</p>
        @endforelse
    </div>

    <div class="mt-10 flex flex-wrap gap-3">
        <a href="{{ route('faq') }}" class="px-5 py-3 rounded-xl border border-[#c5c5d4] font-bold text-[#001a61]">FAQ</a>
        <a href="{{ route('contact') }}" class="px-5 py-3 rounded-xl bg-[#001a61] text-white font-bold">Contact</a>
        <a href="{{ route('support.ticket') }}" class="px-5 py-3 rounded-xl border border-[#c5c5d4] font-bold text-[#001a61]">Ouvrir un ticket</a>
    </div>
</div>
