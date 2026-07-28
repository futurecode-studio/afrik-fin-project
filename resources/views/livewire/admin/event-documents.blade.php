<div x-data="{ open: @entangle('showModal').live, del: @entangle('showDeleteModal').live }">
    <div class="p-6 lg:p-8 space-y-6">
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-[#757683] mb-2 text-xs font-semibold tracking-wider uppercase flex-wrap">
                    <a href="{{ route('admin.events') }}" class="hover:text-[#001a61]" wire:navigate.hover>Événements</a>
                    <span class="material-symbols-outlined text-sm">chevron_right</span>
                    <span class="text-[#001a61]">{{ Str::limit($event->title, 40) }}</span>
                </nav>
                <h1 class="text-2xl font-extrabold text-[#001a61]">Documents</h1>
                <p class="text-sm text-[#444652] mt-1">{{ $event->title }}</p>
            </div>
            <button @click="open = true; $wire.openModal()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#001a61] text-white text-sm font-bold hover:bg-[#0a2e8c]">
                <span class="material-symbols-outlined text-[18px]">upload_file</span>
                Ajouter un document
            </button>
        </div>

        @include('livewire.admin.partials.event-admin-nav', ['event' => $event])

        <div class="admin-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="admin-table w-full text-sm">
                    <thead class="bg-[#eef3ff]">
                        <tr>
                            <th class="text-left p-4 font-semibold">Document</th>
                            <th class="text-left p-4 font-semibold">Type</th>
                            <th class="text-left p-4 font-semibold">Taille</th>
                            <th class="text-left p-4 font-semibold">Public</th>
                            <th class="text-right p-4 font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documents as $doc)
                        <tr class="border-t border-[#c5c5d4] hover:bg-[#f7f9ff]">
                            <td class="p-4">
                                <p class="font-semibold text-[#001a61]">{{ $doc->title }}</p>
                                <p class="text-[11px] text-[#757683] font-mono truncate max-w-xs">{{ $doc->file_path }}</p>
                            </td>
                            <td class="p-4 uppercase text-xs font-bold">{{ $doc->file_type }}</td>
                            <td class="p-4">{{ $doc->file_size > 0 ? number_format($doc->file_size / 1024, 0) . ' Ko' : '—' }}</td>
                            <td class="p-4">
                                <button wire:click="toggleDownloadable({{ $doc->id }})" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $doc->is_downloadable ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $doc->is_downloadable ? 'Téléchargeable' : 'Masqué' }}
                                </button>
                            </td>
                            <td class="p-4 text-right whitespace-nowrap">
                                @if($doc->file_path)
                                    <a href="{{ $doc->download_url }}" target="_blank" class="text-sm text-[#001a61] underline mr-2">Voir</a>
                                @endif
                                <button wire:click="edit({{ $doc->id }})" class="text-sm text-primary hover:underline mr-2">Modifier</button>
                                <button wire:click="confirmDelete({{ $doc->id }})" class="text-sm text-red-600 hover:underline">Supprimer</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-[#757683]">Aucun document. Ajoutez les PDF / fichiers affichés sur la page publique.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div x-show="open" x-cloak style="display:none" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/60" @click="open = false"></div>
        <div class="relative adf-modal-panel bg-white rounded-lg shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto border border-[#c5c5d4]">
            <div class="p-6 border-b border-[#c5c5d4] flex items-center justify-between">
                <h2 class="text-xl font-bold">{{ $editMode ? 'Modifier' : 'Ajouter' }} un document</h2>
                <button @click="open = false" class="text-[#757683]"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Titre *</label>
                    <input type="text" wire:model="title" placeholder="Règlement, Programme PDF…" class="w-full px-3 py-2 border border-[#c5c5d4] rounded-lg bg-[#f9f9ff] focus:ring-2 focus:ring-primary">
                    @error('title')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Fichier {{ $editMode ? '' : '*' }}</label>
                    <input type="file" wire:model="file" class="w-full text-sm">
                    <p class="text-[11px] text-[#757683] mt-1">PDF, Word, image… max 10 Mo</p>
                    @error('file')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    @if($editMode && $file_path && !$file)
                        <p class="text-xs text-[#001a61] mt-2">Fichier actuel : {{ basename($file_path) }}</p>
                    @endif
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Ordre</label>
                        <input type="number" wire:model="display_order" min="0" class="w-full px-3 py-2 border border-[#c5c5d4] rounded-lg bg-[#f9f9ff] focus:ring-2 focus:ring-primary">
                    </div>
                    <div class="flex items-end pb-2">
                        <label class="inline-flex items-center gap-2 text-sm font-medium">
                            <input type="checkbox" wire:model="is_downloadable" class="rounded border-[#c5c5d4]">
                            Visible / téléchargeable
                        </label>
                    </div>
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
            <h3 class="text-lg font-bold mb-2">Supprimer ce document ?</h3>
            <div class="flex justify-end gap-3 mt-6">
                <button @click="del = false" class="px-4 py-2 border border-[#c5c5d4] rounded-lg">Annuler</button>
                <button wire:click="delete" class="px-4 py-2 bg-red-600 text-white rounded-lg">Supprimer</button>
            </div>
        </div>
    </div>
</div>
