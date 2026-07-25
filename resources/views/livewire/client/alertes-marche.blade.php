<div>
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-[#001a61]">Centre d'Alertes Intelligentes</h1>
            <p class="text-[#444652] mt-2 max-w-2xl">Surveillez vos titres BRVM en temps réel et configurez des notifications sur seuils, volumes et calendrier.</p>
        </div>
        <button type="button" wire:click="openForm" class="inline-flex items-center gap-2 px-5 py-3 rounded-lg bg-[#ffbf00] text-[#261a00] font-bold hover:brightness-95">
            <span class="material-symbols-outlined">add_alert</span> Nouvelle Alerte
        </button>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white border border-[#c5c5d4] rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-[#e7eeff] flex flex-wrap items-center justify-between gap-2">
                    <h2 class="font-bold text-[#001a61] text-lg">Alertes Actives</h2>
                    <div class="flex gap-2 text-xs font-bold">
                        <span class="px-2.5 py-1 rounded-full bg-red-50 text-red-700">{{ $critical }} Critiques</span>
                        <span class="px-2.5 py-1 rounded-full bg-[#e7eeff] text-[#001a61]">{{ $active }} Actives</span>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-[#f0f3ff] text-[#757683] text-xs uppercase">
                            <tr>
                                <th class="text-left px-4 py-3">Actif</th>
                                <th class="text-left px-4 py-3">Déclencheur</th>
                                <th class="text-left px-4 py-3">Seuil</th>
                                <th class="text-left px-4 py-3">Statut</th>
                                <th class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($alerts as $a)
                                <tr class="border-t border-[#e7eeff]">
                                    <td class="px-4 py-3 font-semibold text-[#001a61]">{{ $a->stock?->symbol ?? $a->asset_label }}</td>
                                    <td class="px-4 py-3">{{ str_replace('_', ' ', $a->trigger_type) }}</td>
                                    <td class="px-4 py-3">{{ $a->threshold !== null ? number_format($a->threshold, 2, ',', ' ') : '—' }}</td>
                                    <td class="px-4 py-3">
                                        @php
                                            $badge = match($a->status) {
                                                'triggered' => 'bg-red-50 text-red-700',
                                                'planned' => 'bg-[#dae3f6] text-[#001a61]',
                                                default => 'bg-amber-50 text-amber-800',
                                            };
                                        @endphp
                                        <span class="px-2 py-0.5 rounded-full text-xs font-bold uppercase {{ $badge }}">{{ $a->status }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <button type="button" wire:click="deleteAlert({{ $a->id }})" class="text-[#757683] hover:text-red-600" title="Supprimer">
                                            <span class="material-symbols-outlined text-lg">delete</span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-4 py-8 text-center text-[#757683]">Aucune alerte. Créez-en une pour démarrer.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($showForm)
                <div class="bg-white border border-[#c5c5d4] rounded-xl p-5 space-y-4">
                    <h3 class="font-bold text-[#001a61]">Nouvelle alerte</h3>
                    <div class="grid sm:grid-cols-2 gap-3">
                        <div>
                            <label class="text-sm font-medium">Titre</label>
                            <select wire:model="stock_symbol" class="w-full mt-1 rounded-lg border-[#c5c5d4]">
                                @foreach ($stocks as $s)
                                    <option value="{{ $s->symbol }}">{{ $s->symbol }} — {{ $s->company_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-medium">Déclencheur</label>
                            <select wire:model="trigger_type" class="w-full mt-1 rounded-lg border-[#c5c5d4]">
                                <option value="price_above">Prix supérieur à</option>
                                <option value="price_below">Prix inférieur à</option>
                                <option value="rsi">RSI</option>
                                <option value="volume">Volume</option>
                                <option value="calendar">Calendrier</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-medium">Seuil</label>
                            <input type="number" step="0.01" wire:model="threshold" class="w-full mt-1 rounded-lg border-[#c5c5d4]">
                        </div>
                        <div>
                            <label class="text-sm font-medium">Sévérité</label>
                            <select wire:model="alert_severity" class="w-full mt-1 rounded-lg border-[#c5c5d4]">
                                <option value="faible">Faible</option>
                                <option value="normale">Normale</option>
                                <option value="critique">Critique</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" wire:click="create" class="px-5 py-2.5 rounded-lg bg-[#001a61] text-white font-bold">Enregistrer</button>
                        <button type="button" wire:click="$set('showForm', false)" class="px-5 py-2.5 rounded-lg border border-[#c5c5d4]">Annuler</button>
                    </div>
                </div>
            @endif
        </div>

        <aside class="space-y-4">
            <div class="bg-white border border-[#c5c5d4] rounded-xl p-5">
                <h3 class="font-bold text-[#001a61] mb-3 flex items-center gap-2"><span class="material-symbols-outlined">filter_list</span> Filtrer</h3>
                <p class="text-xs uppercase text-[#757683] mb-2">Sévérité</p>
                <div class="flex flex-wrap gap-2 mb-4">
                    <button type="button" wire:click="$set('severity','')" @class(['px-3 py-1.5 rounded text-xs font-bold border', 'bg-[#001a61] text-white border-[#001a61]' => $severity==='', 'border-[#c5c5d4]' => $severity!==''])>Toutes</button>
                    <button type="button" wire:click="$set('severity','critique')" @class(['px-3 py-1.5 rounded text-xs font-bold border', 'bg-[#001a61] text-white border-[#001a61]' => $severity==='critique', 'border-[#c5c5d4]' => $severity!=='critique'])>Critique</button>
                    <button type="button" wire:click="$set('severity','faible')" @class(['px-3 py-1.5 rounded text-xs font-bold border', 'bg-[#001a61] text-white border-[#001a61]' => $severity==='faible', 'border-[#c5c5d4]' => $severity!=='faible'])>Faible</button>
                </div>
                <p class="text-xs uppercase text-[#757683] mb-2">Catégorie</p>
                <select wire:model.live="category" class="w-full rounded-lg border-[#c5c5d4] text-sm">
                    <option value="">Toutes</option>
                    <option value="action">Actions</option>
                    <option value="obligation">Fixed Income</option>
                    <option value="indice">Indices</option>
                </select>
            </div>
            <div class="bg-[#001a61] text-white rounded-xl p-5 relative overflow-hidden">
                <p class="text-sm text-white/70">Sentiment de Marché AI</p>
                <p class="text-4xl font-extrabold mt-2">74<span class="text-lg font-medium">/100</span></p>
                <p class="text-[#ffbf00] text-sm mt-1">Sentiment élevé</p>
                <div class="mt-4 h-2 rounded-full bg-white/20"><div class="h-2 rounded-full bg-[#ffbf00]" style="width:74%"></div></div>
            </div>
        </aside>
    </div>
</div>
