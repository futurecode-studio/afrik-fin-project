<div x-data="{ open: @entangle('showModal').live, del: @entangle('showDeleteModal').live }">

    <main class="container mx-auto px-4 py-8">
        <div class="rounded-lg border bg-card shadow-sm">
            <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-2xl font-semibold">Services du site</h3>
                    <p class="text-sm text-muted-foreground">{{ $services->total() }} service(s) — affichés sur /services</p>
                </div>
                <button @click="open = true; $wire.openModal()" type="button" class="bg-primary text-primary-foreground px-5 py-2.5 rounded-md text-sm font-medium">
                    Ajouter un service
                </button>
            </div>

            <div class="px-6 pb-4">
                <input wire:model.live.debounce.300ms="search" type="search" placeholder="Rechercher…"
                    class="h-10 w-full md:w-96 rounded-md border px-3 text-sm">
            </div>

            <div class="overflow-x-auto px-6 pb-6">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b text-left text-muted-foreground">
                            <th class="py-3 pr-3">Ordre</th>
                            <th class="py-3 pr-3">Titre</th>
                            <th class="py-3 pr-3">Slug</th>
                            <th class="py-3 pr-3">Statut</th>
                            <th class="py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($services as $service)
                            <tr class="border-b">
                                <td class="py-3 pr-3">{{ $service->order }}</td>
                                <td class="py-3 pr-3 font-medium">{{ $service->title }}</td>
                                <td class="py-3 pr-3 text-muted-foreground">{{ $service->slug }}</td>
                                <td class="py-3 pr-3">
                                    <button type="button" wire:click="toggleActive({{ $service->id }})"
                                        class="text-xs px-2 py-1 rounded-full {{ $service->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700' }}">
                                        {{ $service->is_active ? 'Actif' : 'Inactif' }}
                                    </button>
                                </td>
                                <td class="py-3 text-right space-x-2">
                                    <button type="button" wire:click="edit({{ $service->id }})" class="text-primary font-medium">Modifier</button>
                                    <button type="button" wire:click="confirmDelete({{ $service->id }})" class="text-red-600 font-medium">Suppr.</button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-10 text-center text-muted-foreground">Aucun service</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">{{ $services->links() }}</div>
            </div>
        </div>
    </main>

    <div x-show="open" x-cloak style="display:none" class="fixed inset-0 z-50 bg-black/40 flex items-start justify-center overflow-y-auto py-10 px-4">
            <div class="bg-white rounded-xl w-full max-w-2xl p-6 space-y-4">
                <h4 class="text-xl font-bold">{{ $editMode ? 'Modifier' : 'Nouveau' }} service</h4>
                <div class="grid md:grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-medium">Titre *</label>
                        <input wire:model="title" class="w-full border rounded-md h-10 px-3 text-sm">
                        @error('title') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-xs font-medium">Slug</label>
                        <input wire:model="slug" class="w-full border rounded-md h-10 px-3 text-sm" placeholder="auto">
                    </div>
                    <div>
                        <label class="text-xs font-medium">Icône (Material Symbol)</label>
                        <input wire:model="icon" class="w-full border rounded-md h-10 px-3 text-sm">
                    </div>
                    <div>
                        <label class="text-xs font-medium">Ordre</label>
                        <input type="number" wire:model="order" class="w-full border rounded-md h-10 px-3 text-sm">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-medium">Sous-titre</label>
                    <input wire:model="subtitle" class="w-full border rounded-md h-10 px-3 text-sm">
                </div>
                <div>
                    <label class="text-xs font-medium">Extrait</label>
                    <textarea wire:model="excerpt" rows="2" class="w-full border rounded-md px-3 py-2 text-sm"></textarea>
                </div>
                <div>
                    <label class="text-xs font-medium">Contenu</label>
                    <textarea wire:model="content" rows="5" class="w-full border rounded-md px-3 py-2 text-sm"></textarea>
                </div>
                <div>
                    <label class="text-xs font-medium">Points forts (1 par ligne)</label>
                    <textarea wire:model="featuresText" rows="4" class="w-full border rounded-md px-3 py-2 text-sm"></textarea>
                </div>
                <div class="grid md:grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-medium">Tarif affiché</label>
                        <input wire:model="price_label" class="w-full border rounded-md h-10 px-3 text-sm">
                    </div>
                    <div>
                        <label class="text-xs font-medium">Durée / formule</label>
                        <input wire:model="duration_label" class="w-full border rounded-md h-10 px-3 text-sm">
                    </div>
                    <div>
                        <label class="text-xs font-medium">CTA label</label>
                        <input wire:model="cta_label" class="w-full border rounded-md h-10 px-3 text-sm">
                    </div>
                    <div>
                        <label class="text-xs font-medium">CTA URL</label>
                        <input wire:model="cta_url" class="w-full border rounded-md h-10 px-3 text-sm">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-medium">Image URL</label>
                    <input wire:model="image_url" class="w-full border rounded-md h-10 px-3 text-sm">
                </div>
                <div class="flex gap-4 text-sm">
                    <label class="flex items-center gap-2"><input type="checkbox" wire:model="is_active"> Actif</label>
                    <label class="flex items-center gap-2"><input type="checkbox" wire:model="is_featured"> Mis en avant</label>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="open = false" class="px-4 py-2 border rounded-md text-sm">Annuler</button>
                    <button type="button" wire:click="save" class="px-4 py-2 bg-primary text-white rounded-md text-sm font-medium">Enregistrer</button>
                </div>
            </div>
        </div>
    

    <div x-show="del" x-cloak style="display:none" class="fixed inset-0 z-50 bg-black/40 flex items-center justify-center px-4">
            <div class="bg-white rounded-xl p-6 max-w-md w-full">
                <p class="font-semibold">Supprimer ce service ?</p>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" @click="del = false" class="px-4 py-2 border rounded-md text-sm">Annuler</button>
                    <button type="button" wire:click="delete" class="px-4 py-2 bg-red-600 text-white rounded-md text-sm">Supprimer</button>
                </div>
            </div>
        </div>
    
</div>
