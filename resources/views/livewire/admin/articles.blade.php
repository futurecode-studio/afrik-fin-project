 <div>
    {{-- Indicateur de chargement Livewire --}}
    <div wire:loading class="fixed top-0 left-0 right-0 bg-blue-500 text-white text-center py-2 z-50">
        Chargement en cours...
    </div>

    <main class="container mx-auto px-4 py-8">
        {{-- Message de succès --}}
        @if (session()->has('message'))
            <div class="mb-4 rounded-lg bg-green-50 p-4 text-green-800 border border-green-200">
                {{ session('message') }}
            </div>
        @endif

        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="flex flex-col space-y-1.5 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-semibold leading-none tracking-tight">Liste des Articles</h3>
                        <p class="text-sm text-muted-foreground">{{ $articles->total() }} article(s) enregistré(s)</p>
                    </div>
                    <button wire:click="openModal" type="button"
                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary-light shadow-elegant hover:shadow-glow transition-smooth h-11 px-6 py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="h-4 w-4 mr-2">
                            <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                            <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                            <path d="M12 18v-6"></path>
                            <path d="m9 15 3 3 3-3"></path>
                        </svg>
                        Créer un article
                    </button>
                </div>

                {{-- Barre de recherche --}}
                <div class="mt-4">
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Rechercher par titre, catégorie..."
                        class="flex h-10 w-full md:w-96 rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm">
                </div>
            </div>

            <div class="p-6 pt-0">
                <div class="relative w-full overflow-auto">
                    <table class="w-full caption-bottom text-sm">
                        <thead class="[&_tr]:border-b">
                            <tr class="border-b transition-colors hover:bg-muted/50">
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Titre</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Catégorie</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Statut</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Auteur</th>
                                <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Date</th>
                                <th class="h-12 px-4 align-middle font-medium text-muted-foreground text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="[&_tr:last-child]:border-0">
                            @forelse($articles as $article)
                                <tr class="border-b transition-colors hover:bg-muted/50 {{ $article->trashed() ? 'opacity-50' : '' }}">
                                    <td class="p-4 align-middle">
                                        <div class="font-medium">{{ Str::limit($article->titre, 50) }}</div>
                                        @if($article->trashed())
                                            <span class="text-xs text-red-500">(Supprimé)</span>
                                        @endif
                                    </td>
                                    <td class="p-4 align-middle">
                                        @if($article->categorie)
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold bg-blue-100 text-blue-800">
                                                {{ $article->categorie }}
                                            </span>
                                        @else
                                            <span class="text-muted-foreground">-</span>
                                        @endif
                                    </td>
                                    <td class="p-4 align-middle">
                                        @if($article->statut === 'publie')
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold bg-green-100 text-green-800">Publié</span>
                                        @elseif($article->statut === 'brouillon')
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold bg-gray-100 text-gray-800">Brouillon</span>
                                        @else
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold bg-yellow-100 text-yellow-800">Archivé</span>
                                        @endif
                                    </td>
                                    <td class="p-4 align-middle text-sm text-muted-foreground">{{ $article->user->name }}</td>
                                    <td class="p-4 align-middle text-sm text-muted-foreground">{{ $article->created_at->format('d/m/Y') }}</td>
                                    <td class="p-4 align-middle text-right">
                                        <div class="flex justify-end gap-2">
                                            @if($article->trashed())
                                                <button type="button" wire:click="restore({{ $article->id }})"
                                                    class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-3"
                                                    title="Restaurer">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/>
                                                        <path d="M21 3v5h-5"/>
                                                        <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/>
                                                        <path d="M3 21v-5h5"/>
                                                    </svg>
                                                </button>
                                            @else
                                                <button type="button" wire:click="edit({{ $article->id }})"
                                                    class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-3"
                                                    title="Modifier">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/>
                                                        <path d="m15 5 4 4"/>
                                                    </svg>
                                                </button>
                                                <button type="button" wire:click="confirmDelete({{ $article->id }})"
                                                    class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-red-200 bg-background hover:bg-red-50 text-red-600 h-9 px-3"
                                                    title="Supprimer">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M3 6h18"/>
                                                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                                                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-4 text-center text-muted-foreground">
                                        Aucun article trouvé
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $articles->links() }}
                </div>
            </div>
        </div>
    </main>

    {{-- Modal Ajout/Modification --}}
    @if($showModal)
        <div class="fixed inset-0 z-[100] bg-black bg-opacity-80 flex items-center justify-center p-4" wire:click.self="closeModal">
            <div class="bg-white rounded-lg shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto relative">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold">
                            {{ $editMode ? 'Modifier l\'article' : 'Créer un article' }}
                        </h2>
                        <button wire:click="closeModal" class="text-gray-500 hover:text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18"/>
                                <path d="m6 6 12 12"/>
                            </svg>
                        </button>
                    </div>
                    <p class="text-sm text-gray-600 mb-6">Remplissez les informations de l'article</p>

                    <form wire:submit="save">
                        <div class="space-y-4">
                            {{-- Titre --}}
                            <div>
                                <label class="block text-sm font-medium mb-2">Titre <span class="text-red-500">*</span></label>
                                <input wire:model.live="titre" type="text"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm">
                                @error('titre') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            {{-- Slug (URL) --}}
                            <div>
                                <label class="block text-sm font-medium mb-2">Slug (URL)</label>
                                <input wire:model="slug" type="text"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                                    placeholder="genere-automatiquement">
                                @error('slug') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            {{-- Extrait --}}
                            <div>
                                <label class="block text-sm font-medium mb-2">Extrait</label>
                                <textarea wire:model="extrait" rows="3"
                                    class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                                    placeholder="Résumé court de l'article"></textarea>
                                @error('extrait') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            {{-- Contenu avec éditeur riche --}}
                            <div
                                wire:ignore
                                x-data
                                x-init="
                                    let quill;

                                    const initQuill = () => {
                                        if (quill) return;

                                        quill = new Quill($refs.quillEditor, {
                                            theme: 'snow',
                                            placeholder: 'Contenu complet de l\'article...',
                                            modules: {
                                                toolbar: [
                                                    ['bold', 'italic', 'underline', 'strike'],
                                                    [{ 'header': 1 }, { 'header': 2 }],
                                                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                                    [{ 'script': 'sub'}, { 'script': 'super' }],
                                                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                                                    [{ 'direction': 'rtl' }],
                                                    [{ 'size': ['small', false, 'large', 'huge'] }],
                                                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                                                    [{ 'color': [] }, { 'background': [] }],
                                                    [{ 'font': [] }],
                                                    [{ 'align': [] }],
                                                    ['link', 'image'],
                                                    ['clean']
                                                ]
                                            }
                                        });

                                        // Valeur initiale depuis Livewire (create + edit)
                                        quill.root.innerHTML = @js($contenu);

                                        // Sync Livewire à chaque changement de contenu
                                        quill.on('text-change', function () {
                                            $wire.set('contenu', quill.root.innerHTML);
                                        });
                                    };

                                    initQuill();

                                    // Ré-initialiser proprement après chaque navigation Livewire
                                    document.addEventListener('livewire:navigated', () => {
                                        quill = null;
                                        initQuill();
                                    });
                                "
                            >
                                <label class="block text-sm font-medium mb-2">Contenu <span class="text-red-500">*</span></label>

                                {{-- Zone d'édition Quill --}}
                                <div
                                    x-ref="quillEditor"
                                    class="flex w-full rounded-md border border-input bg-background text-base ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm min-h-[200px]"
                                ></div>

                                {{-- Champ caché pour garder une valeur en cas de désactivation JS --}}
                                <textarea
                                    wire:model="contenu"
                                    class="hidden"
                                ></textarea>

                                @error('contenu') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            {{-- URL de l'image --}}
                            <div>
                                <label class="block text-sm font-medium mb-2">URL de l'image</label>
                                <input wire:model="image_url" type="url"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                                    placeholder="https://example.com/image.jpg">
                                @error('image_url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            {{-- Catégorie et Statut --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2">Catégorie</label>
                                    <input wire:model="categorie" type="text"
                                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                                        placeholder="Ex: Finance, Bourse, Conseil">
                                    @error('categorie') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Statut <span class="text-red-500">*</span></label>
                                    <select wire:model="statut"
                                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm">
                                        <option value="brouillon">Brouillon</option>
                                        <option value="publie">Publié</option>
                                        <option value="archive">Archivé</option>
                                    </select>
                                    @error('statut') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 mt-6">
                            <button type="button" wire:click="closeModal"
                                class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-11 px-6 py-3">
                                Annuler
                            </button>
                            <button type="submit"
                                class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary-light shadow-elegant hover:shadow-glow transition-smooth h-11 px-6 py-3">
                                {{ $editMode ? 'Modifier' : 'Créer' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Confirmation Suppression --}}
    @if($showDeleteModal)
        <div class="fixed inset-0 z-[100] bg-black bg-opacity-80 flex items-center justify-center p-4" wire:click.self="$set('showDeleteModal', false)">
            <div class="bg-white rounded-lg shadow-2xl max-w-md w-full p-6 relative">
                <h2 class="text-xl font-bold mb-4">Confirmer la suppression</h2>
                <p class="text-gray-600 mb-6">Êtes-vous sûr de vouloir supprimer cet article ? Cette action peut être annulée en restaurant l'article.</p>
                
                <div class="flex justify-end gap-3">
                    <button wire:click="$set('showDeleteModal', false)"
                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-11 px-6 py-3">
                        Annuler
                    </button>
                    <button wire:click="delete"
                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-red-600 text-white hover:bg-red-700 h-11 px-6 py-3">
                        Supprimer
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

@script
<script>
    $wire.on('article-saved', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
</script>
@endscript