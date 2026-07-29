{{-- Formulaire actif uniquement si feature_flag client.ordres = true (admin) --}}
<div>
    <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900 mb-6">
        Mode intention : Africaine des Finances n’exécute pas les ordres. Chaque demande est destinée à être relayée vers une <strong>SGI agréée</strong>.
    </div>
    <h1 class="text-3xl font-extrabold text-[#001a61]">Stratégies d'Ordres Programmés</h1>
    <p class="text-[#444652] mt-2 max-w-2xl">Enregistrez une intention (seuil, OCO, trailing) pour suivi / mise en relation SGI.</p>

    <div class="mt-8 grid lg:grid-cols-2 gap-6">
        <div class="bg-white border border-[#c5c5d4] rounded-xl p-6">
            @if ($stock)
                <p class="text-xs uppercase tracking-wider text-[#757683]">Instrument</p>
                <h2 class="text-xl font-extrabold text-[#001a61]">{{ $stock->company_name }}</h2>
                <p class="text-sm text-[#444652]">BRVM: {{ $stock->symbol }}</p>
                <p class="mt-3 text-2xl font-bold text-[#001a61]">{{ number_format($stock->current_price, 2, ',', ' ') }} XOF</p>
            @endif
            <div class="mt-4">
                <label class="text-sm font-medium">Titre</label>
                <select wire:model.live="symbol" class="w-full mt-1 rounded-lg border-[#c5c5d4]">
                    @foreach ($stocks as $s)
                        <option value="{{ $s->symbol }}">{{ $s->symbol }} — {{ $s->company_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <form wire:submit.prevent="prepareSubmit" class="bg-white border border-[#c5c5d4] rounded-xl p-6 space-y-4">
            <h3 class="font-bold text-[#001a61] text-lg">Nouvelle intention</h3>
            <div>
                <label class="text-sm font-medium">Type</label>
                <select wire:model="condition_type" class="w-full mt-1 rounded-lg border-[#c5c5d4]">
                    <option value="threshold">Seuil</option>
                    <option value="oco">OCO</option>
                    <option value="trailing">Trailing</option>
                    <option value="linked">TP &amp; SL</option>
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
                <p class="text-[11px] text-[#757683] mt-1">Utilisé uniquement si vous avez déjà un compte SGI.</p>
            </div>
            <button type="submit" class="w-full py-3 rounded-xl bg-[#001a61] text-white font-bold">Continuer</button>
        </form>
    </div>

    <div class="mt-8 bg-white border border-[#c5c5d4] rounded-xl overflow-hidden">
        <div class="px-5 py-4 border-b font-bold text-[#001a61]">Mes intentions</div>
        <table class="w-full text-sm">
            <thead class="bg-[#f0f3ff] text-xs uppercase text-[#757683]">
                <tr>
                    <th class="text-left px-4 py-3">Date</th>
                    <th class="text-left px-4 py-3">Titre</th>
                    <th class="text-left px-4 py-3">Détail</th>
                    <th class="text-left px-4 py-3">SGI</th>
                    <th class="text-left px-4 py-3">Statut</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $o)
                    <tr class="border-t">
                        <td class="px-4 py-3">{{ $o->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 font-semibold">{{ $o->stock?->symbol }}</td>
                        <td class="px-4 py-3">{{ strtoupper($o->side) }} · {{ $o->condition_type }} @ {{ $o->target_price }}</td>
                        <td class="px-4 py-3 text-xs">
                            {{ $o->partner?->nom ?? '—' }}
                            @if ($o->sgi_account_number)
                                <span class="block text-[#757683]">{{ $o->sgi_account_number }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $o->statusLabel() }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-[#757683]">Aucune intention.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @include('livewire.partials.sgi-order-modal')
</div>
