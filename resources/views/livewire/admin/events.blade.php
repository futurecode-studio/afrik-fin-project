<div>
    <div class="container mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Événements</h1>
            <button wire:click="openModal" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary-light transition-colors text-sm font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                Nouvel événement
            </button>
        </div>

        @if (session()->has('message'))
            <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800 border border-green-200">{{ session('message') }}</div>
        @endif

        <div class="mb-4 flex items-center gap-4">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Rechercher un événement..." class="w-full max-w-md px-4 py-2 border border-border rounded-lg bg-background text-foreground focus:ring-2 focus:ring-primary">
        </div>

        <div class="rounded-lg border bg-card text-card-foreground shadow-sm overflow-visible">
            <div class="overflow-visible">
                <table class="w-full text-sm">
                    <thead class="bg-muted">
                        <tr>
                            <th class="text-left p-4 font-semibold">Événement</th>
                            <th class="text-left p-4 font-semibold">Date</th>
                            <th class="text-left p-4 font-semibold">Lieu</th>
                            <th class="text-left p-4 font-semibold">Statut</th>
                            <th class="text-left p-4 font-semibold">Inscrits</th>
                            <th class="text-right p-4 font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($events as $event)
                        <tr class="border-t border-border hover:bg-muted/50 transition-colors">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    @if($event->featured_image)
                                        <img src="{{ asset('storage/'.$event->featured_image) }}" alt="" class="w-10 h-10 rounded-lg object-cover">
                                    @else
                                        <div class="w-10 h-10 rounded-lg bg-muted flex items-center justify-center text-muted-foreground text-xs">Aucune</div>
                                    @endif
                                    <div>
                                        <p class="font-semibold">{{ $event->title }}</p>
                                        <p class="text-xs text-muted-foreground">{{ $event->category }}</p>
</div>
</div>

