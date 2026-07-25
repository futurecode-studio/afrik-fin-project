<div class="max-w-3xl mx-auto text-center">
    <span class="material-symbols-outlined text-6xl text-[#ffbf00]">workspace_premium</span>
    <h1 class="text-3xl font-extrabold text-[#001a61] mt-4">Félicitations, {{ Auth::user()->name }} !</h1>
    <p class="text-[#444652] mt-3 text-lg">
        Vous avez complété avec succès le programme
        <span class="font-bold text-[#001a61]">« {{ $formation->titre }} »</span>.
    </p>

    <div class="mt-8 grid sm:grid-cols-2 gap-4 text-left">
        <div class="bg-white border border-[#c5c5d4] rounded-xl p-5">
            <p class="text-xs uppercase text-[#757683]">Statut final</p>
            <p class="text-xl font-extrabold text-green-700 mt-1 flex items-center gap-2">
                <span class="material-symbols-outlined">check_circle</span> Formation terminée
            </p>
            <p class="text-sm text-[#757683] mt-1">Validé le {{ optional($enrollment->completed_at)->format('d/m/Y') ?? '—' }}</p>
        </div>
        <div class="bg-white border border-[#c5c5d4] rounded-xl p-5">
            <p class="text-xs uppercase text-[#757683]">Progression</p>
            <p class="text-xl font-extrabold text-[#001a61] mt-1">{{ (int) $enrollment->progress }}%</p>
            @if ($enrollment->hasCertificate())
                <a href="{{ route('client.certificate.show', $enrollment->id) }}" class="inline-block mt-2 text-sm font-bold text-[#001a61] underline">Voir le certificat</a>
            @endif
        </div>
    </div>

    <div class="mt-8 bg-white border border-[#c5c5d4] rounded-2xl overflow-hidden text-left">
        <div class="px-5 py-4 border-b border-[#e7eeff]"><h2 class="font-bold text-[#001a61]">Résumé du parcours</h2></div>
        <ul>
            @foreach ($moduleSummaries as $m)
                <li class="px-5 py-3 border-t border-[#e7eeff] flex items-center justify-between gap-3">
                    <div>
                        <p class="font-semibold text-[#001a61]">{{ $m['title'] }}</p>
                        <p class="text-xs text-[#757683]">{{ $m['lessons'] }} leçons</p>
                    </div>
                    @if ($m['quiz_score'] !== null)
                        <span @class(['text-sm font-bold', 'text-green-700' => $m['quiz_passed'], 'text-amber-700' => !$m['quiz_passed']])>
                            Quiz {{ $m['quiz_score'] }}%
                        </span>
                    @else
                        <span class="text-xs text-[#757683]">Pas de quiz</span>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>

    <div class="mt-8 flex flex-wrap justify-center gap-3">
        <a href="{{ route('client.formation', $formation->slug) }}" class="px-5 py-3 rounded-xl border border-[#c5c5d4] font-bold text-[#001a61]">Revoir le cours</a>
        <a href="{{ route('client.learning-history') }}" class="px-5 py-3 rounded-xl bg-[#001a61] text-white font-bold">Historique</a>
    </div>
</div>
