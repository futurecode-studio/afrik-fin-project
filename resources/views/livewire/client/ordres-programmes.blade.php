<div>
    <h1 class="text-3xl font-extrabold text-[#001a61]">Stratégies d'Ordres Programmés</h1>
    <p class="text-[#444652] mt-2 max-w-2xl">Automatisez vos intentions d’ordres (seuil, OCO, trailing). L’exécution est relayée vers une SGI agréée.</p>

    <div class="mt-8 grid lg:grid-cols-2 gap-6">
        <div class="bg-white border border-[#c5c5d4] rounded-xl p-6">
            @if ($stock)
                <p class="text-xs uppercase tracking-wider text-[#757683]">Instrument</p>
                <h2 class="text-xl font-extrabold text-[#001a61]">{{ $stock->company_name }}</h2>
                <p class="text-sm text-[#444652]">BRVM: {{ $stock->symbol }}</p>
                <p class="mt-3 text-2xl font-bold text-[#001a61]">{{ number_format($stock->current_price, 2, ',', ' ') }} XOF
                    <span @class(['text-sm font-semibold ml-2', 'text-green-600' => $stock->variation_percent >= 0, 'text-red-600' => $stock->variation_percent < 0])>
                        {{ $stock->variation_percent >= 0 ? '+' : '' }}{{ number_format($stock->variation_percent, 2, ',', ' ') }}%
                    </span>
                </p>
                <div class="mt-4 flex items-center gap-2 text-sm text-green-700 bg-green-50 rounded-lg px-3 py-2">
                    <span class="material-symbols-outlined text-base">monitoring</span> Flux marché connecté
                </div>
            @endif
            <div class="mt-4">
                <label class="text-sm font-medium">Changer de titre</label>
                <select wire:model.live="symbol" class="w-full mt-1 rounded-lg border-[#c5c5d4]">
                    @foreach ($stocks as $s)
                        <option value="{{ $s->symbol }}">{{ $s->symbol }} — {{ $s->company_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <form wire:submit.prevent="place" class="bg-white border border-[#c5c5d4] rounded-xl p-6 space-y-4">
            <h3 class="font-bold text-[#001a61] text-lg">Nouvel Ordre</h3>
            <div>
                <label class="text-sm font-medium">Type de condition</label>
                <select wire:model="condition_type" class="w-full mt-1 rounded-lg border-[#c5c5d4]">
                    <option value="threshold">Ordre à seuil de déclenchement</option>
                    <option value="oco">Ordre OCO</option>
                    <option value="trailing">Trailing Stop</option>
                    <option value="linked">Take Profit &amp; Stop Loss</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-sm font-medium">Sens</label>
                    <select wire:model="side" class="w-full mt-1 rounded-lg border-[#c5c5d4]"><option value="buy">Achat</option><option value="sell">Vente</option></select>
                </div>
                <div>
                    <label class="text-sm font-medium">Quantité</label>
                    <input type="number" wire:model="quantity" min="1" class="w-full mt-1 rounded-lg border-[#c5c5d4]">
                </div>
            </div>
            <div>
                <label class="text-sm font-medium">Prix cible (XOF)</label>
                <input type="number" step="0.01" wire:model="target_price" class="w-full mt-1 rounded-lg border-[#c5c5d4]">
            </div>
            <label class="flex items-center gap-2 text-sm font-medium">
                <input type="checkbox" wire:model.live="protection_active" class="rounded border-[#c5c5d4]"> Protection active
            </label>
            @if ($protection_active)
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-sm text-red-700 font-medium">Stop Loss</label>
                        <input type="number" step="0.01" wire:model="stop_loss" class="w-full mt-1 rounded-lg border-red-200">
                    </div>
                    <div>
                        <label class="text-sm text-green-700 font-medium">Take Profit</label>
                        <input type="number" step="0.01" wire:model="take_profit" class="w-full mt-1 rounded-lg border-green-200">
                    </div>
                </div>
            @endif
            <button type="submit" class="w-full py-3 rounded-xl bg-[#001a61] text-white font-bold inline-flex items-center justify-center gap-2">
                <span class="material-symbols-outlined">bolt</span> Placer l'Ordre Programmé
            </button>
            <p class="text-xs text-[#757683]">Frais de courtage estimés via SGI (~1,50 % HT). Africaine des Finances n’exécute pas les ordres.</p>
        </form>
    </div>

    <div class="mt-8 bg-white border border-[#c5c5d4] rounded-xl overflow-hidden">
        <div class="px-5 py-4 border-b border-[#e7eeff]"><h2 class="font-bold text-[#001a61]">Stratégies Actives</h2></div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-[#f0f3ff] text-xs uppercase text-[#757683]">
                    <tr>
                        <th class="text-left px-4 py-3">Date</th>
                        <th class="text-left px-4 py-3">Instrument</th>
                        <th class="text-left px-4 py-3">Condition</th>
                        <th class="text-left px-4 py-3">Volume</th>
                        <th class="text-left px-4 py-3">Statut</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $o)
                        <tr class="border-t border-[#e7eeff]">
                            <td class="px-4 py-3">{{ $o->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 font-semibold text-[#001a61]">{{ $o->stock?->symbol }}</td>
                            <td class="px-4 py-3">{{ strtoupper($o->side) }} @ {{ number_format($o->target_price ?? 0, 0, ',', ' ') }} ({{ $o->condition_type }})</td>
                            <td class="px-4 py-3">{{ number_format($o->quantity, 0, ',', ' ') }}</td>
                            <td class="px-4 py-3">
                                <span @class(['px-2 py-0.5 rounded-full text-xs font-bold', 'bg-green-50 text-green-700' => $o->status==='pending', 'bg-amber-50 text-amber-800' => $o->status==='triggered', 'bg-slate-100 text-slate-600' => $o->status==='cancelled'])>{{ $o->status }}</span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                @if ($o->status === 'pending')
                                    <button type="button" wire:click="cancelOrder({{ $o->id }})" class="text-xs font-bold text-red-600">Annuler</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-[#757683]">Aucun ordre programmé.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
