<div class="bg-[#f9f9ff] min-h-screen">
    @include('partials.markets-nav')
    <section class="max-w-[1200px] mx-auto px-5 lg:px-8 py-12">
        <h1 class="text-3xl font-extrabold text-[#001a61]">Comparateur de Performance Multi-Actifs</h1>
        <p class="text-[#444652] mt-2">Comparez actions BRVM, indices et obligations sur un même tableau de bord.</p>

        <div class="mt-8 grid md:grid-cols-2 gap-4">
            <div class="bg-white border border-[#c5c5d4] rounded-xl p-5">
                <h2 class="font-bold text-[#001a61] mb-3">Actif A</h2>
                <select wire:model.live="assetA" class="w-full rounded-lg border-[#c5c5d4]">
                    <optgroup label="Actions">
                        @foreach ($stocks->take(40) as $s)
                            <option value="stock:{{ $s->symbol }}">{{ $s->symbol }} — {{ $s->company_name }}</option>
                        @endforeach
                    </optgroup>
                    <optgroup label="Indices">
                        <option value="index:BRVM-C">BRVM-C</option>
                        <option value="index:BRVM-10">BRVM-10</option>
                    </optgroup>
                    @if ($bonds->isNotEmpty())
                        <optgroup label="Obligations">
                            @foreach ($bonds as $b)
                                <option value="bond:{{ $b->id }}">{{ $b->name }}</option>
                            @endforeach
                        </optgroup>
                    @endif
                </select>
                <p class="mt-4 text-lg font-extrabold text-[#001a61]">{{ $left['name'] }}</p>
                <p class="text-sm text-[#757683]">{{ $left['type'] }}</p>
            </div>
            <div class="bg-white border border-[#c5c5d4] rounded-xl p-5">
                <h2 class="font-bold text-[#001a61] mb-3">Actif B</h2>
                <select wire:model.live="assetB" class="w-full rounded-lg border-[#c5c5d4]">
                    <optgroup label="Actions">
                        @foreach ($stocks->take(40) as $s)
                            <option value="stock:{{ $s->symbol }}">{{ $s->symbol }} — {{ $s->company_name }}</option>
                        @endforeach
                    </optgroup>
                    <optgroup label="Indices">
                        <option value="index:BRVM-C">BRVM-C</option>
                        <option value="index:BRVM-10">BRVM-10</option>
                    </optgroup>
                    @if ($bonds->isNotEmpty())
                        <optgroup label="Obligations">
                            @foreach ($bonds as $b)
                                <option value="bond:{{ $b->id }}">{{ $b->name }}</option>
                            @endforeach
                        </optgroup>
                    @endif
                </select>
                <p class="mt-4 text-lg font-extrabold text-[#001a61]">{{ $right['name'] }}</p>
                <p class="text-sm text-[#757683]">{{ $right['type'] }}</p>
            </div>
        </div>

        <div class="mt-8 bg-white border border-[#c5c5d4] rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-[#e7eeff] flex items-center justify-between">
                <h2 class="font-bold text-[#001a61]">Indicateurs de risque et rendement</h2>
                <select wire:model.live="period" class="rounded-lg border-[#c5c5d4] text-sm">
                    <option value="1m">1 mois</option>
                    <option value="3m">3 mois</option>
                    <option value="1y">1 an</option>
                </select>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-[#f0f3ff] text-xs uppercase text-[#757683]">
                    <tr>
                        <th class="text-left px-4 py-3">Métrique</th>
                        <th class="text-right px-4 py-3">{{ $left['name'] }}</th>
                        <th class="text-right px-4 py-3">{{ $right['name'] }}</th>
                        <th class="text-right px-4 py-3">Différentiel</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($metrics as $m)
                        @php
                            $va = $m['a']; $vb = $m['b'];
                            $diff = (is_numeric($va) && is_numeric($vb)) ? $va - $vb : null;
                        @endphp
                        <tr class="border-t border-[#e7eeff]">
                            <td class="px-4 py-3 font-medium">{{ $m['label'] }}</td>
                            <td class="px-4 py-3 text-right font-semibold text-[#001a61]">
                                @if ($m['fmt']==='pct' && is_numeric($va)) {{ number_format($va, 2, ',', ' ') }}%
                                @elseif ($m['fmt']==='money' && is_numeric($va)) {{ number_format($va, 2, ',', ' ') }}
                                @else {{ $va ?? '—' }} @endif
                            </td>
                            <td class="px-4 py-3 text-right font-semibold text-[#001a61]">
                                @if ($m['fmt']==='pct' && is_numeric($vb)) {{ number_format($vb, 2, ',', ' ') }}%
                                @elseif ($m['fmt']==='money' && is_numeric($vb)) {{ number_format($vb, 2, ',', ' ') }}
                                @else {{ $vb ?? '—' }} @endif
                            </td>
                            <td class="px-4 py-3 text-right">{{ $diff !== null ? number_format($diff, 2, ',', ' ') : '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-10 rounded-2xl bg-[#001a61] text-white p-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-extrabold">Prêt à optimiser votre stratégie ?</h2>
                <p class="text-white/70 mt-1">Parlez à un conseiller Africaine des Finances.</p>
            </div>
            <a href="{{ route('mise-en-relation') }}" class="inline-flex px-6 py-3 rounded-lg bg-[#ffbf00] text-[#261a00] font-extrabold">Prendre rendez-vous</a>
        </div>
    </section>
</div>
