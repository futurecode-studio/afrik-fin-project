<div x-data="{ open: @entangle('showModal').live, del: @entangle('showDeleteModal').live }">
    <div class="p-6 lg:p-8 space-y-6">
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-[#757683] mb-2 text-xs font-semibold tracking-wider uppercase flex-wrap">
                    <a href="{{ route('admin.events') }}" class="hover:text-[#001a61]" wire:navigate.hover>Événements</a>
                    <span class="material-symbols-outlined text-sm">chevron_right</span>
                    <span class="text-[#001a61]">{{ Str::limit($event->title, 40) }}</span>
                </nav>
                <h1 class="text-2xl font-extrabold text-[#001a61]">Intervenants</h1>
                <p class="text-sm text-[#444652] mt-1">{{ $event->title }}</p>
            </div>
            <button @click="open = true; $wire.openModal()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#001a61] text-white text-sm font-bold hover:bg-[#0a2e8c]">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Ajouter un intervenant
            </button>
        </div>

        @include('livewire.admin.partials.event-admin-nav', ['event' => $event])

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @forelse($speakers as $speaker)
            <div class="admin-card p-4 flex gap-3 items-start">
                @if($speaker->photo)
                    <img src="{{ asset('storage/'.$speaker->photo) }}" alt="" class="w-14 h-14 rounded-full object-cover border border-[#c5c5d4]">
                @else
                    <div class="w-14 h-14 rounded-full bg-[#eef3ff] text-[#001a61] flex items-center justify-center font-bold text-lg">{{ substr($speaker->name, 0, 1) }}</div>
                @endif
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-[#001a61]">{{ $speaker->name }}</p>
                    <p class="text-sm text-[#444652]">{{ $speaker->role }}</p>
                    <p class="text-xs text-[#757683]">{{ $speaker->company }}</p>
                    <div class="mt-3 flex gap-2">
                        <button wire:click="edit({{ $speaker->id }})" class="text-xs font-bold text-primary underline">Modifier</button>
                        <button wire:click="confirmDelete({{ $speaker->id }})" class="text-xs font-bold text-red-600 underline">Supprimer</button>
                    </div>
                </div>
            </div>
            @empty
            <div class="md:col-span-2 xl:col-span-3 admin-card p-8 text-center text-[#757683]">
                Aucun intervenant. Ajoutez les personnes affichées sur la page publique.
            </div>
            @endforelse
        </div>
    </div>

    <div x-show="open" x-cloak style="display:none" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/60" @click="open = false"></div>
        <div class="relative adf-modal-panel bg-white rounded-lg shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto border border-[#c5c5d4]">
            <div class="p-6 border-b border-[#c5c5d4] flex items-center justify-between">
                <h2 class="text-xl font-bold">{{ $editMode ? 'Modifier' : 'Ajouter' }} un intervenant</h2>
                <button @click="open = false" class="text-[#757683]"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Nom *</label>
                    <input type="text" wire:model="name" class="w-full px-3 py-2 border border-[#c5c5d4] rounded-lg bg-[#f9f9ff] focus:ring-2 focus:ring-primary">
                    @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Rôle / fonction</label>
                    <input type="text" wire:model="role" placeholder="Coach, Modérateur…" class="w-full px-3 py-2 border border-[#c5c5d4] rounded-lg bg-[#f9f9ff] focus:ring-2 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Organisation</label>
                    <input type="text" wire:model="company" class="w-full px-3 py-2 border border-[#c5c5d4] rounded-lg bg-[#f9f9ff] focus:ring-2 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Bio</label>
                    <textarea wire:model="bio" rows="3" class="w-full px-3 py-2 border border-[#c5c5d4] rounded-lg bg-[#f9f9ff] focus:ring-2 focus:ring-primary"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Photo</label>
                    <input type="file" wire:model="photo" accept="image/*" class="w-full text-sm">
                    @error('photo')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    @if($photo)
                        <img src="{{ $photo->temporaryUrl() }}" class="mt-2 h-16 w-16 rounded-full object-cover">
                    @elseif($photo_url)
                        <img src="{{ asset('storage/'.$photo_url) }}" class="mt-2 h-16 w-16 rounded-full object-cover">
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Ordre d’affichage</label>
                    <input type="number" wire:model="display_order" min="0" class="w-full px-3 py-2 border border-[#c5c5d4] rounded-lg bg-[#f9f9ff] focus:ring-2 focus:ring-primary">
                </div>
            </div>
            <div class="p-6 border-t border-[#c5c5d4] flex justify-end gap-3">
                <button @click="open = false" class="px-4 py-2 border border-[#c5c5d4] rounded-lg">Annuler</button>
                <button wire:click="save" class="px-4 py-2 admin-btn-primary rounded-lg font-medium">Enregistrer</button>
            </div>
        </div>
    </div>

    <div x-show="del" x-cloak style="display:none" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/60" @click="del = false"></div>
        <div class="relative adf-modal-panel bg-white rounded-lg shadow-xl w-full max-w-md border border-[#c5c5d4] p-6">
            <h3 class="text-lg font-bold mb-2">Supprimer cet intervenant ?</h3>
            <div class="flex justify-end gap-3 mt-6">
                <button @click="del = false" class="px-4 py-2 border border-[#c5c5d4] rounded-lg">Annuler</button>
                <button wire:click="delete" class="px-4 py-2 bg-red-600 text-white rounded-lg">Supprimer</button>
            </div>
        </div>
    </div>
</div>
