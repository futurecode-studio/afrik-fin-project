<div>
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#001a61]">Questions formateur</h1>
            <p class="text-[#444652] mt-2">{{ $openCount }} question(s) en attente</p>
        </div>
        <div class="flex gap-2">
            <input type="search" wire:model.live.debounce.300ms="q" placeholder="Rechercher…" class="rounded-xl border border-[#c5c5d4] px-3 py-2 text-sm">
            <select wire:model.live="status" class="rounded-xl border border-[#c5c5d4] px-3 py-2 text-sm font-semibold">
                <option value="open">Ouvertes</option>
                <option value="answered">Répondues</option>
                <option value="all">Toutes</option>
            </select>
        </div>
    </div>

    <div class="space-y-3">
        @forelse ($items as $item)
            <article class="adf-card-static p-5">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-bold uppercase text-[#0a2e8c]">{{ $item->formation?->titre ?? 'Formation' }}</p>
                        <h2 class="font-extrabold text-[#001a61] mt-1">{{ $item->subject }}</h2>
                        <p class="text-sm text-[#757683] mt-1">{{ $item->user?->name }} · {{ $item->created_at?->diffForHumans() }}</p>
                    </div>
                    <span @class([
                        'text-xs font-bold px-2 py-1 rounded-lg',
                        'bg-amber-100 text-amber-900' => $item->status !== 'answered',
                        'bg-green-100 text-green-800' => $item->status === 'answered',
                    ])>{{ $item->status === 'answered' ? 'Répondu' : 'Ouvert' }}</span>
                </div>
                <p class="text-sm text-[#444652] mt-3 whitespace-pre-line">{{ $item->body }}</p>
                @if ($item->answer)
                    <div class="mt-3 rounded-xl bg-[#e7eeff] p-3 text-sm text-[#001a61] whitespace-pre-line">{{ $item->answer }}</div>
                @endif
                <button type="button" wire:click="openReply({{ $item->id }})" class="mt-3 text-sm font-bold text-[#001a61] underline">
                    {{ $item->status === 'answered' ? 'Modifier la réponse' : 'Répondre' }}
                </button>
            </article>
        @empty
            <p class="text-center text-[#757683] py-16">Aucune question.</p>
        @endforelse
    </div>

    @if ($replyingId)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
            <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl">
                <h3 class="text-lg font-extrabold text-[#001a61]">Réponse formateur</h3>
                <textarea wire:model="answer" rows="6" class="mt-4 w-full rounded-xl border border-[#c5c5d4] px-3 py-2" placeholder="Votre réponse…"></textarea>
                @error('answer') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                <div class="mt-4 flex justify-end gap-2">
                    <button type="button" wire:click="closeReply" class="px-4 py-2 rounded-xl border border-[#c5c5d4] font-bold">Annuler</button>
                    <button type="button" wire:click="sendReply" class="px-4 py-2 rounded-xl bg-[#001a61] text-white font-bold">Envoyer</button>
                </div>
            </div>
        </div>
    @endif
</div>
