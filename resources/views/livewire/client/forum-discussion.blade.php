<div>
    <div class="mb-6 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <a href="{{ route('client.formation', $formation->slug) }}" class="text-sm font-bold text-[#001a61] underline">← Cours</a>
            <h1 class="text-3xl font-extrabold text-[#001a61] mt-2">Forum de discussion</h1>
            <p class="text-[#444652] mt-1">{{ $formation->titre }}</p>
        </div>
        <a href="{{ route('client.ask-instructor') }}" class="text-sm font-bold bg-[#001a61] text-white px-4 py-2.5 rounded-xl">Poser une question</a>
    </div>

    <form wire:submit.prevent="post" class="bg-white border border-[#c5c5d4] rounded-2xl p-5 mb-6 space-y-3">
        <h2 class="font-bold text-[#001a61]">Nouvelle discussion</h2>
        <input wire:model="title" class="w-full rounded-lg border-[#c5c5d4]" placeholder="Titre">
        @error('title') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
        <textarea wire:model="body" rows="4" class="w-full rounded-lg border-[#c5c5d4]" placeholder="Votre question…"></textarea>
        @error('body') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
        <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#001a61] text-white font-bold">Publier</button>
    </form>

    <div class="space-y-4">
        @forelse ($threads as $thread)
            <article class="bg-white border border-[#c5c5d4] rounded-2xl p-5">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="font-bold text-[#001a61]">{{ $thread->title }}</h3>
                        <p class="text-xs text-[#757683] mt-1">{{ $thread->user?->name }} · {{ $thread->created_at->diffForHumans() }}</p>
                    </div>
                    <button type="button" wire:click="setReply({{ $thread->id }})" class="text-xs font-bold text-[#001a61]">Répondre</button>
                </div>
                <p class="text-sm text-[#444652] mt-3 whitespace-pre-line">{{ $thread->body }}</p>
                @if ($thread->replies->isNotEmpty())
                    <div class="mt-4 space-y-2 border-t border-[#e7eeff] pt-3">
                        @foreach ($thread->replies as $reply)
                            <div class="rounded-lg bg-[#f0f3ff] p-3 text-sm">
                                <p class="text-xs font-bold text-[#001a61]">{{ $reply->user?->name }}</p>
                                <p class="text-[#444652] mt-1 whitespace-pre-line">{{ $reply->body }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
                @if ($replyTo === $thread->id)
                    <div class="mt-3 space-y-2">
                        <textarea wire:model="replyBody" rows="3" class="w-full rounded-lg border-[#c5c5d4] text-sm"></textarea>
                        <button type="button" wire:click="reply" class="px-4 py-2 rounded-lg bg-[#001a61] text-white text-sm font-bold">Envoyer</button>
                    </div>
                @endif
            </article>
        @empty
            <p class="text-center text-[#757683] py-10">Aucune discussion pour le moment.</p>
        @endforelse
    </div>
</div>
