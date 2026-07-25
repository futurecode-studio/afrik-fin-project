<div class="admin-page space-y-6">
    <div class="flex flex-wrap justify-between items-end gap-4 mb-2">
        <div>
            <nav class="flex items-center gap-2 text-[#757683] mb-2 text-xs font-semibold tracking-wider uppercase">
                <span>Administration</span>
                <span class="material-symbols-outlined text-sm">chevron_right</span>
                <span class="text-[#001a61]">SUPPORT</span>
            </nav>
            <h2 class="text-3xl font-extrabold text-[#001a61] tracking-tight">Centre de Support</h2>
        </div>
    </div>

    <div class="admin-card">
        <div class="flex flex-col space-y-1.5 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-[#001a61]">Messages de Contact</h3>
                    <p class="text-sm text-[#757683] mt-1">{{ $contacts->total() }} message(s) reçu(s)</p>
                </div>
            </div>

            <!-- Filtres -->
            <div class="mt-4 flex flex-col md:flex-row gap-4">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Rechercher par nom, email ou sujet..."
                    class="flex h-10 w-full md:w-96 rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 py-2 text-base ring-offset-background placeholder:text-[#757683] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm">
                
                <select wire:model.live="statusFilter"
                    class="flex h-10 rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 py-2 text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm">
                    <option value="all">Tous les statuts</option>
                    <option value="new">Nouveaux</option>
                    <option value="read">Lus</option>
                    <option value="replied">Répondus</option>
                </select>
            </div>
        </div>

        <div class="p-6 pt-0">
            <div class="relative w-full overflow-auto">
                <table class="admin-table w-full caption-bottom text-sm">
                    <thead class="[&_tr]:border-b">
                        <tr class="border-b transition-colors hover:bg-[#f0f3ff]">
                            <th class="h-12 px-4 text-left align-middle font-medium text-[#757683]">Nom</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-[#757683]">Email</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-[#757683]">Téléphone</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-[#757683]">Sujet</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-[#757683]">Statut</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-[#757683]">Date</th>
                            <th class="h-12 px-4 align-middle font-medium text-[#757683] text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="[&_tr:last-child]:border-0">
                        @forelse($contacts as $contact)
                            <tr class="border-b transition-colors hover:bg-[#f0f3ff] {{ $contact->status === 'new' ? 'bg-blue-50/50' : '' }}">
                                <td class="p-4 align-middle">
                                    <div class="font-medium">{{ $contact->first_name }} {{ $contact->last_name }}</div>
                                </td>
                                <td class="p-4 align-middle">{{ $contact->email }}</td>
                                <td class="p-4 align-middle">{{ $contact->phone }}</td>
                                <td class="p-4 align-middle max-w-xs truncate">{{ $contact->subject }}</td>
                                <td class="p-4 align-middle">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold 
                                        {{ $contact->status === 'new' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $contact->status === 'read' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $contact->status === 'replied' ? 'bg-green-100 text-green-800' : '' }}">
                                        @if($contact->status === 'new') Nouveau
                                        @elseif($contact->status === 'read') Lu
                                        @else Répondu
                                        @endif
                                    </span>
                                </td>
                                <td class="p-4 align-middle">{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                                <td class="p-4 align-middle text-right">
                                    <div class="flex justify-end gap-2">
                                        <button type="button" wire:click="viewContact({{ $contact->id }})"
                                            class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-[#c5c5d4] bg-[#f9f9ff] hover:bg-accent hover:text-accent-foreground h-9 px-3"
                                            title="Voir le message">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                        </button>
                                        @if($contact->status !== 'replied')
                                            <button type="button" wire:click="markAsReplied({{ $contact->id }})"
                                                class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-green-200 bg-[#f9f9ff] hover:bg-green-50 text-green-600 h-9 px-3"
                                                title="Marquer comme répondu">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M20 6 9 17l-5-5"/>
                                                </svg>
                                            </button>
                                        @endif
                                        <button type="button" wire:click="deleteContact({{ $contact->id }})"
                                            wire:confirm="Êtes-vous sûr de vouloir supprimer ce message ?"
                                            class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-red-200 bg-[#f9f9ff] hover:bg-red-50 text-red-600 h-9 px-3"
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
                                <td colspan="7" class="p-4 text-center text-[#757683]">
                                    Aucun message trouvé
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $contacts->links() }}
            </div>
        </div>
    </div>

    <!-- Modal pour voir le message -->
    @if($showModal && $selectedContact)
        <div class="fixed inset-0 z-[100] bg-black bg-opacity-80 flex items-center justify-center p-4" wire:click.self="closeModal">
            <div class="bg-white rounded-lg shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto relative">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold">Message de Contact</h2>
                        <button wire:click="closeModal" class="text-gray-500 hover:text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18"/>
                                <path d="m6 6 12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <!-- Informations de contact -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-600 mb-1">Nom complet</p>
                                <p class="font-semibold">{{ $selectedContact->first_name }} {{ $selectedContact->last_name }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-600 mb-1">Email</p>
                                <a href="mailto:{{ $selectedContact->email }}" class="font-semibold text-primary hover:underline">
                                    {{ $selectedContact->email }}
                                </a>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-600 mb-1">Téléphone</p>
                                <a href="tel:{{ $selectedContact->phone }}" class="font-semibold text-primary hover:underline">
                                    {{ $selectedContact->phone }}
                                </a>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-600 mb-1">Date</p>
                                <p class="font-semibold">{{ $selectedContact->created_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>

                        <!-- Sujet -->
                        <div class="p-4 bg-blue-50 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">Sujet</p>
                            <p class="font-semibold text-lg">{{ $selectedContact->subject }}</p>
                        </div>

                        <!-- Message -->
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600 mb-2">Message</p>
                            <p class="text-gray-800 whitespace-pre-wrap">{{ $selectedContact->message }}</p>
                        </div>

                        <!-- Statut -->
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">Statut</p>
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold 
                                {{ $selectedContact->status === 'new' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $selectedContact->status === 'read' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $selectedContact->status === 'replied' ? 'bg-green-100 text-green-800' : '' }}">
                                @if($selectedContact->status === 'new') Nouveau
                                @elseif($selectedContact->status === 'read') Lu
                                @else Répondu
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        @if($selectedContact->status !== 'replied')
                            <button wire:click="markAsReplied({{ $selectedContact->id }})"
                                class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-green-600 text-white hover:bg-green-700 h-11 px-6 py-3">
                                Marquer comme répondu
                            </button>
                        @endif
                        <button wire:click="closeModal"
                            class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-[#c5c5d4] bg-[#f9f9ff] hover:bg-accent hover:text-accent-foreground h-11 px-6 py-3">
                            Fermer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
