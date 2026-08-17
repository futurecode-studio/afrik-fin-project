<div x-data="{ open: @entangle('showModal').live, del: @entangle('showDeleteModal').live }">
    <div class="admin-page space-y-6">
        <div class="flex flex-wrap justify-between items-end gap-4 mb-2">
            <div>
                <nav class="flex items-center gap-2 text-[#757683] mb-2 text-xs font-semibold tracking-wider uppercase">
                    <span>Administration</span>
                    <span class="material-symbols-outlined text-sm">chevron_right</span>
                    <span class="text-[#001a61]">FCP / OPCVM</span>
                </nav>
                <h2 class="text-3xl font-extrabold text-[#001a61] tracking-tight">FCP du Bénin</h2>
                <p class="text-sm text-[#444652] mt-1">
                    Catalogue éditable — source initiale : bulletin officiel BRVM du 23 juillet 2026 (p. 19).
                </p>
            </div>
        </div>

        <div class="admin-card">
            <div class="flex flex-col space-y-1.5 p-6">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-2xl font-semibold leading-none tracking-tight">Liste des fonds</h3>
                        <p class="text-sm text-[#757683]">{{ $funds->total() }} FCP — visibles sur /investir/opcvm</p>
                    </div>
                    <button @click="open = true; $wire.openModal()" type="button"
                        class="inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium admin-btn-primary h-11 px-6">
                        Ajouter un FCP
                    </button>
                </div>

                <div class="mt-4 flex flex-col sm:flex-row gap-3">
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Rechercher un fonds ou une SGO…"
                        class="h-10 w-full md:w-80 rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 text-sm">
                    <select wire:model.live="filterCompany" class="h-10 rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 text-sm">
                        <option value="">Toutes les SGO</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company }}">{{ $company }}</option>
                        @endforeach
                    </select>
                    <select wire:model.live="filterCategory" class="h-10 rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 text-sm">
                        <option value="">Toutes catégories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="p-6 pt-0">
                <div class="relative w-full overflow-auto">
                    <table class="admin-table w-full text-sm">
                        <thead>
                            <tr class="border-b">
                                <th class="h-12 px-4 text-left font-medium text-[#757683]">Fonds</th>
                                <th class="h-12 px-4 text-left font-medium text-[#757683]">SGO</th>
                                <th class="h-12 px-4 text-left font-medium text-[#757683]">Catégorie</th>
                                <th class="h-12 px-4 text-right font-medium text-[#757683]">Origine</th>
                                <th class="h-12 px-4 text-right font-medium text-[#757683]">VL actuelle</th>
                                <th class="h-12 px-4 text-right font-medium text-[#757683]">Var. origine</th>
                                <th class="h-12 px-4 text-left font-medium text-[#757683]">Date</th>
                                <th class="h-12 px-4 text-left font-medium text-[#757683]">Statut</th>
                                <th class="h-12 px-4 text-right font-medium text-[#757683]">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($funds as $fund)
                                <tr class="border-b hover:bg-[#f0f3ff]">
                                    <td class="p-4 font-semibold text-[#001a61]">{{ $fund->name }}</td>
                                    <td class="p-4 text-[#444652]">{{ $fund->company_short ?: $fund->company }}</td>
                                    <td class="p-4">
                                        <span class="inline-flex rounded-full bg-[#e7eeff] text-[#001a61] px-2 py-0.5 text-xs font-semibold">{{ $fund->category }}</span>
                                    </td>
                                    <td class="p-4 text-right tabular-nums">{{ $fund->origin_nav !== null ? number_format((float) $fund->origin_nav, 0, ',', ' ') : '—' }}</td>
                                    <td class="p-4 text-right tabular-nums font-medium">{{ $fund->current_nav !== null ? number_format((float) $fund->current_nav, 2, ',', ' ') : '—' }}</td>
                                    <td class="p-4 text-right tabular-nums {{ $fund->variation_origin === null ? 'text-[#757683]' : ((float) $fund->variation_origin >= 0 ? 'text-green-700' : 'text-red-700') }}">
                                        {{ $fund->variation_origin === null ? 'ND' : number_format((float) $fund->variation_origin, 2, ',', ' ').' %' }}
                                    </td>
                                    <td class="p-4 whitespace-nowrap">{{ $fund->vl_date?->format('d/m/Y') ?? '—' }}</td>
                                    <td class="p-4">
                                        <button type="button" wire:click="toggleActive({{ $fund->id }})"
                                            class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $fund->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $fund->is_active ? 'Publié' : 'Masqué' }}
                                        </button>
                                    </td>
                                    <td class="p-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <button type="button" wire:click="edit({{ $fund->id }})"
                                                class="h-9 px-3 rounded-md border border-[#c5c5d4] bg-[#f9f9ff] hover:bg-accent" title="Modifier">
                                                Modifier
                                            </button>
                                            <button type="button" wire:click="confirmDelete({{ $fund->id }})"
                                                class="h-9 px-3 rounded-md border border-[#c5c5d4] bg-[#f9f9ff] hover:bg-red-50 hover:text-red-600" title="Supprimer">
                                                Supprimer
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="p-8 text-center text-[#757683]">Aucun FCP. Cliquez sur « Ajouter un FCP » ou lancez le seeder.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($funds->hasPages())
                    <div class="p-4">{{ $funds->links() }}</div>
                @endif
            </div>
        </div>
    </div>

    <div x-show="open" x-cloak style="display:none" class="fixed inset-0 z-50 flex items-center justify-center p-4" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-black/50" @click="open = false"></div>
        <div class="relative z-10 w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-lg bg-[#f9f9ff] p-6 shadow-lg">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold">{{ $editMode ? 'Modifier le FCP' : 'Ajouter un FCP' }}</h3>
                <button @click="open = false" type="button" class="text-[#757683]">✕</button>
            </div>
            <form wire:submit="save" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Nom du fonds <span class="text-red-500">*</span></label>
                    <input type="text" wire:model.live="name" class="w-full h-10 rounded-md border border-[#c5c5d4] bg-white px-3 text-sm">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">SGO <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="company" class="w-full h-10 rounded-md border border-[#c5c5d4] bg-white px-3 text-sm" placeholder="Ex. SOAGA">
                        @error('company') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Libellé court</label>
                        <input type="text" wire:model="company_short" class="w-full h-10 rounded-md border border-[#c5c5d4] bg-white px-3 text-sm" placeholder="NSIA, AGI…">
                    </div>
                </div>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Catégorie</label>
                        <select wire:model="category" class="w-full h-10 rounded-md border border-[#c5c5d4] bg-white px-3 text-sm">
                            @foreach ($categories as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Pays</label>
                        <input type="text" wire:model="country" class="w-full h-10 rounded-md border border-[#c5c5d4] bg-white px-3 text-sm">
                    </div>
                </div>
                <div class="grid sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">VL d’origine</label>
                        <input type="text" wire:model="origin_nav" class="w-full h-10 rounded-md border border-[#c5c5d4] bg-white px-3 text-sm" placeholder="5000">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">VL actuelle</label>
                        <input type="text" wire:model="current_nav" class="w-full h-10 rounded-md border border-[#c5c5d4] bg-white px-3 text-sm" placeholder="13155.22">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Variation origine %</label>
                        <input type="text" wire:model="variation_origin" class="w-full h-10 rounded-md border border-[#c5c5d4] bg-white px-3 text-sm" placeholder="Vide = ND">
                    </div>
                </div>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Date VL</label>
                        <input type="date" wire:model="vl_date" class="w-full h-10 rounded-md border border-[#c5c5d4] bg-white px-3 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Ordre d’affichage</label>
                        <input type="number" wire:model="display_order" min="0" class="w-full h-10 rounded-md border border-[#c5c5d4] bg-white px-3 text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Source</label>
                    <input type="text" wire:model="source" class="w-full h-10 rounded-md border border-[#c5c5d4] bg-white px-3 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Notes (ex. ND, dernière VL connue)</label>
                    <textarea wire:model="notes" rows="3" class="w-full rounded-md border border-[#c5c5d4] bg-white px-3 py-2 text-sm"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Flyer (PDF ou image, optionnel)</label>
                    <input type="file" wire:model="flyer" accept=".pdf,image/*" class="w-full text-sm">
                    @error('flyer') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    @if ($flyer_path)
                        <p class="text-xs text-[#757683] mt-1">Fichier actuel enregistré.</p>
                    @endif
                </div>
                <label class="inline-flex items-center gap-2 text-sm">
                    <input type="checkbox" wire:model="is_active" class="rounded border-[#c5c5d4]">
                    Publié sur le site
                </label>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="open = false" class="h-10 px-4 rounded-md border border-[#c5c5d4]">Annuler</button>
                    <button type="submit" class="h-10 px-4 rounded-md admin-btn-primary">{{ $editMode ? 'Enregistrer' : 'Créer' }}</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="del" x-cloak style="display:none" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/50" @click="del = false"></div>
        <div class="relative z-10 w-full max-w-md rounded-lg bg-[#f9f9ff] p-6 shadow-lg">
            <h3 class="text-lg font-semibold mb-2">Supprimer ce FCP ?</h3>
            <p class="text-[#757683] mb-6">Il disparaîtra du site public.</p>
            <div class="flex justify-end gap-3">
                <button type="button" @click="del = false" class="h-10 px-4 rounded-md border border-[#c5c5d4]">Annuler</button>
                <button type="button" wire:click="delete" class="h-10 px-4 rounded-md bg-red-600 text-white">Supprimer</button>
            </div>
        </div>
    </div>
</div>
