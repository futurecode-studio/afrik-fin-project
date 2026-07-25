<div class="max-w-2xl mx-auto">
    <p class="text-xs font-bold uppercase tracking-widest text-[#0a2e8c]">Formation</p>
    <h1 class="text-3xl font-extrabold text-[#001a61] mt-2">Évaluation de la formation</h1>
    <p class="text-[#444652] mt-2">{{ $formation->titre }}</p>

    <form wire:submit.prevent="submit" class="mt-8 bg-white border border-[#c5c5d4] rounded-2xl p-6 space-y-5">
        <p class="font-bold text-[#001a61]">Appréciation générale</p>
        @foreach ([
            'rating_overall' => 'Note globale',
            'rating_content' => 'Qualité du contenu',
            'rating_instructor' => 'Expertise du formateur',
            'rating_difficulty' => 'Niveau de difficulté',
            'rating_materials' => 'Supports pédagogiques',
        ] as $field => $label)
            <div>
                <label class="text-sm font-medium text-[#444652]">{{ $label }} (1–5)</label>
                <input type="range" min="1" max="5" wire:model.live="{{ $field }}" class="w-full mt-1">
                <p class="text-sm font-bold text-[#001a61]">{{ $this->$field }}/5</p>
            </div>
        @endforeach
        <div>
            <label class="text-sm font-bold text-[#001a61]">Commentaire</label>
            <textarea wire:model="comment" rows="4" class="w-full mt-1 rounded-lg border-[#c5c5d4]" placeholder="Votre retour…"></textarea>
        </div>
        <button type="submit" class="w-full py-3 rounded-xl bg-[#001a61] text-white font-bold">Envoyer l'évaluation</button>
    </form>
</div>
