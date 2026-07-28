@php
    $type = $lesson->type;
    $resumeAt = (int) ($videoProgress->video_position ?? 0);
@endphp

{{-- Vidéo --}}
@if (in_array($type, ['video', 'mixte'], true) && $lesson->video_url)
    <div class="mb-6" x-data="{
        speed: 1,
        showResume: {{ $resumeAt > 15 ? 'true' : 'false' }},
        resumeAt: {{ $resumeAt }},
        lastSave: 0,
        initPlayer() {
            const v = this.$refs.player;
            if (!v) return;
            v.playbackRate = this.speed;
            if (this.resumeAt > 15 && !this.showResume) {
                v.currentTime = this.resumeAt;
            }
        },
        applyResume() {
            const v = this.$refs.player;
            if (v) v.currentTime = this.resumeAt;
            this.showResume = false;
        },
        skipResume() {
            this.showResume = false;
            this.resumeAt = 0;
        },
        onTimeUpdate() {
            const v = this.$refs.player;
            if (!v) return;
            const t = Math.floor(v.currentTime);
            if (t - this.lastSave >= 5) {
                this.lastSave = t;
                $wire.saveVideoProgress(t, Math.floor(v.duration || 0));
                $wire.set('noteVideoSeconds', t);
            }
        },
        setSpeed(s) {
            this.speed = s;
            if (this.$refs.player) this.$refs.player.playbackRate = s;
        }
    }" x-init="initPlayer()">
        <div class="aspect-video rounded-xl overflow-hidden bg-[#001a61] relative">
            @if ($lesson->youtube_id)
                <iframe src="https://www.youtube.com/embed/{{ $lesson->youtube_id }}" class="w-full h-full" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
            @else
                <video x-ref="player" controls class="w-full h-full" src="{{ $lesson->video_url }}"
                    @timeupdate="onTimeUpdate()" @pause="onTimeUpdate()">
                    <a href="{{ $lesson->video_url }}" target="_blank" class="text-white p-4 block">Ouvrir la vidéo</a>
                </video>
                <div x-show="showResume" x-cloak class="absolute inset-0 bg-black/60 flex items-center justify-center p-4">
                    <div class="bg-white rounded-2xl p-5 max-w-sm text-center shadow-xl">
                        <p class="font-extrabold text-[#001a61]">Reprendre à {{ gmdate('i:s', $resumeAt) }} ?</p>
                        <p class="text-sm text-[#757683] mt-1">Vous aviez une progression sauvegardée.</p>
                        <div class="mt-4 flex gap-2 justify-center">
                            <button type="button" @click="applyResume()" class="px-4 py-2 rounded-xl bg-[#001a61] text-white font-bold text-sm">Reprendre</button>
                            <button type="button" @click="skipResume()" class="px-4 py-2 rounded-xl border border-[#c5c5d4] font-bold text-sm text-[#001a61]">Depuis le début</button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        @unless ($lesson->youtube_id)
            <div class="mt-2 flex flex-wrap items-center gap-2">
                <span class="text-xs font-bold text-[#757683] uppercase">Vitesse</span>
                @foreach ([0.75, 1, 1.25, 1.5, 2] as $s)
                    <button type="button" @click="setSpeed({{ $s }})"
                        :class="speed === {{ $s }} ? 'bg-[#001a61] text-white' : 'bg-[#e7eeff] text-[#001a61]'"
                        class="px-2.5 py-1 rounded-lg text-xs font-bold">{{ $s }}×</button>
                @endforeach
            </div>
        @endunless
    </div>
@endif

