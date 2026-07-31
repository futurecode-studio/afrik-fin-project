<div class="bg-[#f9f9ff] min-h-screen">
    @include('partials.markets-nav')
    <section class="max-w-[1400px] mx-auto px-4 lg:px-8 py-6">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-[#001a61] text-3xl">candlestick_chart</span>
                <div>
                    <h1 class="text-xl font-extrabold text-[#001a61]">Analyse Graphique Pro</h1>
                    <p class="text-sm text-[#757683]">BRVM · indicateurs techniques</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <select wire:model.live="symbol" class="rounded-lg border-[#c5c5d4] text-sm">
                    @foreach ($stocks as $s)
                        <option value="{{ $s->symbol }}">{{ $s->symbol }}</option>
                    @endforeach
                </select>
                <select wire:model.live="range" class="rounded-lg border-[#c5c5d4] text-sm">
                    <option value="30">30 pts</option>
                    <option value="90">90 pts</option>
                    <option value="180">180 pts</option>
                </select>
            </div>
        </div>

        <div class="grid lg:grid-cols-4 gap-4">
            <div class="lg:col-span-3 bg-white border border-[#c5c5d4] rounded-xl p-4">
                @if ($stock)
                    <div class="flex flex-wrap items-end justify-between gap-3 mb-4">
                        <div>
                            <p class="text-xs uppercase text-[#757683]">{{ $stock->company_name }}</p>
                            <p class="text-2xl font-extrabold text-[#001a61]">{{ number_format($stock->current_price, 2, ',', ' ') }}
                                <span @class(['text-sm font-semibold', 'text-green-600' => $stock->variation_percent >= 0, 'text-red-600' => $stock->variation_percent < 0])>
                                    {{ $stock->variation_percent >= 0 ? '+' : '' }}{{ number_format($stock->variation_percent, 2, ',', ' ') }}%
                                </span>
                            </p>
                        </div>
                        <div class="flex gap-2 text-xs font-bold">
                            <button type="button" wire:click="$set('indicator','rsi')" @class(['px-3 py-1.5 rounded border', 'bg-[#001a61] text-white border-[#001a61]' => $indicator==='rsi', 'border-[#c5c5d4]' => $indicator!=='rsi'])>RSI (14)</button>
                            <button type="button" wire:click="$set('indicator','macd')" @class(['px-3 py-1.5 rounded border', 'bg-[#001a61] text-white border-[#001a61]' => $indicator==='macd', 'border-[#c5c5d4]' => $indicator!=='macd'])>MACD</button>
                            <button type="button" wire:click="$set('indicator','ema')" @class(['px-3 py-1.5 rounded border', 'bg-[#001a61] text-white border-[#001a61]' => $indicator==='ema', 'border-[#c5c5d4]' => $indicator!=='ema'])>EMA</button>
                        </div>
                    </div>
                @endif

                <div class="h-56 flex items-end gap-1 px-1">
                    @foreach ($bars as $h)
                        <div class="flex-1 rounded-t bg-[#001a61]/80 hover:bg-[#ffbf00] transition" style="height: {{ max(8, $h) }}%"></div>
                    @endforeach
                </div>
                <p class="text-xs text-[#757683] mt-2 text-center">Historique prix ({{ $history->count() }} points) — visualisation indicative</p>

                <div class="mt-4 grid sm:grid-cols-3 gap-3">
                    <div class="rounded-lg bg-[#f0f3ff] p-3">
                        <p class="text-xs text-[#757683]">RSI (14)</p>
                        <p class="text-xl font-extrabold text-[#001a61]">{{ $rsi !== null ? number_format($rsi, 1, ',', ' ') : '—' }}</p>
                    </div>
                    <div class="rounded-lg bg-[#f0f3ff] p-3">
                        <p class="text-xs text-[#757683]">Ouverture / Haut / Bas</p>
                        <p class="text-sm font-bold text-[#001a61]">
                            {{ $stock->formatMoney($stock->effectiveOpen()) }} /
                            {{ $stock->formatMoney($stock->effectiveHigh()) }} /
                            {{ $stock->formatMoney($stock->effectiveLow()) }}
                        </p>
                    </div>
                    <div class="rounded-lg bg-[#f0f3ff] p-3">
                        <p class="text-xs text-[#757683]">Volume</p>
                        <p class="text-xl font-extrabold text-[#001a61]">{{ number_format($stock->volume ?? 0, 0, ',', ' ') }}</p>
                    </div>
                </div>
            </div>

            <aside class="space-y-4">
                <div class="bg-white border border-[#c5c5d4] rounded-xl p-4">
                    <h3 class="font-bold text-[#001a61] mb-3">Favoris BRVM</h3>
                    <ul class="space-y-2 text-sm">
                        @foreach ($favorites as $f)
                            <li>
                                <button type="button" wire:click="$set('symbol','{{ $f->symbol }}')" class="w-full flex justify-between hover:bg-[#f0f3ff] rounded px-1 py-1">
                                    <span class="font-bold text-[#001a61]">{{ $f->symbol }}</span>
                                    <span>{{ number_format($f->current_price, 0, ',', ' ') }}</span>
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="bg-white border border-[#c5c5d4] rounded-xl p-4">
                    <h3 class="font-bold text-[#001a61] mb-3">Flux volumes</h3>
                    <ul class="space-y-2 text-sm">
                        @foreach ($book as $b)
                            <li class="flex justify-between border-b border-[#e7eeff] pb-1">
                                <span class="font-semibold">{{ $b->symbol }}</span>
                                <span class="text-[#757683]">{{ number_format($b->volume, 0, ',', ' ') }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <a href="{{ route('marches.carnet') }}" class="block text-center py-3 rounded-xl bg-[#001a61] text-white font-bold text-sm">Ouvrir le carnet d'ordres</a>
            </aside>
        </div>
    </section>
</div>
