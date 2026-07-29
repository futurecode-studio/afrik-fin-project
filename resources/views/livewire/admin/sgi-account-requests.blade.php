<div x-data="{ notesOpen: @entangle('viewId').live }">
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4 mb-6">
        <div>
            <a href="{{ route('admin.sgi-sgo') }}" class="text-xs font-bold text-[#0a2e8c] hover:underline">← SGI / SGO</a>
            <h1 class="text-3xl font-extrabold text-[#001a61] mt-1">Demandes d’ouverture de compte SGI</h1>
            <p class="text-sm text-[#444652] mt-1">
                Clients à contacter pour créer un compte chez une SGI.
                <span class="font-bold text-[#001a61]">{{ $pendingCount }}</span> en attente · {{ $totalCount }} au total.
            </p>
        </div>
        <a href="{{ route('admin.sgi-documents') }}" class="text-sm font-bold text-[#0a2e8c] hover:underline">Gérer les documents à fournir →</a>
    </div>

    <div class="flex flex-col md:flex-row gap-3 mb-4">
        <input wire:model.live.debounce.300ms="search" type="search" placeholder="Nom, email, téléphone…"
            class="rounded-lg border-[#c5c5d4] text-sm w-full md:w-72">
        <select wire:model.live="status" class="rounded-lg border-[#c5c5d4] text-sm">
            <option value="">Tous les statuts</option>
            <option value="pending">En attente</option>
            <option value="contacted">Contacté</option>
            <option value="in_progress">En cours</option>
            <option value="done">Compte créé</option>
            <option value="cancelled">Annulé</option>
        </select>
    </div>

    <div class="bg-white border border-[#c5c5d4] rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-[#f0f3ff] text-xs uppercase text-[#757683]">
                    <tr>
                        <th class="text-left px-4 py-3">Date</th>
                        <th class="text-left px-4 py-3">Client</th>
                        <th class="text-left px-4 py-3">Contact</th>
                        <th class="text-left px-4 py-3">Source</th>
                        <th class="text-left px-4 py-3">Statut</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($requests as $req)
                        <tr class="border-t border-[#e7eeff]" wire:key="sgi-req-{{ $req->id }}">
                            <td class="px-4 py-3 whitespace-nowrap">{{ $req->created_at?->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3">
                                <p class="font-semibold text-[#001a61]">{{ $req->name }}</p>
                                @if ($req->user)
                                    <p class="text-[11px] text-[#757683]">Compte #{{ $req->user_id }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <a href="mailto:{{ $req->email }}" class="text-[#0a2e8c] font-semibold hover:underline block">{{ $req->email }}</a>
                                <a href="tel:{{ $req->phone }}" class="text-xs text-[#444652] hover:underline">{{ $req->phone }}</a>
                            </td>
                            <td class="px-4 py-3 text-xs">{{ $req->sourceLabel() }}</td>
                            <td class="px-4 py-3">
                                <span class="text-xs font-bold uppercase px-2 py-0.5 rounded bg-[#e7eeff] text-[#001a61]">{{ $req->statusLabel() }}</span>
                            </td>
                            <td class="px-4 py-3 text-right whitespace-nowrap space-x-1">
                                <button type="button" wire:click="openNotes({{ $req->id }})" class="text-xs font-bold text-[#444652] hover:underline">Notes</button>
                                <button type="button" wire:click="setStatus({{ $req->id }}, 'contacted')" class="text-xs font-bold text-[#0a2e8c] hover:underline">Contacté</button>
                                <button type="button" wire:click="setStatus({{ $req->id }}, 'in_progress')" class="text-xs font-bold text-amber-700 hover:underline">En cours</button>
                                <button type="button" wire:click="setStatus({{ $req->id }}, 'done')" class="text-xs font-bold text-emerald-700 hover:underline">Créé</button>
                                <button type="button" wire:click="setStatus({{ $req->id }}, 'cancelled')" class="text-xs font-bold text-red-600 hover:underline">Annuler</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-10 text-center text-[#757683]">Aucune demande.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($requests->hasPages())
            <div class="px-4 py-3 border-t border-[#e7eeff]">{{ $requests->links() }}</div>
        @endif
    </div>

    <div x-show="notesOpen" x-cloak style="display:none" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/50" wire:click="closeNotes"></div>
        <div class="relative bg-white rounded-xl border border-[#c5c5d4] w-full max-w-md p-5 space-y-4 shadow-xl">
            <h3 class="font-bold text-[#001a61] text-lg">Notes internes</h3>
            <textarea wire:model="admin_notes" rows="5" class="w-full rounded-lg border-[#c5c5d4]" placeholder="Suivi d’appel, SGI proposée…"></textarea>
            <div class="flex gap-2">
                <button type="button" wire:click="closeNotes" class="flex-1 py-2.5 rounded-lg border border-[#c5c5d4] font-bold text-[#001a61]">Fermer</button>
                <button type="button" wire:click="saveNotes" class="flex-1 py-2.5 rounded-lg bg-[#001a61] text-white font-bold">Enregistrer</button>
            </div>
        </div>
    </div>
</div>
