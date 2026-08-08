<div class="bg-[#f9f9ff] text-[#131c2a] min-h-screen">
    @include('partials.markets-nav')

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pt-10 pb-6">
        <h1 class="text-3xl md:text-4xl font-extrabold text-[#001a61]">Comparateur d'actions</h1>
        <p class="text-[#444652] mt-2">Comparez jusqu'à 5 titres BRVM côte à côte — données réelles de la table <code class="text-xs">stocks</code></p>
    </section>

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pb-6">
        <div class="bg-white border border-[#c5c5d4] rounded-xl p-4 flex flex-wrap gap-2 items-center">
            @forelse ($compared as $s)
                <button type="button" wire:click="removeSymbol('{{ $s->symbol }}')" wire:key="chip-{{ $s->symbol }}"
                    class="inline-flex items-center gap-2 bg-[#e7eeff] text-[#001a61] font-semibold px-3 py-1.5 rounded-full text-sm hover:bg-[#001a61] hover:text-white transition">
                    {{ $s->symbol }}
                    <span class="material-symbols-outlined text-[16px]">close</span>
                </button>
            @empty
                <span class="text-sm text-[#757683]">Aucune action sélectionnée</span>
            @endforelse

            @if (count($selected) < 5)
                <select wire:model.live="pendingSymbol" wire:key="add-select"
                    class="rounded-lg border-[#c5c5d4] text-sm focus:border-[#001a61] focus:ring-[#001a61] min-w-[220px]">
                    <option value="">+ Ajouter une action</option>
                    @foreach ($available as $s)
                        <option value="{{ $s->symbol }}">{{ $s->symbol }} — {{ $s->company_name }}</option>
                    @endforeach
                </select>
            @else
                <span class="text-xs text-[#757683]">Maximum 5 titres</span>
            @endif

            <span class="text-xs text-[#757683] ml-auto">{{ count($selected) }}/5</span>
        </div>
    </section>

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pb-20">
        @if ($compared->isEmpty())
            <div class="bg-white border border-dashed border-[#c5c5d4] rounded-xl p-10 text-center text-[#757683]">
                Sélectionnez au moins une action pour comparer.
            </div>
        @else
            <div class="overflow-x-auto bg-white border border-[#c5c5d4] rounded-xl" wire:key="table-{{ implode('-', $selected) }}">
                <table class="w-full text-sm min-w-[700px]">
                    <thead class="bg-[#e7eeff] text-[#001a61]">
                        <tr>
                            <th class="text-left px-4 py-3">Métrique</th>
                            @foreach ($compared as $s)
                                <th class="text-left px-4 py-3" wire:key="head-{{ $s->symbol }}">
                                    <a href="{{ route('marches.action', $s->symbol) }}" class="hover:underline">{{ $s->symbol }}</a>
                                    <div class="text-xs font-normal text-[#757683]">{{ $s->company_name }}</div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($metrics as $field => $label)
                            <tr class="border-t border-[#c5c5d4]" wire:key="row-{{ $field }}">
                                <td class="px-4 py-3 font-medium text-[#444652]">{{ $label }}</td>
                                @foreach ($compared as $s)
                                    @php
                                        $raw = $s->{$field};
                                        $hasValue = !is_null($raw) && $raw !== '' && !(is_numeric($raw) && (float) $raw == 0 && in_array($field, ['market_cap', 'high_price', 'low_price'], true));
                                    @endphp
                                    <td class="px-4 py-3 font-semibold" wire:key="cell-{{ $field }}-{{ $s->symbol }}">
                                        @if ($field === 'variation_percent')
                                            <span class="{{ $s->variation_percent >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $s->variation_percent >= 0 ? '+' : '' }}{{ number_format((float) $s->variation_percent, 2) }}%
                                            </span>
                                        @elseif ($field === 'sector')
                                            {{ $s->sector ?: '—' }}
                                        @elseif ($field === 'volume')
                                            {{ number_format((int) $s->volume, 0, ',', ' ') }}
                                        @elseif ($field === 'current_price')
                                            {{ number_format((float) $s->current_price, 0, ',', ' ') }}
                                        @elseif ($field === 'market_cap')
                                            {{ \App\Models\Stock::formatCapMrd($raw) }}
                                        @elseif (! $hasValue)
                                            <span class="text-[#757683] font-normal">—</span>
                                        @else
                                            {{ number_format((float) $raw, 0, ',', ' ') }}
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p class="text-xs text-[#757683] mt-3">
                Les champs affichés « — » sont absents en base pour ce titre (souvent high/low/cap non renseignés par la dernière sync).
            </p>
        @endif
    </section>
</div>
