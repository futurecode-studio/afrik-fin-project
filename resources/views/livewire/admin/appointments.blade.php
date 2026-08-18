<div x-data="{ open: @entangle('showModal').live, del: @entangle('showDeleteModal').live }">
    {{-- Indicateur de chargement Livewire --}}
    <div wire:loading class="fixed top-0 left-0 right-0 bg-blue-500 text-white text-center py-2 z-50">
        Chargement en cours...
    </div>

    <main class="container mx-auto px-4 py-8">
        {{-- Message de succès --}}

        {{-- Statistiques --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6 pb-3">
                    <h3 class="tracking-tight text-sm font-medium text-muted-foreground">En attente</h3>
                </div>
                <div class="p-6 pt-0">
                    <p class="text-2xl font-bold text-yellow-600">{{ $appointments->where('status', 'pending')->count() }}</p>
                </div>
            </div>
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6 pb-3">
                    <h3 class="tracking-tight text-sm font-medium text-muted-foreground">Confirmés</h3>
                </div>
                <div class="p-6 pt-0">
                    <p class="text-2xl font-bold text-blue-600">{{ $appointments->where('status', 'confirmed')->count() }}</p>
                </div>
            </div>
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6 pb-3">
                    <h3 class="tracking-tight text-sm font-medium text-muted-foreground">Complétés</h3>
                </div>
                <div class="p-6 pt-0">
                    <p class="text-2xl font-bold text-green-600">{{ $appointments->where('status', 'completed')->count() }}</p>
                </div>
            </div>
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6 pb-3">
                    <h3 class="tracking-tight text-sm font-medium text-muted-foreground">Annulés</h3>
                </div>
                <div class="p-6 pt-0">
                    <p class="text-2xl font-bold text-red-600">{{ $appointments->where('status', 'cancelled')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="flex flex-col space-y-1.5 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-semibold leading-none tracking-tight">Rendez-vous d'Investissement</h3>
                        <p class="text-sm text-muted-foreground">{{ $appointments->total() }} rendez-vous enregistré(s)</p>
                    </div>
                </div>

                {{-- Barre de recherche et filtres --}}
                <div class="mt-4 flex flex-col md:flex-row gap-4">
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Rechercher par nom, email, téléphone..."
                        class="flex h-10 w-full md:w-96 rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm">
                    <select wire:model.live="filterStatus"
                        class="flex h-10 w-full md:w-48 rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm">
                        <option value="">Tous les statuts</option>
                        <option value="pending">En attente</option>
                        <option value="confirmed">Confirmé</option>
                        <option value="completed">Complété</option>
                        <option value="cancelled">Annulé</option>
                    </select>
                    <select wire:model.live="filterType"
                        class="flex h-10 w-full md:w-48 rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm">
                        <option value="">Tous les types</option>
                        <option value="actions_brvm">Actions BRVM</option>
                        <option value="obligations">Obligations d'États</option>
                        <option value="fcp">FCP</option>
                        <option value="gestion_mandat">Gestion sous mandat</option>
                        <option value="institutionnel">Portail institutionnel</option>
                        <option value="mise_en_relation">Mise en relation</option>
                    </select>
                </div>
            </div>

            <div class="p-6 pt-0">
                <div class="relative w-full overflow-auto">
                    <table class="w-full caption-bottom text-sm">
                        <thead class="[&_tr]:border-b">
                            <tr class="border-b transition-colors hover:bg-muted/50">
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Client</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Type d'investissement</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Montant</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Date souhaitée</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Statut</th>
                                <th class="h-12 px-4 align-middle font-medium text-muted-foreground text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="[&_tr:last-child]:border-0">
                            @forelse($appointments as $appointment)
                                <tr class="border-b transition-colors hover:bg-muted/50">
                                    <td class="p-4 align-middle">
                                        <div class="font-medium">{{ $appointment->name }}</div>
                                        <div class="text-xs text-muted-foreground">{{ $appointment->email }}</div>
                                        <div class="text-xs text-muted-foreground">{{ $appointment->phone }}</div>
                                    </td>
                                    <td class="p-4 align-middle">
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold bg-primary/10 text-primary">
                                            {{ $appointment->getInvestmentTypeLabel() }}
                                        </span>
                                    </td>
                                    <td class="p-4 align-middle">
                                        @if($appointment->investment_amount)
                                            <span class="font-medium">{{ number_format($appointment->investment_amount, 0, ',', ' ') }} FCFA</span>
                                        @else
                                            <span class="text-muted-foreground">Non spécifié</span>
                                        @endif
                                    </td>
                                    <td class="p-4 align-middle">
                                        @if($appointment->preferred_date)
                                            {{ $appointment->preferred_date->format('d/m/Y H:i') }}
                                        @else
                                            <span class="text-muted-foreground">Non spécifiée</span>
                                        @endif
                                    </td>
                                    <td class="p-4 align-middle">
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'confirmed' => 'bg-blue-100 text-blue-800',
                                                'completed' => 'bg-green-100 text-green-800',
                                                'cancelled' => 'bg-red-100 text-red-800',
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $statusColors[$appointment->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $appointment->getStatusLabel() }}
                                        </span>
                                    </td>
                                    <td class="p-4 align-middle text-right">
                                        <div class="flex justify-end gap-2">
                                            <button type="button" wire:click="viewAppointment({{ $appointment->id }})"
                                                class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-3"
                                                title="Voir les détails">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                                    <circle cx="12" cy="12" r="3"/>
                                                </svg>
                                            </button>
                                            <button type="button" wire:click="confirmDelete({{ $appointment->id }})"
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
                                        Aucun rendez-vous trouvé
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $appointments->links() }}
                </div>
            </div>
        </div>
    </main>

    {{-- Modal Détails --}}
    @if($showModal)
        @php
            $appointment = \App\Models\InvestmentAppointment::find($appointmentId);
        @endphp
        <div class="fixed inset-0 z-[100] bg-black bg-opacity-80 flex items-center justify-center p-4" @click.self="open = false">
            <div class="bg-white rounded-lg shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto relative">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold">Détails du rendez-vous</h2>
                        <button @click="open = false" class="text-gray-500 hover:text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18"/>
                                <path d="m6 6 12 12"/>
                            </svg>
                        </button>
                    </div>

                    @if($appointment)
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Nom complet</label>
                                    <p class="text-base font-semibold">{{ $appointment->name }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Type d'investissement</label>
                                    <p class="text-base font-semibold">{{ $appointment->getInvestmentTypeLabel() }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Email</label>
                                    <p class="text-base">{{ $appointment->email }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Téléphone</label>
                                    <p class="text-base">{{ $appointment->phone }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Entreprise</label>
                                    <p class="text-base">{{ $appointment->company ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Montant d'investissement</label>
                                    <p class="text-base">
                                        @if($appointment->investment_amount)
                                            {{ number_format($appointment->investment_amount, 0, ',', ' ') }} FCFA
                                        @else
                                            Non spécifié
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-500">Date souhaitée</label>
                                <p class="text-base">
                                    @if($appointment->preferred_date)
                                        {{ $appointment->preferred_date->format('d/m/Y à H:i') }}
                                    @else
                                        Non spécifiée
                                    @endif
                                </p>
                            </div>

                            @if($appointment->message)
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Message</label>
                                    <p class="text-base mt-1 p-3 bg-gray-50 rounded-lg">{{ $appointment->message }}</p>
                                </div>
                            @endif

                            <hr class="my-4">

                            <form wire:submit="updateAppointment">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium mb-2">Statut</label>
                                        <select wire:model="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                            <option value="pending">En attente</option>
                                            <option value="confirmed">Confirmé</option>
                                            <option value="completed">Complété</option>
                                            <option value="cancelled">Annulé</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium mb-2">Date confirmée</label>
                                        <input type="datetime-local" wire:model="confirmed_date" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                        <p class="text-xs text-gray-500 mt-1">Laissez vide si non confirmé</p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium mb-2">Notes administratives</label>
                                        <textarea wire:model="admin_notes" rows="4" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary" placeholder="Ajoutez des notes sur ce rendez-vous..."></textarea>
                                    </div>

                                    <div class="flex justify-end gap-3">
                                        <button type="button" @click="open = false"
                                            class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-11 px-6 py-3">
                                            Annuler
                                        </button>
                                        <button type="submit"
                                            class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary-light shadow-elegant hover:shadow-glow transition-smooth h-11 px-6 py-3">
                                            Mettre à jour
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Confirmation Suppression --}}
    <div x-show="del" x-cloak style="display:none" class="fixed inset-0 z-[100] bg-black bg-opacity-80 flex items-center justify-center p-4" @click.self="del = false">
            <div class="bg-white rounded-lg shadow-2xl max-w-md w-full p-6 relative">
                <h2 class="text-xl font-bold mb-4">Confirmer la suppression</h2>
                <p class="text-gray-600 mb-6">Êtes-vous sûr de vouloir supprimer ce rendez-vous ? Cette action est irréversible.</p>
                
                <div class="flex justify-end gap-3">
                    <button @click="del = false"
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
    
</div>