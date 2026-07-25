<div class="bg-[#f9f9ff] text-[#131c2a] min-h-screen">
    @include('partials.markets-nav')

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pt-10 pb-6">
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
            <div>
                <span class="text-sm font-medium uppercase tracking-wider text-[#001a61]">BRVM · Temps réel (DB)</span>
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#001a61] mt-2">Marchés financiers</h1>
                <p class="text-[#444652] mt-2 max-w-2xl">Vue d'ensemble des actions et indices de la Bourse Régionale des Valeurs Mobilières.</p>
            </div>
            <a href="{{ route('marches.recherche') }}" class="inline-flex items-center gap-2 bg-[#001a61] text-white font-bold px-5 py-3 rounded-lg hover:bg-[#0a2e8c] transition">
                <span class="material-symbols-outlined">search</span> Recherche
            </a>
        </div>
    </section>

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pb-10">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white border border-[#c5c5d4] rounded-xl p-5">
                <p class="text-xs uppercase tracking-wide text-[#757683]">BRVM Composite</p>
                <p class="text-2xl font-bold text-[#001a61] mt-1">{{ $composite ? number_format((float)$composite->value, 2, ',', ' ') : '—' }}</p>
                @if($composite)
                    <p class="text-sm mt-1 {{ $composite->variation_percent >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $composite->variation_percent >= 0 ? '+' : '' }}{{ number_format((float)$composite->variation_percent, 2) }}%
                    </p>
                @endif
            </div>
            <div class="bg-white border border-[#c5c5d4] rounded-xl p-5">
                <p class="text-xs uppercase tracking-wide text-[#757683]">BRVM 30</p>
                <p class="text-2xl font-bold text-[#001a61] mt-1">{{ $brvm30 ? number_format((float)$brvm30->value, 2, ',', ' ') : '—' }}</p>
                @if($brvm30)
                    <p class="text-sm mt-1 {{ $brvm30->variation_percent >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $brvm30->variation_percent >= 0 ? '+' : '' }}{{ number_format((float)$brvm30->variation_percent, 2) }}%
                    </p>
                @endif
            </div>
            <div class="bg-white border border-[#c5c5d4] rounded-xl p-5">
                <p class="text-xs uppercase tracking-wide text-[#757683]">Volume</p>
                <p class="text-2xl font-bold text-[#001a61] mt-1">{{ number_format($totalVolume, 0, ',', ' ') }}</p>
                <p class="text-sm text-[#757683] mt-1">titres échangés</p>
            </div>
            <div class="bg-white border border-[#c5c5d4] rounded-xl p-5">
                <p class="text-xs uppercase tracking-wide text-[#757683]">Cap. boursière</p>
                <p class="text-2xl font-bold text-[#001a61] mt-1">{{ number_format($totalMarketCap, 0, ',', ' ') }}</p>
                <p class="text-sm text-[#757683] mt-1">M FCFA (estim.)</p>
            </div>
        </div>
    </section>

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pb-16 grid lg:grid-cols-12 gap-6">
        <div class="lg:col-span-7 bg-white border border-[#c5c5d4] rounded-2xl p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-[#001a61]">Performance BRVM Composite</h2>
                <a href="{{ route('marches.indices') }}" class="text-sm font-bold text-[#001a61]">Voir indices →</a>
            </div>
            <div class="h-48 flex items-end gap-1">
                @foreach ($chartBars as $h)
                    <div class="bg-[#001a61]/20 hover:bg-[#001a61] transition-all w-full rounded-t-sm" style="height: {{ $h }}%"></div>
                @endforeach
            </div>
        </div>

        <div class="lg:col-span-5 grid gap-6">
            <div class="bg-white border border-[#c5c5d4] rounded-2xl p-6">
                <div class="flex justify-between mb-4">
                    <h3 class="font-bold text-[#001a61]">Top Hausse</h3>
                    <a href="{{ route('marches.palmares', ['onglet' => 'gainers']) }}" class="text-sm text-[#001a61] font-medium">Palmarès</a>
                </div>
                <div class="space-y-3">
                    @foreach ($gainers as $s)
                        <a href="{{ route('marches.action', $s->symbol) }}" class="flex justify-between items-center hover:bg-[#f0f3ff] rounded px-2 py-1">
                            <span class="font-semibold">{{ $s->symbol }}</span>
                            <span class="text-green-600 font-semibold">+{{ number_format((float)$s->variation_percent, 2) }}%</span>
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="bg-white border border-[#c5c5d4] rounded-2xl p-6">
                <div class="flex justify-between mb-4">
                    <h3 class="font-bold text-[#001a61]">Top Baisse</h3>
                    <a href="{{ route('marches.palmares', ['onglet' => 'losers']) }}" class="text-sm text-[#001a61] font-medium">Palmarès</a>
                </div>
                <div class="space-y-3">
                    @foreach ($losers as $s)
                        <a href="{{ route('marches.action', $s->symbol) }}" class="flex justify-between items-center hover:bg-[#f0f3ff] rounded px-2 py-1">
                            <span class="font-semibold">{{ $s->symbol }}</span>
                            <span class="text-red-600 font-semibold">{{ number_format((float)$s->variation_percent, 2) }}%</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pb-20">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-[#001a61]">Plus fortes capitalisations</h2>
            <a href="{{ route('marches.cotations') }}" class="text-sm font-bold text-[#001a61]">Toutes les cotations →</a>
        </div>
        <div class="overflow-x-auto bg-white border border-[#c5c5d4] rounded-xl">
            <table class="w-full text-sm">
                <thead class="bg-[#e7eeff] text-[#001a61]">
                    <tr>
                        <th class="text-left px-4 py-3">Symbole</th>
                        <th class="text-left px-4 py-3">Société</th>
                        <th class="text-right px-4 py-3">Cours</th>
                        <th class="text-right px-4 py-3">Var. %</th>
                        <th class="text-right px-4 py-3">Volume</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stocks->sortByDesc('market_cap')->take(8) as $s)
                        <tr class="border-t border-[#c5c5d4] hover:bg-[#f0f3ff]">
                            <td class="px-4 py-3 font-bold"><a href="{{ route('marches.action', $s->symbol) }}" class="text-[#001a61]">{{ $s->symbol }}</a></td>
                            <td class="px-4 py-3">{{ $s->company_name }}</td>
                            <td class="px-4 py-3 text-right">{{ number_format((float)$s->current_price, 0, ',', ' ') }}</td>
                            <td class="px-4 py-3 text-right {{ $s->variation_percent >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $s->variation_percent >= 0 ? '+' : '' }}{{ number_format((float)$s->variation_percent, 2) }}%
                            </td>
                            <td class="px-4 py-3 text-right">{{ number_format((int)$s->volume, 0, ',', ' ') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
