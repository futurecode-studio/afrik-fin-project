<div class="bg-[#f9f9ff] text-[#131c2a] min-h-screen" x-data>
    @include('partials.markets-nav')

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pt-10 pb-6">
        <h1 class="text-3xl md:text-4xl font-extrabold text-[#001a61]">Cotations des Actions</h1>
        <p class="text-[#444652] mt-2">{{ $stocks->count() }} titres BRVM — données synchronisées en base</p>
    </section>

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pb-6">
        <div class="bg-white border border-[#c5c5d4] rounded-xl p-4 flex flex-col md:flex-row gap-3">
            <input type="search" wire:model.live.debounce.300ms="search" placeholder="Rechercher symbole ou société…"
                class="flex-1 rounded-lg border-[#c5c5d4] focus:border-[#001a61] focus:ring-[#001a61]">
            <select wire:model.live="sector" class="rounded-lg border-[#c5c5d4] focus:border-[#001a61] focus:ring-[#001a61]">
                <option value="">Tous les secteurs</option>
                @foreach ($sectors as $sec)
                    <option value="{{ $sec }}">{{ $sec }}</option>
                @endforeach
            </select>
        </div>
    </section>

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pb-20">
        <div class="overflow-x-auto bg-white border border-[#c5c5d4] rounded-xl">
            <table class="w-full text-sm min-w-[900px]">
                <thead class="bg-[#e7eeff] text-[#001a61]">
                    <tr>
                        @foreach ([
                            'symbol' => 'Symbole',
                            'company_name' => 'Société',
                            'sector' => 'Secteur',
                            'current_price' => 'Cours',
                            'variation_percent' => 'Var. %',
                            'volume' => 'Volume',
                            'market_cap' => 'Capit.',
                            'high_price' => 'Plus Haut',
                            'low_price' => 'Plus Bas',
                        ] as $col => $label)
                            <th class="text-left px-3 py-3 cursor-pointer select-none" wire:click="sort('{{ $col }}')">
                                {{ $label }}
                                @if($sortBy === $col)<span class="text-xs">{{ $sortDir === 'asc' ? '↑' : '↓' }}</span>@endif
                            </th>
                        @endforeach
                        <th class="px-3 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stocks as $s)
                        <tr class="border-t border-[#c5c5d4] hover:bg-[#f0f3ff]">
                            <td class="px-3 py-3 font-bold text-[#001a61]">{{ $s->symbol }}</td>
                            <td class="px-3 py-3">{{ $s->company_name }}</td>
                            <td class="px-3 py-3 text-[#444652]">{{ $s->sector }}</td>
                            <td class="px-3 py-3">{{ number_format((float)$s->current_price, 0, ',', ' ') }}</td>
                            <td class="px-3 py-3 font-semibold {{ $s->variation_percent >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $s->variation_percent >= 0 ? '+' : '' }}{{ number_format((float)$s->variation_percent, 2) }}%
                            </td>
                            <td class="px-3 py-3">{{ number_format((int)$s->volume, 0, ',', ' ') }}</td>
                            <td class="px-3 py-3">{{ $s->formatMoney($s->market_cap !== null ? (float) $s->market_cap : null) }}</td>
                            <td class="px-3 py-3">{{ $s->formatMoney($s->effectiveHigh()) }}</td>
                            <td class="px-3 py-3">{{ $s->formatMoney($s->effectiveLow()) }}</td>
                            <td class="px-3 py-3">
                                <a href="{{ route('marches.action', $s->symbol) }}" class="text-[#001a61] font-bold hover:underline">Fiche</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="10" class="px-4 py-10 text-center text-[#757683]">Aucun titre trouvé</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
