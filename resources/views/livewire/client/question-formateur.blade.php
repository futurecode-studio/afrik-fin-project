<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-extrabold text-[#001a61]">Poser une question au formateur</h1>
    <p class="text-[#444652] mt-2">Contactez votre formateur sur une formation active.</p>

    <form wire:submit.prevent="submit" class="mt-8 bg-white border border-[#c5c5d4] rounded-2xl p-6 space-y-4">
        <div>
            <label class="text-sm font-bold text-[#001a61]">Formation concernée</label>
            <select wire:model="formationId" class="w-full mt-1 rounded-lg border-[#c5c5d4]">
                <option value="">Sélectionner…</option>
                @foreach ($formations as $f)
                    <option value="{{ $f->id }}">{{ $f->titre }}</option>
                @endforeach
            </select>
            @error('formationId') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="text-sm font-bold text-[#001a61]">Sujet</label>
            <input wire:model="subject" class="w-full mt-1 rounded-lg border-[#c5c5d4]">
            @error('subject') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="text-sm font-bold text-[#001a61]">Message</label>
            <textarea wire:model="body" rows="6" class="w-full mt-1 rounded-lg border-[#c5c5d4]"></textarea>
            @error('body') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <button type="submit" class="px-6 py-3 rounded-xl bg-[#001a61] text-white font-bold">Envoyer</button>
    </form>

    <div class="mt-10 space-y-3">
        <h2 class="font-bold text-[#001a61]">Mes questions</h2>
        @forelse ($mine as $q)
            <div class="bg-white border border-[#c5c5d4] rounded-xl p-4">
                <div class="flex justify-between gap-2">
                    <p class="font-semibold text-[#001a61]">{{ $q->subject }}</p>
                    <span class="text-xs font-bold uppercase text-[#757683]">{{ $q->status }}</span>
                </div>
                <p class="text-xs text-[#757683] mt-1">{{ $q->formation?->titre }} · {{ $q->created_at->format('d/m/Y') }}</p>
                @if ($q->answer)
                    <p class="mt-3 text-sm bg-[#f0f3ff] rounded-lg p-3 whitespace-pre-line">{{ $q->answer }}</p>
                @endif
            </div>
        @empty
            <p class="text-sm text-[#757683]">Aucune question envoyée.</p>
        @endforelse
    </div>
</div>
