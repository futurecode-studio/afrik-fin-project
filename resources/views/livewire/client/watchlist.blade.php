<div>
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#001a61]">Ma liste de suivi</h1>
            <p class="text-[#444652] mt-2">Titres BRVM que vous suivez — cours issus de la base.</p>
        </div>
        <a href="{{ route('marches.cotations') }}" class="text-sm font-bold text-[#001a61] hover:underline">Voir toutes les cotations →</a>
    </div>

    <section class="bg-white border border-[#c5c5d4] rounded-xl overflow-hidden mb-10">
        <div class="px-5 py-4 border-b border-[#c5c5d4] flex items-center justify-between">
            <h2 class="font-bold text-[#001a61]">{{ $items->count() }} titre(s)</h2>
        </div>
        @forelse ($items as $item)
            @php $s = $item->stock; @endphp
            @if ($s)
                <div class="flex items-center gap-4 px-5 py-4 border-t border-[#c5c5d4] first:border-0">
                    <div class="flex-1 min-w-0">
                        <a href="{{ route('marches.action', $s->symbol) }}" class="font-bold text-[#001a61] hover:underline">{{ $s->symbol }}</a>
                        <p class="text-sm text-[#757683] truncate">{{ $s->company_name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold">{{ number_format((float)$s->current_price, 0, ',', ' ') }}</p>
                        <p @class(['text-sm font-bold', 'text-green-600' => $s->variation_percent >= 0, 'text-red-600' => $s->variation_percent < 0])>
                            {{ $s->variation_percent >= 0 ? '+' : '' }}{{ number_format((float)$s->variation_percent, 2) }}%
                        </p>
                    </div>
                    <button type="button" wire:click="remove({{ $s->id }})" class="text-red-600 text-sm font-bold px-2">Retirer</button>
                </div>
            @endif
        @empty
            <p class="px-5 py-10 text-center text-[#757683]">Votre liste est vide. Ajoutez des titres ci-dessous.</p>
        @endforelse
    </section>

    <section>
        <h2 class="font-bold text-lg text-[#001a61] mb-3">Ajouter une valeur</h2>
        <input type="search" wire:model.live.debounce.300ms="search" placeholder="Symbole ou société…"
            class="w-full md:w-96 mb-4 rounded-lg border-[#c5c5d4] focus:border-[#001a61] focus:ring-[#001a61]">
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @forelse ($suggestions as $s)
                <div class="bg-white border border-[#c5c5d4] rounded-xl p-4 flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="font-bold text-[#001a61]">{{ $s->symbol }}</p>
                        <p class="text-xs text-[#757683] truncate">{{ $s->company_name }}</p>
                    </div>
                    <button type="button" wire:click="add({{ $s->id }})"
                        class="shrink-0 text-sm font-bold bg-[#001a61] text-white px-3 py-1.5 rounded hover:bg-[#0a2e8c]">Ajouter</button>
                </div>
            @empty
                <p class="text-[#757683] text-sm col-span-full">Aucun titre à suggérer.</p>
            @endforelse
        </div>
    </section>
</div>
