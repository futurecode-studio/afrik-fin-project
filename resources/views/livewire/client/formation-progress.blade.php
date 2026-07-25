<div>
    <a href="{{ route('client.formations') }}" class="inline-flex items-center gap-1 text-sm font-bold text-[#001a61] hover:underline">
        <span class="material-symbols-outlined text-base">arrow_back</span> Mes formations
    </a>

    <div class="mt-4 flex flex-col lg:flex-row lg:items-end justify-between gap-4">
        <div>
            <p class="text-xs font-bold uppercase tracking-widest text-[#757683]">Détail de progression</p>
            <h1 class="text-3xl font-extrabold text-[#001a61] mt-1">{{ $formation->titre }}</h1>
            <p class="text-[#444652] mt-2">{{ (int) $enrollment->progress }}% · {{ $completed->count() }}/{{ $lessons->count() }} leçons</p>
        </div>
        <div class="flex flex-wrap gap-2">
            @if ($resumeLesson)
                <a href="{{ route('client.formation', ['slug' => $formation->slug, 'lecon' => $resumeLesson->id]) }}"
                    class="inline-flex items-center gap-2 bg-[#001a61] text-white font-bold px-5 py-3 rounded-xl">
                    <span class="material-symbols-outlined">play_arrow</span> Reprendre
                </a>
            @endif
        </div>
    </div>

    <div class="mt-8 grid sm:grid-cols-3 gap-4">
        <div class="adf-card-static p-5">
            <p class="text-xs uppercase text-[#757683]">Temps estimé</p>
            <p class="text-2xl font-extrabold text-[#001a61] mt-1">{{ $estimatedMinutes }} min</p>
        </div>
        <div class="adf-card-static p-5">
            <p class="text-xs uppercase text-[#757683]">Temps écoulé (approx.)</p>
            <p class="text-2xl font-extrabold text-[#001a61] mt-1">{{ $doneMinutes }} min</p>
        </div>
        <div class="adf-card-static p-5">
            <p class="text-xs uppercase text-[#757683]">Statut</p>
            <p class="text-2xl font-extrabold text-[#001a61] mt-1 capitalize">{{ $enrollment->status }}</p>
        </div>
    </div>

    @if ($resumeModule && $resumeLesson)
        <div class="mt-6 rounded-2xl bg-[#001a61] text-white p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <p class="text-xs uppercase text-[#ffbf00] font-bold">Reprenez là où vous vous êtes arrêté</p>
                <p class="text-lg font-extrabold mt-1">{{ $resumeModule->titre }}</p>
                <p class="text-white/80 text-sm mt-1">{{ $resumeLesson->titre }}</p>
            </div>
            <a href="{{ route('client.formation', ['slug' => $formation->slug, 'lecon' => $resumeLesson->id]) }}"
                class="inline-flex items-center gap-2 bg-[#ffbf00] text-[#261a00] font-extrabold px-5 py-3 rounded-xl">
                <span class="material-symbols-outlined">play_circle</span> Continuer
            </a>
        </div>
    @endif

    @if ($quizResults->isNotEmpty())
        <div class="mt-8 adf-card-static overflow-hidden">
            <div class="px-5 py-4 border-b border-[#e7eeff]"><h2 class="font-bold text-[#001a61]">Résultats quiz & examens</h2></div>
            <ul>
                @foreach ($quizResults as $attempt)
                    <li class="px-5 py-3 border-t border-[#e7eeff] flex flex-wrap items-center justify-between gap-2">
                        <div>
                            <p class="font-semibold text-[#001a61]">{{ $attempt->quiz?->titre ?? 'Quiz' }}</p>
                            <p class="text-xs text-[#757683]">{{ $attempt->completed_at?->format('d/m/Y H:i') }}</p>
                        </div>
                        <span @class([
                            'text-sm font-bold px-3 py-1 rounded-lg',
                            'bg-green-100 text-green-800' => $attempt->is_passed,
                            'bg-amber-100 text-amber-900' => ! $attempt->is_passed,
                        ])>{{ (int) $attempt->score }}% · {{ $attempt->is_passed ? 'Réussi' : 'Échoué' }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mt-8 adf-card-static overflow-hidden">
        <div class="px-5 py-4 border-b border-[#e7eeff]"><h2 class="font-bold text-[#001a61]">Programme détaillé</h2></div>
        @foreach ($modules as $module)
            @php $moduleOpen = $this->isModuleUnlocked($module, $modules); @endphp
            <div class="border-b border-[#e7eeff] last:border-0">
                <div class="px-5 py-3 bg-[#f0f3ff] flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2 min-w-0">
                        @unless ($moduleOpen)
                            <span class="material-symbols-outlined text-[#757683]">lock</span>
                        @endunless
                        <h3 class="font-bold text-[#001a61]">{{ $module->titre }}</h3>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        @if ($module->quiz)
                            @php $passed = $module->quiz->isPassedByUser(auth()->id()); @endphp
                            <span class="text-xs font-bold {{ $passed ? 'text-green-700' : 'text-[#757683]' }}">
                                Quiz {{ $passed ? 'réussi' : 'à passer' }}
                            </span>
                        @endif
                        <span class="text-xs text-[#757683]">{{ $module->lessons->count() }} leçons</span>
                    </div>
                </div>
                <ul>
                    @foreach ($module->lessons as $lesson)
                        @php
                            $done = $completed->contains($lesson->id);
                            $unlocked = $this->isLessonUnlocked($module, $lesson, $modules);
                        @endphp
                        <li class="px-5 py-3 flex items-center gap-3 border-t border-[#e7eeff] {{ $unlocked ? '' : 'opacity-60' }}">
                            <span class="material-symbols-outlined {{ $done ? 'text-green-600' : ($unlocked ? 'text-[#001a61]' : 'text-[#757683]') }}">
                                {{ $done ? 'check_circle' : ($unlocked ? $lesson->icon() : 'lock') }}
                            </span>
                            <div class="flex-1 min-w-0">
                                @if ($unlocked)
                                    <a href="{{ route('client.formation', ['slug' => $formation->slug, 'lecon' => $lesson->id]) }}" class="font-semibold text-[#001a61] hover:underline">{{ $lesson->titre }}</a>
                                @else
                                    <span class="font-semibold text-[#757683]">{{ $lesson->titre }}</span>
                                @endif
                                <p class="text-xs text-[#757683] uppercase">{{ $lesson->type }}{{ $lesson->duree_estimee ? ' · '.$lesson->duree_estimee.' min' : '' }}</p>
                            </div>
                            <span class="text-xs font-bold {{ $done ? 'text-green-700' : 'text-[#757683]' }}">
                                {{ $done ? 'Terminé' : ($unlocked ? 'À faire' : 'Verrouillé') }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>
</div>
