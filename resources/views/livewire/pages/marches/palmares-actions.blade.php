<div class="bg-[#f9f9ff] text-[#131c2a] min-h-screen">
    @include('partials.markets-nav')

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pt-10 pb-6">
        <h1 class="text-3xl md:text-4xl font-extrabold text-[#001a61]">Palmarès du Marché</h1>
        <p class="text-[#444652] mt-2">Classements basés sur les dernières cotations en base</p>
    </section>

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pb-6">
        <div class="inline-flex bg-white border border-[#c5c5d4] rounded-lg p-1 gap-1">
            @foreach (['gainers' => 'Plus fortes hausses', 'losers' => 'Plus fortes baisses', 'volume' => 'Plus forts volumes'] as $key => $label)
                <button type="button" wire:click="setTab('{{ $key }}')"
                    @class([
                        'px-4 py-2 rounded-md text-sm font-semibold transition',
                        'bg-[#001a61] text-white' => $tab === $key,
                        'text-[#444652] hover:bg-[#e7eeff]' => $tab !== $key,
                    ])>{{ $label }}</button>
            @endforeach
        </div>
    </section>

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pb-10">
        <div class="overflow-x-auto bg-white border border-[#c5c5d4] rounded-xl">
            <table class="w-full text-sm">
                <thead class="bg-[#e7eeff] text-[#001a61]">
                    <tr>
                        <th class="text-left px-4 py-3">Rang</th>
                        <th class="text-left px-4 py-3">Valeur</th>
                        <th class="text-left px-4 py-3">Secteur</th>
                        <th class="text-right px-4 py-3">Cours (XOF)</th>
                        <th class="text-right px-4 py-3">Variation</th>
                        <th class="text-right px-4 py-3">Volume</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($list as $i => $s)
                        <tr class="border-t border-[#c5c5d4] hover:bg-[#f0f3ff]">
                            <td class="px-4 py-3 font-bold text-[#757683]">{{ $i + 1 }}</td>
                            <td class="px-4 py-3">
                                <div class="font-bold text-[#001a61]">{{ $s->symbol }}</div>
                                <div class="text-xs text-[#757683]">{{ $s->company_name }}</div>
                            </td>
                            <td class="px-4 py-3">{{ $s->sector }}</td>
                            <td class="px-4 py-3 text-right">{{ number_format((float)$s->current_price, 0, ',', ' ') }}</td>
                            <td class="px-4 py-3 text-right font-semibold {{ $s->variation_percent >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $s->variation_percent >= 0 ? '+' : '' }}{{ number_format((float)$s->variation_percent, 2) }}%
                            </td>
                            <td class="px-4 py-3 text-right">{{ number_format((int)$s->volume, 0, ',', ' ') }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('marches.action', $s->symbol) }}" class="font-bold text-[#001a61]">Voir</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pb-20 grid md:grid-cols-3 gap-4">
        @foreach ([
            ['title' => 'Hausses', 'items' => $gainers, 'color' => 'text-green-600'],
            ['title' => 'Baisses', 'items' => $losers, 'color' => 'text-red-600'],
            ['title' => 'Volumes', 'items' => $byVolume, 'color' => 'text-[#001a61]'],
        ] as $box)
            <div class="bg-white border border-[#c5c5d4] rounded-xl p-5">
                <h3 class="font-bold text-[#001a61] mb-3">{{ $box['title'] }}</h3>
                @foreach ($box['items'] as $s)
                    <div class="flex justify-between py-1 text-sm">
                        <span class="font-medium">{{ $s->symbol }}</span>
                        <span class="{{ $box['color'] }} font-semibold">
                            @if($box['title'] === 'Volumes')
                                {{ number_format((int)$s->volume, 0, ',', ' ') }}
                            @else
                                {{ $s->variation_percent >= 0 ? '+' : '' }}{{ number_format((float)$s->variation_percent, 2) }}%
                            @endif
                        </span>
                    </div>
                @endforeach
            </div>
        @endforeach
    </section>
</div>
