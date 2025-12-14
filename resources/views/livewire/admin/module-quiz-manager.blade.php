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
                <li class="font-medium">Quiz</li>
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
                    <h2 class="text-xl font-bold">Quiz - {{ $module->titre }}</h2>
                    <p class="text-sm text-muted-foreground">
                        Module {{ $module->ordre }} de la formation "{{ $module->formation->titre }}"
                    </p>
                </div>
                <a href="{{ route('admin.formations.modules.lessons', ['formation' => $module->formation_id, 'module' => $module->id]) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 border border-input rounded-md hover:bg-accent">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    Voir les Leçons
                </a>
            </div>
        </div>

        {{-- Configuration du Quiz --}}
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm mb-6">
            <div class="p-6">
                @if($quiz)
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-xl font-semibold">{{ $quiz->titre }}</h3>
                            <p class="text-sm text-muted-foreground">{{ $quiz->description }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $quiz->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $quiz->is_active ? 'Actif' : 'Inactif' }}
                            </span>
                            <button wire:click="openQuizModal"
                                class="inline-flex items-center gap-2 px-3 py-2 border border-input rounded-md hover:bg-accent text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Modifier
                            </button>
                            <button wire:click="deleteQuiz" wire:confirm="Êtes-vous sûr de vouloir supprimer ce quiz ?"
                                class="inline-flex items-center gap-2 px-3 py-2 border border-red-200 rounded-md hover:bg-red-50 text-red-600 text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Supprimer
                            </button>
                        </div>
                    </div>

                    {{-- Stats du Quiz --}}
                    <div class="grid grid-cols-4 gap-4 mt-4">
                        <div class="bg-muted rounded-lg p-4 text-center">
                            <p class="text-2xl font-bold text-primary">{{ $questions->count() }}</p>
                            <p class="text-sm text-muted-foreground">Questions</p>
                        </div>
                        <div class="bg-muted rounded-lg p-4 text-center">
                            <p class="text-2xl font-bold text-primary">{{ $quiz->score_minimum }}%</p>
                            <p class="text-sm text-muted-foreground">Score minimum</p>
                        </div>
                        <div class="bg-muted rounded-lg p-4 text-center">
                            <p class="text-2xl font-bold text-primary">{{ $quiz->tentatives_max }}</p>
                            <p class="text-sm text-muted-foreground">Tentatives max</p>
                        </div>
                        <div class="bg-muted rounded-lg p-4 text-center">
                            <p class="text-2xl font-bold text-primary">{{ $quiz->duree_minutes ?? '∞' }}</p>
                            <p class="text-sm text-muted-foreground">Minutes</p>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-4 text-muted-foreground opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        <h3 class="text-lg font-semibold mb-2">Aucun quiz configuré</h3>
                        <p class="text-muted-foreground mb-4">Créez un quiz pour évaluer les apprenants à la fin de ce module.</p>
                        <button wire:click="openQuizModal"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-primary-foreground rounded-md hover:bg-primary/90">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Créer le Quiz
                        </button>
                    </div>
                @endif
            </div>
        </div>

        {{-- Liste des Questions --}}
        @if($quiz)
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="flex flex-col space-y-1.5 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-semibold">Questions du Quiz</h3>
                        <p class="text-sm text-muted-foreground">{{ $questions->count() }} question(s)</p>
                    </div>
                    <button wire:click="openQuestionModal"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-primary-foreground rounded-md hover:bg-primary/90">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Ajouter une question
                    </button>
                </div>
            </div>

            <div class="p-6 pt-0">
                <div class="space-y-4">
                    @forelse($questions as $index => $question)
                        <div class="rounded-lg border bg-background p-4 {{ !$question->is_active ? 'opacity-50' : '' }}">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-primary text-primary-foreground text-sm font-bold">
                                            {{ $index + 1 }}
                                        </span>
                                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $question->type === 'choix_unique' ? 'Choix unique' : ($question->type === 'choix_multiple' ? 'Choix multiple' : 'Vrai/Faux') }}
                                        </span>
                                        <span class="text-sm text-muted-foreground">{{ $question->points }} point(s)</span>
                                    </div>
                                    <p class="font-medium mb-3">{{ $question->question }}</p>

                                    {{-- Réponses --}}
                                    <div class="grid grid-cols-2 gap-2 ml-10">
                                        @foreach($question->answers as $answer)
                                            <div class="flex items-center gap-2 text-sm {{ $answer->is_correct ? 'text-green-700 font-medium' : 'text-muted-foreground' }}">
                                                @if($answer->is_correct)
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                @endif
                                                {{ $answer->reponse }}
                                            </div>
                                        @endforeach
                                    </div>

                                    @if($question->explication)
                                        <div class="mt-3 ml-10 p-2 bg-yellow-50 rounded text-sm text-yellow-800">
                                            <strong>Explication:</strong> {{ $question->explication }}
                                        </div>
                                    @endif
                                </div>

                                {{-- Actions --}}
                                <div class="flex items-center gap-2 ml-4">
                                    <button wire:click="editQuestion({{ $question->id }})"
                                        class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-input bg-background hover:bg-accent h-9 px-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button wire:click="confirmDeleteQuestion({{ $question->id }})"
                                        class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-red-200 bg-white hover:bg-red-50 text-red-600 h-9 px-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-muted-foreground">
                            <p>Aucune question dans ce quiz</p>
                            <button wire:click="openQuestionModal" class="mt-4 text-primary hover:underline">
                                Ajouter la première question
                            </button>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        @endif
    </main>

    {{-- Modal Configuration Quiz --}}
    @if($showQuizModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" wire:click.self="closeQuizModal">
            <div class="fixed inset-0 bg-black bg-opacity-50"></div>
            <div class="relative bg-card rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold">
                            {{ $editQuizMode ? 'Modifier le Quiz' : 'Créer le Quiz' }}
                        </h2>
                        <button wire:click="closeQuizModal" class="text-muted-foreground hover:text-foreground">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form wire:submit="saveQuiz">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Titre du Quiz <span class="text-red-500">*</span></label>
                                <input wire:model="quizTitre" type="text"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                                @error('quizTitre') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Description</label>
                                <textarea wire:model="quizDescription" rows="2"
                                    class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm"></textarea>
                            </div>

                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2">Durée (minutes)</label>
                                    <input wire:model="quizDureeMinutes" type="number" min="1"
                                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                                        placeholder="Illimité">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Score minimum (%)</label>
                                    <input wire:model="quizScoreMinimum" type="number" min="0" max="100"
                                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Tentatives max</label>
                                    <input wire:model="quizTentativesMax" type="number" min="1"
                                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                                </div>
                            </div>

                            <div class="flex items-center gap-6">
                                <label class="flex items-center gap-2">
                                    <input wire:model="quizIsActive" type="checkbox" class="h-4 w-4 rounded border-gray-300">
                                    <span class="text-sm">Quiz actif</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input wire:model="quizAfficherCorrections" type="checkbox" class="h-4 w-4 rounded border-gray-300">
                                    <span class="text-sm">Afficher les corrections</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 mt-6">
                            <button type="button" wire:click="closeQuizModal"
                                class="px-4 py-2 border border-input rounded-md hover:bg-accent">
                                Annuler
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-primary text-primary-foreground rounded-md hover:bg-primary/90">
                                {{ $editQuizMode ? 'Modifier' : 'Créer' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Question --}}
    @if($showQuestionModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" wire:click.self="closeQuestionModal">
            <div class="fixed inset-0 bg-black bg-opacity-50"></div>
            <div class="relative bg-card rounded-lg shadow-xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold">
                            {{ $editQuestionMode ? 'Modifier la Question' : 'Ajouter une Question' }}
                        </h2>
                        <button wire:click="closeQuestionModal" class="text-muted-foreground hover:text-foreground">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form wire:submit="saveQuestion">
                        <div class="space-y-4">
                            {{-- Question --}}
                            <div>
                                <label class="block text-sm font-medium mb-2">Question <span class="text-red-500">*</span></label>
                                <textarea wire:model="questionTexte" rows="3"
                                    class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                                    placeholder="Posez votre question ici..."></textarea>
                                @error('questionTexte') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-3 gap-4">
                                {{-- Type --}}
                                <div>
                                    <label class="block text-sm font-medium mb-2">Type de question</label>
                                    <select wire:model.live="questionType"
                                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                                        <option value="choix_unique">Choix unique</option>
                                        <option value="choix_multiple">Choix multiple</option>
                                        <option value="vrai_faux">Vrai / Faux</option>
                                    </select>
                                </div>

                                {{-- Points --}}
                                <div>
                                    <label class="block text-sm font-medium mb-2">Points</label>
                                    <input wire:model="questionPoints" type="number" min="1"
                                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm">
                                </div>

                                {{-- Actif --}}
                                <div class="flex items-end">
                                    <label class="flex items-center gap-2">
                                        <input wire:model="questionIsActive" type="checkbox" class="h-4 w-4 rounded border-gray-300">
                                        <span class="text-sm">Question active</span>
                                    </label>
                                </div>
                            </div>

                            {{-- Réponses --}}
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-sm font-medium">Réponses <span class="text-red-500">*</span></label>
                                    @if($questionType !== 'vrai_faux')
                                        <button type="button" wire:click="addReponse"
                                            class="text-sm text-primary hover:underline">
                                            + Ajouter une réponse
                                        </button>
                                    @endif
                                </div>
                                @error('reponses') <span class="text-red-500 text-sm block mb-2">{{ $message }}</span> @enderror

                                <div class="space-y-2">
                                    @foreach($reponses as $index => $reponse)
                                        <div class="flex items-center gap-2">
                                            <input wire:model="reponses.{{ $index }}.is_correct" type="checkbox"
                                                class="h-5 w-5 rounded border-gray-300 text-green-600">
                                            <input wire:model="reponses.{{ $index }}.texte" type="text"
                                                class="flex-1 h-10 rounded-md border border-input bg-background px-3 py-2 text-sm"
                                                placeholder="Texte de la réponse..."
                                                {{ $questionType === 'vrai_faux' ? 'readonly' : '' }}>
                                            @if($questionType !== 'vrai_faux' && count($reponses) > 2)
                                                <button type="button" wire:click="removeReponse({{ $index }})"
                                                    class="text-red-500 hover:text-red-700 p-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                        @error('reponses.'.$index.'.texte') <span class="text-red-500 text-sm ml-7">{{ $message }}</span> @enderror
                                    @endforeach
                                </div>
                                <p class="text-xs text-muted-foreground mt-2">Cochez la ou les réponse(s) correcte(s)</p>
                            </div>

                            {{-- Explication --}}
                            <div>
                                <label class="block text-sm font-medium mb-2">Explication (optionnel)</label>
                                <textarea wire:model="questionExplication" rows="2"
                                    class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                                    placeholder="Explication de la bonne réponse..."></textarea>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 mt-6">
                            <button type="button" wire:click="closeQuestionModal"
                                class="px-4 py-2 border border-input rounded-md hover:bg-accent">
                                Annuler
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-primary text-primary-foreground rounded-md hover:bg-primary/90">
                                {{ $editQuestionMode ? 'Modifier' : 'Ajouter' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal Suppression Question --}}
    @if($showDeleteQuestionModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black bg-opacity-50" wire:click="$set('showDeleteQuestionModal', false)"></div>
            <div class="relative bg-card rounded-lg shadow-xl max-w-md w-full p-6">
                <h3 class="text-lg font-bold mb-4">Confirmer la suppression</h3>
                <p class="text-muted-foreground mb-6">
                    Êtes-vous sûr de vouloir supprimer cette question ?
                </p>
                <div class="flex justify-end gap-3">
                    <button wire:click="$set('showDeleteQuestionModal', false)"
                        class="px-4 py-2 border border-input rounded-md hover:bg-accent">
                        Annuler
                    </button>
                    <button wire:click="deleteQuestion"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Supprimer
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
