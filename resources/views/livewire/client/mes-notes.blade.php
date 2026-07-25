<div>
    <div class="mb-6 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#001a61]">Mes notes</h1>
            <p class="text-[#444652] mt-2">Archives personnelles de vos leçons.</p>
        </div>
        <input wire:model.live.debounce.300ms="q" type="search" placeholder="Rechercher…" class="rounded-lg border-[#c5c5d4] text-sm">
    </div>

    @forelse ($notes as $day => $items)
        <div class="mb-8">
            <h2 class="text-sm font-bold uppercase tracking-wide text-[#757683] mb-3">{{ \Carbon\Carbon::parse($day)->translatedFormat('l d F Y') }}</h2>
            <div class="space-y-3">
                @foreach ($items as $note)
                    <article class="bg-white border border-[#c5c5d4] rounded-xl p-4">
                        <div class="flex justify-between gap-3">
                            <div>
                                <p class="text-xs font-bold text-[#0a2e8c] uppercase">
                                    {{ $note->lesson?->module?->formation?->titre }}
                                    @if ($note->lesson) — {{ $note->lesson->module?->titre }} @endif
                                </p>
                                @if ($note->lesson && $note->lesson->module?->formation)
                                    <a href="{{ route('client.formation', ['slug' => $note->lesson->module->formation->slug, 'lecon' => $note->module_lesson_id]) }}"
                                        class="font-bold text-[#001a61] hover:underline">{{ $note->lesson->titre }}</a>
                                @endif
                            </div>
                            <button type="button" wire:click="deleteNote({{ $note->id }})" class="text-[#757683] hover:text-red-600">
                                <span class="material-symbols-outlined">delete</span>
                            </button>
                        </div>
                        <p class="text-sm text-[#444652] mt-3 whitespace-pre-line">{{ $note->body }}</p>
                        <div class="mt-2 flex flex-wrap items-center gap-3 text-xs text-[#757683]">
                            <span>{{ $note->created_at->format('H:i') }}</span>
                            @if ($note->video_seconds !== null && $note->lesson && $note->lesson->module?->formation)
                                <a href="{{ route('client.formation', ['slug' => $note->lesson->module->formation->slug, 'lecon' => $note->module_lesson_id]) }}#t={{ $note->video_seconds }}"
                                    class="inline-flex items-center gap-1 font-bold text-[#001a61] hover:underline">
                                    <span class="material-symbols-outlined text-sm">play_circle</span>
                                    Aller à {{ gmdate('i:s', $note->video_seconds) }}
                                </a>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    @empty
        <p class="text-center text-[#757683] py-16">Aucune note. Ajoutez-en depuis le lecteur de cours.</p>
    @endforelse
</div>
