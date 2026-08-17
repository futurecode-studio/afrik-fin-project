<div x-data="{ open: @entangle('showModal').live, del: @entangle('showDeleteModal').live }">

    <div class="admin-page space-y-6">
<div class="flex flex-wrap justify-between items-end gap-4 mb-2">
        <div>
            <nav class="flex items-center gap-2 text-[#757683] mb-2 text-xs font-semibold tracking-wider uppercase">
                <span>Administration</span>
                <span class="material-symbols-outlined text-sm">chevron_right</span>
                <span class="text-[#001a61]">PARTENAIRES</span>
            </nav>
            <h2 class="text-3xl font-extrabold text-[#001a61] tracking-tight">Partenaires SGI / SGO</h2>
            <p class="text-sm text-[#444652] mt-1">{{ $sgiCount }} SGI · {{ $sgoCount }} SGO —
                <a href="{{ route('admin.sgi-sgo') }}" class="font-bold text-[#0a2e8c] hover:underline">Hub SGI/SGO</a>
            </p>
        </div>
    </div>

        <div class="admin-card">
            <div class="flex flex-col space-y-1.5 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-semibold leading-none tracking-tight">Liste des Partenaires</h3>
                        <p class="text-sm text-[#757683]">{{ $partners->total() }} partenaire(s) enregistré(s)</p>
                    </div>
                    <button @click="open = true; $wire.openModal()" type="button"
                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 admin-btn-primary  h-11 px-6 py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="h-4 w-4 mr-2">
                            <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                            <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                            <path d="M12 18v-6"></path>
                            <path d="m9 15 3 3 3-3"></path>
                        </svg>
                        Ajouter un partenaire
                    </button>
                </div>

                <div class="mt-4 flex flex-col sm:flex-row gap-3">
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Rechercher par nom, agrément, contact…"
                        class="flex h-10 w-full md:w-96 rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 py-2 text-base ring-offset-background placeholder:text-[#757683] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm">
                    <select wire:model.live="filterType" class="h-10 rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 text-sm">
                        <option value="">Tous types</option>
                        <option value="SGI">SGI</option>
                        <option value="SGO">SGO</option>
                        <option value="Autre">Autre</option>
                    </select>
                </div>
            </div>

            <div class="p-6 pt-0">
                <div class="relative w-full overflow-auto">
                    <table class="admin-table w-full caption-bottom text-sm">
                        <thead class="[&_tr]:border-b">
                            <tr class="border-b transition-colors hover:bg-[#f0f3ff]">
                                <th class="h-12 px-4 text-left align-middle font-medium text-[#757683]">Logo</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-[#757683]">Nom</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-[#757683]">Catégorie</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-[#757683]">Contact</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-[#757683]">Email</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-[#757683]">Statut</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-[#757683]">Ordre</th>
                                <th class="h-12 px-4 align-middle font-medium text-[#757683] text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="[&_tr:last-child]:border-0">
                            @forelse($partners as $partner)
                                <tr class="border-b transition-colors hover:bg-[#f0f3ff]">
                                    <td class="p-4 align-middle">
                                        @if($partner->logo_url)
                                            <img src="{{ $partner->logo_url }}" alt="{{ $partner->nom }}" class="w-12 h-12 object-contain rounded border bg-white p-1">
                                        @else
                                            <div class="w-12 h-12 bg-gray-100 rounded flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-gray-400">
                                                    <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                                                    <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="p-4 align-middle">
                                        <div class="font-medium">{{ $partner->nom }}</div>
                                    </td>
                                    <td class="p-4 align-middle">
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                            {{ $partner->type === 'SGO' ? 'bg-blue-100 text-blue-800' : ($partner->type === 'SGI' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-700') }}">
                                            {{ $partner->type }}
                                        </span>
                                    </td>
                                    <td class="p-4 align-middle text-sm text-[#757683]">{{ $partner->contact ?? '-' }}</td>
                                    <td class="p-4 align-middle text-sm text-[#757683]">{{ $partner->email ?? '-' }}</td>
                                    <td class="p-4 align-middle">
                                        <button wire:click="toggleActive({{ $partner->id }})" type="button"
                                            class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $partner->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $partner->is_active ? 'Actif' : 'Inactif' }}
                                        </button>
                                    </td>
                                    <td class="p-4 align-middle text-sm text-[#757683]">{{ $partner->order }}</td>
                                    <td class="p-4 align-middle text-right">
                                        <div class="flex justify-end gap-2">
                                            <button type="button" wire:click="edit({{ $partner->id }})"
                                                class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-[#c5c5d4] bg-[#f9f9ff] hover:bg-accent hover:text-accent-foreground h-9 px-3"
                                                title="Modifier">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
                                                </svg>
                                            </button>
                                            <button type="button" wire:click="confirmDelete({{ $partner->id }})"
                                                class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-[#c5c5d4] bg-[#f9f9ff] hover:bg-red-50 hover:text-red-600 h-9 px-3"
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
                                    <td colspan="8" class="p-8 text-center text-[#757683]">
                                        Aucun partenaire trouvé. Cliquez sur "Ajouter un partenaire" pour en créer un.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($partners->hasPages())
                    <div class="p-4">
                        {{ $partners->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Modal de création/modification --}}
    <div x-show="open" x-cloak style="display:none" class="fixed inset-0 z-50 flex items-center justify-center p-4" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-black/50" @click="open = false"></div>
            
            <div class="relative z-10 w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-lg bg-[#f9f9ff] p-6 shadow-lg">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold">{{ $editMode ? 'Modifier le partenaire' : 'Ajouter un partenaire' }}</h3>
                    <button @click="open = false" type="button" class="text-[#757683] hover:text-foreground">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18"/>
                            <path d="m6 6 12 12"/>
                        </svg>
                    </button>
                </div>
                <p class="text-sm text-gray-600 mb-6">Remplissez les informations du partenaire</p>

                <form wire:submit="save">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Nom <span class="text-red-500">*</span></label>
                            <input wire:model.live="nom" type="text"
                                class="flex h-10 w-full rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 py-2 text-base ring-offset-background placeholder:text-[#757683] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm">
                            @error('nom') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Catégorie <span class="text-red-500">*</span></label>
                            <select wire:model="type"
                                class="flex h-10 w-full rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 py-2 text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm">
                                <option value="SGI">SGI — Sociétés de Gestion et d’Intermédiation</option>
                                <option value="SGO">SGO — Sociétés de Gestion d’OPCVM</option>
                                <option value="Autre">Autre</option>
                            </select>
                            @error('type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Pays</label>
                                <input wire:model="country" type="text" class="flex h-10 w-full rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 py-2 text-sm" placeholder="Bénin, Côte d’Ivoire…">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Ville</label>
                                <input wire:model="city" type="text" class="flex h-10 w-full rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 py-2 text-sm" placeholder="Cotonou">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">N° agrément AMF-UMOA</label>
                            <input wire:model="agreement_number" type="text" class="flex h-10 w-full rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 py-2 text-sm" placeholder="Ex. AA/20XX-XX">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Contact (Téléphone)</label>
                            <input wire:model="contact" type="text"
                                class="flex h-10 w-full rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 py-2 text-base ring-offset-background placeholder:text-[#757683] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                                placeholder="+225 07 00 00 00 00">
                            @error('contact') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Email</label>
                            <input wire:model="email" type="email"
                                class="flex h-10 w-full rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 py-2 text-base ring-offset-background placeholder:text-[#757683] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                                placeholder="contact@entreprise.com">
                            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Site Web</label>
                            <input wire:model="website" type="url"
                                class="flex h-10 w-full rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 py-2 text-base ring-offset-background placeholder:text-[#757683] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                                placeholder="https://www.entreprise.com">
                            @error('website') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Logo</label>
                            <input wire:model="logo" type="file" accept="image/*"
                                class="flex w-full rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-[#757683] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm">
                            @error('logo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            
                            @if($logo_url)
                                <div class="mt-2">
                                    <img src="{{ \App\Models\Partner::urlForLogo($logo_url) }}" alt="Logo actuel" class="w-20 h-20 object-contain border rounded bg-white p-1">
                                    <p class="text-xs text-gray-500 mt-1">Logo actuel</p>
                                </div>
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Description</label>
                            <textarea wire:model="description" rows="3"
                                class="flex w-full rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 py-2 text-base ring-offset-background placeholder:text-[#757683] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                                placeholder="Description courte du partenaire..."></textarea>
                            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Notes internes (admin)</label>
                            <textarea wire:model="admin_notes" rows="2"
                                class="flex w-full rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 py-2 text-sm"
                                placeholder="Non visible sur le site public…"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Statut</label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input wire:model="is_active" type="checkbox" class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary">
                                    <span class="text-sm">Actif (publié)</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer mt-2">
                                    <input wire:model="is_featured" type="checkbox" class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary">
                                    <span class="text-sm">Mis en avant</span>
                                </label>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Ordre d'affichage</label>
                                <input wire:model="order" type="number" min="0"
                                    class="flex h-10 w-full rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 py-2 text-base ring-offset-background placeholder:text-[#757683] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm">
                                @error('order') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <button @click="open = false" type="button"
                            class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-[#c5c5d4] bg-[#f9f9ff] hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                            Annuler
                        </button>
                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 admin-btn-primary h-10 px-4 py-2">
                            {{ $editMode ? 'Enregistrer' : 'Créer' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

    {{-- Modal de confirmation de suppression --}}
    <div x-show="del" x-cloak style="display:none" class="fixed inset-0 z-50 flex items-center justify-center p-4" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-black/50" @click="del = false"></div>
            
            <div class="relative z-10 w-full max-w-md rounded-lg bg-[#f9f9ff] p-6 shadow-lg">
                <h3 class="text-lg font-semibold mb-2">Confirmer la suppression</h3>
                <p class="text-[#757683] mb-6">Êtes-vous sûr de vouloir supprimer ce partenaire ? Cette action est irréversible.</p>
                
                <div class="flex justify-end gap-3">
                    <button @click="del = false" type="button"
                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-[#c5c5d4] bg-[#f9f9ff] hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                        Annuler
                    </button>
                    <button wire:click="delete" type="button"
                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-red-600 text-white hover:bg-red-700 h-10 px-4 py-2">
                        Supprimer
                    </button>
                </div>
            </div>
        </div>
    

    @push('scripts')
        <script>
            document.addEventListener('partner-saved', () => {
                // Fermer les modales proprement
            });
        </script>
    @endpush
</div>