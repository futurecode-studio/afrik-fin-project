<div class="bg-[#f9f9ff] text-[#131c2a] min-h-screen">
    @include('partials.markets-nav')

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pt-8 pb-4">
        <a href="{{ route('marches.cotations') }}" class="inline-flex items-center gap-1 text-sm text-[#001a61] font-medium hover:underline">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span> Cotations
        </a>
    </section>

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pb-8 grid lg:grid-cols-12 gap-6">
        <div class="lg:col-span-8">
            <div class="bg-white border border-[#c5c5d4] rounded-2xl p-6 md:p-8">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <p class="text-sm text-[#757683] uppercase tracking-wide">{{ $stock->sector }}</p>
                        <h1 class="text-3xl md:text-4xl font-extrabold text-[#001a61] mt-1">{{ $stock->symbol }}</h1>
                        <p class="text-lg text-[#444652]">{{ $stock->company_name }}</p>
                    </div>
                    <div class="text-right space-y-2">
                        <p class="text-3xl font-extrabold text-[#001a61]">{{ number_format((float)$stock->current_price, 0, ',', ' ') }} <span class="text-base font-medium">FCFA</span></p>
                        <p class="text-lg font-bold {{ $stock->variation_percent >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $stock->variation_percent >= 0 ? '+' : '' }}{{ number_format((float)$stock->variation_percent, 2) }}%
                        </p>
                        <button type="button" wire:click="toggleWatchlist"
                            class="inline-flex items-center gap-1 text-sm font-bold px-3 py-1.5 rounded border {{ !empty($watched) ? 'bg-[#001a61] text-white border-[#001a61]' : 'border-[#001a61] text-[#001a61] hover:bg-[#e7eeff]' }}">
                            <span class="material-symbols-outlined text-base">{{ !empty($watched) ? 'visibility_off' : 'visibility' }}</span>
                            {{ !empty($watched) ? 'Retirer du suivi' : 'Suivre' }}
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mt-8">
                    @foreach ([
                        'Ouverture' => $stock->formatMoney($stock->effectiveOpen()),
                        'Clôture préc.' => $stock->formatMoney($stock->previous_price !== null ? (float) $stock->previous_price : null),
                        'Plus haut' => $stock->formatMoney($stock->effectiveHigh()),
                        'Plus bas' => $stock->formatMoney($stock->effectiveLow()),
                        'Volume' => number_format((int) $stock->volume, 0, ',', ' '),
                    ] as $label => $val)
                        <div class="bg-[#f0f3ff] rounded-xl p-4">
                            <p class="text-xs uppercase tracking-wide text-[#757683]">{{ $label }}</p>
                            <p class="text-lg font-bold text-[#001a61] mt-1">{{ $val }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 pt-6 border-t border-[#c5c5d4] flex flex-wrap gap-3">
                    <a href="{{ route('marches.comparateur', ['symbols' => $stock->symbol]) }}"
                        class="inline-flex items-center gap-2 border border-[#001a61] text-[#001a61] font-bold px-4 py-2 rounded-lg hover:bg-[#001a61] hover:text-white transition">
                        Comparer
                    </a>
                    <a href="{{ route('investir.actions-brvm') }}"
                        class="inline-flex items-center gap-2 bg-[#ffbf00] text-[#001a61] font-bold px-4 py-2 rounded-lg">
                        Investir
                    </a>
                </div>
            </div>
        </div>

        <aside class="lg:col-span-4 space-y-4">
            <div class="bg-white border border-[#c5c5d4] rounded-2xl p-6">
                <h3 class="font-bold text-[#001a61] mb-3">Capitalisation</h3>
                <p class="text-2xl font-extrabold">{{ number_format((float)$stock->market_cap, 0, ',', ' ') }} <span class="text-sm font-medium">M FCFA</span></p>
                @if($stock->last_updated)
                    <p class="text-xs text-[#757683] mt-2">MAJ {{ $stock->last_updated->format('d/m/Y H:i') }}</p>
                @endif
            </div>

            <div class="bg-white border border-[#c5c5d4] rounded-2xl p-6">
                <h3 class="font-bold text-[#001a61] mb-3">Pairs du secteur</h3>
                <div class="space-y-2">
                    @forelse ($peers as $p)
                        <a href="{{ route('marches.action', $p->symbol) }}" class="flex justify-between text-sm hover:bg-[#f0f3ff] rounded px-2 py-1">
                            <span class="font-semibold">{{ $p->symbol }}</span>
                            <span class="{{ $p->variation_percent >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $p->variation_percent >= 0 ? '+' : '' }}{{ number_format((float)$p->variation_percent, 2) }}%
                            </span>
                        </a>
                    @empty
                        <p class="text-sm text-[#757683]">Aucun pair</p>
                    @endforelse
                </div>
            </div>
        </aside>
    </section>
</div>
