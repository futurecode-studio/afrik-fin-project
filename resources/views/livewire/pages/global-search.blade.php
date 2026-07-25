<div class="max-w-4xl mx-auto px-4 py-16">
    <h1 class="text-3xl md:text-4xl font-extrabold text-[#001a61]">Recherche</h1>
    <p class="text-[#444652] mt-2">Formations, actualités et événements.</p>

    <form class="mt-8" onsubmit="return false;">
        <input type="search" wire:model.live.debounce.300ms="q" placeholder="Que recherchez-vous ?"
            class="w-full rounded-2xl border border-[#c5c5d4] bg-white/80 px-5 py-4 text-lg text-[#001a61] shadow-sm focus:ring-2 focus:ring-[#001a61]/20">
    </form>

    @if (mb_strlen(trim($q)) < 2)
        <p class="mt-10 text-[#757683]">Saisissez au moins 2 caractères.</p>
    @else
        <div class="mt-10 space-y-10">
            <section>
                <h2 class="text-sm font-bold uppercase tracking-wide text-[#757683] mb-3">Formations ({{ $formations->count() }})</h2>
                <div class="space-y-2">
                    @forelse ($formations as $f)
                        <a href="{{ route('formation-detail', $f->slug) }}" class="block adf-card-static p-4 hover:border-[#001a61]">
                            <p class="font-bold text-[#001a61]">{{ $f->titre }}</p>
                            <p class="text-sm text-[#757683] line-clamp-1">{{ $f->categorie }}</p>
                        </a>
                    @empty
                        <p class="text-sm text-[#757683]">Aucun résultat.</p>
                    @endforelse
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-wide text-[#757683] mb-3">Actualités ({{ $articles->count() }})</h2>
                <div class="space-y-2">
                    @forelse ($articles as $a)
                        <a href="{{ route('actualite-detail', $a->slug) }}" class="block adf-card-static p-4 hover:border-[#001a61]">
                            <p class="font-bold text-[#001a61]">{{ $a->titre }}</p>
                        </a>
                    @empty
                        <p class="text-sm text-[#757683]">Aucun résultat.</p>
                    @endforelse
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-wide text-[#757683] mb-3">Événements ({{ $events->count() }})</h2>
                <div class="space-y-2">
                    @forelse ($events as $e)
                        <a href="{{ route('event-detail', $e->slug) }}" class="block adf-card-static p-4 hover:border-[#001a61]">
                            <p class="font-bold text-[#001a61]">{{ $e->title }}</p>
                        </a>
                    @empty
                        <p class="text-sm text-[#757683]">Aucun résultat.</p>
                    @endforelse
                </div>
            </section>
        </div>
    @endif
</div>
