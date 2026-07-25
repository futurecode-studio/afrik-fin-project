<div class="admin-page space-y-6" x-data="{ open: @entangle('showModal').live, del: @entangle('showDeleteModal').live }">
<div class="flex flex-wrap justify-between items-end gap-4 mb-2">
        <div>
            <nav class="flex items-center gap-2 text-[#757683] mb-2 text-xs font-semibold tracking-wider uppercase">
                <span>Administration</span>
                <span class="material-symbols-outlined text-sm">chevron_right</span>
                <span class="text-[#001a61]">MARCHÉS</span>
            </nav>
            <h2 class="text-3xl font-extrabold text-[#001a61] tracking-tight">Contrôle des Marchés</h2>
        </div>
    </div>

    <!-- Messages Flash -->

    <div class="admin-card">
        <div class="flex flex-col space-y-1.5 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-semibold leading-none tracking-tight">Liste des Titres</h3>
                    <p class="text-sm text-[#757683]">{{ $stocks->total() }} titre(s) suivi(s)</p>
                </div>
                <button @click="open = true; $wire.openModal()"
                    class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 admin-btn-primary  h-10 rounded-lg px-6 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                        <path d="M5 12h14"></path>
                        <path d="M12 5v14"></path>
                    </svg>
                    Ajouter un titre
                </button>
            </div>
        </div>

        <!-- Barre de recherche -->
        <div class="px-6 pb-4">
            <input wire:model.live="search" type="text" placeholder="Rechercher par symbole ou nom d'entreprise..."
                class="flex h-10 w-full md:w-96 rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
        </div>

        <div class="p-6 pt-0">
            <div class="relative w-full overflow-auto">
                <table class="admin-table w-full caption-bottom text-sm">
                    <thead class="[&_tr]:border-b"> 
                        <tr class="border-b transition-colors">
                            <th class="h-12 px-4 text-left align-middle font-medium text-[#757683]">Symbole</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-[#757683]">Entreprise</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-[#757683]">Secteur</th>
                            <th class="h-12 px-4 align-middle font-medium text-[#757683] text-right">Prix</th>
                            <th class="h-12 px-4 align-middle font-medium text-[#757683] text-right">Variation</th>
                            <th class="h-12 px-4 align-middle font-medium text-[#757683] text-right">Volume</th>
                            <th class="h-12 px-4 align-middle font-medium text-[#757683] text-right">Cap. (M)</th>
                            <th class="h-12 px-4 align-middle font-medium text-[#757683] text-center">Statut</th>
                            <th class="h-12 px-4 align-middle font-medium text-[#757683] text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="[&_tr:last-child]:border-0">
                        @forelse($stocks as $stock)
                        <tr class="border-b transition-colors hover:bg-[#f0f3ff]">
                            <td class="p-4">
                                <span class="font-bold text-primary">{{ $stock->symbol }}</span>
                            </td>
                            <td class="p-4">{{ $stock->company_name }}</td>
                            <td class="p-4">
                                <span class="text-sm text-[#757683]">{{ $stock->sector ?? '-' }}</span>
                            </td>
                            <td class="p-4 text-right font-semibold">
                                {{ number_format($stock->current_price, 0, ',', ' ') }}
                            </td>
                            <td class="p-4 text-right">
                                @if($stock->variation_percent > 0)
                                    <span class="inline-flex items-center gap-1 text-accent font-semibold">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                            <polyline points="16 7 22 7 22 13"></polyline>
                                        </svg>
                                        +{{ number_format($stock->variation_percent, 2) }}%
                                    </span>
                                @elseif($stock->variation_percent < 0)
                                    <span class="inline-flex items-center gap-1 text-destructive font-semibold">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="22 17 13.5 8.5 8.5 13.5 2 7"></polyline>
                                            <polyline points="16 17 22 17 22 11"></polyline>
                                        </svg>
                                        {{ number_format($stock->variation_percent, 2) }}%
                                    </span>
                                @else
                                    <span class="text-[#757683]">0.00%</span>
                                @endif
                            </td>
                            <td class="p-4 text-right text-[#757683]">
                                {{ number_format($stock->volume) }}
                            </td>
                            <td class="p-4 text-right text-[#757683]">
                                {{ $stock->market_cap ? number_format($stock->market_cap, 0) : '-' }}
                            </td>
                            <td class="p-4 text-center">
                                <button wire:click="toggleActive({{ $stock->id }})"
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-colors
                                    {{ $stock->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                                    {{ $stock->is_active ? 'Actif' : 'Inactif' }}
                                </button>
                            </td>
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="editStock({{ $stock->id }})"
                                        class="inline-flex items-center justify-center h-8 w-8 rounded-md border border-[#c5c5d4] bg-[#f9f9ff] hover:bg-accent hover:text-accent-foreground transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
                                            <path d="m15 5 4 4"></path>
                                        </svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $stock->id }})"
                                        class="inline-flex items-center justify-center h-8 w-8 rounded-md border border-[#c5c5d4] bg-[#f9f9ff] hover:bg-destructive hover:text-destructive-foreground transition-colors">
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
                            <td colspan="9" class="p-8 text-center text-[#757683]">
                                Aucun titre trouvé. Cliquez sur "Ajouter un titre" pour commencer.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($stocks->hasPages())
            <div class="mt-4">
                {{ $stocks->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- Modale Ajout/Edition -->
    @if($showModal)
    <div @click.self="open = false" class="fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-[100] p-4">
        <div class="adf-modal-panel bg-white rounded-lg shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto border border-[#c5c5d4]">
            <!-- Header -->
            <div class="sticky top-0 bg-card border-b border-[#c5c5d4] px-6 py-4 flex items-center justify-between">
                <h2 class="text-2xl font-bold">{{ $editMode ? 'Modifier le titre' : 'Ajouter un titre' }}</h2>
                <button @click="open = false" class="text-[#757683] hover:text-foreground transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <div class="p-6">
                <form wire:submit.prevent="saveStock" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Symbole -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Symbole *</label>
                            <input wire:model="symbol" type="text" placeholder="Ex: SNTS"
                                class="flex h-10 w-full rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
                                {{ $editMode ? 'readonly' : '' }}>
                            @error('symbol') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Nom de l'entreprise -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Nom de l'entreprise *</label>
                            <input wire:model="company_name" type="text" placeholder="Ex: Sonatel"
                                class="flex h-10 w-full rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                            @error('company_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Prix actuel -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Prix actuel (FCFA) *</label>
                            <input wire:model="current_price" type="number" step="0.01" placeholder="16500"
                                class="flex h-10 w-full rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                            @error('current_price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Prix précédent -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Prix précédent (FCFA)</label>
                            <input wire:model="previous_price" type="number" step="0.01" placeholder="16000"
                                class="flex h-10 w-full rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                            @error('previous_price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Volume -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Volume</label>
                            <input wire:model="volume" type="number" placeholder="1245"
                                class="flex h-10 w-full rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                            @error('volume') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Capitalisation -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Capitalisation (Millions FCFA)</label>
                            <input wire:model="market_cap" type="number" step="0.01" placeholder="2500"
                                class="flex h-10 w-full rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                            @error('market_cap') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Secteur -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Secteur</label>
                            <input wire:model="sector" type="text" placeholder="Ex: Télécommunications"
                                class="flex h-10 w-full rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                            @error('sector') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Plus haut -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Plus haut (FCFA)</label>
                            <input wire:model="high_price" type="number" step="0.01" placeholder="17000"
                                class="flex h-10 w-full rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                            @error('high_price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Plus bas -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Plus bas (FCFA)</label>
                            <input wire:model="low_price" type="number" step="0.01" placeholder="15800"
                                class="flex h-10 w-full rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                            @error('low_price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Statut actif -->
                        <div class="flex items-center gap-2 md:col-span-2">
                            <input wire:model="is_active" type="checkbox" id="is_active"
                                class="h-4 w-4 shrink-0 rounded border border-primary text-primary focus:ring-2 focus:ring-ring focus:ring-offset-2">
                            <label for="is_active" class="text-sm font-medium cursor-pointer">
                                Titre actif (coché = actif, décoché = inactif)
                            </label>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="button" @click="open = false"
                            class="flex-1 inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-[#c5c5d4] bg-[#f9f9ff] hover:bg-accent hover:text-accent-foreground h-11 rounded-lg px-6">
                            Annuler
                        </button>
                        <button type="submit" wire:loading.attr="disabled"
                            class="flex-1 inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 admin-btn-primary  h-11 rounded-lg px-6">
                            <span wire:loading.remove>{{ $editMode ? 'Modifier' : 'Ajouter' }}</span>
                            <span wire:loading>Enregistrement...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Modale Confirmation Suppression -->
    @if($showDeleteModal)
    <div wire:click.self="closeDeleteModal" class="fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-[100] p-4">
        <div class="adf-modal-panel bg-white rounded-lg shadow-2xl max-w-md w-full border border-[#c5c5d4]">
            <div class="p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-full bg-destructive/10 flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-destructive">
                            <path d="M3 6h18"></path>
                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold">Confirmer la suppression</h3>
                        <p class="text-sm text-[#757683]">Êtes-vous sûr de vouloir supprimer ce titre ? Cette action est irréversible.</p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button wire:click="closeDeleteModal"
                        class="flex-1 inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-[#c5c5d4] bg-[#f9f9ff] hover:bg-accent hover:text-accent-foreground h-10 rounded-lg px-6">
                        Annuler
                    </button>
                    <button wire:click="deleteStock"
                        class="flex-1 inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-destructive text-destructive-foreground hover:bg-destructive/90 h-10 rounded-lg px-6">
                        Supprimer
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
