<div class="max-w-3xl mx-auto text-center">
    <span class="material-symbols-outlined text-6xl text-[#ffbf00]">workspace_premium</span>
    <p class="mt-4 text-xs font-bold uppercase tracking-widest text-[#0a2e8c]">Certification</p>
    <h1 class="text-3xl md:text-4xl font-extrabold text-[#001a61] mt-2">Examen réussi avec succès</h1>
    <p class="text-lg font-bold text-[#ffbf00] mt-2">{{ $mention }}</p>
    <p class="text-[#444652] mt-3">Score : <span class="font-extrabold text-[#001a61]">{{ (int) $attempt->score }}%</span> sur {{ $quiz->titre }}</p>

    <div class="mt-8 rounded-2xl bg-[#001a61] text-white p-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-left">
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-[#ffbf00] text-4xl">verified_user</span>
            <div>
                <p class="font-extrabold">Éligible au certificat</p>
                <p class="text-sm text-white/70">Document officiel Africaine des Finances · AMF-UMOA</p>
            </div>
        </div>
        @if ($enrollment->hasCertificate())
            <a href="{{ route('client.certificate.show', $enrollment->id) }}" class="px-5 py-3 rounded-xl bg-[#ffbf00] text-[#261a00] font-extrabold shrink-0">Obtenir mon certificat</a>
        @else
            <a href="{{ route('client.formation.completed', $formation->slug) }}" class="px-5 py-3 rounded-xl bg-[#ffbf00] text-[#261a00] font-extrabold shrink-0">Voir le parcours</a>
        @endif
    </div>

    <div class="mt-6 flex flex-wrap justify-center gap-3">
        <a href="{{ route('client.quiz.result', [$formation->slug, $quiz->id, $attempt->id]) }}" class="px-5 py-3 rounded-xl border border-[#c5c5d4] font-bold text-[#001a61]">Voir le détail des résultats</a>
        <a href="{{ route('client.learning-history') }}" class="px-5 py-3 rounded-xl border border-[#c5c5d4] font-bold text-[#001a61]">Historique</a>
    </div>
</div>
