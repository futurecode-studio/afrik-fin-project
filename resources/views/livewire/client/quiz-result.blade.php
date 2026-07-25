<div class="max-w-3xl mx-auto text-center">
    <span class="material-symbols-outlined text-6xl {{ $attempt->is_passed ? 'text-green-600' : 'text-amber-600' }}">
        {{ $attempt->is_passed ? 'check_circle' : 'info' }}
    </span>
    <h1 class="text-3xl font-extrabold text-[#001a61] mt-4">{{ $quiz->titre }}</h1>
    <p class="text-[#444652] mt-2">Votre score final</p>
    <p @class(['text-5xl font-extrabold mt-2', 'text-green-600' => $attempt->is_passed, 'text-red-600' => !$attempt->is_passed])>
        {{ (int) $attempt->score }}%
    </p>
    <p class="text-sm text-[#757683] mt-2">
        {{ $attempt->points_obtenus }}/{{ $attempt->points_total }} points
        · Seuil {{ $quiz->score_minimum }}%
        · {{ $attempt->is_passed ? 'Réussi' : 'Non validé' }}
    </p>
    @if ($attempt->duration)
        <p class="text-sm text-[#757683]">Temps utilisé : {{ $attempt->duration }} min</p>
    @endif

    <div class="mt-8 flex flex-wrap justify-center gap-3">
        <a href="{{ route('client.formation', $formation->slug) }}" class="px-5 py-3 rounded-xl bg-[#001a61] text-white font-bold">Continuer le cours</a>
        @if ($quiz->canAttempt(Auth::id()))
            <a href="{{ route('client.quiz.intro', [$formation->slug, $quiz->id]) }}" class="px-5 py-3 rounded-xl border border-[#c5c5d4] font-bold text-[#001a61]">Refaire le quiz</a>
        @endif
    </div>

    @if ($quiz->afficher_corrections)
        <div class="mt-10 text-left bg-white border border-[#c5c5d4] rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-[#e7eeff] font-bold text-[#001a61]">Revoir les réponses</div>
            @foreach ($quiz->questions as $i => $q)
                @php
                    $given = $attempt->reponses[$q->id] ?? $attempt->reponses[(string)$q->id] ?? [];
                    $given = is_array($given) ? $given : [$given];
                    $ok = $q->checkAnswers(array_map('intval', $given));
                @endphp
                <div class="px-5 py-4 border-t border-[#e7eeff]">
                    <p class="font-semibold text-[#001a61]">{{ $i+1 }}. {{ $q->question }}</p>
                    <p class="text-xs mt-1 {{ $ok ? 'text-green-700' : 'text-red-700' }}">{{ $ok ? 'Correct' : 'Incorrect' }}</p>
                    @if ($q->explication)
                        <p class="text-sm text-[#444652] mt-2">{{ $q->explication }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
