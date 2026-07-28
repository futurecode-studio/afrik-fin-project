<div>
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-[#001a61]">Centre d'Alertes</h1>
            <p class="text-[#444652] mt-2 max-w-2xl">
                Surveillez vos titres BRVM sur les cotations synchronisées : seuils de prix, variation et volume.
                Notifications in-app (et e-mail si choisi).
            </p>
        </div>
        <div class="flex flex-wrap gap-2">
            <button type="button" wire:click="checkNow"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg border border-[#c5c5d4] text-[#001a61] font-bold hover:bg-[#e7eeff]">
                <span class="material-symbols-outlined text-base">sync</span> Vérifier maintenant
            </button>
            <button type="button" wire:click="openForm"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-[#ffbf00] text-[#261a00] font-bold hover:brightness-95">
                <span class="material-symbols-outlined">add_alert</span> Nouvelle alerte
            </button>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white border border-[#c5c5d4] rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-[#e7eeff] flex flex-wrap items-center justify-between gap-2">
                    <h2 class="font-bold text-[#001a61] text-lg">Mes alertes</h2>
                    <div class="flex flex-wrap gap-2 text-xs font-bold">
                        <span class="px-2.5 py-1 rounded-full bg-red-50 text-red-700">{{ $critical }} critiques</span>
                        <span class="px-2.5 py-1 rounded-full bg-[#e7eeff] text-[#001a61]">{{ $active }} actives</span>
                        <span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-800">{{ $triggeredCount }} déclenchées</span>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-[#f0f3ff] text-[#757683] text-xs uppercase">
                            <tr>
                                <th class="text-left px-4 py-3">Actif</th>
                                <th class="text-left px-4 py-3">Condition</th>
                                <th class="text-left px-4 py-3">Cours</th>
                                <th class="text-left px-4 py-3">Statut</th>
                                <th class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($alerts as $a)
                                @php
                                    $dist = $a->distanceToThreshold();
                                @endphp
                                <tr @class(['border-t border-[#e7eeff]', 'bg-red-50/40' => $a->status === 'triggered'])>
                                    <td class="px-4 py-3">
                                        <p class="font-semibold text-[#001a61]">{{ $a->stock?->symbol ?? $a->asset_label }}</p>
                                        <p class="text-xs text-[#757683] line-clamp-1">{{ $a->asset_label }}</p>
                                        <span class="inline-block mt-1 text-[10px] font-bold uppercase px-1.5 py-0.5 rounded {{ $a->severityBadgeClasses() }}">
                                            {{ \App\Models\MarketAlert::severityLabel($a->severity) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="font-medium text-[#131c2a]">{{ $a->triggerLabelInstance() }}</p>
                                        <p class="text-xs text-[#757683] mt-0.5">
                                            Seuil :
                                            @if ($a->threshold !== null)
                                                {{ number_format((float) $a->threshold, in_array($a->trigger_type, ['variation_above', 'variation_below'], true) ? 2 : 2, ',', ' ') }}
                                                @if (str_starts_with($a->trigger_type, 'variation')) % @elseif ($a->trigger_type === 'volume') titres @else XOF @endif
                                            @else
                                                —
                                            @endif
                                        </p>
                                        @if ($a->status === 'triggered' && $a->notes)
                                            <p class="text-xs text-red-700 mt-1">{{ $a->notes }}</p>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 tabular-nums">
                                        @if ($a->stock)
                                            <p class="font-semibold text-[#001a61]">{{ number_format((float) $a->stock->current_price, 2, ',', ' ') }} XOF</p>
                                            <p @class([
                                                'text-xs font-semibold',
                                                'text-emerald-700' => (float) $a->stock->variation_percent >= 0,
                                                'text-red-700' => (float) $a->stock->variation_percent < 0,
                                            ])>
                                                {{ (float) $a->stock->variation_percent >= 0 ? '+' : '' }}{{ number_format((float) $a->stock->variation_percent, 2, ',', ' ') }}%
                                            </p>
                                            @if ($a->status === 'active' && $dist !== null)
                                                <p class="text-[11px] text-[#757683] mt-0.5">
                                                    @if ($dist > 0)
                                                        Encore {{ number_format($dist, 2, ',', ' ') }}
                                                    @else
                                                        Zone atteinte
                                                    @endif
                                                </p>
                                            @endif
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-0.5 rounded-full text-xs font-bold uppercase {{ $a->statusBadgeClasses() }}">
                                            {{ \App\Models\MarketAlert::statusLabel($a->status) }}
                                        </span>
                                        @if ($a->triggered_at)
                                            <p class="text-[11px] text-[#757683] mt-1">{{ $a->triggered_at->format('d/m H:i') }}</p>
                                        @endif
                                        <p class="text-[10px] text-[#757683] mt-1 uppercase">{{ $a->channel === 'email' ? 'E-mail + app' : 'In-app' }}</p>
                                    </td>
                                    <td class="px-4 py-3 text-right whitespace-nowrap">
                                        @if ($a->status === 'triggered')
                                            <button type="button" wire:click="rearm({{ $a->id }})" class="text-xs font-bold text-[#001a61] hover:underline mr-2">Réarmer</button>
                                        @elseif (in_array($a->status, ['active', 'paused'], true))
                                            <button type="button" wire:click="togglePause({{ $a->id }})" class="text-xs font-bold text-[#0a2e8c] hover:underline mr-2">
                                                {{ $a->status === 'paused' ? 'Reprendre' : 'Pause' }}
                                            </button>
                                        @endif
                                        <button type="button" wire:click="deleteAlert({{ $a->id }})" class="text-[#757683] hover:text-red-600" title="Supprimer">
                                            <span class="material-symbols-outlined text-lg">delete</span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-10 text-center text-[#757683]">
                                        Aucune alerte. Créez-en une pour démarrer.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($showForm)
                <div class="bg-white border border-[#001a61]/25 rounded-xl p-5 space-y-4 shadow-sm">
                    <div class="flex items-center justify-between gap-3">
                        <h3 class="font-bold text-[#001a61] text-lg">Nouvelle alerte</h3>
                        <button type="button" wire:click="$set('showForm', false)" class="text-[#757683] hover:text-[#001a61]">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>

                    @if ($selected)
                        <div class="rounded-lg bg-[#e7eeff] px-4 py-3 flex flex-wrap items-center justify-between gap-2 text-sm">
                            <div>
                                <p class="font-bold text-[#001a61]">{{ $selected->symbol }} — {{ $selected->company_name }}</p>
                                <p class="text-[#444652]">Cours : {{ number_format((float) $selected->current_price, 2, ',', ' ') }} XOF
                                    <span @class(['font-semibold', 'text-emerald-700' => $selected->variation_percent >= 0, 'text-red-700' => $selected->variation_percent < 0])>
                                        {{ $selected->variation_percent >= 0 ? '+' : '' }}{{ number_format((float) $selected->variation_percent, 2, ',', ' ') }}%
                                    </span>
                                </p>
                            </div>
                            <p class="text-xs text-[#757683]">Volume : {{ number_format((int) ($selected->volume ?? 0), 0, ',', ' ') }}</p>
                        </div>
                    @endif

                    <div class="grid sm:grid-cols-2 gap-3">
                        <div class="sm:col-span-2">
                            <label class="text-sm font-medium">Titre BRVM</label>
                            <select wire:model.live="stock_symbol" class="w-full mt-1 rounded-lg border-[#c5c5d4]">
                                @foreach ($stocks as $s)
                                    <option value="{{ $s->symbol }}">{{ $s->symbol }} — {{ $s->company_name }}</option>
                                @endforeach
                            </select>
                            @error('stock_symbol') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium">Déclencheur</label>
                            <select wire:model.live="trigger_type" class="w-full mt-1 rounded-lg border-[#c5c5d4]">
                                <option value="price_above">Prix supérieur ou égal au seuil</option>
                                <option value="price_below">Prix inférieur ou égal au seuil</option>
                                <option value="variation_above">Variation journalière ≥ seuil (%)</option>
                                <option value="variation_below">Variation journalière ≤ seuil (%)</option>
                                <option value="volume">Volume ≥ seuil</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-medium">Seuil</label>
                            <input type="number" step="0.01" wire:model="threshold" class="w-full mt-1 rounded-lg border-[#c5c5d4]"
                                placeholder="{{ str_starts_with($trigger_type, 'variation') ? '% (ex. 2)' : ($trigger_type === 'volume' ? 'Volume' : 'Prix XOF') }}">
                            @error('threshold') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium">Sévérité</label>
                            <select wire:model="alert_severity" class="w-full mt-1 rounded-lg border-[#c5c5d4]">
                                <option value="faible">Faible</option>
                                <option value="normale">Normale</option>
                                <option value="critique">Critique</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-medium">Notification</label>
                            <select wire:model="channel" class="w-full mt-1 rounded-lg border-[#c5c5d4]">
                                <option value="in_app">Dans l’espace client</option>
                                <option value="email">E-mail + espace client</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-3 pt-1">
                        <button type="button" wire:click="create" class="px-5 py-2.5 rounded-lg bg-[#001a61] text-white font-bold hover:bg-[#0a2e8c]">
                            Enregistrer
                        </button>
                        <button type="button" wire:click="$set('showForm', false)" class="px-5 py-2.5 rounded-lg border border-[#c5c5d4] font-medium">
                            Annuler
                        </button>
                    </div>
                </div>
            @endif
        </div>

        <aside class="space-y-4">
            <div class="bg-white border border-[#c5c5d4] rounded-xl p-5">
                <h3 class="font-bold text-[#001a61] mb-3 flex items-center gap-2">
                    <span class="material-symbols-outlined">filter_list</span> Filtrer
                </h3>
                <p class="text-xs uppercase text-[#757683] mb-2">Statut</p>
                <div class="flex flex-wrap gap-2 mb-4">
                    @foreach (['' => 'Tous', 'active' => 'Actives', 'triggered' => 'Déclenchées', 'paused' => 'Pause'] as $key => $label)
                        <button type="button" wire:click="$set('statusFilter', '{{ $key }}')"
                            @class([
                                'px-3 py-1.5 rounded text-xs font-bold border',
                                'bg-[#001a61] text-white border-[#001a61]' => $statusFilter === $key,
                                'border-[#c5c5d4] text-[#444652]' => $statusFilter !== $key,
                            ])>{{ $label }}</button>
                    @endforeach
                </div>
                <p class="text-xs uppercase text-[#757683] mb-2">Sévérité</p>
                <div class="flex flex-wrap gap-2 mb-4">
                    @foreach (['' => 'Toutes', 'critique' => 'Critique', 'normale' => 'Normale', 'faible' => 'Faible'] as $key => $label)
                        <button type="button" wire:click="$set('severity', '{{ $key }}')"
                            @class([
                                'px-3 py-1.5 rounded text-xs font-bold border',
                                'bg-[#001a61] text-white border-[#001a61]' => $severity === $key,
                                'border-[#c5c5d4] text-[#444652]' => $severity !== $key,
                            ])>{{ $label }}</button>
                    @endforeach
                </div>
                <p class="text-xs uppercase text-[#757683] mb-2">Catégorie</p>
                <select wire:model.live="category" class="w-full rounded-lg border-[#c5c5d4] text-sm">
                    <option value="">Toutes</option>
                    <option value="action">Actions</option>
                </select>
            </div>

            <div class="bg-[#001a61] text-white rounded-xl p-5 relative overflow-hidden">
                <p class="text-sm text-white/70">Largeur du marché BRVM</p>
                <p class="text-4xl font-extrabold mt-2 tabular-nums">{{ $sentiment['score'] }}<span class="text-lg font-medium">/100</span></p>
                <p class="text-[#ffbf00] text-sm mt-1 font-semibold">{{ $sentiment['label'] }}</p>
                <div class="mt-4 h-2 rounded-full bg-white/20">
                    <div class="h-2 rounded-full bg-[#ffbf00] transition-all" style="width: {{ $sentiment['score'] }}%"></div>
                </div>
                <p class="mt-3 text-xs text-white/65">
                    {{ $sentiment['up'] }} hausses · {{ $sentiment['down'] }} baisses · {{ $sentiment['flat'] }} stables
                    @if ($sentiment['total'] > 0)
                        ({{ $sentiment['total'] }} titres)
                    @endif
                </p>
            </div>

            <div class="rounded-xl border border-[#c5c5d4] bg-[#f9f9ff] p-4 text-xs text-[#444652] leading-relaxed">
                <p class="font-bold text-[#001a61] mb-1">Comment ça marche</p>
                Les alertes sont comparées aux cotations stockées (sync BRVM). Une alerte déclenchée peut être réarmée.
                Choisissez « E-mail » pour recevoir aussi un message sur votre adresse de compte.
            </div>
        </aside>
    </div>
</div>
