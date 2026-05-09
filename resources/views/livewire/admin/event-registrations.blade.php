<div>
    <div class="container mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold">Inscriptions</h1>
                <p class="text-sm text-muted-foreground">{{ $event->title }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.event.checkin', $event) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary-light transition-colors text-sm font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7v18"/><path d="M21 7v18"/><path d="M8 7v18"/><path d="M16 7v18"/></svg>
                    Émargement
                </a>
                <button wire:click="exportCsv" class="inline-flex items-center gap-2 px-4 py-2 border border-border rounded-lg text-sm font-medium hover:bg-muted transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                    Export CSV
                </button>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800 border border-green-200">{{ session('message') }}</div>
        @endif
        @if (session()->has('error'))
            <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 border border-red-200">{{ session('error') }}</div>
        @endif

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-6">
            <div class="rounded-lg border bg-card p-4 border-border">
                <p class="text-xs text-muted-foreground">Total inscrits</p>
                <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
            </div>
            <div class="rounded-lg border bg-card p-4 border-border">
                <p class="text-xs text-muted-foreground">Confirmés</p>
                <p class="text-2xl font-bold text-emerald-600">{{ $stats['confirmed'] }}</p>
            </div>
            <div class="rounded-lg border bg-card p-4 border-border">
                <p class="text-xs text-muted-foreground">Présents</p>
                <p class="text-2xl font-bold text-primary">{{ $stats['checked_in'] }}</p>
            </div>
            <div class="rounded-lg border bg-card p-4 border-border">
                <p class="text-xs text-muted-foreground">Annulés</p>
                <p class="text-2xl font-bold text-red-600">{{ $stats['cancelled'] }}</p>
            </div>
            <div class="rounded-lg border bg-card p-4 border-border">
                <p class="text-xs text-muted-foreground">Places restantes</p>
                <p class="text-2xl font-bold">{{ $stats['capacity'] > 0 ? $stats['remaining'] : '∞' }}</p>
            </div>
        </div>

        <div class="mb-4 flex flex-col md:flex-row gap-3">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Rechercher..." class="w-full max-w-md px-4 py-2 border border-border rounded-lg bg-background focus:ring-2 focus:ring-primary">
            <select wire:model.live="statusFilter" class="px-4 py-2 border border-border rounded-lg bg-background focus:ring-2 focus:ring-primary">
                <option value="">Tous les statuts</option>
                <option value="registered">Enregistré</option>
                <option value="confirmed">Confirmé</option>
                <option value="checked_in">Présent</option>
                <option value="cancelled">Annulé</option>
            </select>
        </div>

        <div class="rounded-lg border bg-card text-card-foreground shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-muted">
                        <tr>
                            <th class="text-left p-4 font-semibold">Participant</th>
                            <th class="text-left p-4 font-semibold">Institution</th>
                            <th class="text-left p-4 font-semibold">Billet</th>
                            <th class="text-left p-4 font-semibold">T-shirt</th>
                            <th class="text-left p-4 font-semibold">Statut</th>
                            <th class="text-left p-4 font-semibold">Émargement</th>
                            <th class="text-right p-4 font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registrations as $reg)
                        <tr class="border-t border-border hover:bg-muted/50 transition-colors">
                            <td class="p-4">
                                <p class="font-semibold">{{ $reg->fullName() }}</p>
                                <p class="text-xs text-muted-foreground">{{ $reg->email }}<br>{{ $reg->phone }}</p>
                            </td>
                            <td class="p-4">{{ $reg->institution_name ?? '-' }}<br><span class="text-xs text-muted-foreground">{{ $reg->job_title ?? '' }}</span></td>
                            <td class="p-4">{{ $reg->ticketType?->name ?? '-' }}</td>
                            <td class="p-4">{{ $reg->t_shirt_size ?? '-' }}</td>
                            <td class="p-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @match($reg->status)
                                        'registered' => 'bg-blue-100 text-blue-800'
                                        'confirmed' => 'bg-emerald-100 text-emerald-800'
                                        'checked_in' => 'bg-primary/10 text-primary'
                                        'cancelled' => 'bg-red-100 text-red-800'
                                        'no_show' => 'bg-gray-100 text-gray-800'
                                    @endmatch">
                                    @match($reg->status)
                                        'registered' => 'Enregistré'
                                        'confirmed' => 'Confirmé'
                                        'checked_in' => 'Présent'
                                        'cancelled' => 'Annulé'
                                        'no_show' => 'Absent'
                                        @default => $reg->status
                                    @endmatch
                                </span>
                            </td>
                            <td class="p-4">
                                @if($reg->checkIn)
                                    <span class="text-xs text-emerald-600 font-medium">{{ $reg->checkIn->checked_in_at?->format('H:i') }}</span>
                                @else
                                    <span class="text-xs text-muted-foreground">—</span>
                                @endif
                            </td>
                            <td class="p-4 text-right">
                                @if(!in_array($reg->status, ['cancelled','no_show']))
                                    @if(!$reg->isCheckedIn())
                                        <button wire:click="checkInManual({{ $reg->id }})" class="text-xs text-primary hover:underline mr-2">Émargement manuel</button>
                                    @endif
                                    <button wire:click="confirmCancel({{ $reg->id }})" class="text-xs text-red-600 hover:underline">Annuler</button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="p-8 text-center text-muted-foreground">Aucune inscription trouvée.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4">{{ $registrations->links() }}</div>
    </div>

    @if($showCancelModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="$set('showCancelModal', false)"></div>
        <div class="relative bg-card rounded-lg shadow-xl w-full max-w-md border border-border p-6">
            <h3 class="text-lg font-bold mb-2">Annuler une inscription</h3>
            <p class="text-sm text-muted-foreground mb-4">Cette action libérera une place et pourra déclencher la promotion d'une personne en liste d'attente.</p>
            <textarea wire:model="cancellationReason" placeholder="Motif d'annulation..." rows="3" class="w-full px-3 py-2 border border-border rounded-lg bg-background mb-4 text-sm"></textarea>
            <div class="flex justify-end gap-3">
                <button wire:click="$set('showCancelModal', false)" class="px-4 py-2 border border-border rounded-lg text-foreground hover:bg-muted">Annuler</button>
                <button wire:click="cancelRegistration" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Confirmer l'annulation</button>
            </div>
        </div>
    </div>
    @endif
</div>
