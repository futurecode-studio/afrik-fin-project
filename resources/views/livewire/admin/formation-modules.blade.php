<div x-data="{ open: @entangle('showModal').live, del: @entangle('showDeleteModal').live }">
    <main class="container mx-auto px-4 py-8">
        {{-- Breadcrumb --}}
        <nav class="mb-6 text-sm">
            <ol class="flex items-center space-x-2">
                <li><a href="{{ route('admin.formations') }}" class="text-primary hover:underline" wire:navigate.hover>Formations</a></li>
                <li><span class="text-muted-foreground">/</span></li>
                <li><a href="{{ route('admin.formations.show', $formation) }}" class="text-primary hover:underline" wire:navigate.hover>{{ $formation->titre }}</a></li>
                <li><span class="text-muted-foreground">/</span></li>
                <li class="font-medium">Modules</li>
            </ol>
        </nav>

        @include('livewire.admin.partials.formation-admin-nav', ['formation' => $formation])

        {{-- Message de succès --}}

        {{-- Info Formation --}}
        <div class="mb-6 rounded-lg border bg-card p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold">{{ $formation->titre }}</h2>
                    <p class="text-sm text-muted-foreground">
                        {{ $formation->is_free ? 'Gratuite' : number_format($formation->prix, 0, ',', ' ') . ' FCFA' }}
                        • {{ ucfirst($formation->niveau) }}
                        • {{ $formation->modules_count }} module(s)
                    </p>
                </div>
                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                    {{ $formation->statut === 'publie' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ ucfirst($formation->statut) }}
                </span>
            </div>
        </div>

        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="flex flex-col space-y-1.5 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-semibold leading-none tracking-tight">Modules de la formation</h3>
                        <p class="text-sm text-muted-foreground">{{ $modules->total() }} module(s)</p>
                    </div>
                    <button @click="open = true; $wire.openModal()" type="button"
                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium bg-primary text-primary-foreground hover:bg-primary/90 h-11 px-6 py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Ajouter un module
                    </button>
                </div>
            </div>

            <div class="p-6 pt-0">
                <div class="space-y-4">
                    @forelse($modules as $module)
                        <div class="rounded-lg border bg-background p-4 {{ !$module->is_active ? 'opacity-50' : '' }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    {{-- Ordre --}}
                                    <div class="flex flex-col gap-1">
                                        <button wire:click="updateOrder({{ $module->id }}, 'up')" 
                                            class="text-muted-foreground hover:text-foreground p-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                            </svg>
                                        </button>
                                        <span class="text-center text-sm font-bold text-primary">{{ $module->ordre }}</span>
                                        <button wire:click="updateOrder({{ $module->id }}, 'down')" 
                                            class="text-muted-foreground hover:text-foreground p-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                    </div>

                                    {{-- Info Module --}}
                                    <div>
                                        <h4 class="font-semibold">{{ $module->titre }}</h4>
                                        <p class="text-sm text-muted-foreground">
                                            {{ $module->lessons_count }} leçon(s)
                                            @if($module->duree_estimee)
                                                • {{ $module->duree_estimee }}
                                            @endif
                                            @if($module->quiz)
                                                • <span class="text-green-600">Quiz configuré</span>
                                            @else
                                                • <span class="text-orange-600">Pas de quiz</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                {{-- Actions --}}
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.formations.modules.lessons', ['formation' => $formation->id, 'module' => $module->id]) }}"
                                        class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-input bg-background hover:bg-accent h-9 px-3" wire:navigate.hover>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                        Leçons
                                    </a>
                                    <a href="{{ route('admin.formations.modules.quiz', ['formation' => $formation->id, 'module' => $module->id]) }}"
                                        class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-input bg-background hover:bg-accent h-9 px-3" wire:navigate.hover>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                        </svg>
                                        Quiz
                                    </a>
                                    <button wire:click="edit({{ $module->id }})" 
                                        class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-input bg-background hover:bg-accent h-9 px-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $module->id }})"
                                        class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-red-200 bg-white hover:bg-red-50 text-red-600 h-9 px-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            @if($module->description)
                                <p class="mt-2 text-sm text-muted-foreground pl-16">{{ plain_text($module->description, 150) }}</p>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-8 text-muted-foreground">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <p>Aucun module pour cette formation</p>
                            <button @click="open = true; $wire.openModal()" class="mt-4 text-primary hover:underline">
                                Créer le premier module
                            </button>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $modules->links() }}
                </div>
            </div>
        </div>
    </main>

    {{-- Modal Ajout/Modification Module --}}
    <div x-show="open" x-cloak style="display:none" class="fixed inset-0 z-[100] bg-black bg-opacity-80 flex items-center justify-center p-4" @click.self="open = false">
            <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto relative">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold text-gray-900">
                            {{ $editMode ? 'Modifier le module' : 'Ajouter un module' }}
                        </h2>
                        <button @click="open = false" class="text-gray-500 hover:text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <p class="text-sm text-gray-600 mb-6">Remplissez les informations du module</p>

                    <form wire:submit="save">
                        <div class="space-y-4">
                            {{-- Titre --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Titre <span class="text-red-500">*</span></label>
                                <input wire:model.live="titre" type="text"
                                    class="flex h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                    placeholder="Ex: Introduction à la finance">
                                @error('titre') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            {{-- Slug --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Slug (URL)</label>
                                <input wire:model="slug" type="text"
                                    class="flex h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                    placeholder="introduction-finance">
                                @error('slug') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            {{-- Description --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea wire:model="description" rows="3"
                                    class="flex w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                    placeholder="Description du module..."></textarea>
                                @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                {{-- Ordre --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Ordre</label>
                                    <input wire:model="ordre" type="number" min="0"
                                        class="flex h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                    @error('ordre') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                {{-- Durée estimée --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Durée estimée</label>
                                    <input wire:model="duree_estimee" type="text"
                                        class="flex h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                        placeholder="Ex: 2 heures">
                                    @error('duree_estimee') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            {{-- Actif --}}
                            <div class="flex items-center gap-2">
                                <input wire:model="is_active" type="checkbox" id="is_active"
                                    class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary">
                                <label for="is_active" class="text-sm font-medium text-gray-700">Module actif</label>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 mt-6">
                            <button type="button" @click="open = false"
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
    

    {{-- Modal Suppression --}}
    <div x-show="del" x-cloak style="display:none" class="fixed inset-0 z-[100] bg-black bg-opacity-80 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-2xl max-w-md w-full p-6 relative">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Confirmer la suppression</h3>
                <p class="text-gray-600 mb-6">
                    Êtes-vous sûr de vouloir supprimer ce module ? Cette action supprimera également toutes les leçons et le quiz associés.
                </p>
                <div class="flex justify-end gap-3">
                    <button @click="del = false"
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
    
</div>
