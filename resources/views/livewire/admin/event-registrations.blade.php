<div>
    <div class="p-6 lg:p-8 space-y-6">
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-[#757683] mb-2 text-xs font-semibold tracking-wider uppercase flex-wrap">
                    <a href="{{ route('admin.events') }}" class="hover:text-[#001a61]" wire:navigate.hover>Événements</a>
                    <span class="material-symbols-outlined text-sm">chevron_right</span>
                    <span class="text-[#001a61]">{{ Str::limit($event->title, 40) }}</span>
                </nav>
                <h1 class="text-2xl font-extrabold text-[#001a61]">Inscriptions</h1>
                <p class="text-sm text-[#444652] mt-1">{{ $event->title }}</p>
                <div class="mt-3 flex flex-wrap items-center gap-2">
                    <code class="text-xs bg-[#f0f3ff] text-[#001a61] px-2.5 py-1.5 rounded-lg border border-[#c5c5d4]">{{ $event->publicUrl() }}</code>
                    <button type="button"
                        onclick="navigator.clipboard.writeText(@js($event->publicUrl())).then(() => window.adfToast && window.adfToast('success', 'Lien public copié'))"
                        class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1.5 rounded-lg border border-[#ffbf00] bg-[#fff8e1] text-[#001a61]">
                        <span class="material-symbols-outlined text-[16px]">content_copy</span> Copier le lien
                    </button>
                    <a href="{{ $event->publicUrl() }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1.5 rounded-lg border border-[#c5c5d4] text-[#001a61] hover:bg-[#e7eeff]">
                        <span class="material-symbols-outlined text-[16px]">open_in_new</span> Page publique
                    </a>
                </div>
            </div>
            <button wire:click="exportCsv" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-[#c5c5d4] text-sm font-bold text-[#001a61] hover:bg-[#e7eeff]">
                <span class="material-symbols-outlined text-[18px]">download</span>
                Export CSV
            </button>
        </div>

        @include('livewire.admin.partials.event-admin-nav', ['event' => $event])

        <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
            <div class="admin-card p-4">
                <p class="text-xs text-[#757683]">Total inscrits</p>
                <p class="text-2xl font-extrabold text-[#001a61]">{{ $stats['total'] }}</p>
            </div>
            <div class="admin-card p-4">
                <p class="text-xs text-[#757683]">Confirmés</p>
                <p class="text-2xl font-extrabold text-emerald-600">{{ $stats['confirmed'] }}</p>
            </div>
            <div class="admin-card p-4">
                <p class="text-xs text-[#757683]">Présents</p>
                <p class="text-2xl font-extrabold text-[#001a61]">{{ $stats['checked_in'] }}</p>
            </div>
            <div class="admin-card p-4">
                <p class="text-xs text-[#757683]">Annulés</p>
                <p class="text-2xl font-extrabold text-red-600">{{ $stats['cancelled'] }}</p>
            </div>
            <div class="admin-card p-4">
                <p class="text-xs text-[#757683]">Places restantes</p>
                <p class="text-2xl font-extrabold text-[#001a61]">{{ $stats['capacity'] > 0 ? $stats['remaining'] : '∞' }}</p>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-3">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Rechercher..." class="admin-input w-full max-w-md text-sm">
            <select wire:model.live="statusFilter" class="admin-input text-sm">
                <option value="">Tous les statuts</option>
                <option value="registered">Enregistré</option>
                <option value="confirmed">Confirmé</option>
                <option value="checked_in">Présent</option>
                <option value="cancelled">Annulé</option>
            </select>
        </div>

        <div class="admin-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="admin-table w-full text-sm">
                    <thead>
                        <tr>
                            <th class="text-left px-4 py-3">Participant</th>
                            <th class="text-left px-4 py-3">Institution</th>
                            <th class="text-left px-4 py-3">Billet</th>
                            <th class="text-left px-4 py-3">T-shirt</th>
                            <th class="text-left px-4 py-3">QR</th>
                            <th class="text-left px-4 py-3">Statut</th>
                            <th class="text-left px-4 py-3">Émargement</th>
                            <th class="text-right px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($registrations as $reg)
                            <tr class="border-t border-[#e7eeff]">
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-[#001a61]">{{ $reg->fullName() }}</p>
                                    <p class="text-xs text-[#757683]">{{ $reg->email }}<br>{{ $reg->phone }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    {{ $reg->institution_name ?? '—' }}
                                    <br><span class="text-xs text-[#757683]">{{ $reg->job_title ?? '' }}</span>
                                </td>
                                <td class="px-4 py-3">{{ $reg->ticketType?->name ?? '—' }}</td>
                                <td class="px-4 py-3">{{ $reg->t_shirt_size ?? '—' }}</td>
                                <td class="px-4 py-3">
                                    @if ($reg->qr_code)
                                        <code class="text-[10px] bg-[#f0f3ff] px-1.5 py-0.5 rounded font-mono" title="{{ $reg->qr_code }}">{{ Str::limit($reg->qr_code, 16) }}</code>
                                    @else
                                        <span class="text-xs text-[#757683]">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $reg->statusColorClasses() }}">
                                        {{ $reg->statusLabel() }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    @if ($reg->checkIn)
                                        <span class="text-xs text-emerald-600 font-medium">{{ $reg->checkIn->checked_in_at?->format('H:i') }}</span>
                                    @else
                                        <span class="text-xs text-[#757683]">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                    @if (! in_array($reg->status, ['cancelled', 'no_show']))
                                        @if ($reg->qr_code)
                                            <a href="{{ route('event.ticket.public', $reg->qr_code) }}" target="_blank" class="text-xs font-bold text-[#001a61] underline mr-2">Ticket</a>
                                            <button wire:click="resendTicket({{ $reg->id }})" class="text-xs font-bold text-[#0a2e8c] underline mr-2">Renvoyer email</button>
                                        @endif
                                        @if (! $reg->isCheckedIn())
                                            <button wire:click="checkInManual({{ $reg->id }})" class="text-xs font-bold text-[#001a61] underline mr-2">Émargement</button>
                                        @endif
                                        <button wire:click="confirmCancel({{ $reg->id }})" class="text-xs text-red-600 underline">Annuler</button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-[#757683]">Aucune inscription trouvée.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4">{{ $registrations->links() }}</div>
        </div>
    </div>

    @if ($showCancelModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/60" wire:click="$set('showCancelModal', false)"></div>
            <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md border border-[#c5c5d4] p-6">
                <h3 class="text-lg font-bold mb-2">Annuler une inscription</h3>
                <p class="text-sm text-[#757683] mb-4">Cette action libérera une place.</p>
                <textarea wire:model="cancellationReason" placeholder="Motif d'annulation..." rows="3" class="admin-input w-full mb-4 text-sm"></textarea>
                <div class="flex justify-end gap-3">
                    <button wire:click="$set('showCancelModal', false)" class="px-4 py-2 border border-[#c5c5d4] rounded-lg hover:bg-[#e7eeff]">Retour</button>
                    <button wire:click="cancelRegistration" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Confirmer</button>
                </div>
            </div>
        </div>
    @endif
</div>
