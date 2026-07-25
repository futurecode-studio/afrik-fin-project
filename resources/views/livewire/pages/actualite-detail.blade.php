<div class="bg-[#f9f9ff] text-[#131c2a] min-h-screen">
    <section class="max-w-[900px] mx-auto px-5 lg:px-8 pt-10">
        <a href="{{ route('actualites') }}" class="text-sm font-bold text-[#001a61] hover:underline">← Actualités</a>
        <div class="mt-6">
            <span class="text-xs font-bold uppercase tracking-wide text-[#0a2e8c]">{{ $article->categorie }}</span>
            <h1 class="text-3xl md:text-4xl font-extrabold text-[#001a61] mt-2 leading-tight">{{ $article->titre }}</h1>
            <p class="text-sm text-[#757683] mt-4">
                {{ optional($article->published_at)->format('d F Y') }}
                · {{ $article->user->name ?? 'Africaine des Finances' }}
            </p>
        </div>
        @if ($article->image_url)
            <div class="mt-8 rounded-2xl overflow-hidden border border-[#c5c5d4] max-h-[420px]">
                <img src="{{ $article->image_url }}" alt="" class="w-full h-full object-cover">
            </div>
        @endif
        @if ($article->extrait)
            <p class="mt-8 text-lg text-[#444652] font-medium leading-relaxed border-l-4 border-[#ffbf00] pl-4">{{ $article->extrait }}</p>
        @endif
        <article class="mt-8 prose prose-slate max-w-none text-[#444652] whitespace-pre-line leading-relaxed">
            {!! nl2br(e($article->contenu)) !!}
        </article>
    </section>

    @if ($relatedArticles->isNotEmpty())
        <section class="max-w-[1280px] mx-auto px-5 lg:px-16 py-16">
            <h2 class="text-xl font-bold text-[#001a61] mb-6">À lire aussi</h2>
            <div class="grid sm:grid-cols-3 gap-4">
                @foreach ($relatedArticles as $related)
                    <a href="{{ route('actualite-detail', $related->slug) }}" class="bg-white border border-[#c5c5d4] rounded-xl p-4 hover:border-[#001a61] block">
                        <p class="font-bold text-[#001a61] line-clamp-2">{{ $related->titre }}</p>
                        <p class="text-xs text-[#757683] mt-2">{{ optional($related->published_at)->format('d/m/Y') }}</p>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
</div>
