<div>
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
        <div>
            <p class="text-xs uppercase tracking-widest text-[#757683] font-bold">Rapport de performance mensuelle</p>
            <h1 class="text-3xl font-extrabold text-[#001a61] mt-1">{{ $periodLabel }}</h1>
        </div>
        <div class="flex items-center gap-2">
            <input type="month" wire:model.live="month" class="rounded-lg border-[#c5c5d4] text-sm">
        </div>
    </div>

    <div class="grid sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-white border border-[#c5c5d4] rounded-xl p-5">
            <p class="text-xs uppercase text-[#757683]">Performance nette</p>
            <p @class(['text-3xl font-extrabold mt-2', 'text-green-600' => $avgPerf >= 0, 'text-red-600' => $avgPerf < 0])>
                {{ $avgPerf >= 0 ? '+' : '' }}{{ number_format($avgPerf, 2, ',', ' ') }}%
            </p>
            @if ($benchmark)
                <p class="text-sm text-[#757683] mt-1">vs. {{ number_format($benchmark->variation_percent ?? 0, 2, ',', ' ') }}% {{ $benchmark->index_name ?? 'Indice' }}</p>
            @endif
        </div>
        <div class="bg-white border border-[#c5c5d4] rounded-xl p-5">
            <p class="text-xs uppercase text-[#757683]">Valeur portefeuille</p>
            <p class="text-3xl font-extrabold text-[#001a61] mt-2">{{ number_format($total, 0, ',', ' ') }} <span class="text-base font-medium">FCFA</span></p>
        </div>
        <div class="bg-white border border-[#c5c5d4] rounded-xl p-5">
            <p class="text-xs uppercase text-[#757683]">Lignes</p>
            <p class="text-3xl font-extrabold text-[#001a61] mt-2">{{ count($rows) }}</p>
            <p class="text-sm text-[#757683] mt-1">Actifs suivis</p>
        </div>
    </div>

    <div class="bg-white border border-[#c5c5d4] rounded-xl overflow-hidden">
        <div class="px-5 py-4 border-b border-[#e7eeff]"><h2 class="font-bold text-[#001a61]">Contribution par classe d'actifs</h2></div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-[#f0f3ff] text-xs uppercase text-[#757683]">
                    <tr>
                        <th class="text-left px-4 py-3">Actif</th>
                        <th class="text-left px-4 py-3">Classe</th>
                        <th class="text-right px-4 py-3">Poids</th>
                        <th class="text-right px-4 py-3">Valeur</th>
                        <th class="text-right px-4 py-3">Perf.</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rows as $r)
                        <tr class="border-t border-[#e7eeff]">
                            <td class="px-4 py-3 font-semibold text-[#001a61]">{{ $r['label'] }}</td>
                            <td class="px-4 py-3 capitalize">{{ $r['type'] }}</td>
                            <td class="px-4 py-3 text-right">{{ number_format($r['weight'], 1, ',', ' ') }}%</td>
                            <td class="px-4 py-3 text-right">{{ number_format($r['value'], 0, ',', ' ') }}</td>
                            <td @class(['px-4 py-3 text-right font-semibold', 'text-green-600' => $r['perf'] >= 0, 'text-red-600' => $r['perf'] < 0])>
                                {{ number_format($r['perf'], 2, ',', ' ') }}%
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-10 text-center text-[#757683]">Ajoutez des lignes dans <a href="{{ route('client.patrimoine') }}" class="text-[#001a61] font-bold underline">Patrimoine</a> pour générer le rapport.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
