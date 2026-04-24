<div>
    @if (session()->has('message'))
        <div class="mb-4 rounded-lg bg-green-50 p-4 text-green-800 border border-green-200">
            {{ session('message') }}
        </div>
    @endif

    <main class="container mx-auto px-4 py-8">
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="flex flex-col space-y-1.5 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-semibold leading-none tracking-tight">Liste des Membres de l'Équipe</h3>
                        <p class="text-sm text-muted-foreground">{{ $members->total() }} membre(s) enregistré(s)</p>
                    </div>
                    <button wire:click="openModal" type="button"
                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary-light shadow-elegant hover:shadow-glow transition-smooth h-11 px-6 py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="h-4 w-4 mr-2">
                            <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                            <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                            <path d="M12 18v-6"></path>
                            <path d="m9 15 3 3 3-3"></path>
                        </svg>
                        Ajouter un membre
                    </button>
                </div>

                <div class="mt-4">
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Rechercher par nom, poste..."
                        class="flex h-10 w-full md:w-96 rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm">
                </div>
            </div>

            <div class="p-6 pt-0">
                <div class="relative w-full overflow-auto">
                    <table class="w-full caption-bottom text-sm">
                        <thead class="[&_tr]:border-b">
                            <tr class="border-b transition-colors hover:bg-muted/50">
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Photo</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Nom</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Poste</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Email</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Statut</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Ordre</th>
                                <th class="h-12 px-4 align-middle font-medium text-muted-foreground text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="[&_tr:last-child]:border-0">
                            @forelse($members as $member)
                                <tr class="border-b transition-colors hover:bg-muted/50">
                                    <td class="p-4 align-middle">
                                        @if($member->photo)
                                            <img src="{{ $member->photo ? '/storage/' . $member->photo : '' }}" alt="{{ $member->nom }}" class="w-12 h-12 rounded-full object-cover border">
                                        @else
                                            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-gray-400">
                                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="12" cy="7" r="4"></circle>
                                                </svg>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="p-4 align-middle">
                                        <div class="font-medium">{{ $member->nom }}</div>
                                    </td>
                                    <td class="p-4 align-middle text-sm text-muted-foreground">{{ $member->poste }}</td>
                                    <td class="p-4 align-middle text-sm text-muted-foreground">{{ $member->email ?? '-' }}</td>
                                    <td class="p-4 align-middle">
                                        <button wire:click="toggleActive({{ $member->id }})" type="button"
                                            class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $member->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $member->is_active ? 'Actif' : 'Inactif' }}
                                        </button>
                                    </td>
                                    <td class="p-4 align-middle text-sm text-muted-foreground">{{ $member->order }}</td>
                                    <td class="p-4 align-middle text-right">
                                        <div class="flex justify-end gap-2">
                                            <button type="button" wire:click="edit({{ $member->id }})"
                                                class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-3"
                                                title="Modifier">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
                                                </svg>
                                            </button>
                                            <button type="button" wire:click="confirmDelete({{ $member->id }})"
                                                class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-red-50 hover:text-red-600 h-9 px-3"
                                                title="Supprimer">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M3 6h18"></path>
                                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-8 text-center text-muted-foreground">
                                        Aucun membre trouvé. Cliquez sur "Ajouter un membre" pour en créer un.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($members->hasPages())
                    <div class="p-4">
                        {{ $members->links() }}
                    </div>
                @endif
            </div>
        </div>
    </main>

    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-black/50" wire:click="closeModal"></div>
            
            <div class="relative z-10 w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-lg bg-background p-6 shadow-lg">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold">{{ $editMode ? 'Modifier le membre' : 'Ajouter un membre' }}</h3>
                    <button wire:click="closeModal" type="button" class="text-muted-foreground hover:text-foreground">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18"/>
                            <path d="m6 6 12 12"/>
                        </svg>
                    </button>
                </div>
                <p class="text-sm text-gray-600 mb-6">Remplissez les informations du membre de l'équipe</p>

                <form wire:submit="save">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Nom complet <span class="text-red-500">*</span></label>
                            <input wire:model.live="nom" type="text"
                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm">
                            @error('nom') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Poste <span class="text-red-500">*</span></label>
                            <input wire:model="poste" type="text"
                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                                placeholder="ex: Directeur Général, Analyste Financier...">
                            @error('poste') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Attributs</label>
                            <input wire:model="attributs" type="text"
                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                                placeholder="ex: MBA, Certifié AMF, Expert BRVM (séparés par des virgules)">
                            <p class="text-xs text-gray-500 mt-1">Attributs séparés par des virgules</p>
                            @error('attributs') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Email</label>
                            <input wire:model="email" type="email"
                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                                placeholder="email@entreprise.com">
                            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Contact (Téléphone)</label>
                            <input wire:model="contact" type="text"
                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                                placeholder="+229 01 00 00 00 00">
                            @error('contact') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Photo</label>
                            <input wire:model="photo" type="file" accept="image/*"
                                class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm">
                            @error('photo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            
                            @if($photo_url)
                                <div class="mt-2">
                                    <img src="{{ '/storage/' . $photo_url }}" alt="Photo actuelle" class="w-20 h-20 object-cover rounded-full border">
                                    <p class="text-xs text-gray-500 mt-1">Photo actuelle</p>
                                </div>
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Description</label>
                            <textarea wire:model="description" rows="4"
                                class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                                placeholder="Brève description du membre..."></textarea>
                            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Statut</label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input wire:model="is_active" type="checkbox" class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary">
                                    <span class="text-sm">Actif</span>
                                </label>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Ordre d'affichage</label>
                                <input wire:model="order" type="number" min="0"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm">
                                @error('order') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <button wire:click="closeModal" type="button"
                            class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                            Annuler
                        </button>
                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary-light h-10 px-4 py-2">
                            {{ $editMode ? 'Enregistrer' : 'Créer' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-black/50" wire:click="$set('showDeleteModal', false)"></div>
            
            <div class="relative z-10 w-full max-w-md rounded-lg bg-background p-6 shadow-lg">
                <h3 class="text-lg font-semibold mb-2">Confirmer la suppression</h3>
                <p class="text-muted-foreground mb-6">Êtes-vous sûr de vouloir supprimer ce membre ? Cette action est irréversible.</p>
                
                <div class="flex justify-end gap-3">
                    <button wire:click="$set('showDeleteModal', false)" type="button"
                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                        Annuler
                    </button>
                    <button wire:click="delete" type="button"
                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-red-600 text-white hover:bg-red-700 h-10 px-4 py-2">
                        Supprimer
                    </button>
                </div>
            </div>
        </div>
    @endif

    @push('scripts')
        <script>
            document.addEventListener('member-saved', () => {
                // Fermer les modales proprement
            });
        </script>
    @endpush
</div>