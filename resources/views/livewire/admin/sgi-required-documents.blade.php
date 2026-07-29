<div x-data="{ open: @entangle('showModal').live }">
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4 mb-6">
        <div>
            <a href="{{ route('admin.sgi-sgo') }}" class="text-xs font-bold text-[#0a2e8c] hover:underline">← SGI / SGO</a>
            <h1 class="text-3xl font-extrabold text-[#001a61] mt-1">Documents à fournir</h1>
            <p class="text-sm text-[#444652] mt-1">
                Liste affichée aux clients lors d’une demande d’ouverture de compte SGI via ADF.
            </p>
        </div>
        <button type="button" wire:click="openModal" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#001a61] text-white text-sm font-bold hover:bg-[#0a2e8c]">
            <span class="material-symbols-outlined text-[18px]">add</span>
            Ajouter
        </button>
    </div>

    <div class="bg-white border border-[#c5c5d4] rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-[#f0f3ff] text-xs uppercase text-[#757683]">
                <tr>
                    <th class="text-left px-4 py-3">Ordre</th>
                    <th class="text-left px-4 py-3">Document</th>
                    <th class="text-left px-4 py-3">Statut</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($documents as $doc)
                    <tr class="border-t border-[#e7eeff]" wire:key="sgi-doc-{{ $doc->id }}">
                        <td class="px-4 py-3 tabular-nums">{{ $doc->display_order }}</td>
                        <td class="px-4 py-3">
                            <p class="font-semibold text-[#001a61]">{{ $doc->title }}</p>
                            @if ($doc->description)
                                <p class="text-xs text-[#757683] mt-0.5">{{ $doc->description }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <button type="button" wire:click="toggleActive({{ $doc->id }})"
                                class="text-xs font-bold uppercase px-2 py-0.5 rounded {{ $doc->is_active ? 'bg-emerald-50 text-emerald-800' : 'bg-gray-100 text-gray-600' }}">
                                {{ $doc->is_active ? 'Actif' : 'Masqué' }}
                            </button>
                        </td>
                        <td class="px-4 py-3 text-right whitespace-nowrap space-x-2">
                            <button type="button" wire:click="edit({{ $doc->id }})" class="text-xs font-bold text-[#0a2e8c] hover:underline">Modifier</button>
                            <button type="button" wire:click="delete({{ $doc->id }})" wire:confirm="Supprimer ce document ?" class="text-xs font-bold text-red-600 hover:underline">Supprimer</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-10 text-center text-[#757683]">Aucun document. Ajoutez la liste à présenter aux clients.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div x-show="open" x-cloak style="display:none" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/50" wire:click="closeModal"></div>
        <div class="relative bg-white rounded-xl border border-[#c5c5d4] w-full max-w-lg p-5 space-y-4 shadow-xl">
            <h3 class="font-bold text-[#001a61] text-lg">{{ $editMode ? 'Modifier' : 'Ajouter' }} un document</h3>
            <div>
                <label class="text-sm font-medium">Titre *</label>
                <input type="text" wire:model="title" class="w-full mt-1 rounded-lg border-[#c5c5d4]">
                @error('title')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="text-sm font-medium">Description</label>
                <textarea wire:model="description" rows="3" class="w-full mt-1 rounded-lg border-[#c5c5d4]"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-sm font-medium">Ordre</label>
                    <input type="number" wire:model="display_order" min="0" class="w-full mt-1 rounded-lg border-[#c5c5d4]">
                </div>
                <div class="flex items-end pb-2">
                    <label class="inline-flex items-center gap-2 text-sm font-medium">
                        <input type="checkbox" wire:model="is_active" class="rounded border-[#c5c5d4]">
                        Actif / visible
                    </label>
                </div>
            </div>
            <div class="flex gap-2 pt-1">
                <button type="button" wire:click="closeModal" class="flex-1 py-2.5 rounded-lg border border-[#c5c5d4] font-bold text-[#001a61]">Annuler</button>
                <button type="button" wire:click="save" class="flex-1 py-2.5 rounded-lg bg-[#001a61] text-white font-bold">Enregistrer</button>
            </div>
        </div>
    </div>
</div>
