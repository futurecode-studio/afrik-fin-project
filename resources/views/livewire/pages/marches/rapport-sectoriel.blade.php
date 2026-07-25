<div class="bg-[#f9f9ff] min-h-screen">
    @include('partials.markets-nav')
    <section class="max-w-[1200px] mx-auto px-5 lg:px-8 py-12">
        <h1 class="text-3xl font-extrabold text-[#001a61]">Rapport d’Analyse Sectorielle</h1>
        <p class="text-[#444652] mt-2">Synthèse calculée depuis les cotations BRVM en base.</p>

        <div class="mt-6 flex flex-wrap gap-2">
            @foreach ($report as $sec)
                <button type="button" wire:click="$set('sector','{{ $sec['name'] }}')"
                    @class(['px-3 py-1.5 rounded-lg text-sm font-bold border', ($selected['name'] ?? '')===$sec['name']?'bg-[#001a61] text-white border-[#001a61]':'bg-white border-[#c5c5d4]'])>
                    {{ $sec['name'] }} ({{ $sec['count'] }})
                </button>
            @endforeach
        </div>

        @if ($selected)
            <div class="mt-8 grid lg:grid-cols-4 gap-4">
                <div class="bg-white border border-[#c5c5d4] rounded-xl p-5"><p class="text-xs text-[#757683]">Titres</p><p class="text-2xl font-extrabold text-[#001a61]">{{ $selected['count'] }}</p></div>
                <div class="bg-white border border-[#c5c5d4] rounded-xl p-5"><p class="text-xs text-[#757683]">Var. moyenne</p><p class="text-2xl font-extrabold {{ $selected['avg_variation']>=0?'text-green-700':'text-red-700' }}">{{ number_format($selected['avg_variation'],2,',',' ') }}%</p></div>
                <div class="bg-white border border-[#c5c5d4] rounded-xl p-5"><p class="text-xs text-[#757683]">Volume</p><p class="text-2xl font-extrabold text-[#001a61]">{{ number_format($selected['total_volume'],0,',',' ') }}</p></div>
                <div class="bg-white border border-[#c5c5d4] rounded-xl p-5"><p class="text-xs text-[#757683]">Meilleur / Pire</p><p class="text-sm font-bold text-green-700 mt-1">{{ $selected['best']->symbol ?? '—' }}</p><p class="text-sm font-bold text-red-700">{{ $selected['worst']->symbol ?? '—' }}</p></div>
            </div>

            <div class="mt-6 bg-white border border-[#c5c5d4] rounded-xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-[#eef3ff] text-xs uppercase text-[#001a61]"><tr><th class="text-left px-4 py-3">Symbole</th><th class="text-left px-4 py-3">Société</th><th class="text-right px-4 py-3">Prix</th><th class="text-right px-4 py-3">Var %</th><th class="text-right px-4 py-3">Volume</th></tr></thead>
                    <tbody>
                        @foreach ($selected['stocks'] as $s)
                            <tr class="border-t border-[#c5c5d4]">
                                <td class="px-4 py-2.5"><a class="font-bold text-[#001a61]" href="{{ route('marches.action', $s->symbol) }}">{{ $s->symbol }}</a></td>
                                <td class="px-4 py-2.5">{{ $s->company_name }}</td>
                                <td class="px-4 py-2.5 text-right">{{ number_format($s->current_price,0,',',' ') }}</td>
                                <td class="px-4 py-2.5 text-right font-bold {{ $s->variation_percent>=0?'text-green-700':'text-red-700' }}">{{ number_format($s->variation_percent,2,',',' ') }}%</td>
                                <td class="px-4 py-2.5 text-right">{{ number_format($s->volume,0,',',' ') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
</div>
