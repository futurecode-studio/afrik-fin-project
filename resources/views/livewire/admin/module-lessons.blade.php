<div>
    <main class="container mx-auto px-4 py-8">
        {{-- Breadcrumb --}}
        <nav class="mb-6 text-sm">
            <ol class="flex items-center space-x-2">
                <li><a href="{{ route('admin.formations') }}" class="text-primary hover:underline">Formations</a></li>
                <li><span class="text-muted-foreground">/</span></li>
                <li><a href="{{ route('admin.formations.modules', $module->formation_id) }}" class="text-primary hover:underline">{{ $module->formation->titre }}</a></li>
                <li><span class="text-muted-foreground">/</span></li>
                <li class="text-muted-foreground">{{ $module->titre }}</li>
                <li><span class="text-muted-foreground">/</span></li>
                <li class="font-medium">Leçons</li>
            </ol>
        </nav>

        {{-- Message de succès --}}
        @if (session()->has('message'))
            <div class="mb-4 rounded-lg bg-green-50 p-4 text-green-800 border border-green-200">
                {{ session('message') }}
            </div>
        @endif

        {{-- Info Module --}}
        <div class="mb-6 rounded-lg border bg-card p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold">{{ $module->titre }}</h2>
                    <p class="text-sm text-muted-foreground">
                        Module {{ $module->ordre }} • {{ $module->lessons_count ?? 0 }} leçon(s)
                        @if($module->duree_estimee) • {{ $module->duree_estimee }} @endif
                    </p>
                </div>
                <a href="{{ route('admin.formations.modules.quiz', ['formation' => $module->formation_id, 'module' => $module->id]) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 border border-input rounded-md hover:bg-accent">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    Gérer le Quiz
                </a>
            </div>
        </div>

        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="flex flex-col space-y-1.5 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-semibold leading-none tracking-tight">Leçons du module</h3>
                        <p class="text-sm text-muted-foreground">{{ $lessons->total() }} leçon(s)</p>
                    </div>
                    <button wire:click="openModal" type="button"
                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium bg-primary text-primary-foreground hover:bg-primary/90 h-11 px-6 py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Ajouter une leçon
                    </button>
                </div>
            </div>

            <div class="p-6 pt-0">
                <div class="space-y-4">
                    @forelse($lessons as $lesson)
                        <div class="rounded-lg border bg-background p-4 {{ !$lesson->is_active ? 'opacity-50' : '' }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    {{-- Ordre --}}
                                    <div class="flex flex-col gap-1">
                                        <button wire:click="updateOrder({{ $lesson->id }}, 'up')" 
                                            class="text-muted-foreground hover:text-foreground p-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                            </svg>
                                        </button>
                                        <span class="text-center text-sm font-bold text-primary">{{ $lesson->ordre }}</span>
                                        <button wire:click="updateOrder({{ $lesson->id }}, 'down')" 
                                            class="text-muted-foreground hover:text-foreground p-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                    </div>

                                    {{-- Type Icon --}}
                                    <div class="flex-shrink-0">
                                        @if($lesson->type === 'video')
                                            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                        @elseif($lesson->type === 'mixte')
                                            <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                                </svg>
                                            </div>
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Info Leçon --}}
                                    <div>
                                        <h4 class="font-semibold">{{ $lesson->titre }}</h4>
                                        <p class="text-sm text-muted-foreground">
                                            {{ ucfirst($lesson->type) }}
                                            @if($lesson->duree_estimee) • {{ $lesson->duree_estimee }} @endif
                                            @if($lesson->video_url) • <span class="text-red-600">Vidéo</span> @endif
                                        </p>
                                    </div>
                                </div>

                                {{-- Actions --}}
                                <div class="flex items-center gap-2">
                                    <button wire:click="edit({{ $lesson->id }})" 
                                        class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-input bg-background hover:bg-accent h-9 px-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $lesson->id }})"
                                        class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-red-200 bg-white hover:bg-red-50 text-red-600 h-9 px-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            @if($lesson->description)
                                <p class="mt-2 text-sm text-muted-foreground pl-20">{{ Str::limit($lesson->description, 150) }}</p>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-8 text-muted-foreground">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <p>Aucune leçon dans ce module</p>
                            <button wire:click="openModal" class="mt-4 text-primary hover:underline">
                                Créer la première leçon
                            </button>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $lessons->links() }}
                </div>
            </div>
        </div>
    </main>

    {{-- Modal Ajout/Modification Leçon --}}
    @if($showModal)
        <div class="fixed inset-0 z-[100] bg-black bg-opacity-80 flex items-center justify-center p-4" wire:click.self="closeModal">
            <div class="bg-white rounded-lg shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto relative">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold text-gray-900">
                            {{ $editMode ? 'Modifier la leçon' : 'Ajouter une leçon' }}
                        </h2>
                        <button wire:click="closeModal" class="text-gray-500 hover:text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <p class="text-sm text-gray-600 mb-6">Remplissez les informations de la leçon</p>

                    <form wire:submit="save">
                        <div class="space-y-4">
                            {{-- Titre --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Titre <span class="text-red-500">*</span></label>
                                <input wire:model.live="titre" type="text"
                                    class="flex h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                    placeholder="Ex: Introduction aux marchés financiers">
                                @error('titre') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                {{-- Type --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Type de contenu</label>
                                    <select wire:model.live="type"
                                        class="flex h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                        <option value="texte">Texte</option>
                                        <option value="video">Vidéo</option>
                                        <option value="mixte">Mixte (Texte + Vidéo)</option>
                                    </select>
                                </div>

                                {{-- Durée estimée --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Durée estimée</label>
                                    <input wire:model="duree_estimee" type="text"
                                        class="flex h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                        placeholder="Ex: 15 min">
                                </div>
                            </div>

                            {{-- Description --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea wire:model="description" rows="2"
                                    class="flex w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                    placeholder="Brève description de la leçon..."></textarea>
                            </div>

                            {{-- URL Vidéo --}}
                            @if($type === 'video' || $type === 'mixte')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">URL de la vidéo</label>
                                <input wire:model="video_url" type="url"
                                    class="flex h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                    placeholder="https://www.youtube.com/watch?v=...">
                                @error('video_url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            @endif

                            {{-- Contenu --}}
                            @if($type === 'texte' || $type === 'mixte')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Contenu de la leçon</label>
                                <textarea wire:model="contenu" rows="10"
                                    class="flex w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 font-mono placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                    placeholder="Contenu HTML de la leçon..."></textarea>
                                <p class="text-xs text-gray-500 mt-1">Vous pouvez utiliser du HTML pour formater le contenu.</p>
                            </div>
                            @endif

                            <div class="grid grid-cols-2 gap-4">
                                {{-- Ordre --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Ordre</label>
                                    <input wire:model="ordre" type="number" min="0"
                                        class="flex h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                </div>

                                {{-- Actif --}}
                                <div class="flex items-end">
                                    <label class="flex items-center gap-2">
                                        <input wire:model="is_active" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary">
                                        <span class="text-sm font-medium text-gray-700">Leçon active</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 mt-6">
                            <button type="button" wire:click="closeModal"
                                class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Annuler
                            </button>
                            <button type="submit"
                                class="inline-flex items-center justify-center px-4 py-2 bg-primary text-white rounded-md text-sm font-medium hover:bg-primary/90">
                                {{ $editMode ? 'Modifier' : 'Créer' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Suppression --}}
    @if($showDeleteModal)
        <div class="fixed inset-0 z-[100] bg-black bg-opacity-80 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-2xl max-w-md w-full p-6 relative">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Confirmer la suppression</h3>
                <p class="text-gray-600 mb-6">
                    Êtes-vous sûr de vouloir supprimer cette leçon ? Cette action est irréversible.
                </p>
                <div class="flex justify-end gap-3">
                    <button wire:click="$set('showDeleteModal', false)"
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Annuler
                    </button>
                    <button wire:click="delete"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Supprimer
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
