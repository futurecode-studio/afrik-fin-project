<div class="bg-[#f9f9ff] text-[#131c2a] min-h-screen">
    @include('partials.investir-nav')
    @include('partials.markets-nav')

    <section class="relative overflow-hidden px-5 lg:px-16 pt-10 pb-16 lg:pb-24">
        <div class="pointer-events-none absolute inset-0" aria-hidden="true">
            <div class="absolute -top-24 -left-16 w-80 h-80 rounded-full bg-[#ffbf00]/15 blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 rounded-full bg-[#001a61]/10 blur-3xl"></div>
        </div>

        <div class="relative max-w-[1280px] mx-auto">
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-8">
                <div>
                    <h1 class="inline-flex items-center bg-[#c1121f] text-white uppercase text-sm font-extrabold tracking-wider px-4 py-2 rounded-md">
                        Marketmap
                    </h1>
                    <p class="mt-4 text-3xl md:text-4xl font-extrabold tracking-tight text-[#001a61]">
                        Heatmap du marché <span class="text-[#0a2e8c]">BRVM</span>
                    </p>
                    <p class="mt-2 text-[#444652] text-sm max-w-xl leading-relaxed">
                        {{ $treemap['count'] }} titres — taille = <span class="text-[#001a61] font-semibold">{{ $treemap['size_label'] }}</span>,
                        couleur = variation du jour. Cliquez une case pour ouvrir la fiche.
                    </p>
                </div>

                <div class="flex flex-wrap gap-2 shrink-0">
                    @foreach (['market_cap' => 'Capitalisation', 'volume' => 'Volume', 'variation' => 'Variation'] as $key => $label)
                        <button type="button" wire:click="$set('metric', '{{ $key }}')"
                            @class([
                                'px-4 py-2 rounded-lg text-sm font-bold transition-colors border',
                                'bg-[#001a61] text-white border-[#001a61]' => $metric === $key,
                                'bg-white text-[#001a61] border-[#c5c5d4] hover:border-[#001a61] hover:bg-[#e7eeff]' => $metric !== $key,
                            ])>
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>

            @if(!empty($treemap['nodes']))
                <div wire:key="treemap-{{ $metric }}-{{ $treemap['metric'] ?? 'x' }}"
                    class="rounded-2xl border border-[#c5c5d4] bg-white p-3 sm:p-4 shadow-sm">
                    <x-market-treemap :treemap="$treemap" height="min(640px, 75vh)" tone="light" />
                </div>

                @if(!empty($sectors))
                    <div class="mt-12">
                        <h2 class="text-lg font-extrabold text-[#001a61] mb-4">Performance par secteur</h2>
                        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-3">
                            @foreach ($sectors as $sector)
                                @php
                                    $avg = (float) ($sector['avg_variation'] ?? 0);
                                    $share = $mapTotal > 0 ? ($sector['total'] / $mapTotal) * 100 : 0;
                                @endphp
                                <div class="rounded-xl border border-[#c5c5d4] bg-white p-4 shadow-sm">
                                    <p class="text-sm font-extrabold text-[#001a61] truncate">{{ $sector['name'] }}</p>
                                    <p class="text-xs text-[#757683] mt-0.5">{{ count($sector['stocks']) }} titres · {{ number_format($share, 1) }}%</p>
                                    <p class="mt-2 text-lg font-extrabold tabular-nums {{ $avg >= 0 ? 'text-emerald-700' : 'text-red-700' }}">
                                        {{ $avg >= 0 ? '+' : '' }}{{ number_format($avg, 2, ',', ' ') }}%
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @else
                <div class="rounded-xl border border-dashed border-[#c5c5d4] bg-white p-10 text-center text-[#444652]">
                    Aucune donnée marché. Lancez <code class="text-[#001a61] font-semibold">php artisan market:sync-brvm</code>.
                </div>
            @endif
        </div>
    </section>
</div>