@push('scripts')
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
@endpush
                            </td>
                            <td class="p-4 whitespace-nowrap">
                                {{ $event->starts_at?->format('d/m/Y H:i') }}
                            </td>
                            <td class="p-4">{{ $event->city ?? $event->location_name ?? '-' }}</td>
                            <td class="p-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $event->statusColorClasses() }}">
                                    {{ $event->statusLabel() }}
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="font-semibold">{{ $event->registrations_count }}</span>
                                @if($event->capacity > 0)
                                    <span class="text-muted-foreground text-xs"> / {{ $event->capacity }}</span>
                                @endif
                            </td>
                            <td class="p-4 text-right">
                                <div class="relative inline-block text-left" x-data="{ open: false }" @scroll.window="open = false">
                                    <button @click="open = !open" @click.away="open = false" class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-foreground bg-card border border-border rounded-lg hover:bg-muted transition-colors">
                                        Actions
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                                    </button>
                                    <div x-show="open" x-transition
                                         class="absolute right-0 z-50 mt-1 w-48 rounded-lg border border-border bg-card shadow-lg overflow-hidden"
                                         style="display: none;">
                                        <a href="{{ route('admin.event.registrations', $event) }}" @click="open = false" class="block w-full text-left px-4 py-2 text-sm text-foreground hover:bg-muted transition-colors">
                                            <span class="inline-flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                                Inscrits
                                            </span>
                                        </a>
                                        <a href="{{ route('admin.event.checkin', $event) }}" @click="open = false" class="block w-full text-left px-4 py-2 text-sm text-foreground hover:bg-muted transition-colors">
                                            <span class="inline-flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="14" x="3" y="3" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                                                Émargement
                                            </span>
                                        </a>
                                        <div class="border-t border-border"></div>
                                        <button wire:click="edit({{ $event->id }})" @click="open = false" class="w-full text-left block px-4 py-2 text-sm text-primary hover:bg-muted transition-colors">
                                            <span class="inline-flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                                Modifier
                                            </span>
                                        </button>
                                        <button wire:click="duplicate({{ $event->id }})" @click="open = false" class="w-full text-left block px-4 py-2 text-sm text-muted-foreground hover:text-foreground hover:bg-muted transition-colors">
                                            <span class="inline-flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                                                Dupliquer
                                            </span>
                                        </button>
                                        @if($event->trashed())
                                            <button wire:click="restore({{ $event->id }})" @click="open = false" class="w-full text-left block px-4 py-2 text-sm text-emerald-600 hover:bg-emerald-50 transition-colors">
                                                <span class="inline-flex items-center gap-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                                                    Restaurer
                                                </span>
                                            </button>
                                        @else
                                            <button wire:click="confirmDelete({{ $event->id }})" @click="open = false" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                                <span class="inline-flex items-center gap-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                                    Supprimer
                                                </span>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="p-8 text-center text-muted-foreground">Aucun événement trouvé.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">{{ $events->links() }}</div>
    </div>

    <!-- Modal Event -->
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-data="{ open: true }">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>
        <div class="relative bg-card rounded-lg shadow-xl w-full max-w-3xl max-h-[90vh] overflow-y-auto border border-border">
            <div class="p-6 border-b border-border flex items-center justify-between">
                <h2 class="text-xl font-bold">{{ $editMode ? 'Modifier' : 'Créer' }} un événement</h2>
                <button wire:click="closeModal" class="text-muted-foreground hover:text-foreground"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1">Titre *</label>
                        <input type="text" wire:model="title" class="w-full px-3 py-2 border border-border rounded-lg bg-background focus:ring-2 focus:ring-primary">
                        @error('title')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Slug</label>
                        <input type="text" wire:model="slug" class="w-full px-3 py-2 border border-border rounded-lg bg-background focus:ring-2 focus:ring-primary">
                        @error('slug')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Catégorie</label>
                        <input type="text" wire:model="category" class="w-full px-3 py-2 border border-border rounded-lg bg-background focus:ring-2 focus:ring-primary">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1">Description courte</label>
                        <textarea wire:model="description" rows="3" class="w-full px-3 py-2 border border-border rounded-lg bg-background focus:ring-2 focus:ring-primary"></textarea>
                    </div>
                    <div class="md:col-span-2" wire:ignore x-data="{
                        editor: null,
                        initEditor() {
                            if (this.editor) { tinymce.remove(this.editor); this.editor = null; }
                            tinymce.init({
                                selector: '#event-content-editor',
                                plugins: 'link lists table code',
                                toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | link table | code',
                                height: 300,
                                menubar: false,
                                setup: (ed) => {
                                    ed.on('change input', () => {
                                        this.$wire.set('content', ed.getContent());
                                    });
                                }
                            }).then((editors) => {
                                this.editor = editors[0];
                                this.editor.setContent(this.$wire.content || '');
                            });
                        }
                    }" x-init="initEditor()" @event-modal-opened.window="initEditor()" @event-modal-closed.window="if (editor) { tinymce.remove(editor); editor = null; }">
                        <label class="block text-sm font-medium mb-1">Description détaillée</label>
                        <textarea id="event-content-editor" class="w-full"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Type</label>
                        <select wire:model="event_type" class="w-full px-3 py-2 border border-border rounded-lg bg-background focus:ring-2 focus:ring-primary">
                            <option value="physical">Présentiel</option>
                            <option value="online">En ligne</option>
                            <option value="hybrid">Hybride</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Capacité (0 = illimité)</label>
                        <input type="number" wire:model="capacity" min="0" class="w-full px-3 py-2 border border-border rounded-lg bg-background focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Date de début *</label>
                        <input type="datetime-local" wire:model="starts_at" class="w-full px-3 py-2 border border-border rounded-lg bg-background focus:ring-2 focus:ring-primary">
                        @error('starts_at')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Date de fin</label>
                        <input type="datetime-local" wire:model="ends_at" class="w-full px-3 py-2 border border-border rounded-lg bg-background focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Ouverture inscriptions</label>
                        <input type="datetime-local" wire:model="registration_opens_at" class="w-full px-3 py-2 border border-border rounded-lg bg-background focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Fermeture inscriptions</label>
                        <input type="datetime-local" wire:model="registration_closes_at" class="w-full px-3 py-2 border border-border rounded-lg bg-background focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Lieu</label>
                        <input type="text" wire:model="location_name" placeholder="Nom du lieu" class="w-full px-3 py-2 border border-border rounded-lg bg-background focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Adresse</label>
                        <input type="text" wire:model="location_address" class="w-full px-3 py-2 border border-border rounded-lg bg-background focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Ville</label>
                        <input type="text" wire:model="city" class="w-full px-3 py-2 border border-border rounded-lg bg-background focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Pays</label>
                        <input type="text" wire:model="country" class="w-full px-3 py-2 border border-border rounded-lg bg-background focus:ring-2 focus:ring-primary">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1">Image mise en avant</label>
                        <input type="file" wire:model="featured_image" accept="image/*" class="w-full text-sm">
                        @if($featured_image_url)
                            <img src="{{ asset('storage/'.$featured_image_url) }}" class="mt-2 h-20 rounded-lg object-cover">
                        @endif
                    </div>

                    <!-- Galerie photos -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1">Galerie photos</label>
                        <input type="file" wire:model="galleryImages" multiple accept="image/*" class="w-full text-sm">
                        @error('galleryImages.*')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror

                        <!-- Nouvelles images sélectionnées -->
                        @if($galleryImages)
                            <div class="flex flex-wrap gap-2 mt-2">
                                @foreach($galleryImages as $img)
                                    <div class="w-20 h-20 rounded-lg overflow-hidden border border-border">
                                        <img src="{{ $img->temporaryUrl() }}" class="w-full h-full object-cover">
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Images existantes -->
                        @if($existingGallery)
                            <div class="flex flex-wrap gap-2 mt-3">
                                @foreach($existingGallery as $g)
                                    <div class="relative w-20 h-20 rounded-lg overflow-hidden border border-border group">
                                        <img src="{{ asset('storage/'.$g['image_path']) }}" class="w-full h-full object-cover">
                                        <button wire:click="removeGalleryImage({{ $g['id'] }})" class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity text-white text-xs">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Titre SEO</label>
                        <input type="text" wire:model="seo_title" class="w-full px-3 py-2 border border-border rounded-lg bg-background focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Description SEO</label>
                        <input type="text" wire:model="seo_description" class="w-full px-3 py-2 border border-border rounded-lg bg-background focus:ring-2 focus:ring-primary">
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="checkbox" wire:model="is_featured" id="is_featured" class="rounded border-border">
                        <label for="is_featured" class="text-sm font-medium">Événement mis en avant</label>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Statut</label>
                        <select wire:model="status" class="w-full px-3 py-2 border border-border rounded-lg bg-background focus:ring-2 focus:ring-primary">
                            <option value="draft">Brouillon</option>
                            <option value="published">Publié</option>
                            <option value="ongoing">En cours</option>
                            <option value="completed">Terminé</option>
                            <option value="cancelled">Annulé</option>
                            <option value="archived">Archivé</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="p-6 border-t border-border flex justify-end gap-3">
                <button wire:click="closeModal" class="px-4 py-2 border border-border rounded-lg text-foreground hover:bg-muted transition-colors">Annuler</button>
                <button wire:click="save" class="px-4 py-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary-light transition-colors font-medium">Enregistrer</button>
            </div>
        </div>
    </div>
    @endif

    @if($showDeleteModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="$set('showDeleteModal', false)"></div>
        <div class="relative bg-card rounded-lg shadow-xl w-full max-w-md border border-border p-6">
            <h3 class="text-lg font-bold mb-2">Confirmer la suppression</h3>
            <p class="text-sm text-muted-foreground mb-6">Cet événement sera mis à la corbeille. Les inscriptions et commandes associées restent en base.</p>
            <div class="flex justify-end gap-3">
                <button wire:click="$set('showDeleteModal', false)" class="px-4 py-2 border border-border rounded-lg text-foreground hover:bg-muted">Annuler</button>
                <button wire:click="delete" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Supprimer</button>
            </div>
        </div>
    </div>
    @endif
</div>
