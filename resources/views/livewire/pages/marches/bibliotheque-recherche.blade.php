<div class="bg-[#f9f9ff] min-h-screen">
    @include('partials.markets-nav')
    <section class="max-w-[1280px] mx-auto px-5 lg:px-16 py-12">
        <h1 class="text-3xl lg:text-4xl font-extrabold text-[#001a61]">Centre d'Analyses &amp; Intelligence Économique</h1>
        <p class="text-[#444652] mt-3 max-w-2xl">Bibliothèque premium d’analyses financières et de prévisions macroéconomiques sur les marchés africains.</p>

        <form wire:submit.prevent="$refresh" class="mt-8 flex flex-col sm:flex-row gap-2 bg-white border border-[#c5c5d4] rounded-xl p-2">
            <div class="flex-1 flex items-center gap-2 px-3">
                <span class="material-symbols-outlined text-[#757683]">search</span>
                <input type="search" wire:model.live.debounce.300ms="q" placeholder="Rechercher une publication, un mot-clé..." class="w-full border-0 focus:ring-0 text-sm">
            </div>
            <button type="submit" class="px-6 py-3 rounded-lg bg-[#001a61] text-white font-bold">Rechercher</button>
        </form>

        <div class="mt-10 grid lg:grid-cols-4 gap-8">
            <aside class="space-y-6">
                <div>
                    <h3 class="font-bold text-[#001a61] mb-3">Secteurs / catégories</h3>
                    <div class="space-y-2">
                        <button type="button" wire:click="$set('categorie','')" @class(['text-sm block w-full text-left px-3 py-2 rounded', 'bg-[#001a61] text-white' => $categorie==='', 'hover:bg-[#e7eeff]' => $categorie!==''])>Toutes</button>
                        @foreach ($categories as $cat)
                            <button type="button" wire:click="$set('categorie','{{ $cat }}')" @class(['text-sm block w-full text-left px-3 py-2 rounded', 'bg-[#001a61] text-white' => $categorie===$cat, 'hover:bg-[#e7eeff]' => $categorie!==$cat])>{{ $cat }}</button>
                        @endforeach
                    </div>
                </div>
            </aside>

            <div class="lg:col-span-3">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-bold text-[#001a61]">Publications récentes <span class="text-[#757683] font-medium">({{ $articles->total() }})</span></h2>
                </div>
                <div class="grid sm:grid-cols-2 gap-5">
                    @forelse ($articles as $article)
                        <a href="{{ route('actualite-detail', $article->slug) }}" class="bg-white border border-[#c5c5d4] rounded-xl overflow-hidden hover:shadow-md transition group">
                            @if ($article->image_url)
                                <div class="h-40 bg-[#dae3f6] overflow-hidden">
                                    <img src="{{ $article->image_url }}" alt="" class="w-full h-full object-cover group-hover:scale-105 transition">
                                </div>
                            @else
                                <div class="h-40 bg-gradient-to-br from-[#001a61] to-[#3d58b5]"></div>
                            @endif
                            <div class="p-4">
                                <div class="flex items-center gap-2 text-[11px] uppercase tracking-wide text-[#757683] mb-2">
                                    <span class="px-2 py-0.5 rounded bg-[#ffbf00]/20 text-[#5c4300] font-bold">{{ $article->categorie ?? 'Analyse' }}</span>
                                    <span>{{ optional($article->published_at)->translatedFormat('M Y') }}</span>
                                </div>
                                <h3 class="font-bold text-[#001a61] line-clamp-2">{{ $article->titre }}</h3>
                                <p class="text-sm text-[#444652] mt-2 line-clamp-2">{{ $article->extrait }}</p>
                                <p class="text-xs text-[#757683] mt-3">Par {{ $article->user?->name ?? 'Rédaction AF' }}</p>
                            </div>
                        </a>
                    @empty
                        <p class="col-span-2 text-center text-[#757683] py-12">Aucune publication trouvée.</p>
                    @endforelse
                </div>
                <div class="mt-8">{{ $articles->links() }}</div>
            </div>
        </div>

        <div class="mt-16 rounded-2xl bg-[#001a61] text-white p-8 md:p-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <h2 class="text-2xl font-extrabold">Ne manquez aucune analyse stratégique.</h2>
                <p class="text-white/70 mt-2">Recevez notre newsletter hebdomadaire.</p>
            </div>
            <a href="{{ route('newsletter') }}" class="inline-flex px-6 py-3 rounded-lg bg-[#ffbf00] text-[#261a00] font-extrabold">S'abonner</a>
        </div>
    </section>
</div>
