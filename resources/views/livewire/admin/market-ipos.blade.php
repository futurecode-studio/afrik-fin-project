<div class="admin-page space-y-8" x-data="{ open: @entangle('showModal').live }">
    <div>
        <nav class="flex items-center gap-2 text-[#757683] mb-2 text-xs font-semibold tracking-wider uppercase">
            <span>Administration</span>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <span class="text-[#001a61]">MARCHÉS AVANCÉS</span>
        </nav>
        <h2 class="text-3xl font-extrabold text-[#001a61]">Introductions & intentions d’ordres</h2>
    </div>

    <div class="admin-card p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-[#001a61]">Introductions en bourse</h3>
            <button type="button" @click="open = true; $wire.openModal()" class="admin-btn-primary px-4 py-2 text-sm">Ajouter</button>
        </div>
        <div class="overflow-x-auto">
            <table class="admin-table w-full text-sm">
                <thead><tr class="border-b"><th class="text-left p-3">Société</th><th class="text-left p-3">Statut</th><th class="text-left p-3">Publié</th><th class="text-right p-3">Actions</th></tr></thead>
                <tbody>
                    @foreach ($ipos as $ipo)
                        <tr class="border-b border-[#c5c5d4]">
                            <td class="p-3 font-semibold">{{ $ipo->company_name }}</td>
                            <td class="p-3">{{ $ipo->getStatusLabel() }}</td>
                            <td class="p-3">{{ $ipo->is_published ? 'Oui' : 'Non' }}</td>
                            <td class="p-3 text-right space-x-2">
                                <button type="button" wire:click="openModal({{ $ipo->id }})" class="text-[#001a61] font-bold text-xs">Éditer</button>
                                <button type="button" wire:click="delete({{ $ipo->id }})" wire:confirm="Supprimer ?" class="text-red-600 font-bold text-xs">Suppr.</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $ipos->links() }}</div>
    </div>

    <div class="admin-card p-6">
        <h3 class="font-bold text-[#001a61] mb-4">Intentions d’ordres (carnet)</h3>
        <div class="overflow-x-auto">
            <table class="admin-table w-full text-sm">
                <thead><tr class="border-b"><th class="text-left p-3">Date</th><th class="text-left p-3">Titre</th><th class="text-left p-3">Sens</th><th class="text-left p-3">Qty</th><th class="text-left p-3">Contact</th><th class="text-left p-3">Statut</th></tr></thead>
                <tbody>
                    @forelse ($orders as $o)
                        <tr class="border-b border-[#c5c5d4]">
                            <td class="p-3">{{ $o->created_at->format('d/m/Y H:i') }}</td>
                            <td class="p-3 font-semibold">{{ $o->stock?->symbol }}</td>
                            <td class="p-3">{{ strtoupper($o->side) }}</td>
                            <td class="p-3">{{ $o->quantity }} @ {{ $o->limit_price }}</td>
                            <td class="p-3">{{ $o->name }} · {{ $o->email }} · {{ $o->phone }}</td>
                            <td class="p-3">{{ $o->status }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="p-4 text-[#757683]">Aucune intention.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $orders->links() }}</div>
    </div>

    <div x-show="open" x-cloak style="display:none" class="fixed inset-0 z-50 bg-black/60 flex items-center justify-center p-4" wire:click.self="$set('showModal', false)">
            <div class="bg-white rounded-xl max-w-lg w-full p-6 space-y-3">
                <h3 class="font-bold text-lg text-[#001a61]">{{ $ipoId ? 'Modifier' : 'Nouvelle' }} introduction</h3>
                <input wire:model="company_name" placeholder="Société" class="w-full rounded-lg border-[#c5c5d4]">
                <div class="grid grid-cols-2 gap-2">
                    <input wire:model="symbol" placeholder="Symbole" class="rounded-lg border-[#c5c5d4]">
                    <input wire:model="sector" placeholder="Secteur" class="rounded-lg border-[#c5c5d4]">
                </div>
                <select wire:model="status" class="w-full rounded-lg border-[#c5c5d4]">
                    <option value="annonce">Annoncée</option>
                    <option value="souscription">Souscription</option>
                    <option value="cloture">Clôturée</option>
                    <option value="cote">Cotée</option>
                </select>
                <textarea wire:model="description" rows="3" class="w-full rounded-lg border-[#c5c5d4]" placeholder="Description"></textarea>
                <div class="grid grid-cols-2 gap-2">
                    <input type="number" wire:model="offer_price_min" placeholder="Prix min" class="rounded-lg border-[#c5c5d4]">
                    <input type="number" wire:model="offer_price_max" placeholder="Prix max" class="rounded-lg border-[#c5c5d4]">
                    <input type="date" wire:model="subscription_start" class="rounded-lg border-[#c5c5d4]">
                    <input type="date" wire:model="subscription_end" class="rounded-lg border-[#c5c5d4]">
                </div>
                <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="is_published"> Publié</label>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 border rounded-lg">Annuler</button>
                    <button type="button" wire:click="save" class="admin-btn-primary px-4 py-2">Enregistrer</button>
                </div>
            </div>
        </div>
    
</div>
