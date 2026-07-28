<div>
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4 mb-6">
        <div>
            <a href="{{ route('admin.sgi-sgo') }}" class="text-xs font-bold text-[#0a2e8c] hover:underline">← SGI / SGO</a>
            <h1 class="text-3xl font-extrabold text-[#001a61] mt-1">Intentions d’ordres</h1>
            <p class="text-sm text-[#444652] mt-1">
                Demandes clients à relayer vers une SGI.
                <span class="font-bold text-[#001a61]">{{ $pendingTotal }}</span> en attente.
            </p>
        </div>
        <select wire:model.live="status" class="rounded-lg border-[#c5c5d4] text-sm">
            <option value="">Tous les statuts</option>
            <option value="pending">En attente</option>
            <option value="relayed">Relayé SGI</option>
            <option value="done">Traité</option>
            <option value="cancelled">Annulé</option>
        </select>
    </div>

    <div class="flex gap-2 mb-4">
        <button type="button" wire:click="$set('tab', 'carnet')"
            @class([
                'px-4 py-2 rounded-lg text-sm font-bold border transition',
                'bg-[#001a61] text-white border-[#001a61]' => $tab === 'carnet',
                'bg-white text-[#001a61] border-[#c5c5d4]' => $tab !== 'carnet',
            ])>
            Carnet public ({{ $carnetCount }})
        </button>
        <button type="button" wire:click="$set('tab', 'programmes')"
            @class([
                'px-4 py-2 rounded-lg text-sm font-bold border transition',
                'bg-[#001a61] text-white border-[#001a61]' => $tab === 'programmes',
                'bg-white text-[#001a61] border-[#c5c5d4]' => $tab !== 'programmes',
            ])>
            Espace client ({{ $programmeCount }})
        </button>
    </div>

    <div class="bg-white border border-[#c5c5d4] rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-[#f0f3ff] text-xs uppercase text-[#757683]">
                    <tr>
                        <th class="text-left px-4 py-3">Date</th>
                        <th class="text-left px-4 py-3">Client</th>
                        <th class="text-left px-4 py-3">Ordre</th>
                        <th class="text-left px-4 py-3">SGI souhaitée</th>
                        <th class="text-left px-4 py-3">Statut</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @if ($tab === 'carnet')
                        @forelse ($intents as $intent)
                            <tr class="border-t border-[#e7eeff]" wire:key="carnet-{{ $intent->id }}">
                                <td class="px-4 py-3 whitespace-nowrap">{{ $intent->created_at?->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-[#001a61]">{{ $intent->name ?: ($intent->user?->name ?? '—') }}</p>
                                    <p class="text-xs text-[#757683]">{{ $intent->email }} · {{ $intent->phone }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="font-bold text-[#001a61]">{{ strtoupper($intent->side) }} {{ $intent->stock?->symbol }}</p>
                                    <p class="text-xs text-[#757683]">
                                        {{ $intent->order_type }} · qty {{ number_format((float) $intent->quantity, 0, ',', ' ') }}
                                        @if ($intent->limit_price)
                                            @ {{ number_format((float) $intent->limit_price, 2, ',', ' ') }}
                                        @endif
                                    </p>
                                </td>
                                <td class="px-4 py-3 text-xs font-semibold text-[#001a61]">{{ $intent->partner?->nom ?? 'Non précisée' }}</td>
                                <td class="px-4 py-3">
                                    <span class="text-xs font-bold uppercase px-2 py-0.5 rounded bg-[#e7eeff] text-[#001a61]">{{ $intent->statusLabel() }}</span>
                                </td>
                                <td class="px-4 py-3 text-right whitespace-nowrap space-x-1">
                                    <button type="button" wire:click="setCarnetStatus({{ $intent->id }}, 'relayed')" class="text-xs font-bold text-[#0a2e8c] hover:underline">Relayé</button>
                                    <button type="button" wire:click="setCarnetStatus({{ $intent->id }}, 'done')" class="text-xs font-bold text-emerald-700 hover:underline">Traité</button>
                                    <button type="button" wire:click="setCarnetStatus({{ $intent->id }}, 'cancelled')" class="text-xs font-bold text-red-600 hover:underline">Annuler</button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-10 text-center text-[#757683]">Aucune intention carnet.</td></tr>
                        @endforelse
                    @else
                        @forelse ($intents as $order)
                            <tr class="border-t border-[#e7eeff]" wire:key="prog-{{ $order->id }}">
                                <td class="px-4 py-3 whitespace-nowrap">{{ $order->created_at?->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-[#001a61]">{{ $order->user?->name ?? '—' }}</p>
                                    <p class="text-xs text-[#757683]">{{ $order->user?->email }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="font-bold text-[#001a61]">{{ strtoupper($order->side) }} {{ $order->stock?->symbol }}</p>
                                    <p class="text-xs text-[#757683]">
                                        {{ $order->condition_type }} · qty {{ number_format((float) $order->quantity, 0, ',', ' ') }}
                                        @if ($order->target_price)
                                            @ {{ number_format((float) $order->target_price, 2, ',', ' ') }}
                                        @endif
                                    </p>
                                </td>
                                <td class="px-4 py-3 text-xs font-semibold text-[#001a61]">{{ $order->partner?->nom ?? 'Non précisée' }}</td>
                                <td class="px-4 py-3">
                                    <span class="text-xs font-bold uppercase px-2 py-0.5 rounded bg-[#e7eeff] text-[#001a61]">{{ $order->statusLabel() }}</span>
                                </td>
                                <td class="px-4 py-3 text-right whitespace-nowrap space-x-1">
                                    <button type="button" wire:click="setProgrammeStatus({{ $order->id }}, 'relayed')" class="text-xs font-bold text-[#0a2e8c] hover:underline">Relayé</button>
                                    <button type="button" wire:click="setProgrammeStatus({{ $order->id }}, 'done')" class="text-xs font-bold text-emerald-700 hover:underline">Traité</button>
                                    <button type="button" wire:click="setProgrammeStatus({{ $order->id }}, 'cancelled')" class="text-xs font-bold text-red-600 hover:underline">Annuler</button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-10 text-center text-[#757683]">Aucune intention espace client.</td></tr>
                        @endforelse
                    @endif
                </tbody>
            </table>
        </div>
        @if ($intents->hasPages())
            <div class="px-4 py-3 border-t border-[#e7eeff]">{{ $intents->links() }}</div>
        @endif
    </div>
</div>
