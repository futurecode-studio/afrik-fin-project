<div x-data="{ open: @entangle('showModal').live, del: @entangle('showDeleteModal').live }">
    <div class="p-6 lg:p-8 space-y-6">
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-[#757683] mb-2 text-xs font-semibold tracking-wider uppercase flex-wrap">
                    <a href="{{ route('admin.events') }}" class="hover:text-[#001a61]" wire:navigate.hover>Événements</a>
                    <span class="material-symbols-outlined text-sm">chevron_right</span>
                    <span class="text-[#001a61]">{{ Str::limit($event->title, 40) }}</span>
                </nav>
                <h1 class="text-2xl font-extrabold text-[#001a61]">Boutique événement</h1>
                <p class="text-sm text-[#444652] mt-1">{{ $event->title }}</p>
            </div>
            <button @click="open = true; $wire.openModal()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#001a61] text-white text-sm font-bold hover:bg-[#0a2e8c]">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Nouvel article boutique
            </button>
        </div>

        @include('livewire.admin.partials.event-admin-nav', ['event' => $event])

        <div class="rounded-lg border bg-card text-card-foreground shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-muted">
                        <tr>
                            <th class="text-left p-4 font-semibold">Article</th>
                            <th class="text-left p-4 font-semibold">Variantes</th>
                            <th class="text-left p-4 font-semibold">Prix</th>
                            <th class="text-left p-4 font-semibold">Statut</th>
                            <th class="text-right p-4 font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr class="border-t border-border hover:bg-muted/50 transition-colors">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    @if($product->image_url)
                                        <img src="{{ $product->image_url }}" alt="" class="w-12 h-12 rounded-lg object-cover border border-border">
                                    @else
                                        <div class="w-12 h-12 rounded-lg bg-muted flex items-center justify-center text-muted-foreground text-xs">Aucune</div>
                                    @endif
                                    <div>
                                        <p class="font-semibold">{{ $product->name }}</p>
                                        <p class="text-xs text-muted-foreground line-clamp-1">{{ plain_text($product->description, 80) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($product->variants as $v)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-muted border border-border" title="Stock: {{ $v->stock_quantity }}">
                                            {{ $v->variant_name }}
                                            @if($v->size) <span class="ml-1 text-muted-foreground">({{ $v->size }})</span> @endif
                                            @if($v->price) <span class="ml-1 font-semibold text-primary">{{ number_format($v->price, 0, ',', ' ') }} F</span> @endif
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="p-4 whitespace-nowrap font-semibold">
                                @if($product->variants->contains(fn($v) => $v->price > 0))
                                    <span class="text-xs text-muted-foreground font-normal">À partir de</span><br>
                                    {{ number_format($product->variants->min('price') ?? $product->price, 0, ',', ' ') }} FCFA
                                @else
                                    {{ number_format($product->price, 0, ',', ' ') }} FCFA
                                @endif
                            </td>
                            <td class="p-4">
                                <button wire:click="toggleActive({{ $product->id }})" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $product->is_active ? 'Actif' : 'Inactif' }}
                                </button>
                            </td>
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="edit({{ $product->id }})" class="text-sm text-primary hover:underline">Modifier</button>
                                    <button wire:click="confirmDelete({{ $product->id }})" class="text-sm text-red-600 hover:underline">Supprimer</button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="p-8 text-center text-muted-foreground">Aucun article pour cet événement.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">{{ $products->links() }}</div>
    </div>

    <!-- Modal Article -->
    <div x-show="open" x-cloak style="display:none" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/60" @click="open = false"></div>
        <div class="relative adf-modal-panel bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto border border-[#c5c5d4]">
            <div class="p-6 border-b border-border flex items-center justify-between">
                <h2 class="text-xl font-bold">{{ $editMode ? 'Modifier' : 'Créer' }} un article</h2>
                <button @click="open = false" class="text-muted-foreground hover:text-foreground"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1">Nom *</label>
                        <input type="text" wire:model="name" class="w-full px-3 py-2 border border-border rounded-lg bg-background focus:ring-2 focus:ring-primary">
                        @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1">Description</label>
                        <textarea wire:model="description" rows="2" class="w-full px-3 py-2 border border-border rounded-lg bg-background focus:ring-2 focus:ring-primary"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Prix (FCFA) *</label>
                        <input type="number" wire:model="price" min="0" class="w-full px-3 py-2 border border-border rounded-lg bg-background focus:ring-2 focus:ring-primary">
                        @error('price')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="checkbox" wire:model="is_active" id="is_active" class="rounded border-border">
                        <label for="is_active" class="text-sm font-medium">Article visible</label>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1">Photo</label>
                        <input type="file" wire:model="image" accept="image/*" class="w-full text-sm">
                        @if($image_url)
                            <img src="{{ asset('storage/'.$image_url) }}" class="mt-2 h-20 rounded-lg object-cover border border-border">
                        @endif
                    </div>
                </div>

                <!-- Variantes -->
                <div class="border-t border-border pt-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-semibold text-sm">Variantes (tailles, couleurs...)</h3>
                        <button wire:click="addVariant" type="button" class="text-xs text-primary hover:underline">+ Ajouter une variante</button>
                    </div>
                    @foreach($variants as $index => $variant)
                    <div class="grid grid-cols-12 gap-2 mb-2 items-end">
                        <div class="col-span-3">
                            <input type="text" wire:model="variants.{{ $index }}.variant_name" placeholder="Nom (ex: M)" class="w-full px-2 py-1.5 text-sm border border-border rounded bg-background">
                        </div>
                        <div class="col-span-2">
                            <input type="number" wire:model="variants.{{ $index }}.price" placeholder="Prix (FCFA)" min="0" class="w-full px-2 py-1.5 text-sm border border-border rounded bg-background">
                        </div>
                        <div class="col-span-2">
                            <input type="text" wire:model="variants.{{ $index }}.size" placeholder="Taille" class="w-full px-2 py-1.5 text-sm border border-border rounded bg-background">
                        </div>
                        <div class="col-span-2">
                            <input type="text" wire:model="variants.{{ $index }}.color" placeholder="Couleur" class="w-full px-2 py-1.5 text-sm border border-border rounded bg-background">
                        </div>
                        <div class="col-span-2">
                            <input type="number" wire:model="variants.{{ $index }}.stock_quantity" placeholder="Stock" min="0" class="w-full px-2 py-1.5 text-sm border border-border rounded bg-background">
                        </div>
                        <div class="col-span-1">
                            <button wire:click="removeVariant({{ $index }})" type="button" class="text-xs text-red-600 hover:underline w-full text-left">Retirer</button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="p-6 border-t border-border flex justify-end gap-3">
                <button @click="open = false" class="px-4 py-2 border border-border rounded-lg text-foreground hover:bg-muted transition-colors">Annuler</button>
                <button wire:click="save" class="px-4 py-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary-light transition-colors font-medium">Enregistrer</button>
            </div>
        </div>
    </div>
    <div x-show="del" x-cloak style="display:none" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/60" @click="del = false"></div>
        <div class="relative adf-modal-panel bg-white rounded-lg shadow-xl w-full max-w-md border border-[#c5c5d4] p-6">
            <h3 class="text-lg font-bold mb-2">Confirmer la suppression</h3>
            <p class="text-sm text-muted-foreground mb-6">Cet article et ses variantes seront supprimés définitivement.</p>
            <div class="flex justify-end gap-3">
                <button @click="del = false" class="px-4 py-2 border border-border rounded-lg text-foreground hover:bg-muted">Annuler</button>
                <button wire:click="delete" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Supprimer</button>
            </div>
        </div>
    </div>
    
</div>