{{-- Audio --}}
@if ($type === 'audio' || ($lesson->audio_url && $type !== 'video'))
    <div class="rounded-2xl bg-gradient-to-br from-[#001a61] to-[#0a2e8c] text-white p-6 mb-6">
        <div class="flex items-center gap-3 mb-4">
            <span class="material-symbols-outlined text-4xl text-[#ffbf00]">headphones</span>
            <div>
                <p class="font-bold">Leçon audio</p>
                <p class="text-sm text-white/70">Écoutez puis marquez comme terminé</p>
            </div>
        </div>
        @if ($lesson->audio_url)
            <audio controls class="w-full" src="{{ $lesson->audio_url }}"></audio>
            <a href="{{ $lesson->audio_url }}" download class="inline-flex items-center gap-1 mt-3 text-sm font-bold text-[#ffbf00]">
                <span class="material-symbols-outlined text-base">cloud_download</span> Télécharger l'audio
            </a>
        @else
            <p class="text-sm text-white/70">Fichier audio à venir.</p>
        @endif
        @if ($lesson->transcript)
            <details class="mt-4 rounded-xl bg-white/10 p-3">
                <summary class="cursor-pointer font-bold text-sm text-[#ffbf00]">Transcription</summary>
                <div class="mt-2 text-sm text-white/85 whitespace-pre-line max-h-48 overflow-y-auto">{{ $lesson->transcript }}</div>
            </details>
        @endif
    </div>
@endif

{{-- PDF --}}
@if ($type === 'pdf' || ($lesson->pdf_url && in_array($type, ['pdf', 'mixte'], true)))
    <div class="rounded-xl border border-[#c5c5d4] overflow-hidden mb-6 bg-[#f0f3ff]"
        x-data="{ zoom: 100 }">
        <div class="flex flex-wrap items-center justify-between gap-2 px-4 py-3 border-b border-[#c5c5d4] bg-white">
            <p class="font-bold text-[#001a61] text-sm truncate">{{ $lesson->titre }}.pdf</p>
            <div class="flex items-center gap-2">
                <button type="button" @click="zoom = Math.max(75, zoom - 25)" class="px-2 py-1 rounded-lg bg-[#e7eeff] text-[#001a61] text-xs font-bold">−</button>
                <span class="text-xs font-bold text-[#001a61]" x-text="zoom + '%'"></span>
                <button type="button" @click="zoom = Math.min(200, zoom + 25)" class="px-2 py-1 rounded-lg bg-[#e7eeff] text-[#001a61] text-xs font-bold">+</button>
                @if ($lesson->pdf_url)
                    <a href="{{ $lesson->pdf_url }}" target="_blank" class="inline-flex items-center gap-1 text-sm font-bold text-[#001a61]">
                        <span class="material-symbols-outlined text-base">download</span>
                    </a>
                @endif
            </div>
        </div>
        @if ($lesson->pdf_url)
            <div class="overflow-auto bg-white" style="max-height: 28rem">
                <iframe :style="'width:' + zoom + '%; height: 28rem; transform-origin: top left'"
                    src="{{ $lesson->pdf_url }}#toolbar=1" class="bg-white" title="PDF"></iframe>
            </div>
        @else
            <div class="p-10 text-center text-[#757683] text-sm">Document PDF non encore publié.</div>
        @endif
    </div>
@endif

