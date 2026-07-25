<div>
    {{-- Indicateur de chargement Livewire --}}
    <div wire:loading class="fixed top-0 left-0 right-0 bg-blue-500 text-white text-center py-2 z-50">
        Chargement en cours...
    </div>

    <div class="admin-page space-y-6">
<div class="flex flex-wrap justify-between items-end gap-4 mb-2">
        <div>
            <nav class="flex items-center gap-2 text-[#757683] mb-2 text-xs font-semibold tracking-wider uppercase">
                <span>Administration</span>
                <span class="material-symbols-outlined text-sm">chevron_right</span>
                <span class="text-[#001a61]">TRANSACTIONS</span>
            </nav>
            <h2 class="text-3xl font-extrabold text-[#001a61] tracking-tight">Suivi des Transactions</h2>
        </div>
    </div>

        {{-- Message de succès --}}

        {{-- Statistiques --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            {{-- Total transactions --}}
            <div class="admin-card p-6">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-[#757683]">Total Transactions</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary">
                        <rect width="20" height="14" x="2" y="5" rx="2"></rect>
                        <line x1="2" x2="22" y1="10" y2="10"></line>
                    </svg>
                </div>
                <p class="text-3xl font-bold">{{ number_format($stats['total']) }}</p>
                <p class="text-xs text-[#757683] mt-1">
                    <span class="text-accent font-semibold">{{ $stats['todayCount'] }}</span> aujourd'hui
                </p>
            </div>

            {{-- Transactions réussies --}}
            <div class="admin-card p-6">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-[#757683]">Réussies</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-accent">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>
                <p class="text-3xl font-bold text-accent">{{ number_format($stats['succeeded']) }}</p>
                <p class="text-xs text-[#757683] mt-1">
                    {{ number_format(($stats['total'] > 0 ? ($stats['succeeded'] / $stats['total']) * 100 : 0), 1) }}% du total
                </p>
            </div>

            {{-- Montant total --}}
            <div class="admin-card p-6">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-[#757683]">Revenus</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary">
                        <line x1="12" x2="12" y1="2" y2="22"></line>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                </div>
                <p class="text-3xl font-bold">{{ number_format($stats['totalAmount'], 0, ',', ' ') }}</p>
                <p class="text-xs text-[#757683] mt-1">
                    <span class="text-accent font-semibold">{{ number_format($stats['todayAmount'], 0, ',', ' ') }}</span> XOF aujourd'hui
                </p>
            </div>

            {{-- En attente --}}
            <div class="admin-card p-6">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-[#757683]">En attente</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-orange-500">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>
                <p class="text-3xl font-bold text-orange-500">{{ number_format($stats['pending']) }}</p>
                <p class="text-xs text-[#757683] mt-1">
                    <span class="text-destructive font-semibold">{{ $stats['failed'] }}</span> échouées
                </p>
            </div>
        </div>

        {{-- Card principale avec table --}}
        <div class="admin-card">
            <div class="flex flex-col space-y-1.5 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-semibold leading-none tracking-tight">Historique des Transactions</h3>
                        <p class="text-sm text-[#757683]">{{ $transactions->total() }} transaction(s) enregistrée(s)</p>
                    </div>
                </div>

                {{-- Barre de recherche et filtres --}}
                <div class="mt-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        {{-- Recherche --}}
                        <div class="lg:col-span-1">
                            <input wire:model.live.debounce.300ms="search" type="text" 
                                placeholder="ID, nom, email, téléphone..."
                                class="flex h-10 w-full rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 py-2 text-base ring-offset-background placeholder:text-[#757683] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm">
                        </div>

                        {{-- Statut --}}
                        <div>
                            <select wire:model.live="filterStatus" class="flex h-10 w-full rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 py-2 text-base ring-offset-background placeholder:text-[#757683] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm">
                                <option value="">Tous les statuts</option>
                                <option value="pending">En attente</option>
                                <option value="succeeded">Réussie</option>
                                <option value="approved">Approuvée</option>
                                <option value="failed">Échouée</option>
                                <option value="canceled">Annulée</option>
                                <option value="declined">Refusée</option>
                            </select>
                        </div>

                        {{-- Agrégateur --}}
                        <div>
                            <select wire:model.live="filterAgregateur" class="flex h-10 w-full rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 py-2 text-base ring-offset-background placeholder:text-[#757683] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm">
                                <option value="">Tous les agrégateurs</option>
                                <option value="kkiapay">KKiaPay</option>
                                <option value="fedapay">FedaPay</option>
                            </select>
                        </div>

                        {{-- Bouton réinitialiser --}}
                        <div>
                            <button wire:click="resetFilters" type="button"
                                class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-[#c5c5d4] bg-[#f9f9ff] hover:bg-accent hover:text-accent-foreground h-10 px-4 w-full md:w-auto">
                                Réinitialiser
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 pt-0">
                <div class="relative w-full overflow-auto">
                    <table class="admin-table w-full caption-bottom text-sm">
                        <thead class="[&_tr]:border-b">
                            <tr class="border-b transition-colors hover:bg-[#f0f3ff]">
                                <th class="h-12 px-4 text-left align-middle font-medium text-[#757683] cursor-pointer" wire:click="sortBy('id')">
                                    ID
                                    @if($sortField === 'id')
                                        <span class="text-xs">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-[#757683]">Client</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-[#757683] cursor-pointer" wire:click="sortBy('amount')">
                                    Montant
                                    @if($sortField === 'amount')
                                        <span class="text-xs">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-[#757683]">Agrégateur</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-[#757683]">Statut</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-[#757683] cursor-pointer" wire:click="sortBy('created_at')">
                                    Date
                                    @if($sortField === 'created_at')
                                        <span class="text-xs">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </th>
                                <th class="h-12 px-4 align-middle font-medium text-[#757683] text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="[&_tr:last-child]:border-0">
                            @forelse($transactions as $transaction)
                                <tr class="border-b transition-colors hover:bg-[#f0f3ff]">
                                    <td class="p-4 align-middle">
                                        <span class="font-mono text-xs text-[#757683]">#{{ $transaction->id }}</span>
                                    </td>
                                    <td class="p-4 align-middle">
                                        <div class="font-medium">{{ $transaction->fullname ?? 'N/A' }}</div>
                                        <div class="text-sm text-[#757683]">{{ $transaction->email ?? $transaction->phone }}</div>
                                    </td>
                                    <td class="p-4 align-middle">
                                        <div class="font-semibold">{{ number_format($transaction->amount, 0, ',', ' ') }} {{ $transaction->currency }}</div>
                                        @if($transaction->fees)
                                            <div class="text-xs text-[#757683]">Frais: {{ number_format($transaction->fees, 0, ',', ' ') }}</div>
                                        @endif
                                    </td>
                                    <td class="p-4 align-middle">
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $transaction->agregateur === 'kkiapay' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                            {{ strtoupper($transaction->agregateur) }}
                                        </span>
                                    </td>
                                    <td class="p-4 align-middle">
                                        @php
                                            $statusClasses = [
                                                'succeeded' => 'bg-green-100 text-green-800',
                                                'approved' => 'bg-green-100 text-green-800',
                                                'pending' => 'bg-orange-100 text-orange-800',
                                                'failed' => 'bg-red-100 text-red-800',
                                                'canceled' => 'bg-gray-100 text-gray-800',
                                                'declined' => 'bg-red-100 text-red-800',
                                            ];
                                            $statusLabels = [
                                                'succeeded' => 'Réussie',
                                                'approved' => 'Approuvée',
                                                'pending' => 'En attente',
                                                'failed' => 'Échouée',
                                                'canceled' => 'Annulée',
                                                'declined' => 'Refusée',
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $statusClasses[$transaction->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $statusLabels[$transaction->status] ?? $transaction->status }}
                                        </span>
                                    </td>
                                    <td class="p-4 align-middle">{{ $transaction->created_at->format('d/m/Y') }}</td>
                                    <td class="p-4 align-middle text-right">
                                        <div class="flex justify-end gap-2">
                                            <button type="button" wire:click="viewTransaction({{ $transaction->id }})"
                                                class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-[#c5c5d4] bg-[#f9f9ff] hover:bg-accent hover:text-accent-foreground h-9 px-3"
                                                title="Voir les détails">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                                    <circle cx="12" cy="12" r="3"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-4 text-center text-[#757683]">
                                        Aucune transaction trouvée
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Modal de détails --}}
    @if($showModal && $selectedTransaction)
        <div class="fixed inset-0 z-[100] bg-black bg-opacity-80 flex items-center justify-center p-4" wire:click.self="closeModal">
            <div class="bg-white rounded-lg shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto relative">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold">Détails de la transaction #{{ $selectedTransaction->id }}</h2>
                        <button wire:click="closeModal" class="text-gray-500 hover:text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18"/>
                                <path d="m6 6 12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Content --}}
                    <div class="space-y-6">
                        {{-- Informations principales --}}
                        <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-[#757683]">Statut</label>
                        <p class="mt-1">
                            @php
                                $statusClasses = [
                                    'succeeded' => 'bg-green-100 text-green-800',
                                    'approved' => 'bg-green-100 text-green-800',
                                    'pending' => 'bg-orange-100 text-orange-800',
                                    'failed' => 'bg-red-100 text-red-800',
                                    'canceled' => 'bg-gray-100 text-gray-800',
                                    'declined' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClasses[$selectedTransaction->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $selectedTransaction->status }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-[#757683]">Agrégateur</label>
                        <p class="mt-1 font-medium">{{ strtoupper($selectedTransaction->agregateur) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-[#757683]">Montant</label>
                        <p class="mt-1 text-2xl font-bold">{{ number_format($selectedTransaction->amount, 0, ',', ' ') }} {{ $selectedTransaction->currency }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-[#757683]">Frais</label>
                        <p class="mt-1 font-medium">{{ number_format($selectedTransaction->fees ?? 0, 0, ',', ' ') }} {{ $selectedTransaction->currency }}</p>
                    </div>
                </div>

                {{-- Informations client --}}
                <div>
                    <h4 class="font-semibold mb-3">Informations client</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-[#757683]">Nom complet</label>
                            <p class="mt-1">{{ $selectedTransaction->fullname ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-[#757683]">Email</label>
                            <p class="mt-1">{{ $selectedTransaction->email ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-[#757683]">Téléphone</label>
                            <p class="mt-1">{{ $selectedTransaction->phone ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-[#757683]">Pays</label>
                            <p class="mt-1">{{ $selectedTransaction->country ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Détails paiement --}}
                <div>
                    <h4 class="font-semibold mb-3">Détails du paiement</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-[#757683]">Type de paiement</label>
                            <p class="mt-1">{{ $selectedTransaction->type_paiement ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-[#757683]">Source</label>
                            <p class="mt-1">{{ $selectedTransaction->source_common_name ?? $selectedTransaction->source ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-[#757683]">ID externe</label>
                            <p class="mt-1 font-mono text-sm">{{ $selectedTransaction->external_transaction_id ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-[#757683]">Mode</label>
                            <p class="mt-1">{{ $selectedTransaction->mode ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Description --}}
                @if($selectedTransaction->description)
                <div>
                    <label class="text-sm font-medium text-[#757683]">Description</label>
                    <p class="mt-1">{{ $selectedTransaction->description }}</p>
                </div>
                @endif

                {{-- Relations --}}
                @if($selectedTransaction->user || $selectedTransaction->course)
                <div>
                    <h4 class="font-semibold mb-3">Informations associées</h4>
                    <div class="grid grid-cols-2 gap-4">
                        @if($selectedTransaction->user)
                        <div>
                            <label class="text-sm font-medium text-[#757683]">Utilisateur</label>
                            <p class="mt-1">{{ $selectedTransaction->user->name }}</p>
                        </div>
                        @endif
                        @if($selectedTransaction->course)
                        <div>
                            <label class="text-sm font-medium text-[#757683]">Formation</label>
                            <p class="mt-1">{{ $selectedTransaction->course->title }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Dates --}}
                <div>
                    <h4 class="font-semibold mb-3">Dates</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-[#757683]">Créée le</label>
                            <p class="mt-1">{{ $selectedTransaction->created_at->format('d/m/Y à H:i:s') }}</p>
                        </div>
                        @if($selectedTransaction->performed_at)
                        <div>
                            <label class="text-sm font-medium text-[#757683]">Exécutée le</label>
                            <p class="mt-1">{{ $selectedTransaction->performed_at->format('d/m/Y à H:i:s') }}</p>
                        </div>
                        @endif
                    </div>
                        </div>

                        {{-- Footer --}}
                        <div class="flex justify-end gap-3 mt-6">
                            <button type="button" wire:click="closeModal"
                                class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-[#c5c5d4] bg-[#f9f9ff] hover:bg-accent hover:text-accent-foreground h-11 px-6 py-3">
                                Fermer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@script
<script>
    $wire.on('transaction-updated', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
</script>
@endscript