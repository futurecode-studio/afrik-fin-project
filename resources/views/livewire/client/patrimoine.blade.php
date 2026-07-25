<div>
    <div class="flex flex-wrap items-end justify-between gap-4 mb-8">
        <div>
            <p class="text-xs font-semibold tracking-widest uppercase text-[#0a2e8c]">Espace client</p>
            <h1 class="text-3xl font-extrabold text-[#001a61]">Vue Consolidée du Patrimoine</h1>
            <p class="text-[#444652] mt-1">Positions saisies + valorisation aux cours BRVM en base.</p>
        </div>
        <div class="bg-[#001a61] text-white rounded-xl px-6 py-4">
            <p class="text-xs text-white/70">Valeur totale</p>
            <p class="text-2xl font-extrabold">{{ number_format($total, 0, ',', ' ') }} FCFA</p>
        </div>
    </div>

    <div class="grid sm:grid-cols-3 gap-4 mb-8">
        @forelse ($allocation as $type => $val)
            <div class="bg-white border border-[#c5c5d4] rounded-xl p-4">
                <p class="text-xs uppercase text-[#757683]">{{ $type }}</p>
                <p class="font-bold text-[#001a61] text-lg">{{ number_format($val, 0, ',', ' ') }}</p>
                <p class="text-xs text-[#757683]">{{ $total > 0 ? number_format(($val/$total)*100,1,',',' ') : 0 }}%</p>
            </div>
        @empty
            <div class="sm:col-span-3 text-sm text-[#757683]">Ajoutez vos positions pour voir l’allocation.</div>
        @endforelse
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white border border-[#c5c5d4] rounded-xl overflow-hidden">
            <div class="px-5 py-3 border-b border-[#c5c5d4] font-bold text-[#001a61]">Détails des investissements</div>
            <table class="w-full text-sm">
                <thead class="bg-[#eef3ff] text-xs uppercase text-[#001a61]"><tr><th class="text-left px-4 py-2">Actif</th><th class="text-right px-4 py-2">Valorisation</th><th class="text-right px-4 py-2">P&L</th><th></th></tr></thead>
                <tbody>
                    @forelse ($rows as $row)
                        <tr class="border-t border-[#c5c5d4]">
                            <td class="px-4 py-3">
                                <p class="font-semibold text-[#001a61]">{{ $row['name'] }}</p>
                                <p class="text-xs text-[#757683]">{{ $row['symbol'] ?? $row['model']->asset_type }} · qty {{ $row['model']->quantity }}</p>
                            </td>
                            <td class="px-4 py-3 text-right font-bold">{{ number_format($row['market_value'],0,',',' ') }}</td>
                            <td class="px-4 py-3 text-right font-bold {{ $row['pnl']>=0?'text-green-700':'text-red-700' }}">{{ number_format($row['pnl'],0,',',' ') }} ({{ number_format($row['pnl_pct'],1,',',' ') }}%)</td>
                            <td class="px-4 py-3 text-right"><button type="button" wire:click="remove({{ $row['model']->id }})" class="text-red-600 text-xs font-bold">Retirer</button></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-8 text-center text-[#757683]">Aucune position.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-white border border-[#c5c5d4] rounded-xl p-5 space-y-3">
            <h2 class="font-bold text-[#001a61]">Ajouter une position</h2>
            <div>
                <label class="text-xs font-semibold uppercase text-[#757683]">Type</label>
                <select wire:model.live="asset_type" class="w-full mt-1 rounded-lg border-[#c5c5d4] text-sm">
                    <option value="action">Action BRVM</option>
                    <option value="fcp">FCP / OPCVM</option>
                    <option value="obligation">Obligation</option>
                    <option value="cash">Cash</option>
                </select>
            </div>
            @if ($asset_type === 'action')
                <div>
                    <label class="text-xs font-semibold uppercase text-[#757683]">Titre</label>
                    <select wire:model="stock_id" class="w-full mt-1 rounded-lg border-[#c5c5d4] text-sm">
                        <option value="">Choisir…</option>
                        @foreach ($stocks as $s)
                            <option value="{{ $s->id }}">{{ $s->symbol }} — {{ $s->company_name }}</option>
                        @endforeach
                    </select>
                    @error('stock_id')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            @else
                <div>
                    <label class="text-xs font-semibold uppercase text-[#757683]">Libellé</label>
                    <input wire:model="label" class="w-full mt-1 rounded-lg border-[#c5c5d4] text-sm" placeholder="Ex. FCP SOGEVALOR">
                </div>
            @endif
            <div class="grid grid-cols-2 gap-2">
                <div><label class="text-xs font-semibold uppercase text-[#757683]">Quantité</label><input type="number" step="0.0001" wire:model="quantity" class="w-full mt-1 rounded-lg border-[#c5c5d4] text-sm"></div>
                <div><label class="text-xs font-semibold uppercase text-[#757683]">Prix de revient</label><input type="number" wire:model="avg_cost" class="w-full mt-1 rounded-lg border-[#c5c5d4] text-sm"></div>
            </div>
            <button type="button" wire:click="addHolding" class="w-full py-2.5 rounded-xl bg-[#001a61] text-white font-bold text-sm">Enregistrer</button>
            <p class="text-xs text-[#757683]">Liste de suivi (indicatif) : {{ number_format($watchValue,0,',',' ') }} FCFA (1 titre × cours).</p>
            <a href="{{ route('client.watchlist') }}" class="text-sm font-semibold text-[#0a2e8c] underline">Gérer la liste de suivi</a>
        </div>
    </div>
</div>
