<div>
    <main class="container mx-auto px-4 py-8">
        <nav class="mb-6 text-sm">
            <ol class="flex items-center space-x-2">
                <li><a href="{{ route('admin.formations') }}" class="text-primary hover:underline" wire:navigate.hover>Formations</a></li>
                <li><span class="text-muted-foreground">/</span></li>
                <li><a href="{{ route('admin.formations.show', $formation) }}" class="text-primary hover:underline" wire:navigate.hover>{{ $formation->titre }}</a></li>
                <li><span class="text-muted-foreground">/</span></li>
                <li class="font-medium">Catalogue</li>
            </ol>
        </nav>

        @include('livewire.admin.partials.formation-admin-nav', ['formation' => $formation])

        <div class="mb-6 rounded-lg border bg-card p-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold">Catalogue visuel</h2>
                <p class="text-sm text-muted-foreground mt-1">
                    Affiches et programmes affichés sur la page publique « Voir le catalogue ».
                </p>
            </div>
            @if ($formation->isCatalogOnly())
                <a href="{{ route('formation-catalog', $formation->slug) }}" target="_blank" rel="noopener"
                    class="inline-flex items-center gap-2 text-sm font-bold text-[#001a61] border border-[#c5c5d4] px-4 py-2 rounded-lg hover:bg-[#e7eeff]">
                    <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                    Voir la page publique
                </a>
            @endif
        </div>

        <div class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1">
                <div class="rounded-lg border bg-card p-5 space-y-4">
                    <h3 class="font-bold">Ajouter des visuels</h3>
                    <div>
                        <label class="block text-sm font-medium mb-2">Titre (optionnel, commun aux fichiers)</label>
                        <input type="text" wire:model="newTitle" placeholder="Ex. Responsable financier"
                            class="w-full rounded-md border border-[#c5c5d4] px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Images (JPG, PNG — max 5 Mo)</label>
                        <input type="file" wire:model="catalogImages" accept="image/*" multiple
                            class="w-full text-sm">
                        @error('catalogImages.*') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <button type="button" wire:click="uploadCatalogImages" wire:loading.attr="disabled"
                        class="w-full inline-flex items-center justify-center gap-2 bg-primary text-primary-foreground font-semibold py-2.5 rounded-md hover:bg-primary/90 disabled:opacity-50">
                        <span wire:loading.remove wire:target="uploadCatalogImages">Ajouter au catalogue</span>
                        <span wire:loading wire:target="uploadCatalogImages">Envoi…</span>
                    </button>
                    <p class="text-xs text-muted-foreground">
                        Astuce : renseignez le libellé prix « Voir catalogue » sur la formation pour activer le bouton public.
                    </p>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="rounded-lg border bg-card">
                    <div class="p-5 border-b">
                        <h3 class="font-bold">{{ $items->count() }} visuel(s)</h3>
                    </div>
                    @if ($items->isEmpty())
                        <p class="p-8 text-center text-muted-foreground text-sm">Aucun visuel pour le moment.</p>
                    @else
                        <div class="divide-y">
                            @foreach ($items as $item)
                                <div class="p-4 flex flex-col sm:flex-row gap-4 {{ $item->is_active ? '' : 'opacity-60' }}">
                                    <div class="w-full sm:w-36 aspect-[3/4] rounded-lg overflow-hidden bg-[#e7eeff] shrink-0">
                                        <img src="{{ $item->image_url }}" alt="" class="w-full h-full object-cover object-top">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-bold text-[#001a61]">{{ $item->title ?: 'Sans titre' }}</p>
                                        <p class="text-xs text-muted-foreground mt-1">Ordre {{ $item->display_order }}</p>
                                        <div class="mt-3 flex flex-wrap gap-2">
                                            <button type="button" wire:click="moveItem({{ $item->id }}, 'up')"
                                                class="text-xs font-bold border px-2 py-1 rounded hover:bg-muted">↑</button>
                                            <button type="button" wire:click="moveItem({{ $item->id }}, 'down')"
                                                class="text-xs font-bold border px-2 py-1 rounded hover:bg-muted">↓</button>
                                            <button type="button" wire:click="toggleActive({{ $item->id }})"
                                                class="text-xs font-bold border px-2 py-1 rounded hover:bg-muted">
                                                {{ $item->is_active ? 'Masquer' : 'Afficher' }}
                                            </button>
                                            <button type="button" wire:click="removeItem({{ $item->id }})"
                                                wire:confirm="Supprimer ce visuel ?"
                                                class="text-xs font-bold border border-red-200 text-red-600 px-2 py-1 rounded hover:bg-red-50">
                                                Supprimer
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
</div>
