<div class="max-w-3xl mx-auto">
    <p class="text-xs font-bold uppercase tracking-widest text-[#757683]">Résultats de certification</p>
    <h1 class="text-3xl font-extrabold text-[#001a61] mt-2">Score obtenu : {{ (int) $attempt->score }}%</h1>
    <p class="text-[#444652] mt-2">{{ $attempt->is_passed ? 'Examen réussi.' : 'Persévérance est la clé — le seuil était de '.$quiz->score_minimum.'%.' }}</p>

    <div class="mt-6 flex flex-wrap gap-3">
        <a href="{{ route('client.formation', $formation->slug) }}" class="px-5 py-3 rounded-xl bg-[#001a61] text-white font-bold">Revoir les cours</a>
        @if ($quiz->canAttempt(Auth::id()))
            <a href="{{ route('client.quiz.intro', [$formation->slug, $quiz->id]) }}" class="px-5 py-3 rounded-xl border border-[#c5c5d4] font-bold text-[#001a61]">Réessayer</a>
        @endif
    </div>

    @unless ($attempt->is_passed)
        <div class="mt-8 adf-card-static overflow-hidden">
            <div class="px-5 py-4 border-b border-[#e7eeff]">
                <h2 class="font-bold text-[#001a61]">Modules recommandés à réviser</h2>
                <p class="text-sm text-[#757683]">Priorisez ces modules avant une nouvelle tentative</p>
            </div>
            <ul>
                @foreach ($recommended as $module)
                    @php $first = $module->lessons->first(); @endphp
                    <li class="px-5 py-3 border-t border-[#e7eeff] flex items-center justify-between gap-3">
                        <div>
                            <p class="font-semibold text-[#001a61]">{{ $module->titre }}</p>
                            <p class="text-xs text-[#757683]">{{ $module->lessons->count() }} leçons</p>
                        </div>
                        <a href="{{ route('client.formation', ['slug' => $formation->slug, 'lecon' => $first?->id]) }}"
                            class="text-sm font-bold text-[#001a61] underline">Réviser</a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endunless

    <div class="mt-8 adf-card-static overflow-hidden">
        <div class="px-5 py-4 border-b border-[#e7eeff]">
            <h2 class="font-bold text-[#001a61]">Programme complet</h2>
            <p class="text-sm text-[#757683]">{{ $modules->count() }} modules</p>
        </div>
        <ul>
            @foreach ($modules as $module)
                <li class="px-5 py-3 border-t border-[#e7eeff] flex items-center justify-between gap-3">
                    <div>
                        <p class="font-semibold text-[#001a61]">{{ $module->titre }}</p>
                        <p class="text-xs text-[#757683]">{{ $module->lessons->count() }} leçons</p>
                    </div>
                    <a href="{{ route('client.formation', $formation->slug) }}" class="text-sm font-bold text-[#001a61] underline">Ouvrir</a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
