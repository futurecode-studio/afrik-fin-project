<div class="bg-[#f9f9ff] text-[#131c2a] min-h-screen">
    @include('partials.investir-nav')

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pt-12 pb-6">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <p class="text-sm font-semibold tracking-widest uppercase text-[#0a2e8c] mb-3">OPCVM</p>
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#001a61]">Valeurs liquidatives</h1>
                <p class="mt-2 text-[#444652]">
                    Tableau des VL — FCP du Bénin, bulletin BRVM 23 juillet 2026
                </p>
            </div>
        </div>
    </section>

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pb-6 flex flex-wrap gap-2">
        <button type="button" wire:click="$set('category', '')"
            @class(['px-3 py-1.5 rounded text-sm font-medium', 'bg-[#001a61] text-white' => $category === '', 'bg-white border border-[#c5c5d4] text-[#444652]' => $category !== ''])>
            Tous
        </button>
        @foreach ($categories as $cat)
            <button type="button" wire:click="$set('category', '{{ $cat }}')"
                @class(['px-3 py-1.5 rounded text-sm font-medium', 'bg-[#001a61] text-white' => $category === $cat, 'bg-white border border-[#c5c5d4] text-[#444652]' => $category !== $cat])>
                {{ $cat }}
            </button>
        @endforeach
    </section>

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pb-20">
        @if ($loading)
            <p class="text-center py-16 text-[#757683]">Chargement…</p>
        @elseif ($error)
            <div class="bg-white border border-red-200 rounded-xl p-8 text-center text-red-600">{{ $error }}</div>
        @else
            <div class="overflow-x-auto bg-white border border-[#c5c5d4] rounded-xl">
                <table class="w-full text-sm min-w-[800px]">
                    <thead class="bg-[#e7eeff] text-[#001a61]">
                        <tr>
                            @foreach ([
                                'name' => 'Fonds',
                                'company' => 'SGO',
                                'category' => 'Catégorie',
                                'origin_nav' => 'Origine',
                                'nav_numeric' => 'VL actuelle',
                                'variation_percentage' => 'Var. origine',
                                'date' => 'Date',
                            ] as $col => $label)
                                <th class="text-left px-3 py-3 cursor-pointer select-none" wire:click="sort('{{ $col }}')">
                                    {{ $label }}
                                    @if ($sortBy === $col)<span class="text-xs">{{ $sortDir === 'asc' ? '↑' : '↓' }}</span>@endif
                                </th>
                            @endforeach
                            <th class="px-3 py-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($list as $f)
                            <tr class="border-t border-[#c5c5d4] hover:bg-[#f0f3ff]">
                                <td class="px-3 py-3 font-semibold text-[#001a61]">{{ $f['name'] }}</td>
                                <td class="px-3 py-3 text-[#444652]">{{ $f['company'] }}</td>
                                <td class="px-3 py-3">{{ $f['category'] }}</td>
                                <td class="px-3 py-3 tabular-nums">{{ $f['origin_nav_value'] ?? '—' }}</td>
                                <td class="px-3 py-3 font-medium">{{ $f['nav_value'] }}</td>
                                <td @class(['px-3 py-3 font-semibold', 'text-[#757683]' => ($f['variation_percentage'] ?? null) === null, 'text-green-600' => ($f['variation_percentage'] ?? null) !== null && $f['variation_percentage'] >= 0, 'text-red-600' => ($f['variation_percentage'] ?? null) !== null && $f['variation_percentage'] < 0])>
                                    {{ $f['variation'] ?? 'ND' }}
                                </td>
                                <td class="px-3 py-3">{{ \Carbon\Carbon::parse($f['date'])->format('d/m/Y') }}</td>
                                <td class="px-3 py-3">
                                    <a href="{{ route('investir.fcp-detail', $f['id']) }}" class="font-bold text-[#001a61] hover:underline">Détail</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="px-4 py-10 text-center text-[#757683]">Aucune VL disponible</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </section>
</div>
