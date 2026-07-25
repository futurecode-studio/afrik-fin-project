 <div x-data="{ open: @entangle('showModal').live, del: @entangle('showDeleteModal').live }">
    <div class="admin-page space-y-6">
<div class="flex flex-wrap justify-between items-end gap-4 mb-2">
        <div>
            <nav class="flex items-center gap-2 text-[#757683] mb-2 text-xs font-semibold tracking-wider uppercase">
                <span>Administration</span>
                <span class="material-symbols-outlined text-sm">chevron_right</span>
                <span class="text-[#001a61]">FORMATIONS</span>
            </nav>
            <h2 class="text-3xl font-extrabold text-[#001a61] tracking-tight">Gestion des Formations</h2>
        </div>
    </div>

        {{-- Message de succès --}}

        <div class="admin-card">
            <div class="flex flex-col space-y-1.5 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-semibold leading-none tracking-tight">Liste des Formations</h3>
                        <p class="text-sm text-[#757683]">{{ $formations->total() }} formation(s) enregistrée(s)</p>
                    </div>
                    <button @click="open = true; $wire.openModal()" type="button"
                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 admin-btn-primary  h-11 px-6 py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="h-4 w-4 mr-2">
                            <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                            <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                            <path d="M12 18v-6"></path>
                            <path d="m9 15 3 3 3-3"></path>
                        </svg>
                        Créer une formation
                    </button>
                </div>

                {{-- Barre de recherche --}}
                <div class="mt-4">
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Rechercher par titre, niveau..."
                        class="flex h-10 w-full md:w-96 rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 py-2 text-base ring-offset-background placeholder:text-[#757683] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm">
                </div>
            </div>

            <div class="p-6 pt-0">
                <div class="relative w-full overflow-auto">
                    <table class="admin-table w-full caption-bottom text-sm">
                        <thead class="[&_tr]:border-b">
                            <tr class="border-b transition-colors hover:bg-[#f0f3ff]">
                                <th class="h-12 px-4 text-left align-middle font-medium text-[#757683]">Titre</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-[#757683]">Niveau</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-[#757683]">Modules</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-[#757683]">Prix</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-[#757683]">Statut</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-[#757683]">Date</th>
                                <th class="h-12 px-4 align-middle font-medium text-[#757683] text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="[&_tr:last-child]:border-0">
                            @forelse($formations as $formation)
                                <tr class="border-b transition-colors hover:bg-[#f0f3ff] {{ $formation->trashed() ? 'opacity-50' : '' }}">
                                    <td class="p-4 align-middle">
                                        <div class="font-medium">{{ $formation->titre }}</div>
                                        <div class="text-sm text-[#757683]">{{ Str::limit($formation->description_courte, 50) }}</div>
                                    </td>
                                    <td class="p-4 align-middle">
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                            {{ $formation->niveau === 'debutant' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $formation->niveau === 'intermediaire' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $formation->niveau === 'avance' ? 'bg-purple-100 text-purple-800' : '' }}">
                                            {{ ucfirst($formation->niveau) }}
                                        </span>
                                    </td>
                                    <td class="p-4 align-middle text-sm">
                                        <span class="inline-flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#757683]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                            </svg>
                                            {{ $formation->modules_count ?? 0 }}
                                        </span>
                                    </td>
                                    <td class="p-4 align-middle text-sm font-medium">
                                        @if($formation->is_free)
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-green-100 text-green-800">
                                                Gratuit
                                            </span>
                                        @else
                                            {{ number_format($formation->prix, 0, ',', ' ') }} XOF
                                        @endif
                                    </td>
                                    <td class="p-4 align-middle">
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                            {{ $formation->statut === 'brouillon' ? 'bg-gray-100 text-gray-800' : '' }}
                                            {{ $formation->statut === 'publie' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $formation->statut === 'archive' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                            {{ ucfirst($formation->statut) }}
                                        </span>
                                    </td>
                                    <td class="p-4 align-middle text-sm text-[#757683]">{{ $formation->created_at->format('d/m/Y') }}</td>
                                    <td class="p-4 align-middle text-right">
                                        <div class="flex justify-end gap-2">
                                            @if(!$formation->trashed())
                                                <a href="{{ route('admin.formations.show', $formation->id) }}"
                                                    class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium border border-[#c5c5d4] bg-[#f9f9ff] hover:bg-[#e7eeff] text-[#001a61] h-9 px-3"
                                                    title="Stats & vue d’ensemble" wire:navigate.hover>
                                                    <span class="material-symbols-outlined text-[18px]">analytics</span>
                                                    Stats
                                                </a>
                                                <a href="{{ route('admin.formations.modules', $formation->id) }}"
                                                    class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium border border-primary bg-primary/10 hover:bg-primary/20 text-primary h-9 px-3"
                                                    title="Gérer les modules" wire:navigate.hover>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                                    </svg>
                                                    Modules
                                                </a>
                                                <a href="{{ route('admin.formations.learners', $formation->id) }}"
                                                    class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium border border-[#c5c5d4] bg-[#f9f9ff] hover:bg-[#e7eeff] text-[#001a61] h-9 px-3"
                                                    title="Apprenants" wire:navigate.hover>
                                                    <span class="material-symbols-outlined text-[18px]">groups</span>
                                                </a>
                                                <button wire:click="edit({{ $formation->id }})" 
                                                    class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-[#c5c5d4] bg-[#f9f9ff] hover:bg-accent hover:text-accent-foreground h-9 px-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                                                        <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path>
                                                        <path d="m15 5 4 4"></path>
                                                    </svg>
                                                </button>
                                                <button wire:click="confirmDelete({{ $formation->id }})"
                                                    class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-red-200 bg-white hover:bg-red-50 text-red-600 h-9 px-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                                                        <path d="M3 6h18"></path>
                                                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                                    </svg>
                                                </button>
                                            @else
                                                <button wire:click="restore({{ $formation->id }})"
                                                    class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-green-200 bg-white hover:bg-green-50 text-green-600 h-9 px-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                                                        <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"></path>
                                                        <path d="M21 3v5h-5"></path>
                                                        <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"></path>
                                                        <path d="M3 21v-5h5"></path>
                                                    </svg>
                                                    Restaurer
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-4 text-center text-[#757683]">
                                        Aucune formation trouvée
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $formations->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Ajout/Modification --}}
    <div x-show="open" x-cloak style="display:none" class="fixed inset-0 z-[100] bg-black bg-opacity-80 flex items-center justify-center p-4" @click.self="open = false">
            <div class="bg-white rounded-lg shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto relative">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold">
                            {{ $editMode ? 'Modifier la formation' : 'Créer une formation' }}
                        </h2>
                        <button @click="open = false" class="text-gray-500 hover:text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18"/>
                                <path d="m6 6 12 12"/>
                            </svg>
                        </button>
                    </div>
                    <p class="text-sm text-gray-600 mb-6">Remplissez les informations de la formation</p>

                    <form wire:submit="save">
                        <div class="space-y-4">
                            {{-- Titre --}}
                            <div>
                                <label class="block text-sm font-medium mb-2">Titre <span class="text-red-500">*</span></label>
                                <input wire:model.live="titre" type="text"
                                    class="flex h-10 w-full rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 py-2 text-base ring-offset-background placeholder:text-[#757683] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm">
                                @error('titre') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            {{-- Slug (URL) --}}
                            <div>
                                <label class="block text-sm font-medium mb-2">Slug (URL)</label>
                                <input wire:model="slug" type="text"
                                    class="flex h-10 w-full rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 py-2 text-base ring-offset-background placeholder:text-[#757683] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                                    placeholder="genere-automatiquement">
                                @error('slug') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            {{-- Description courte avec éditeur riche --}}
                            <div
                                wire:ignore
                                x-data
                                x-init="
                                    let quillDescCourte;

                                    const initQuillDescCourte = () => {
                                        if (quillDescCourte) {
                                            quillDescCourte = null;
                                        }

                                        quillDescCourte = new Quill('#editor-desc-courte', {
                                            theme: 'snow',
                                            modules: {
                                                toolbar: [
                                                    ['bold', 'italic', 'underline'],
                                                    [{ 'list': 'ordered'}, { 'list': 'bullet' }]
                                                ]
                                            },
                                            placeholder: 'Saisissez une courte description...'
                                        });

                                        quillDescCourte.root.innerHTML = @js($description_courte);

                                        quillDescCourte.on('text-change', function() {
                                            @this.set('description_courte', quillDescCourte.root.innerHTML);
                                        });
                                    };

                                    initQuillDescCourte();

                                    Livewire.on('formation-saved', () => {
                                        initQuillDescCourte();
                                    });
                                "
                            >
                                <label class="block text-sm font-medium mb-2">Description courte</label>

                                {{-- Zone d'édition Quill pour la description courte --}}
                                <div
                                    id="editor-desc-courte"
                                    class="flex w-full rounded-md border border-[#c5c5d4] bg-[#f9f9ff] text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm min-h-[100px]"
                                ></div>

                                <textarea
                                    wire:model="description_courte"
                                    class="hidden"
                                ></textarea>

                                @error('description_courte') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            {{-- Description complète avec éditeur riche --}}
                            <div
                                wire:ignore
                                x-data
                                x-init="
                                    let quillDescComplete;

                                    const initQuillDescComplete = () => {
                                        if (quillDescComplete) {
                                            quillDescComplete = null;
                                        }

                                        quillDescComplete = new Quill('#editor-desc-complete', {
                                            theme: 'snow',
                                            modules: {
                                                toolbar: [
                                                    [{ 'header': [1, 2, 3, false] }],
                                                    ['bold', 'italic', 'underline', 'strike'],
                                                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                                                    ['link'],
                                                    [{ 'align': [] }],
                                                    ['clean']
                                                ]
                                            },
                                            placeholder: 'Saisissez la description complète de la formation...'
                                        });

                                        quillDescComplete.root.innerHTML = @js($description_complete);

                                        quillDescComplete.on('text-change', function() {
                                            @this.set('description_complete', quillDescComplete.root.innerHTML);
                                        });
                                    };

                                    initQuillDescComplete();

                                    Livewire.on('formation-saved', () => {
                                        initQuillDescComplete();
                                    });
                                "
                            >
                                <label class="block text-sm font-medium mb-2">Description complète <span class="text-red-500">*</span></label>

                                {{-- Zone d'édition Quill --}}
                                <div
                                    id="editor-desc-complete"
                                    class="flex w-full rounded-md border border-[#c5c5d4] bg-[#f9f9ff] text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm min-h-[200px]"
                                ></div>

                                <textarea
                                    wire:model="description_complete"
                                    class="hidden"
                                ></textarea>

                                @error('description_complete') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            {{-- Image (upload de fichier) --}}
                            <div>
                                <label class="block text-sm font-medium mb-2">Image de la formation</label>
                                <input
                                    type="file"
                                    wire:model="image"
                                    accept="image/*"
                                    class="flex h-10 w-full rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 py-2 text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                                >

                                {{-- Prévisualisation si une image existe déjà (édition) --}}
                                @if($editMode && $image_url)
                                    <p class="text-xs text-gray-500 mt-2">Image actuelle :</p>
                                    <img src="{{ $image_url }}" alt="Image actuelle" class="mt-1 h-20 rounded-md object-cover border">
                                

                                @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            {{-- URL de l'image (alternative) --}}
                            <div>
                                <label class="block text-sm font-medium mb-2">OU URL de l'image</label>
                                <input wire:model="image_url" type="text"
                                    class="flex h-10 w-full rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 py-2 text-base ring-offset-background placeholder:text-[#757683] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                                    placeholder="https://example.com/image.jpg">
                                @error('image_url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            {{-- Niveau et Durée --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2">Niveau <span class="text-red-500">*</span></label>
                                    <select wire:model="niveau"
                                        class="flex h-10 w-full rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 py-2 text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm">
                                        <option value="debutant">Débutant</option>
                                        <option value="intermediaire">Intermédiaire</option>
                                        <option value="avance">Avancé</option>
                                    </select>
                                    @error('niveau') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Durée</label>
                                    <input wire:model="duree" type="text"
                                        class="flex h-10 w-full rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 py-2 text-base ring-offset-background placeholder:text-[#757683] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                                        placeholder="ex: 8 semaines">
                                    @error('duree') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            {{-- Tarification --}}
                            <div class="p-4 rounded-lg border bg-[#eef3ff]/30">
                                <label class="block text-sm font-medium mb-3">Tarification</label>
                                <div class="flex items-center gap-4 mb-3">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input wire:model.live="is_free" type="checkbox" 
                                            class="h-5 w-5 rounded border-gray-300 text-primary focus:ring-primary">
                                        <span class="text-sm font-medium">Formation gratuite</span>
                                    </label>
                                </div>
                                @if(!$is_free)
                                <div>
                                    <label class="block text-sm font-medium mb-2">Prix (XOF) <span class="text-red-500">*</span></label>
                                    <input wire:model="prix" type="number" min="0" step="1"
                                        class="flex h-10 w-full rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 py-2 text-base ring-offset-background placeholder:text-[#757683] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                                        placeholder="0">
                                    @error('prix') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                @else
                                <p class="text-sm text-green-600">Cette formation sera accessible gratuitement.</p>
                                @endif
                            </div>

                            {{-- Statut --}}
                            <div>
                                <label class="block text-sm font-medium mb-2">Statut <span class="text-red-500">*</span></label>
                                <select wire:model="statut"
                                    class="flex h-10 w-full rounded-md border border-[#c5c5d4] bg-[#f9f9ff] px-3 py-2 text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm">
                                    <option value="brouillon">Brouillon</option>
                                    <option value="publie">Publié</option>
                                    <option value="archive">Archivé</option>
                                </select>
                                @error('statut') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 mt-6">
                            <button type="button" @click="open = false"
                                class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-[#c5c5d4] bg-[#f9f9ff] hover:bg-accent hover:text-accent-foreground h-11 px-6 py-3">
                                Annuler
                            </button>
                            <button type="submit"
                                class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 admin-btn-primary  h-11 px-6 py-3">
                                {{ $editMode ? 'Modifier' : 'Créer' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    {{-- Modal Confirmation Suppression --}}
    <div x-show="del" x-cloak style="display:none" class="fixed inset-0 z-[100] bg-black bg-opacity-80 flex items-center justify-center p-4" @click.self="del = false">
            <div class="bg-white rounded-lg shadow-2xl max-w-md w-full p-6 relative">
                <h2 class="text-xl font-bold mb-4">Confirmer la suppression</h2>
                <p class="text-gray-600 mb-6">Êtes-vous sûr de vouloir supprimer cette formation ? Cette action peut être annulée en restaurant la formation.</p>
                
                <div class="flex justify-end gap-3">
                    <button @click="del = false"
                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-[#c5c5d4] bg-[#f9f9ff] hover:bg-accent hover:text-accent-foreground h-11 px-6 py-3">
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

@script
<script>
    $wire.on('formation-saved', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
</script>
@endscript