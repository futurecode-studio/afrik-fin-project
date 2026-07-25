<div>
    <h1 class="text-3xl font-extrabold text-[#001a61]">Mes favoris</h1>
    <p class="text-[#444652] mt-2">Leçons et contenus que vous avez mis de côté.</p>

    <div class="mt-8 grid md:grid-cols-2 gap-4">
        @forelse ($favorites as $fav)
            <article class="bg-white border border-[#c5c5d4] rounded-xl p-5 flex gap-3">
                <span class="material-symbols-outlined text-[#ffbf00] text-3xl">bookmark</span>
                <div class="flex-1 min-w-0">
                    @if ($fav->lesson)
                        <p class="text-xs uppercase text-[#0a2e8c] font-bold">{{ $fav->lesson->module?->formation?->titre }}</p>
                        <a href="{{ route('client.formation', ['slug' => $fav->lesson->module?->formation?->slug, 'lecon' => $fav->module_lesson_id]) }}"
                            class="font-bold text-[#001a61] hover:underline">{{ $fav->lesson->titre }}</a>
                        <p class="text-xs text-[#757683] mt-1">{{ $fav->lesson->module?->titre }}</p>
                    @elseif ($fav->article)
                        <a href="{{ route('actualite-detail', $fav->article->slug) }}" class="font-bold text-[#001a61] hover:underline">{{ $fav->article->titre }}</a>
                    @else
                        <p class="font-bold text-[#001a61]">{{ $fav->label ?? 'Favori' }}</p>
                    @endif
                </div>
                <button type="button" wire:click="remove({{ $fav->id }})" class="text-[#757683] hover:text-red-600" title="Retirer">
                    <span class="material-symbols-outlined">delete</span>
                </button>
            </article>
        @empty
            <div class="col-span-full text-center py-16 text-[#757683]">
                Aucun favori. Marquez une leçon depuis le lecteur de cours.
            </div>
        @endforelse
    </div>
</div>
