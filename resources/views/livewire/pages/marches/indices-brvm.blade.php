<div class="bg-[#f9f9ff] text-[#131c2a] min-h-screen">
    @include('partials.markets-nav')

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pt-10 pb-6">
        <h1 class="text-3xl md:text-4xl font-extrabold text-[#001a61]">Indices de Marché</h1>
        <p class="text-[#444652] mt-2">Historique issu des snapshots quotidiens (`brvm:snapshot`)</p>
    </section>

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pb-10 grid md:grid-cols-2 gap-6">
        @forelse ($indices as $name => $data)
            @php $latest = $data['latest']; @endphp
            <div class="bg-white border border-[#c5c5d4] rounded-2xl p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h2 class="text-xl font-bold text-[#001a61]">{{ $name }}</h2>
                        @if($latest)
                            <p class="text-xs text-[#757683]">{{ $latest->snapshot_date->format('d/m/Y') }} · {{ $latest->source }}</p>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-extrabold text-[#001a61]">
                            {{ $latest ? number_format((float)$latest->value, 2, ',', ' ') : '—' }}
                        </p>
                        @if($latest)
                            <p class="font-semibold {{ $latest->variation_percent >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $latest->variation_percent >= 0 ? '+' : '' }}{{ number_format((float)$latest->variation_percent, 2) }}%
                            </p>
                        @endif
                    </div>
                </div>
                <div class="h-28 flex items-end gap-1">
                    @foreach ($data['bars'] as $h)
                        <div class="bg-[#001a61]/20 hover:bg-[#001a61] w-full rounded-t-sm transition-all" style="height: {{ $h }}%"></div>
                    @endforeach
                </div>
            </div>
        @empty
            <p class="text-[#757683] col-span-2">Aucun historique d'indice en base. Lancez <code>php artisan brvm:snapshot</code>.</p>
        @endforelse
    </section>

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pb-20">
        <h2 class="text-xl font-bold text-[#001a61] mb-4">Performance sectorielle (proxy stocks)</h2>
        <div class="overflow-x-auto bg-white border border-[#c5c5d4] rounded-xl">
            <table class="w-full text-sm">
                <thead class="bg-[#e7eeff] text-[#001a61]">
                    <tr>
                        <th class="text-left px-4 py-3">Secteur</th>
                        <th class="text-right px-4 py-3">Titres</th>
                        <th class="text-right px-4 py-3">Var. moy.</th>
                        <th class="text-right px-4 py-3">Volume</th>
                        <th class="text-left px-4 py-3">Meilleur</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sectorStats as $row)
                        <tr class="border-t border-[#c5c5d4]">
                            <td class="px-4 py-3 font-semibold">{{ $row['sector'] }}</td>
                            <td class="px-4 py-3 text-right">{{ $row['count'] }}</td>
                            <td class="px-4 py-3 text-right font-semibold {{ $row['avg_variation'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $row['avg_variation'] >= 0 ? '+' : '' }}{{ number_format($row['avg_variation'], 2) }}%
                            </td>
                            <td class="px-4 py-3 text-right">{{ number_format($row['volume'], 0, ',', ' ') }}</td>
                            <td class="px-4 py-3">
                                @if($row['top'])
                                    <a href="{{ route('marches.action', $row['top']->symbol) }}" class="text-[#001a61] font-bold hover:underline">{{ $row['top']->symbol }}</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
