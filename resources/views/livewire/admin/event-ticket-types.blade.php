<div x-data="{ open: @entangle('showModal').live, del: @entangle('showDeleteModal').live }">
    <div class="p-6 lg:p-8 space-y-6">
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-[#757683] mb-2 text-xs font-semibold tracking-wider uppercase flex-wrap">
                    <a href="{{ route('admin.events') }}" class="hover:text-[#001a61]" wire:navigate.hover>Événements</a>
                    <span class="material-symbols-outlined text-sm">chevron_right</span>
                    <span class="text-[#001a61]">{{ Str::limit($event->title, 40) }}</span>
                </nav>
                <h1 class="text-2xl font-extrabold text-[#001a61]">Types de billets</h1>
                <p class="text-sm text-[#444652] mt-1">{{ $event->title }}</p>
                @if($event->usesTickets())
                    <p class="mt-2 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $event->pricingBadgeClasses() }}">
                        Mode : {{ $event->pricingLabel() }}
                        @if($event->pricingMode() === 'hybrid')
                            — gratuits + payants
                        @endif
                    </p>
                @endif
            </div>
            @if($event->usesTickets())
                <button @click="open = true; $wire.openModal()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#001a61] text-white text-sm font-bold hover:bg-[#0a2e8c]">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    Nouveau billet
                </button>
            @endif
        </div>

        @include('livewire.admin.partials.event-admin-nav', ['event' => $event])

        @unless($event->usesTickets())
            <div class="rounded-xl border border-[#ffbf00] bg-[#fff8e1] p-4 text-sm text-[#001a61]">
                <p class="font-bold mb-1">Inscription libre (sans billets)</p>
                <p>Activez <strong>Types de billets</strong> sur la fiche de l’événement pour proposer des billets gratuits, payants, ou les deux (hybride).</p>
            </div>
        @else
            <div class="rounded-xl border border-[#c5c5d4] bg-[#f7f9ff] p-4 text-sm text-[#444652]">
                <p class="font-bold text-[#001a61] mb-1">Hybride autorisé</p>
                <p>Créez autant de billets que nécessaire : prix <strong>0 FCFA</strong> = gratuit (confirmation immédiate + ticket QR), prix &gt; 0 = paiement puis ticket.</p>
            </div>

            <div class="admin-card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="admin-table w-full text-sm">
                        <thead class="bg-[#eef3ff]">
                            <tr>
                                <th class="text-left p-4 font-semibold">Billet</th>
                                <th class="text-left p-4 font-semibold">Tarif</th>
                                <th class="text-left p-4 font-semibold">Quota</th>
                                <th class="text-left p-4 font-semibold">Vendus</th>
                                <th class="text-left p-4 font-semibold">Ordre</th>
                                <th class="text-left p-4 font-semibold">Statut</th>
                                <th class="text-right p-4 font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets as $ticket)
                            <tr class="border-t border-[#c5c5d4] hover:bg-[#f7f9ff]">
                                <td class="p-4">
                                    <p class="font-semibold text-[#001a61]">{{ $ticket->name }}</p>
                                    @if($ticket->description)
                                        <p class="text-xs text-[#757683] mt-0.5 line-clamp-2">{{ plain_text($ticket->description, 120) }}</p>
                                    @endif
                                </td>
                                <td class="p-4 whitespace-nowrap">
                                    @if($ticket->price > 0)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-[#fff8e1] text-[#7a5c00]">
                                            {{ number_format($ticket->price, 0, ',', ' ') }} FCFA
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800">
                                            Gratuit
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4">{{ $ticket->quantity > 0 ? $ticket->quantity : '∞' }}</td>
                                <td class="p-4">{{ $ticket->sold }}</td>
                                <td class="p-4">{{ $ticket->display_order }}</td>
                                <td class="p-4">
                                    <button wire:click="toggleActive({{ $ticket->id }})" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $ticket->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $ticket->is_active ? 'Actif' : 'Inactif' }}
                                    </button>
                                </td>
                                <td class="p-4 text-right whitespace-nowrap">
                                    <button wire:click="edit({{ $ticket->id }})" class="text-sm text-primary hover:underline mr-2">Modifier</button>
                                    <button wire:click="confirmDelete({{ $ticket->id }})" class="text-sm text-red-600 hover:underline">Supprimer</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-[#757683]">
                                    Aucun billet. Ajoutez au moins un billet gratuit ou payant pour ouvrir les inscriptions.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4">{{ $tickets->links() }}</div>
            </div>
        @endunless
    </div>

    <div x-show="open" x-cloak style="display:none" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/60" @click="open = false"></div>
        <div class="relative adf-modal-panel bg-white rounded-lg shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto border border-[#c5c5d4]">
            <div class="p-6 border-b border-[#c5c5d4] flex items-center justify-between">
                <h2 class="text-xl font-bold">{{ $editMode ? 'Modifier' : 'Créer' }} un billet</h2>
                <button @click="open = false" class="text-[#757683] hover:text-foreground">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Nom *</label>
                    <input type="text" wire:model="name" placeholder="Standard, VIP…" class="w-full px-3 py-2 border border-[#c5c5d4] rounded-lg bg-[#f9f9ff] focus:ring-2 focus:ring-primary">
                    @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Description</label>
                    <textarea wire:model="description" rows="2" class="w-full px-3 py-2 border border-[#c5c5d4] rounded-lg bg-[#f9f9ff] focus:ring-2 focus:ring-primary"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Type de tarif *</label>
                    <div class="grid grid-cols-2 gap-3" x-data="{ paid: {{ (float) $price > 0 ? 'true' : 'false' }} }" x-init="$watch('paid', v => { if (!v) $wire.set('price', 0) })">
                        <button type="button" @click="paid = false; $wire.set('price', 0)"
                            :class="!paid ? 'border-[#001a61] bg-[#eef3ff] ring-1 ring-[#001a61]' : 'border-[#c5c5d4]'"
                            class="border-2 rounded-lg p-3 text-left transition">
                            <span class="block text-sm font-bold text-[#001a61]">Gratuit</span>
                            <span class="block text-xs text-[#757683] mt-0.5">0 FCFA — ticket immédiat</span>
                        </button>
                        <button type="button" @click="paid = true; if (Number($wire.price) <= 0) $wire.set('price', 5000)"
                            :class="paid ? 'border-[#ffbf00] bg-[#fff8e1] ring-1 ring-[#ffbf00]' : 'border-[#c5c5d4]'"
                            class="border-2 rounded-lg p-3 text-left transition">
                            <span class="block text-sm font-bold text-[#001a61]">Payant</span>
                            <span class="block text-xs text-[#757683] mt-0.5">Paiement puis ticket</span>
                        </button>
                        <div class="col-span-2" x-show="paid" x-cloak>
                            <label class="block text-sm font-medium mb-1">Prix (FCFA) *</label>
                            <input type="number" wire:model="price" min="1" step="1" class="w-full px-3 py-2 border border-[#c5c5d4] rounded-lg bg-[#f9f9ff] focus:ring-2 focus:ring-primary">
                            @error('price')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Quota (0 = illimité)</label>
                        <input type="number" wire:model="quantity" min="0" class="w-full px-3 py-2 border border-[#c5c5d4] rounded-lg bg-[#f9f9ff] focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Ordre d’affichage</label>
                        <input type="number" wire:model="display_order" min="0" class="w-full px-3 py-2 border border-[#c5c5d4] rounded-lg bg-[#f9f9ff] focus:ring-2 focus:ring-primary">
                    </div>
                </div>
                <div>
                    <label class="inline-flex items-center gap-2 text-sm font-medium">
                        <input type="checkbox" wire:model="is_active" class="rounded border-[#c5c5d4]">
                        Billet actif
                    </label>
                </div>
            </div>
            <div class="p-6 border-t border-[#c5c5d4] flex justify-end gap-3">
                <button @click="open = false" class="px-4 py-2 border border-[#c5c5d4] rounded-lg hover:bg-[#eef3ff]">Annuler</button>
                <button wire:click="save" class="px-4 py-2 admin-btn-primary rounded-lg font-medium">Enregistrer</button>
            </div>
        </div>
    </div>

    <div x-show="del" x-cloak style="display:none" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/60" @click="del = false"></div>
        <div class="relative adf-modal-panel bg-white rounded-lg shadow-xl w-full max-w-md border border-[#c5c5d4] p-6">
            <h3 class="text-lg font-bold mb-2">Supprimer ce billet ?</h3>
            <p class="text-sm text-[#757683] mb-6">Impossible s’il a déjà des inscriptions. Préférez le désactiver.</p>
            <div class="flex justify-end gap-3">
                <button @click="del = false" class="px-4 py-2 border border-[#c5c5d4] rounded-lg">Annuler</button>
                <button wire:click="delete" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Supprimer</button>
            </div>
        </div>
    </div>
</div>
