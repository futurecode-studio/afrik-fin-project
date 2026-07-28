<div class="admin-page space-y-6" x-data="{ open: @entangle('showModal').live, del: @entangle('showDeleteModal').live }">
    <div class="flex flex-wrap justify-between items-end gap-4 mb-2">
        <div>
            <nav class="flex items-center gap-2 text-[#757683] mb-2 text-xs font-semibold tracking-wider uppercase">
                <span>Administration</span>
                <span class="material-symbols-outlined text-sm">chevron_right</span>
                <span class="text-[#001a61]">CANDIDATURES</span>
            </nav>
            <h2 class="text-3xl font-extrabold text-[#001a61] tracking-tight">Gestion des Candidatures</h2>
        </div>
    </div>

    {{-- Indicateur de chargement Livewire --}}
    <div wire:loading class="fixed top-0 left-0 right-0 bg-[#001a61] text-white text-center py-2 z-50">
        Chargement en cours...
    </div>

        {{-- Message de succès --}}

        <div class="admin-card">
            <div class="flex flex-col space-y-1.5 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-[#001a61]">Candidatures</h3>
                        <p class="text-sm text-[#757683]">{{ $applications->total() }} candidature(s) reçue(s)</p>
                    </div>
                </div>

                {{-- Barre de recherche et filtres --}}
                <div class="mt-4 flex flex-col md:flex-row gap-4">
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Rechercher par nom, email, poste..."
                        class="flex h-10 w-full md:w-96 rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 py-2 text-sm focus:border-[#001a61] outline-none">
                    <select wire:model.live="filterStatus"
                        class="flex h-10 w-full md:w-48 rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 py-2 text-sm focus:border-[#001a61] outline-none">
                        <option value="">Tous les statuts</option>
                        <option value="pending">En attente</option>
                        <option value="reviewing">En examen</option>
                        <option value="shortlisted">Présélectionné</option>
                        <option value="interviewed">Entretien passé</option>
                        <option value="rejected">Rejeté</option>
                        <option value="accepted">Accepté</option>
                    </select>
                </div>
            </div>

            <div class="p-6 pt-0">
                <div class="relative w-full overflow-auto">
                    <table class="w-full caption-bottom text-sm">
                        <thead class="[&_tr]:border-b">
                            <tr class="border-b transition-colors hover:bg-muted/50">
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Candidat</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Poste</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Expérience</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Statut</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Date</th>
                                <th class="h-12 px-4 align-middle font-medium text-muted-foreground text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="[&_tr:last-child]:border-0">
                            @forelse($applications as $application)
                                <tr class="border-b transition-colors hover:bg-muted/50">
                                    <td class="p-4 align-middle">
                                        <div class="font-medium">{{ $application->full_name }}</div>
                                        <div class="text-xs text-muted-foreground">{{ $application->email }}</div>
                                    </td>
                                    <td class="p-4 align-middle">{{ $application->position_applied }}</td>
                                    <td class="p-4 align-middle">{{ $application->years_of_experience }} ans</td>
                                    <td class="p-4 align-middle">
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'reviewing' => 'bg-blue-100 text-blue-800',
                                                'shortlisted' => 'bg-purple-100 text-purple-800',
                                                'interviewed' => 'bg-indigo-100 text-indigo-800',
                                                'rejected' => 'bg-red-100 text-red-800',
                                                'accepted' => 'bg-green-100 text-green-800',
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $statusColors[$application->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $application->status_label }}
                                        </span>
                                    </td>
                                    <td class="p-4 align-middle">{{ $application->created_at->format('d/m/Y') }}</td>
                                    <td class="p-4 align-middle text-right">
                                        <div class="flex justify-end gap-2">
                                            <button type="button" wire:click="viewApplication({{ $application->id }})"
                                                class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-3"
                                                title="Voir les détails">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                                    <circle cx="12" cy="12" r="3"/>
                                                </svg>
                                            </button>
                                            <button type="button" wire:click="downloadCV({{ $application->id }})"
                                                class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-3"
                                                title="Télécharger le CV">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                                    <polyline points="7 10 12 15 17 10"/>
                                                    <line x1="12" x2="12" y1="15" y2="3"/>
                                                </svg>
                                            </button>
                                            <button type="button" wire:click="confirmDelete({{ $application->id }})"
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
                                        Aucune candidature trouvée
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $applications->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Détails --}}
    @if($showModal)
        @php
            $application = \App\Models\JobApplication::find($applicationId);
        @endphp
        <div class="fixed inset-0 z-[100] bg-black bg-opacity-80 flex items-center justify-center p-4" @click.self="open = false">
            <div class="bg-white rounded-lg shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto relative">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold">Détails de la candidature</h2>
                        <button @click="open = false" class="text-gray-500 hover:text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18"/>
                                <path d="m6 6 12 12"/>
                            </svg>
                        </button>
                    </div>

                    @if($application)
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Nom complet</label>
                                    <p class="text-base font-semibold">{{ $application->full_name }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Poste souhaité</label>
                                    <p class="text-base font-semibold">{{ $application->position_applied }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Email</label>
                                    <p class="text-base">{{ $application->email }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Téléphone</label>
                                    <p class="text-base">{{ $application->phone }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Ville / Pays</label>
                                    <p class="text-base">{{ $application->city ?? 'N/A' }}, {{ $application->country }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Expérience</label>
                                    <p class="text-base">{{ $application->years_of_experience }} ans</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Niveau d'études</label>
                                    <p class="text-base">{{ $application->education_level ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Entreprise actuelle</label>
                                    <p class="text-base">{{ $application->current_company ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Disponibilité</label>
                                    <p class="text-base">{{ $application->availability_label }}</p>
                                </div>
                                @if($application->expected_salary)
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Prétentions salariales</label>
                                        <p class="text-base">{{ number_format($application->expected_salary, 0, ',', ' ') }} FCFA</p>
                                    </div>
                                @endif
                            </div>

                            @if($application->linkedin_url || $application->portfolio_url)
                                <div class="grid grid-cols-2 gap-4">
                                    @if($application->linkedin_url)
                                        <div>
                                            <label class="text-sm font-medium text-gray-500">LinkedIn</label>
                                            <a href="{{ $application->linkedin_url }}" target="_blank" class="text-base text-primary hover:underline">Voir le profil</a>
                                        </div>
                                    @endif
                                    @if($application->portfolio_url)
                                        <div>
                                            <label class="text-sm font-medium text-gray-500">Portfolio</label>
                                            <a href="{{ $application->portfolio_url }}" target="_blank" class="text-base text-primary hover:underline">Voir le portfolio</a>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            @if($application->cover_letter)
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Lettre de motivation</label>
                                    <p class="text-base mt-1 p-3 bg-gray-50 rounded-lg">{{ $application->cover_letter }}</p>
                                </div>
                            @endif

                            <hr class="my-4">

                            <form wire:submit="updateApplication">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium mb-2">Statut</label>
                                        <select wire:model="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                            <option value="pending">En attente</option>
                                            <option value="reviewing">En cours d'examen</option>
                                            <option value="shortlisted">Présélectionné</option>
                                            <option value="interviewed">Entretien passé</option>
                                            <option value="rejected">Rejeté</option>
                                            <option value="accepted">Accepté</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium mb-2">Notes administratives</label>
                                        <textarea wire:model="admin_notes" rows="4" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary" placeholder="Ajoutez des notes sur cette candidature..."></textarea>
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
                <p class="text-gray-600 mb-6">Êtes-vous sûr de vouloir supprimer cette candidature ? Cette action est irréversible.</p>
                
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
