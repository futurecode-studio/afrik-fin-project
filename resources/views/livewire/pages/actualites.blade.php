<div class="bg-[#f9f9ff] text-[#131c2a] min-h-screen">
    <section class="bg-[#001a61] text-white">
        <div class="max-w-[1280px] mx-auto px-5 lg:px-16 py-16">
            <p class="text-sm font-semibold tracking-widest uppercase text-[#ffbf00] mb-3">Analyses</p>
            <h1 class="text-3xl md:text-4xl font-extrabold">Actualités des marchés</h1>
            <p class="mt-3 text-white/80 max-w-2xl">Analyses, décryptages et opportunités sur la BRVM et l’espace UEMOA.</p>
        </div>
    </section>

    <section class="max-w-[1280px] mx-auto px-5 lg:px-16 py-12">
        @php $featured = $articles->first(); $rest = $articles->skip(1); @endphp

        @if ($featured)
            <a href="{{ route('actualite-detail', $featured->slug) }}"
                class="grid lg:grid-cols-2 gap-0 bg-white border border-[#c5c5d4] rounded-2xl overflow-hidden hover:border-[#001a61] transition mb-10">
                <div class="min-h-[240px] bg-[#e7eeff]">
                    @if ($featured->image_url)
                        <img src="{{ $featured->image_url }}" alt="" class="w-full h-full object-cover min-h-[240px]">
                    @endif
                </div>
                <div class="p-6 lg:p-10 flex flex-col justify-center">
                    <span class="text-xs font-bold uppercase tracking-wide text-[#0a2e8c]">{{ $featured->categorie }}</span>
                    <h2 class="text-2xl md:text-3xl font-extrabold text-[#001a61] mt-2">{{ $featured->titre }}</h2>
                    <p class="text-[#444652] mt-3 line-clamp-3">{{ $featured->extrait }}</p>
                    <p class="text-sm text-[#757683] mt-4">{{ optional($featured->published_at)->format('d/m/Y') }}</p>
                </div>
            </a>
        @endif

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($rest as $article)
                <a href="{{ route('actualite-detail', $article->slug) }}"
                    class="bg-white border border-[#c5c5d4] rounded-xl overflow-hidden hover:border-[#001a61] transition block">
                    <div class="h-44 bg-[#e7eeff]">
                        @if ($article->image_url)
                            <img src="{{ $article->image_url }}" alt="" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div class="p-5">
                        <span class="text-xs font-bold text-[#0a2e8c]">{{ $article->categorie }}</span>
                        <h3 class="font-bold text-[#001a61] mt-2 line-clamp-2">{{ $article->titre }}</h3>
                        <p class="text-sm text-[#444652] mt-2 line-clamp-2">{{ $article->extrait }}</p>
                        <p class="text-xs text-[#757683] mt-3">{{ optional($article->published_at)->format('d/m/Y') }}</p>
                    </div>
                </a>
            @empty
                @if (!$featured)
                    <p class="col-span-full text-center py-16 text-[#757683]">Aucune actualité publiée.</p>
                @endif
            @endforelse
        </div>
    </section>
</div>