{{-- Texte / contenu --}}
@if (in_array($type, ['texte', 'mixte', 'video', 'audio', 'pdf'], true) && ($lesson->contenu || $lesson->description))
    <article class="prose prose-slate max-w-none text-[#444652]
        prose-headings:text-[#001a61] prose-a:text-[#0a2e8c] prose-strong:text-[#131c2a]
        prose-li:marker:text-[#001a61]">
        {!! $lesson->renderedContent() !!}
    </article>
@endif

{{-- Exercice --}}
@if ($lesson->isExercise())
    <div class="space-y-5">
        <div class="rounded-xl bg-[#e7eeff] border border-[#c5c5d4] p-5">
            <p class="text-xs font-bold uppercase text-[#0a2e8c] flex items-center gap-1"><span class="material-symbols-outlined text-base">history_edu</span> Évaluation</p>
            <h3 class="font-extrabold text-[#001a61] text-lg mt-1">{{ $lesson->titre }}</h3>
            <div class="mt-3 text-sm text-[#444652]">{{ plain_text($lesson->consigne ?? $lesson->description ?? $lesson->contenu ?? 'Suivez les consignes du formateur et déposez votre travail.') }}</div>
        </div>

        @if ($submission)
            @if ($submission->isCorrected())
                <div class="rounded-xl border border-green-200 bg-green-50 p-5">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <p class="text-xs font-bold uppercase text-green-800">Correction d'exercice</p>
                            <p class="text-3xl font-extrabold text-[#001a61] mt-1">{{ number_format($submission->score, 1, ',', ' ') }}<span class="text-base font-medium text-[#757683]"> / {{ number_format($submission->max_score, 0) }}</span></p>
                            <p class="text-sm mt-1 {{ $submission->isPassed() ? 'text-green-700' : 'text-amber-700' }}">
                                {{ $submission->isPassed() ? 'Réussi' : 'À retravailler' }} — seuil 70%
                            </p>
                        </div>
                        <span class="material-symbols-outlined text-4xl text-green-600">check_circle</span>
                    </div>
                    @if ($submission->feedback)
                        <div class="mt-4 text-sm text-[#444652] whitespace-pre-line border-t border-green-200 pt-3">{{ $submission->feedback }}</div>
                    @endif
                    @if ($submission->annotated_file_path)
                        <a href="{{ asset('storage/'.$submission->annotated_file_path) }}" target="_blank" class="inline-flex items-center gap-1 mt-3 text-sm font-bold text-[#001a61]">
                            <span class="material-symbols-outlined text-base">description</span> Fichier annoté
                        </a>
                    @endif
                </div>
            @else
                <div class="rounded-xl border border-[#c5c5d4] bg-white p-5 text-center">
                    <span class="material-symbols-outlined text-5xl text-green-600">check_circle</span>
                    <h3 class="text-xl font-extrabold text-[#001a61] mt-2">Exercice envoyé</h3>
                    <p class="text-[#444652] mt-1">En attente de correction par le formateur.</p>
                    <p class="text-xs uppercase text-[#757683] mt-4">Date d'envoi</p>
                    <p class="font-bold text-[#001a61]">{{ optional($submission->submitted_at)->format('d F Y H:i') }}</p>
                    @if ($submission->file_name)
                        <div class="mt-4 inline-flex items-center gap-2 rounded-lg bg-[#f0f3ff] px-4 py-2 text-sm">
                            <span class="material-symbols-outlined text-[#001a61]">picture_as_pdf</span>
                            <span class="font-semibold">{{ $submission->file_name }}</span>
                        </div>
                    @endif
                    @if ($submission->answer_text)
                        <div class="mt-4 text-left text-sm text-[#444652] bg-[#f9f9ff] rounded-lg p-4 whitespace-pre-line">{{ $submission->answer_text }}</div>
                    @endif
                </div>
            @endif
        @else
            <form wire:submit.prevent="submitExercise" class="space-y-4">
                <div>
                    <label class="text-sm font-bold text-[#001a61]">Votre réponse</label>
                    <textarea wire:model="exerciseAnswer" rows="8" class="mt-1 w-full rounded-xl border-[#c5c5d4]" placeholder="Rédigez votre analyse…"></textarea>
                    @error('exerciseAnswer') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-bold text-[#001a61]">Fichier joint (PDF, DOC — max 10 Mo)</label>
                    <input type="file" wire:model="exerciseFile" class="mt-1 block w-full text-sm">
                    <div wire:loading wire:target="exerciseFile" class="text-xs text-[#757683] mt-1">Téléversement…</div>
                    @error('exerciseFile') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-[#001a61] text-white font-bold" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="submitExercise">Envoyer l'exercice</span>
                    <span wire:loading wire:target="submitExercise">Envoi…</span>
                </button>
            </form>
        @endif
    </div>
@endif

@if (! $lesson->contenu && ! $lesson->description && ! $lesson->video_url && ! $lesson->audio_url && ! $lesson->pdf_url && ! $lesson->isExercise())
    <p class="text-[#757683] py-10 text-center">Contenu de la leçon à venir.</p>
@endif
