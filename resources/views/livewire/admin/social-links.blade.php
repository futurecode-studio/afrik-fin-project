<div x-data="{ open: @entangle('showModal').live, del: @entangle('showDeleteModal').live }">

    <main class="container mx-auto px-4 py-8">
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="flex flex-col space-y-1.5 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-semibold leading-none tracking-tight">Réseaux Sociaux</h3>
                        <p class="text-sm text-muted-foreground">{{ $links->count() }} lien(s) configuré(s)</p>
                    </div>
                    <button @click="open = true; $wire.openModal()" type="button"
                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary-light shadow-elegant hover:shadow-glow transition-smooth h-11 px-6 py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                            <path d="M5 12h14"/><path d="M12 5v14"/>
                        </svg>
                        Ajouter un réseau
                    </button>
                </div>
            </div>

            <div class="p-6 pt-0">
                @if($links->isEmpty())
                    <div class="text-center py-12 text-muted-foreground">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-3 opacity-40">
                            <path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/>
                        </svg>
                        <p>Aucun réseau social configuré. Cliquez sur "Ajouter un réseau" pour commencer.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        @foreach($links as $link)
                            <div class="relative flex flex-col gap-3 rounded-xl border bg-card p-4 shadow-sm hover:shadow-md transition-shadow">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary">
                                                {!! $link->getIconHtml() !!}
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-sm">{{ $link->getPlatformLabel() }}</p>
                                            <p class="text-xs text-muted-foreground truncate max-w-[140px]">{{ $link->url }}</p>
                                        </div>
                                    </div>
                                    <button wire:click="toggleActive({{ $link->id }})" type="button"
                                        class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold shrink-0 {{ $link->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $link->is_active ? 'Actif' : 'Inactif' }}
                                    </button>
                                </div>
                                <div class="flex items-center justify-between border-t pt-3">
                                    <span class="text-xs text-muted-foreground">Ordre : {{ $link->order }}</span>
                                    <div class="flex gap-2">
                                        <button type="button" wire:click="edit({{ $link->id }})"
                                            class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 w-8"
                                            title="Modifier">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/>
                                            </svg>
                                        </button>
                                        <button type="button" wire:click="confirmDelete({{ $link->id }})"
                                            class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-input bg-background hover:bg-red-50 hover:text-red-600 h-8 w-8"
                                            title="Supprimer">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </main>

    {{-- Modal création/modification --}}
    <div x-show="open" x-cloak style="display:none" class="fixed inset-0 z-50 flex items-center justify-center p-4" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-black/50" @click="open = false"></div>
            <div class="relative z-10 adf-modal-panel w-full max-w-lg rounded-lg bg-white p-6 shadow-lg border border-[#c5c5d4]">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold">{{ $editMode ? 'Modifier le lien' : 'Ajouter un réseau social' }}</h3>
                    <button @click="open = false" type="button" class="text-muted-foreground hover:text-foreground">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit="save" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Plateforme <span class="text-red-500">*</span></label>
                        <select wire:model.live="platform"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                            @foreach($platforms as $key => $pInfo)
                                <option value="{{ $key }}">{{ $pInfo['label'] }}</option>
                            @endforeach
                        </select>
                        @error('platform') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    {{-- Aperçu de l'icône --}}
                    @php $pData = \App\Models\SocialLink::PLATFORMS[$platform] ?? null; @endphp
                    <div class="flex items-center gap-3 p-3 rounded-lg bg-muted/50 border border-border">
                        <div class="w-9 h-9 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary">
                                {!! $pData['icon'] ?? '' !!}
                            </svg>
                        </div>
                        <span class="text-sm font-medium">{{ $pData['label'] ?? '' }}</span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">URL <span class="text-red-500">*</span></label>
                        <input wire:model="url" type="url"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                            placeholder="https://facebook.com/africainedesfinances">
                        @error('url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Statut</label>
                            <label class="flex items-center gap-2 cursor-pointer mt-2">
                                <input wire:model="is_active" type="checkbox" class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary">
                                <span class="text-sm">Actif</span>
                            </label>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Ordre d'affichage</label>
                            <input wire:model="order" type="number" min="0"
                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                            @error('order') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <button @click="open = false" type="button"
                            class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                            Annuler
                        </button>
                        <button type="submit"
                            class="inline-flex items-center justify-center rounded-md text-sm font-medium bg-primary text-primary-foreground hover:bg-primary-light h-10 px-4 py-2">
                            {{ $editMode ? 'Enregistrer' : 'Créer' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    

    {{-- Modal suppression --}}
    <div x-show="del" x-cloak style="display:none" class="fixed inset-0 z-50 flex items-center justify-center p-4" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-black/50" @click="del = false"></div>
            <div class="relative z-10 adf-modal-panel w-full max-w-md rounded-lg bg-white p-6 shadow-lg border border-[#c5c5d4]">
                <h3 class="text-lg font-semibold mb-2">Confirmer la suppression</h3>
                <p class="text-muted-foreground mb-6">Êtes-vous sûr de vouloir supprimer ce lien ? Cette action est irréversible.</p>
                <div class="flex justify-end gap-3">
                    <button @click="del = false" type="button"
                        class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                        Annuler
                    </button>
                    <button wire:click="delete" type="button"
                        class="inline-flex items-center justify-center rounded-md text-sm font-medium bg-red-600 text-white hover:bg-red-700 h-10 px-4 py-2">
                        Supprimer
                    </button>
                </div>
            </div>
        </div>
    
</div>
