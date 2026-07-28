<div class="max-w-2xl mx-auto py-8 px-4">
    <div class="bg-white border border-[#c5c5d4] rounded-2xl p-8">
        <p class="text-xs font-bold uppercase tracking-widest text-[#ffbf00] bg-[#001a61] inline-block px-3 py-1 rounded">
            {{ $quiz->is_final ? 'Session officielle' : 'Évaluation' }}
        </p>
        <h1 class="text-3xl font-extrabold text-[#001a61] mt-4">{{ $quiz->titre }}</h1>
        <p class="text-[#444652] mt-2">{{ plain_text($quiz->description ?? $formation->titre) }}</p>

        <div class="mt-8 grid sm:grid-cols-3 gap-4">
            <div class="rounded-xl bg-[#f0f3ff] p-4">
                <p class="text-xs uppercase text-[#757683]">Durée</p>
                <p class="text-xl font-extrabold text-[#001a61] mt-1">{{ $quiz->duree_minutes ?? '—' }} min</p>
            </div>
            <div class="rounded-xl bg-[#f0f3ff] p-4">
                <p class="text-xs uppercase text-[#757683]">Seuil de réussite</p>
                <p class="text-xl font-extrabold text-[#001a61] mt-1">{{ $quiz->score_minimum }}%</p>
            </div>
            <div class="rounded-xl bg-[#f0f3ff] p-4">
                <p class="text-xs uppercase text-[#757683]">Tentatives</p>
                <p class="text-xl font-extrabold text-[#001a61] mt-1">{{ $remaining }} / {{ $quiz->tentatives_max }}</p>
            </div>
        </div>

        <div class="mt-8 rounded-xl border border-[#e7eeff] p-5 text-sm text-[#444652] space-y-2">
            <p class="font-bold text-[#001a61]">Règlement</p>
            <p class="flex gap-2"><span class="material-symbols-outlined text-green-600 text-base">check_circle</span> Répondez à toutes les questions avant de soumettre.</p>
            <p class="flex gap-2"><span class="material-symbols-outlined text-green-600 text-base">check_circle</span> Le score minimum pour valider est de {{ $quiz->score_minimum }}%.</p>
            <p class="flex gap-2"><span class="material-symbols-outlined text-green-600 text-base">check_circle</span> Vous avez déjà effectué {{ $attempts }} tentative(s).</p>
        </div>

        <div class="mt-8 flex flex-wrap gap-3">
            <button type="button" wire:click="start" @disabled($remaining < 1)
                class="px-6 py-3 rounded-xl bg-[#001a61] text-white font-bold disabled:opacity-50">
                {{ $quiz->is_final ? 'Lancer l\'examen' : 'Commencer le quiz' }}
            </button>
            <a href="{{ route('client.formation', $formation->slug) }}" class="px-6 py-3 rounded-xl border border-[#c5c5d4] font-bold text-[#001a61]">Retour au cours</a>
        </div>
    </div>
</div>
