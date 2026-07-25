<div class="bg-[#f9f9ff] text-[#131c2a] min-h-screen">
    @include('partials.investir-nav')
    @include('partials.markets-nav')

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pt-10 pb-6">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <p class="text-xs font-semibold tracking-widest uppercase text-[#0a2e8c]">Marchés</p>
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#001a61]">Heatmap du Marché BRVM</h1>
                <p class="mt-2 text-[#444652]">
                    {{ collect($map['sectors'])->sum(fn ($s) => count($s['stocks'])) }} titres —
                    couleur = variation, taille =
                    @if ($map['metric'] === 'volume') volume
                    @elseif ($map['metric'] === 'variation') |variation|
                    @else capitalisation
                    @endif
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                @foreach (['market_cap' => 'Capitalisation', 'volume' => 'Volume', 'variation' => 'Variation'] as $key => $label)
                    <button type="button" wire:click="$set('metric', '{{ $key }}')"
                        @class(['px-4 py-2 rounded text-sm font-bold', 'bg-[#001a61] text-white' => $metric === $key, 'bg-white border border-[#c5c5d4]' => $metric !== $key])>
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>
    </section>

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pb-20 space-y-8">
        @forelse ($map['sectors'] as $sector)
            @php $sectorShare = $map['total'] > 0 ? ($sector['total'] / $map['total']) * 100 : 0; @endphp
            <div>
                <div class="flex items-baseline justify-between mb-3">
                    <h2 class="text-lg font-bold text-[#001a61]">{{ $sector['name'] }}
                        <span class="text-sm font-semibold {{ ($sector['avg_variation'] ?? 0) >= 0 ? 'text-green-700' : 'text-red-700' }}">
                            {{ number_format($sector['avg_variation'] ?? 0, 2, ',', ' ') }}%
                        </span>
                    </h2>
                    <span class="text-xs text-[#757683]">{{ count($sector['stocks']) }} titres · {{ number_format($sectorShare, 1) }}%</span>
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach ($sector['stocks'] as $cell)
                        @php
                            $share = $sector['total'] > 0 ? max(($cell['size'] / $sector['total']) * 100, 8) : 10;
                            $w = min(max($share * 2.2, 72), 220);
                            $v = (float) $cell['variation'];
                            if ($v > 2) $bg = 'bg-green-600 text-white';
                            elseif ($v > 0) $bg = 'bg-green-200 text-green-900';
                            elseif ($v < -2) $bg = 'bg-red-600 text-white';
                            elseif ($v < 0) $bg = 'bg-red-200 text-red-900';
                            else $bg = 'bg-[#dae3f6] text-[#001a61]';
                        @endphp
                        <a href="{{ route('marches.action', $cell['symbol']) }}"
                            class="rounded-lg p-3 hover:opacity-90 transition {{ $bg }}"
                            style="width: {{ $w }}px; min-height: 72px">
                            <p class="font-extrabold text-sm">{{ $cell['symbol'] }}</p>
                            <p class="text-xs opacity-80 truncate">{{ $cell['name'] }}</p>
                            <p class="text-sm font-bold mt-1">{{ number_format($v, 2, ',', ' ') }}%</p>
                        </a>
                    @endforeach
                </div>
            </div>
        @empty
            <p class="text-[#757683]">Aucune donnée marché. Lancez <code>php artisan market:sync-brvm</code>.</p>
        @endforelse
    </section>
</div>
