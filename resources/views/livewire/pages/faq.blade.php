<div class="bg-[#f9f9ff] min-h-[70vh]">
    <section class="max-w-[900px] mx-auto px-5 lg:px-8 py-16 lg:py-24">
        <p class="text-xs font-semibold tracking-widest uppercase text-[#0a2e8c]">Centre d’aide</p>
        <h1 class="text-4xl lg:text-5xl font-extrabold text-[#001a61] mt-2 tracking-tight">Comment pouvons-nous vous aider ?</h1>
        <div class="mt-8 relative max-w-xl">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#757683]">search</span>
            <input wire:model.live.debounce.300ms="search" type="search" placeholder="Rechercher une question…"
                class="w-full pl-11 pr-4 py-3 rounded-xl border border-[#c5c5d4] bg-white focus:border-[#001a61] focus:ring-2 focus:ring-[#001a61]/15 outline-none">
        </div>

        <h2 class="text-xl font-bold text-[#001a61] mt-12 mb-4">Parcourir par catégorie</h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <button type="button" wire:click="$set('category','all')"
                @class(['text-left p-4 rounded-xl border transition', $category==='all' ? 'bg-[#001a61] text-white border-[#001a61]' : 'bg-white border-[#c5c5d4] hover:border-[#001a61]'])>
                <span class="material-symbols-outlined">apps</span>
                <p class="font-bold mt-2">Toutes</p>
            </button>
            @foreach ($categories as $key => $cat)
                <button type="button" wire:click="$set('category','{{ $key }}')"
                    @class(['text-left p-4 rounded-xl border transition', $category===$key ? 'bg-[#001a61] text-white border-[#001a61]' : 'bg-white border-[#c5c5d4] hover:border-[#001a61]'])>
                    <span class="material-symbols-outlined">{{ $cat['icon'] }}</span>
                    <p class="font-bold mt-2">{{ $cat['label'] }}</p>
                    <p class="text-xs mt-1 opacity-80">{{ $cat['desc'] }}</p>
                </button>
            @endforeach
        </div>

        <h2 class="text-xl font-bold text-[#001a61] mt-12 mb-4">Questions</h2>
        <div class="space-y-3" x-data="{ open: null }">
            @forelse ($filtered as $i => $item)
                <div class="bg-white border border-[#c5c5d4] rounded-xl overflow-hidden">
                    <button type="button" class="w-full flex items-center justify-between gap-3 px-5 py-4 text-left font-semibold text-[#001a61]"
                        @click="open = open === {{ $i }} ? null : {{ $i }}">
                        <span>{{ $item['q'] }}</span>
                        <span class="material-symbols-outlined text-[#757683]" x-text="open === {{ $i }} ? 'expand_less' : 'expand_more'">expand_more</span>
                    </button>
                    <div class="px-5 pb-4 text-[#444652] text-sm leading-relaxed border-t border-[#e7eeff]" x-show="open === {{ $i }}" x-cloak>
                        {{ $item['a'] }}
                    </div>
                </div>
            @empty
                <p class="text-[#757683]">Aucun résultat pour votre recherche.</p>
            @endforelse
        </div>

        <div class="mt-12 p-6 rounded-xl bg-[#e7eeff] border border-[#c5c5d4] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <p class="font-bold text-[#001a61]">Besoin d’une assistance personnalisée ?</p>
                <p class="text-sm text-[#444652] mt-1">Contactez-nous, notre équipe vous répond.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#001a61] text-white font-bold text-sm">Contact</a>
            </div>
        </div>
    </section>
</div>
