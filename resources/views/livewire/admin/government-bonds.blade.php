<div>
    {{-- Indicateur de chargement Livewire --}}
    <div wire:loading class="fixed top-0 left-0 right-0 bg-blue-500 text-white text-center py-2 z-50">
        Chargement en cours...
    </div>

    <main class="container mx-auto px-4 py-8">
        {{-- Message de succès --}}
        @if (session()->has('success'))
            <div class="mb-4 rounded-lg bg-green-50 p-4 text-green-800 border border-green-200">
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="flex flex-col space-y-1.5 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-semibold leading-none tracking-tight">Obligations d'États</h3>
                        <p class="text-sm text-muted-foreground">{{ $bonds->total() }} obligation(s) enregistrée(s)</p>
                    </div>
                    <button wire:click="openModal" type="button"
                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 bg-primary text-primary-foreground hover:bg-primary-light shadow-elegant hover:shadow-glow transition-smooth h-11 px-6 py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="h-4 w-4 mr-2">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                        </svg>
                        Nouvelle obligation
                    </button>
                </div>

                {{-- Barre de recherche et filtres --}}
                <div class="mt-4 flex flex-col md:flex-row gap-4">
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Rechercher par nom, émetteur, pays, ISIN..."
                        class="flex h-10 w-full md:w-96 rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm">
                    <select wire:model.live="filterStatus"
                        class="flex h-10 w-full md:w-48 rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm">
                        <option value="">Tous les statuts</option>
                        <option value="1">Actif</option>
                        <option value="0">Inactif</option>
                    </select>
                </div>
            </div>

            <div class="p-6 pt-0">
                <div class="relative w-full overflow-auto">
                    <table class="w-full caption-bottom text-sm">
                        <thead class="[&_tr]:border-b">
                            <tr class="border-b transition-colors hover:bg-muted/50">
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Obligation</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Émetteur</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Taux</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Échéance</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Statut</th>
                                <th class="h-12 px-4 align-middle font-medium text-muted-foreground text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="[&_tr:last-child]:border-0">
                            @forelse($bonds as $bond)
                                <tr class="border-b transition-colors hover:bg-muted/50">
                                    <td class="p-4 align-middle">
                                        <div class="font-medium">{{ $bond->name }}</div>
                                        <div class="text-xs text-muted-foreground">{{ $bond->country }}</div>
                                    </td>
                                    <td class="p-4 align-middle">{{ $bond->issuer }}</td>
                                    <td class="p-4 align-middle">
                                        <div class="font-medium">{{ number_format($bond->interest_rate, 2) }}%</div>
                                        <div class="text-xs text-muted-foreground">{{ $bond->interest_type_label }}</div>
                                    </td>
                                    <td class="p-4 align-middle">
                                        <div>{{ $bond->maturity_date->format('d/m/Y') }}</div>
                                        <div class="text-xs text-muted-foreground">{{ $bond->maturity_years }} ans</div>
                                    </td>
                                    <td class="p-4 align-middle">
                                        @if($bond->is_active)
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold bg-green-100 text-green-800">Actif</span>
                                        @else
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold bg-red-100 text-red-800">Inactif</span>
                                        @endif
                                    </td>
                                    <td class="p-4 align-middle text-right">
                                        <div class="flex justify-end gap-2">
                                            <button type="button" wire:click="edit({{ $bond->id }})"
                                                class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-3"
                                                title="Modifier">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/>
                                                    <path d="m15 5 4 4"/>
                                                </svg>
                                            </button>
                                            <button type="button" wire:click="toggleStatus({{ $bond->id }})"
                                                class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-3"
                                                title="Changer le statut">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M12 2a10 10 0 1 0 10 10H12V2Z"/>
                                                    <path d="M12 2v10l8.66 5"/>
                                                </svg>
                                            </button>
                                            <button type="button" wire:click="confirmDelete({{ $bond->id }})"
                                                class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-red-200 bg-background hover:bg-red-50 text-red-600 h-9 px-3"
                                                title="Supprimer">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M3 6h18"/>
                                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-4 text-center text-muted-foreground">
                                        Aucune obligation trouvée
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $bonds->links() }}
                </div>
            </div>
        </div>
    </main>

    {{-- Modal Ajout/Modification --}}
    @if($showModal)
        <div class="fixed inset-0 z-[100] bg-black bg-opacity-80 flex items-center justify-center p-4" wire:click.self="closeModal">
            <div class="bg-white rounded-lg shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto relative">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold">
                            {{ $editMode ? 'Modifier l\'obligation' : 'Nouvelle obligation' }}
                        </h2>
                        <button wire:click="closeModal" class="text-gray-500 hover:text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18"/>
                                <path d="m6 6 12 12"/>
                            </svg>
                        </button>
                    </div>

                    <form wire:submit="save">
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Nom -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom de l'obligation *</label>
                                    <input type="text" wire:model="name" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Émetteur -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Émetteur *</label>
                                    <input type="text" wire:model="issuer" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                    @error('issuer') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Pays -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Pays *</label>
                                    <input type="text" wire:model="country" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                    @error('country') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Code ISIN -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Code ISIN</label>
                                    <input type="text" wire:model="isin_code" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                    @error('isin_code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Valeur nominale -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Valeur nominale *</label>
                                    <input type="number" step="0.01" wire:model="nominal_value" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                    @error('nominal_value') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Devise -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Devise *</label>
                                    <select wire:model="currency" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                        <option value="FCFA">FCFA</option>
                                        <option value="EUR">EUR</option>
                                        <option value="USD">USD</option>
                                    </select>
                                    @error('currency') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Taux d'intérêt -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Taux d'intérêt (%) *</label>
                                    <input type="number" step="0.01" wire:model="interest_rate" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                    @error('interest_rate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Type d'intérêt -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Type d'intérêt *</label>
                                    <select wire:model="interest_type" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                        <option value="fixed">Fixe</option>
                                        <option value="variable">Variable</option>
                                        <option value="zero_coupon">Zéro coupon</option>
                                    </select>
                                    @error('interest_type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Fréquence de paiement -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Fréquence de paiement *</label>
                                    <select wire:model="payment_frequency" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                        <option value="annual">Annuel</option>
                                        <option value="semi_annual">Semestriel</option>
                                        <option value="quarterly">Trimestriel</option>
                                        <option value="monthly">Mensuel</option>
                                    </select>
                                    @error('payment_frequency') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Date d'émission -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Date d'émission *</label>
                                    <input type="date" wire:model="issue_date" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                    @error('issue_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Date d'échéance -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Date d'échéance *</label>
                                    <input type="date" wire:model="maturity_date" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                    @error('maturity_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Maturité (années) -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Maturité (années) *</label>
                                    <input type="number" wire:model="maturity_years" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                    @error('maturity_years') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Prix actuel -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Prix actuel</label>
                                    <input type="number" step="0.01" wire:model="current_price" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                    @error('current_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Rendement à l'échéance -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Rendement à l'échéance (%)</label>
                                    <input type="number" step="0.01" wire:model="yield_to_maturity" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                    @error('yield_to_maturity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Notation -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Notation</label>
                                    <input type="text" wire:model="rating" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                    @error('rating') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Niveau de risque -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Niveau de risque *</label>
                                    <select wire:model="risk_level" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                        <option value="low">Faible</option>
                                        <option value="medium">Moyen</option>
                                        <option value="high">Élevé</option>
                                    </select>
                                    @error('risk_level') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Investissement minimum -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Investissement minimum</label>
                                    <input type="number" step="0.01" wire:model="minimum_investment" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                    @error('minimum_investment') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Ordre d'affichage -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Ordre d'affichage</label>
                                    <input type="number" wire:model="display_order" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                    @error('display_order') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Description -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                    <textarea wire:model="description" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary"></textarea>
                                    @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Actif -->
                                <div class="md:col-span-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="is_active" id="is_active" class="rounded border-gray-300 text-primary shadow-sm focus:ring-primary">
                                        <span class="ml-2 text-sm text-gray-700">Obligation active (visible sur le site)</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 mt-6">
                            <button type="button" wire:click="closeModal"
                                class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-11 px-6 py-3">
                                Annuler
                            </button>
                            <button type="submit"
                                class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary-light shadow-elegant hover:shadow-glow transition-smooth h-11 px-6 py-3">
                                {{ $editMode ? 'Mettre à jour' : 'Créer' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Confirmation Suppression --}}
    @if($showDeleteModal)
        <div class="fixed inset-0 z-[100] bg-black bg-opacity-80 flex items-center justify-center p-4" wire:click.self="$set('showDeleteModal', false)">
            <div class="bg-white rounded-lg shadow-2xl max-w-md w-full p-6 relative">
                <h2 class="text-xl font-bold mb-4">Confirmer la suppression</h2>
                <p class="text-gray-600 mb-6">Êtes-vous sûr de vouloir supprimer cette obligation ? Cette action est irréversible.</p>
                
                <div class="flex justify-end gap-3">
                    <button wire:click="$set('showDeleteModal', false)"
                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-11 px-6 py-3">
                        Annuler
                    </button>
                    <button wire:click="delete"
                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-red-600 text-white hover:bg-red-700 h-11 px-6 py-3">
                        Supprimer
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
