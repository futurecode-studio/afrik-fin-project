<div class="bg-[#f9f9ff] min-h-screen">
    @include('partials.markets-nav')
    <section class="max-w-[1200px] mx-auto px-5 lg:px-8 py-12">
        <h1 class="text-3xl font-extrabold text-[#001a61]">Screener d’Investissement</h1>
        <p class="text-[#444652] mt-2">Filtrez les actions BRVM à partir des cotations synchronisées (Mansa → DB).</p>

        <div class="mt-6 bg-white border border-[#c5c5d4] rounded-xl p-4 grid md:grid-cols-3 lg:grid-cols-6 gap-3">
            <select wire:model.live="sector" class="rounded-lg border-[#c5c5d4] text-sm">
                <option value="">Tous secteurs</option>
                @foreach ($sectors as $s)<option value="{{ $s }}">{{ $s }}</option>@endforeach
            </select>
            <input type="number" wire:model.live.debounce.400ms="min_price" placeholder="Prix min" class="rounded-lg border-[#c5c5d4] text-sm">
            <input type="number" wire:model.live.debounce.400ms="max_price" placeholder="Prix max" class="rounded-lg border-[#c5c5d4] text-sm">
            <input type="number" step="0.1" wire:model.live.debounce.400ms="min_variation" placeholder="Var % min" class="rounded-lg border-[#c5c5d4] text-sm">
            <input type="number" wire:model.live.debounce.400ms="min_volume" placeholder="Volume min" class="rounded-lg border-[#c5c5d4] text-sm">
            <select wire:model.live="sort" class="rounded-lg border-[#c5c5d4] text-sm">
                <option value="variation_percent">Tri variation</option>
                <option value="volume">Tri volume</option>
                <option value="current_price">Tri prix</option>
                <option value="symbol">Tri symbole</option>
            </select>
        </div>

        <div class="mt-4 flex gap-2">
            <button type="button" wire:click="$set('dir','desc')" @class(['px-3 py-1.5 text-sm font-bold rounded border', $dir==='desc'?'bg-[#001a61] text-white':'bg-white'])>Desc</button>
            <button type="button" wire:click="$set('dir','asc')" @class(['px-3 py-1.5 text-sm font-bold rounded border', $dir==='asc'?'bg-[#001a61] text-white':'bg-white'])>Asc</button>
            <span class="text-sm text-[#757683] self-center ml-2">{{ $results->count() }} opportunité(s)</span>
        </div>

        <div class="mt-6 bg-white border border-[#c5c5d4] rounded-xl overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-[#eef3ff] text-[#001a61] text-xs uppercase tracking-wider">
                    <tr>
                        <th class="text-left px-4 py-3">Titre</th>
                        <th class="text-left px-4 py-3">Secteur</th>
                        <th class="text-right px-4 py-3">Prix</th>
                        <th class="text-right px-4 py-3">Var %</th>
                        <th class="text-right px-4 py-3">Volume</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($results as $s)
                        <tr class="border-t border-[#c5c5d4] hover:bg-[#f0f3ff]">
                            <td class="px-4 py-3">
                                <a href="{{ route('marches.action', $s->symbol) }}" class="font-bold text-[#001a61] hover:underline">{{ $s->symbol }}</a>
                                <p class="text-xs text-[#757683]">{{ $s->company_name }}</p>
                            </td>
                            <td class="px-4 py-3">{{ $s->sector ?: '—' }}</td>
                            <td class="px-4 py-3 text-right font-semibold">{{ number_format($s->current_price, 0, ',', ' ') }}</td>
                            <td class="px-4 py-3 text-right font-bold {{ $s->variation_percent >= 0 ? 'text-green-700' : 'text-red-700' }}">{{ number_format($s->variation_percent, 2, ',', ' ') }}%</td>
                            <td class="px-4 py-3 text-right">{{ number_format($s->volume, 0, ',', ' ') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-[#757683]">Aucun titre ne correspond aux filtres.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
