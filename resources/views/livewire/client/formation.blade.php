<div class="max-w-[1440px] mx-auto px-3 lg:px-6 py-4 lg:py-6">
    <div class="grid lg:grid-cols-12 gap-4 lg:gap-6 min-h-[70vh]">
        {{-- Sidebar programme --}}
        <aside class="lg:col-span-3 adf-card-static p-4 flex flex-col max-h-[calc(100vh-6rem)] lg:sticky lg:top-20 overflow-hidden">
            <div class="flex gap-3 mb-4 shrink-0">
                <div class="w-14 h-14 rounded-xl bg-[#e7eeff] overflow-hidden shrink-0">
                    @if ($formation->image_url)
                        <img src="{{ $formation->image_url }}" alt="" class="w-full h-full object-cover">
                    @endif
                </div>
                <div class="min-w-0">
                    <h2 class="font-extrabold text-[#001a61] text-sm leading-snug line-clamp-2">{{ $formation->titre }}</h2>
                    <p class="text-xs text-[#757683] mt-1">{{ (int) $enrollment->progress }}% — {{ $completedCount }}/{{ $totalCount }}</p>
                    <div class="mt-1.5 h-1.5 bg-[#e7eeff] rounded-full overflow-hidden">
                        <div class="h-full bg-[#ffbf00]" style="width: {{ min(100, (int)$enrollment->progress) }}%"></div>
                    </div>
                </div>
            </div>

            <div class="flex-1 min-h-0 overflow-y-auto overscroll-contain space-y-4 pr-1">
                @foreach ($formation->modules as $module)
                    @php $moduleOpen = $this->isModuleUnlocked($module); @endphp
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wide text-[#757683] mb-1.5 flex items-center gap-1">
                            @unless ($moduleOpen)<span class="material-symbols-outlined text-sm">lock</span>@endunless
                            {{ $module->titre }}
                        </p>
                        <div class="space-y-1">
                            @foreach ($module->lessons as $lesson)
                                @php $unlocked = $this->isLessonUnlocked($module, $lesson); @endphp
                                <button type="button" @disabled(! $unlocked) wire:click="selectLesson({{ $module->id }}, {{ $lesson->id }})"
                                    @class([
                                        'w-full text-left px-2.5 py-2 rounded-lg text-sm transition flex items-center gap-2',
                                        'bg-[#001a61] text-white' => $currentLesson && $currentLesson->id === $lesson->id,
                                        'hover:bg-[#e7eeff] text-[#131c2a]' => $unlocked && !($currentLesson && $currentLesson->id === $lesson->id),
                                        'opacity-50 cursor-not-allowed text-[#757683]' => ! $unlocked,
                                    ])>
                                    <span class="material-symbols-outlined text-[18px] shrink-0">
                                        @if (! $unlocked)
                                            lock
                                        @elseif ($enrollment->hasCompletedLesson($lesson->id))
                                            check_circle
                                        @else
                                            {{ $lesson->icon() }}
                                        @endif
                                    </span>
                                    <span class="truncate flex-1">{{ $lesson->titre }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <a href="{{ route('client.formation.progress', $formation->slug) }}"
                class="mt-4 w-full text-center py-2.5 rounded-xl bg-[#001a61] text-white text-sm font-bold hover:bg-[#0a2e8c] shrink-0">
                Voir la progression
            </a>

            <div class="mt-3 space-y-1.5 shrink-0">
                <p class="text-[11px] font-bold uppercase text-[#757683]">Communauté</p>
                <a href="{{ route('client.formation.forum', $formation->slug) }}"
                    class="flex items-center gap-2 w-full px-2.5 py-2 rounded-lg text-sm hover:bg-[#e7eeff] text-[#001a61] font-semibold">
                    <span class="material-symbols-outlined text-[18px]">forum</span> Forum
                </a>
                <a href="{{ route('client.formation.resources', $formation->slug) }}"
                    class="flex items-center gap-2 w-full px-2.5 py-2 rounded-lg text-sm hover:bg-[#e7eeff] text-[#001a61] font-semibold">
                    <span class="material-symbols-outlined text-[18px]">folder_open</span> Ressources
                </a>
                <a href="{{ route('client.formation.review', $formation->slug) }}"
                    class="flex items-center gap-2 w-full px-2.5 py-2 rounded-lg text-sm hover:bg-[#e7eeff] text-[#001a61] font-semibold">
                    <span class="material-symbols-outlined text-[18px]">rate_review</span> Évaluer
                </a>
                <a href="{{ route('client.ask-instructor', ['formation' => $formation->slug]) }}"
                    class="flex items-center gap-2 w-full px-2.5 py-2 rounded-lg text-sm hover:bg-[#e7eeff] text-[#001a61] font-semibold">
                    <span class="material-symbols-outlined text-[18px]">contact_support</span> Question formateur
                </a>
            </div>

            @php
                $moduleQuizzes = $formation->modules->map->quiz->filter();
            @endphp
            @if ($moduleQuizzes->isNotEmpty())
                <div class="mt-3 space-y-2 shrink-0">
                    <p class="text-[11px] font-bold uppercase text-[#757683]">Évaluations</p>
                    @foreach ($moduleQuizzes as $mq)
                        <a href="{{ route('client.quiz.intro', [$formation->slug, $mq->id]) }}"
                            class="flex items-center gap-2 w-full text-left px-2.5 py-2 rounded-lg text-sm border border-[#c5c5d4] hover:bg-[#e7eeff] text-[#001a61] font-semibold">
                            <span class="material-symbols-outlined text-[18px]">{{ $mq->is_final ? 'workspace_premium' : 'quiz' }}</span>
                            <span class="truncate">{{ $mq->is_final ? 'Examen final' : $mq->titre }}</span>
                        </a>
                    @endforeach
                </div>
            @endif
        </aside>

        {{-- Contenu leçon --}}
        <section class="lg:col-span-6 adf-card-static p-5 lg:p-8 flex flex-col">
            @if ($currentLesson)
                <nav class="text-xs text-[#757683] flex flex-wrap items-center gap-1 mb-4">
                    <span class="font-medium text-[#001a61]">{{ $formation->titre }}</span>
                    <span class="material-symbols-outlined text-sm">chevron_right</span>
                    <span>{{ $currentModule->titre ?? '' }}</span>
                    <span class="material-symbols-outlined text-sm">chevron_right</span>
                    <span class="uppercase font-bold text-[#0a2e8c]">{{ $currentLesson->type }}</span>
                </nav>

                <div class="flex items-start justify-between gap-3">
                    <h1 class="text-2xl lg:text-3xl font-extrabold text-[#001a61] leading-tight">{{ $currentLesson->titre }}</h1>
                    <button type="button" wire:click="toggleFavorite"
                        class="shrink-0 inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-[#c5c5d4] text-sm font-semibold text-[#001a61] hover:bg-[#e7eeff]"
                        title="{{ $isFavorite ? 'Retirer des favoris' : 'Ajouter aux favoris' }}">
                        <span class="material-symbols-outlined text-[20px]" @if($isFavorite) style="font-variation-settings:'FILL' 1" @endif>bookmark</span>
                    </button>
                </div>
                @if ($currentLesson->duree_estimee)
                    <p class="text-sm text-[#757683] mt-2">{{ $currentLesson->duree_estimee }} · {{ $formation->user->name ?? 'Formateur AF' }}</p>
                @endif

                <div class="mt-6 flex-1">
                    @include('livewire.client.partials.lesson-body', [
                        'lesson' => $currentLesson,
                        'submission' => $submission,
                        'videoProgress' => $videoProgress,
                    ])
                </div>

                <div class="mt-8 pt-5 border-t border-[#e7eeff] flex flex-wrap items-center justify-between gap-3">
                    <button type="button" wire:click="previousLesson" class="text-sm font-bold text-[#001a61] hover:underline">← Leçon précédente</button>
                    @unless ($currentLesson->isExercise() && $submission && !$submission->isCorrected())
                        <button type="button" wire:click="completeLesson"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#5c6700] text-white font-bold hover:brightness-110">
                            <span class="material-symbols-outlined text-base">check_circle</span>
                            Marquer comme terminé
                        </button>
                    @endunless
                    <button type="button" wire:click="nextLesson" class="text-sm font-bold text-[#001a61] hover:underline">Leçon suivante →</button>
                </div>
            @else
                <p class="text-[#757683] py-20 text-center">Sélectionnez une leçon dans le programme.</p>
            @endif
        </section>

        {{-- Notes / ressources --}}
        <aside class="lg:col-span-3 adf-card-static p-4 flex flex-col max-h-[calc(100vh-6rem)] lg:sticky lg:top-20 overflow-hidden">
            <div class="flex gap-1 border-b border-[#e7eeff] mb-4 shrink-0 overflow-x-auto">
                <button type="button" wire:click="$set('sidebarTab','notes')" @class(['px-3 py-2 text-xs font-bold uppercase border-b-2 whitespace-nowrap', 'border-[#ffbf00] text-[#001a61]' => $sidebarTab==='notes', 'border-transparent text-[#757683]' => $sidebarTab!=='notes'])>Notes</button>
                <button type="button" wire:click="$set('sidebarTab','resources')" @class(['px-3 py-2 text-xs font-bold uppercase border-b-2 whitespace-nowrap', 'border-[#ffbf00] text-[#001a61]' => $sidebarTab==='resources', 'border-transparent text-[#757683]' => $sidebarTab!=='resources'])>Ressources</button>
                <button type="button" wire:click="$set('sidebarTab','transcript')" @class(['px-3 py-2 text-xs font-bold uppercase border-b-2 whitespace-nowrap', 'border-[#ffbf00] text-[#001a61]' => $sidebarTab==='transcript', 'border-transparent text-[#757683]' => $sidebarTab!=='transcript'])>Transcript</button>
            </div>

            @if ($sidebarTab === 'notes')
                <form wire:submit.prevent="saveNote" class="mb-4 shrink-0">
                    <textarea wire:model="noteBody" rows="3" placeholder="Ajouter une note…" class="w-full rounded-lg border-[#c5c5d4] text-sm"></textarea>
                    @if ($noteVideoSeconds !== null)
                        <p class="text-[11px] text-[#0a2e8c] mt-1 font-semibold">Horodatage vidéo : {{ gmdate('i:s', $noteVideoSeconds) }}</p>
                    @endif
                    @error('noteBody') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    <button type="submit" class="mt-2 w-full py-2 rounded-lg bg-[#e7eeff] text-[#001a61] text-sm font-bold">Enregistrer</button>
                </form>
                <div class="flex-1 min-h-0 overflow-y-auto overscroll-contain space-y-2">
                    <p class="text-[11px] font-bold uppercase text-[#757683]">Mes notes récentes</p>
                    @forelse ($notes as $note)
                        <div class="rounded-lg bg-[#f0f3ff] p-3 text-sm text-[#444652]">
                            {{ $note->body }}
                            <p class="text-[11px] text-[#757683] mt-1">
                                {{ $note->created_at->diffForHumans() }}
                                @if ($note->video_seconds !== null)
                                    · {{ gmdate('i:s', $note->video_seconds) }}
                                @endif
                            </p>
                        </div>
                    @empty
                        <p class="text-sm text-[#757683]">Aucune note pour cette leçon.</p>
                    @endforelse
                </div>
            @elseif ($sidebarTab === 'transcript')
                <div class="flex-1 min-h-0 overflow-y-auto overscroll-contain text-sm text-[#444652] whitespace-pre-line">
                    @if ($currentLesson?->transcript)
                        {{ $currentLesson->transcript }}
                    @else
                        <p class="text-[#757683]">Pas de transcription pour cette leçon.</p>
                    @endif
                </div>
            @else
                <ul class="space-y-2 text-sm flex-1 min-h-0 overflow-y-auto overscroll-contain">
                    @if ($currentLesson?->pdf_url)
                        <li><a href="{{ $currentLesson->pdf_url }}" target="_blank" class="flex items-center gap-2 text-[#001a61] font-semibold hover:underline"><span class="material-symbols-outlined text-base">picture_as_pdf</span> Document PDF</a></li>
                    @endif
                    @if ($currentLesson?->audio_url)
                        <li><a href="{{ $currentLesson->audio_url }}" target="_blank" class="flex items-center gap-2 text-[#001a61] font-semibold hover:underline"><span class="material-symbols-outlined text-base">headphones</span> Fichier audio</a></li>
                    @endif
                    @if ($currentLesson?->video_url)
                        <li><a href="{{ $currentLesson->video_url }}" target="_blank" class="flex items-center gap-2 text-[#001a61] font-semibold hover:underline"><span class="material-symbols-outlined text-base">play_circle</span> Vidéo</a></li>
                    @endif
                    @foreach (($currentLesson->ressources ?? []) as $key => $res)
                        <li class="text-[#444652]"><span class="font-medium">{{ is_string($key) ? $key : 'Ressource' }} :</span> {{ is_string($res) ? $res : json_encode($res) }}</li>
                    @endforeach
                    @if (! $currentLesson?->pdf_url && ! $currentLesson?->audio_url && ! $currentLesson?->video_url && empty($currentLesson?->ressources))
                        <li class="text-[#757683]">Pas de ressource jointe.</li>
                    @endif
                </ul>
            @endif

            <div class="mt-4 rounded-xl bg-[#001a61] text-white p-4 shrink-0">
                <p class="text-xs uppercase text-white/60">Votre progression</p>
                <p class="text-2xl font-extrabold mt-1">{{ (int) $enrollment->progress }}%</p>
                <p class="text-xs text-[#ffbf00] mt-1">{{ $completedCount }} leçons terminées sur {{ $totalCount }}</p>
            </div>
        </aside>
    </div>
</div>
