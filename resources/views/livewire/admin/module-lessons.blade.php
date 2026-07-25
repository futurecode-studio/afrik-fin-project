@php
    $typeMeta = [
        'texte' => ['icon' => 'description', 'color' => 'bg-[#e7eeff] text-[#001a61]'],
        'video' => ['icon' => 'play_circle', 'color' => 'bg-red-50 text-red-700'],
        'audio' => ['icon' => 'headphones', 'color' => 'bg-violet-50 text-violet-700'],
        'pdf' => ['icon' => 'picture_as_pdf', 'color' => 'bg-orange-50 text-orange-800'],
        'mixte' => ['icon' => 'layers', 'color' => 'bg-sky-50 text-sky-800'],
        'exercice' => ['icon' => 'assignment', 'color' => 'bg-amber-50 text-amber-900'],
    ];
@endphp

<div x-data="{ open: @entangle('showModal').live, del: @entangle('showDeleteModal').live }">
    <nav class="mb-6 text-sm flex flex-wrap items-center gap-2 text-[#757683]">
        <a href="{{ route('admin.formations') }}" class="font-semibold text-[#001a61] hover:underline" wire:navigate.hover>Formations</a>
        <span>/</span>
        <a href="{{ route('admin.formations.modules', $formation) }}" class="font-semibold text-[#001a61] hover:underline" wire:navigate.hover>{{ $formation->titre }}</a>
        <span>/</span>
        <span class="text-[#444652]">{{ $module->titre }}</span>
        <span>/</span>
        <span class="font-bold text-[#001a61]">Leçons</span>
    </nav>

    <div class="mb-6 adf-card-static p-5 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <p class="text-xs font-bold uppercase tracking-widest text-[#757683]">Module {{ $module->ordre }}</p>
            <h1 class="text-2xl font-extrabold text-[#001a61] mt-1">{{ $module->titre }}</h1>
            <p class="text-sm text-[#757683] mt-1">
                {{ $module->lessons_count ?? $lessons->total() }} leçon(s)
                @if ($module->duree_estimee) · {{ $module->duree_estimee }} @endif
            </p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.formations.modules.quiz', [$formation, $module]) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-[#c5c5d4] font-bold text-[#001a61] hover:bg-[#e7eeff]" wire:navigate.hover>
                <span class="material-symbols-outlined text-[20px]">quiz</span> Quiz du module
            </a>
            <button type="button" @click="open = true; $wire.openModal()"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#001a61] text-white font-bold hover:bg-[#0a2e8c]">
                <span class="material-symbols-outlined text-[20px]">add</span> Ajouter une leçon
            </button>
        </div>
    </div>

    <div class="adf-card-static overflow-hidden">
        <div class="px-5 py-4 border-b border-[#e7eeff] flex items-center justify-between">
            <div>
                <h2 class="font-extrabold text-[#001a61]">Leçons du module</h2>
                <p class="text-xs text-[#757683]">{{ $lessons->total() }} élément(s) · réordonnez avec les flèches</p>
            </div>
        </div>

        <div class="divide-y divide-[#e7eeff]">
            @forelse ($lessons as $lesson)
                @php $meta = $typeMeta[$lesson->type] ?? $typeMeta['texte']; @endphp
                <div class="p-4 lg:p-5 flex flex-col sm:flex-row sm:items-center gap-4 {{ $lesson->is_active ? '' : 'opacity-55 bg-[#f9f9ff]' }}">
                    <div class="flex items-center gap-3 shrink-0">
                        <div class="flex flex-col items-center">
                            <button type="button" wire:click="updateOrder({{ $lesson->id }}, 'up')" class="p-0.5 text-[#757683] hover:text-[#001a61]" title="Monter">
                                <span class="material-symbols-outlined text-[18px]">keyboard_arrow_up</span>
                            </button>
                            <span class="text-sm font-extrabold text-[#001a61] w-6 text-center">{{ $lesson->ordre }}</span>
                            <button type="button" wire:click="updateOrder({{ $lesson->id }}, 'down')" class="p-0.5 text-[#757683] hover:text-[#001a61]" title="Descendre">
                                <span class="material-symbols-outlined text-[18px]">keyboard_arrow_down</span>
                            </button>
                        </div>
                        <div class="w-11 h-11 rounded-xl {{ $meta['color'] }} flex items-center justify-center">
                            <span class="material-symbols-outlined">{{ $meta['icon'] }}</span>
                        </div>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="font-bold text-[#001a61]">{{ $lesson->titre }}</h3>
                            @unless ($lesson->is_active)
                                <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded bg-[#c5c5d4] text-[#444652]">Inactive</span>
                            @endunless
                        </div>
                        <p class="text-xs text-[#757683] mt-0.5">
                            {{ $typeLabels[$lesson->type] ?? $lesson->type }}
                            @if ($lesson->duree_estimee) · {{ $lesson->duree_estimee }} @endif
                            @if ($lesson->video_url) · <span class="text-red-600 font-semibold">vidéo</span> @endif
                            @if ($lesson->audio_url) · <span class="text-violet-700 font-semibold">audio</span> @endif
                            @if ($lesson->pdf_url) · <span class="text-orange-700 font-semibold">PDF</span> @endif
                        </p>
                        @if ($lesson->description)
                            <p class="text-sm text-[#444652] mt-1 line-clamp-2">{{ $lesson->description }}</p>
                        @endif
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <button type="button" wire:click="toggleActive({{ $lesson->id }})"
                            class="px-3 py-2 rounded-xl border border-[#c5c5d4] text-xs font-bold text-[#001a61] hover:bg-[#e7eeff]"
                            title="Activer / désactiver">
                            {{ $lesson->is_active ? 'Désactiver' : 'Activer' }}
                        </button>
                        <button type="button" wire:click="edit({{ $lesson->id }})"
                            class="inline-flex items-center gap-1 px-3 py-2 rounded-xl bg-[#001a61] text-white text-sm font-bold hover:bg-[#0a2e8c]">
                            <span class="material-symbols-outlined text-[18px]">edit</span> Éditer
                        </button>
                        <button type="button" @click="del = true; $wire.confirmDelete({{ $lesson->id }})"
                            class="p-2 rounded-xl border border-red-200 text-red-600 hover:bg-red-50">
                            <span class="material-symbols-outlined text-[20px]">delete</span>
                        </button>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <span class="material-symbols-outlined text-5xl text-[#c5c5d4]">menu_book</span>
                    <p class="mt-3 text-[#757683]">Aucune leçon dans ce module.</p>
                    <button type="button" @click="open = true; $wire.openModal()" class="mt-4 font-bold text-[#001a61] underline">Créer la première leçon</button>
                </div>
            @endforelse
        </div>

        @if ($lessons->hasPages())
            <div class="px-5 py-4 border-t border-[#e7eeff]">{{ $lessons->links() }}</div>
        @endif
    </div>

    {{-- Modal édition (instantané via Alpine @entangle) --}}
    <div
        x-show="open"
        x-cloak
        class="fixed inset-0 z-[100] flex items-end sm:items-center justify-center p-0 sm:p-4"
        role="dialog"
        aria-modal="true"
        style="display: none;"
        @keydown.escape.window="if (open) open = false"
    >
            <div class="absolute inset-0 bg-[#131c2a]/55" @click="open = false"></div>
            <div class="relative adf-modal-panel w-full sm:max-w-3xl bg-white rounded-t-3xl sm:rounded-2xl shadow-2xl max-h-[94vh] flex flex-col overflow-hidden border border-[#c5c5d4]" @click.stop>
                <div class="px-5 py-4 border-b border-[#e7eeff] flex items-start justify-between gap-3 shrink-0">
                    <div>
                        <h2 class="text-xl font-extrabold text-[#001a61]">{{ $editMode ? 'Modifier la leçon' : 'Nouvelle leçon' }}</h2>
                        <p class="text-sm text-[#757683]">Configurez le type, les médias et le contenu affiché aux apprenants.</p>
                    </div>
                    <button type="button" @click="open = false" class="p-1 rounded-lg hover:bg-[#e7eeff]">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <div class="px-5 pt-3 flex gap-1 border-b border-[#e7eeff] shrink-0 overflow-x-auto">
                    @foreach (['infos' => 'Infos', 'medias' => 'Médias', 'contenu' => 'Contenu'] as $tab => $label)
                        <button type="button" wire:click="$set('formTab','{{ $tab }}')"
                            @class([
                                'px-4 py-2.5 text-sm font-bold border-b-2 -mb-px whitespace-nowrap',
                                'border-[#ffbf00] text-[#001a61]' => $formTab === $tab,
                                'border-transparent text-[#757683]' => $formTab !== $tab,
                            ])>{{ $label }}</button>
                    @endforeach
                </div>

                <form wire:submit="save" class="flex-1 min-h-0 overflow-y-auto p-5 space-y-4">
                    @if ($formTab === 'infos')
                        <div>
                            <label class="text-xs font-bold uppercase text-[#757683]">Titre <span class="text-red-500">*</span></label>
                            <input wire:model.blur="titre" type="text" class="mt-1 w-full rounded-xl border border-[#c5c5d4] px-3 py-2.5 text-sm" placeholder="Ex. Établir son budget mensuel">
                            @error('titre') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold uppercase text-[#757683]">Type de contenu</label>
                                <select wire:model.live="type" class="mt-1 w-full rounded-xl border border-[#c5c5d4] px-3 py-2.5 text-sm font-semibold text-[#001a61]">
                                    @foreach ($typeLabels as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                <p class="text-[11px] text-[#757683] mt-1">
                                    @switch($type)
                                        @case('video') URL YouTube / MP4 + texte optionnel @break
                                        @case('audio') Fichier ou URL audio + transcript @break
                                        @case('pdf') Document PDF embarqué @break
                                        @case('mixte') Vidéo + audio + PDF + texte @break
                                        @case('exercice') Consigne + dépôt de copie par l’apprenant @break
                                        @default Contenu texte / HTML pédagogique
                                    @endswitch
                                </p>
                            </div>
                            <div>
                                <label class="text-xs font-bold uppercase text-[#757683]">Durée estimée</label>
                                <input wire:model="duree_estimee" type="text" class="mt-1 w-full rounded-xl border border-[#c5c5d4] px-3 py-2.5 text-sm" placeholder="20 min">
                                <p class="text-[11px] text-[#757683] mt-1">Nombre seul accepté (ex. <code>20</code> → 20 min)</p>
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-bold uppercase text-[#757683]">Description courte</label>
                            <textarea wire:model="description" rows="2" class="mt-1 w-full rounded-xl border border-[#c5c5d4] px-3 py-2.5 text-sm" placeholder="Résumé visible dans la liste…"></textarea>
                        </div>
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold uppercase text-[#757683]">Ordre</label>
                                <input wire:model="ordre" type="number" min="0" class="mt-1 w-full rounded-xl border border-[#c5c5d4] px-3 py-2.5 text-sm">
                            </div>
                            <div class="flex items-end pb-2">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input wire:model="is_active" type="checkbox" class="rounded border-[#c5c5d4] text-[#001a61] focus:ring-[#001a61]">
                                    <span class="text-sm font-bold text-[#001a61]">Leçon active (visible aux apprenants)</span>
                                </label>
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-bold uppercase text-[#757683]">Slug (auto)</label>
                            <input wire:model="slug" type="text" class="mt-1 w-full rounded-xl border border-[#c5c5d4] px-3 py-2.5 text-sm font-mono text-xs">
                        </div>
                    @endif

                    @if ($formTab === 'medias')
                        @if (in_array($type, ['video', 'mixte'], true) || filled($video_url))
                            <div class="rounded-xl border border-[#e7eeff] p-4 space-y-3">
                                <p class="text-sm font-extrabold text-[#001a61] flex items-center gap-2">
                                    <span class="material-symbols-outlined text-red-600">play_circle</span> Vidéo
                                </p>
                                <div>
                                    <label class="text-xs font-bold uppercase text-[#757683]">URL YouTube / Vimeo / MP4</label>
                                    <input wire:model.live.debounce.400ms="video_url" type="text" class="mt-1 w-full rounded-xl border border-[#c5c5d4] px-3 py-2.5 text-sm" placeholder="https://www.youtube.com/watch?v=…">
                                </div>
                                @if ($this->youtubePreview)
                                    <div class="aspect-video rounded-xl overflow-hidden bg-[#001a61]">
                                        <iframe src="https://www.youtube.com/embed/{{ $this->youtubePreview }}" class="w-full h-full" allowfullscreen></iframe>
                                    </div>
                                @endif
                                <div>
                                    <label class="text-xs font-bold uppercase text-[#757683]">Ou uploader un fichier MP4</label>
                                    <input type="file" wire:model="videoFile" accept="video/mp4,video/webm,video/quicktime" class="mt-1 block w-full text-sm">
                                    <div wire:loading wire:target="videoFile" class="text-xs text-[#757683] mt-1">Téléversement…</div>
                                    @error('videoFile') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        @endif

                        @if (in_array($type, ['audio', 'mixte'], true) || filled($audio_url))
                            <div class="rounded-xl border border-[#e7eeff] p-4 space-y-3">
                                <p class="text-sm font-extrabold text-[#001a61] flex items-center gap-2">
                                    <span class="material-symbols-outlined text-violet-700">headphones</span> Audio
                                </p>
                                <input wire:model="audio_url" type="text" class="w-full rounded-xl border border-[#c5c5d4] px-3 py-2.5 text-sm" placeholder="https://…/lecon.mp3">
                                <input type="file" wire:model="audioFile" accept="audio/*" class="block w-full text-sm">
                                <div wire:loading wire:target="audioFile" class="text-xs text-[#757683]">Téléversement…</div>
                                @error('audioFile') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        @endif

                        @if (in_array($type, ['pdf', 'mixte'], true) || filled($pdf_url))
                            <div class="rounded-xl border border-[#e7eeff] p-4 space-y-3">
                                <p class="text-sm font-extrabold text-[#001a61] flex items-center gap-2">
                                    <span class="material-symbols-outlined text-orange-700">picture_as_pdf</span> PDF
                                </p>
                                <input wire:model="pdf_url" type="text" class="w-full rounded-xl border border-[#c5c5d4] px-3 py-2.5 text-sm" placeholder="https://…/document.pdf">
                                <input type="file" wire:model="pdfFile" accept="application/pdf" class="block w-full text-sm">
                                <div wire:loading wire:target="pdfFile" class="text-xs text-[#757683]">Téléversement…</div>
                                @error('pdfFile') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        @endif

                        @if ($type === 'texte' && blank($video_url) && blank($audio_url) && blank($pdf_url))
                            <p class="text-sm text-[#757683] bg-[#f0f3ff] rounded-xl p-4">
                                Type <strong>Texte</strong> : passez à l’onglet Contenu. Pour ajouter une vidéo/audio/PDF, choisissez Vidéo, Audio, PDF ou Mixte.
                            </p>
                        @endif

                        @if ($type === 'exercice')
                            <p class="text-sm text-[#757683] bg-amber-50 border border-amber-200 rounded-xl p-4">
                                Type <strong>Exercice</strong> : la consigne se règle dans l’onglet Contenu. Les apprenants déposent texte/fichier.
                            </p>
                        @endif
                    @endif

                    @if ($formTab === 'contenu')
                        @if ($type === 'exercice')
                            <x-admin.rich-editor
                                wire:key="lesson-consigne-{{ $lessonId ?? 'new' }}-{{ $editMode ? 'e' : 'c' }}"
                                model="consigne"
                                :value="$consigne"
                                label="Consigne de l’exercice"
                                :required="true"
                                placeholder="Décrivez précisément ce que l’apprenant doit rendre…"
                                hint="Mise en forme visuelle : gras, listes, liens. Le HTML est enregistré automatiquement."
                                min-height="180px"
                                :simple="true"
                            />
                        @endif

                        <x-admin.rich-editor
                            wire:key="lesson-contenu-{{ $lessonId ?? 'new' }}-{{ $editMode ? 'e' : 'c' }}-{{ $formTab }}"
                            model="contenu"
                            :value="$contenu"
                            label="Contenu pédagogique"
                            :required="$type !== 'exercice'"
                            placeholder="Rédigez le cours ici : titres, paragraphes, listes…"
                            hint="Barre d’outils : gras, italique, titres, listes à puces/numéros, liens, citation. Aucun code HTML à écrire — le formatage est enregistré en base automatiquement."
                            min-height="280px"
                        />

                        @if (in_array($type, ['audio', 'video', 'mixte'], true))
                            <div>
                                <label class="text-xs font-bold uppercase text-[#757683]">Transcript / transcription</label>
                                <textarea wire:model="transcript" rows="6" class="mt-1 w-full rounded-xl border border-[#c5c5d4] px-3 py-2.5 text-sm" placeholder="Texte de la piste audio/vidéo (optionnel)…"></textarea>
                                <p class="text-[11px] text-[#757683] mt-1">Texte brut pour accessibilité / sous-titres. Pas besoin de HTML ici.</p>
                            </div>
                        @endif
                    @endif

                    <div class="sticky bottom-0 -mx-5 -mb-5 px-5 py-4 bg-white border-t border-[#e7eeff] flex flex-wrap justify-end gap-2">
                        <button type="button" @click="open = false" class="px-4 py-2.5 rounded-xl border border-[#c5c5d4] font-bold text-[#001a61]">Annuler</button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#001a61] text-white font-bold hover:bg-[#0a2e8c]"
                            wire:loading.attr="disabled" wire:target="save,videoFile,audioFile,pdfFile">
                            <span wire:loading.remove wire:target="save">{{ $editMode ? 'Enregistrer' : 'Créer la leçon' }}</span>
                            <span wire:loading wire:target="save">Enregistrement…</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    <div
        x-show="del"
        x-cloak
        class="fixed inset-0 z-[100] flex items-center justify-center p-4"
        style="display: none;"
        @keydown.escape.window="if (del) del = false"
    >
            <div class="absolute inset-0 bg-[#131c2a]/55" @click="del = false"></div>
            <div class="relative adf-modal-panel bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 border border-[#c5c5d4]" @click.stop>
                <h3 class="text-lg font-extrabold text-[#001a61]">Supprimer la leçon ?</h3>
                <p class="text-sm text-[#444652] mt-2">Action irréversible. Notes et soumissions liées peuvent être impactées.</p>
                <div class="mt-5 flex justify-end gap-2">
                    <button type="button" @click="del = false" class="px-4 py-2 rounded-xl border border-[#c5c5d4] font-bold">Annuler</button>
                    <button type="button" wire:click="delete" class="px-4 py-2 rounded-xl bg-red-600 text-white font-bold">Supprimer</button>
                </div>
            </div>
        </div>
</div>
