<div>
    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-extrabold text-[#001a61]">Actualités de mon Portefeuille</h1>
            <p class="text-[#444652] mt-2">Fil d’actualité contextualisé selon vos titres détenus et votre liste de suivi.</p>
        </div>
        <div class="flex gap-2">
            @foreach (['portefeuille' => 'Portefeuille', 'watchlist' => 'Watchlist', 'all' => 'Tout'] as $k => $lab)
                <button type="button" wire:click="$set('filter','{{ $k }}')"
                    @class(['px-3 py-2 rounded-lg text-sm font-bold border', 'bg-[#001a61] text-white border-[#001a61]' => $filter===$k, 'border-[#c5c5d4] text-[#444652]' => $filter!==$k])>{{ $lab }}</button>
            @endforeach
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <aside class="space-y-4">
            <div class="bg-[#001a61] text-white rounded-xl p-5">
                <p class="text-xs uppercase text-white/60 flex items-center gap-1"><span class="material-symbols-outlined text-sm">account_balance_wallet</span> Portefeuille</p>
                <p class="text-2xl font-extrabold mt-2">{{ number_format($totalValue, 0, ',', ' ') }} FCFA</p>
                <p class="text-sm text-white/70 mt-1">{{ $holdings->count() }} lignes</p>
            </div>
            <div class="bg-white border border-[#c5c5d4] rounded-xl p-5">
                <h3 class="font-bold text-[#001a61] mb-3">Actifs détenus</h3>
                <ul class="space-y-2 text-sm">
                    @forelse ($holdings->take(8) as $h)
                        <li class="flex justify-between gap-2 border-b border-[#e7eeff] pb-2">
                            <span class="font-semibold text-[#001a61]">{{ $h->stock?->symbol ?? $h->label }}</span>
                            <span class="text-[#757683]">{{ number_format($h->quantity, 0, ',', ' ') }}</span>
                        </li>
                    @empty
                        <li class="text-[#757683]">Aucun holding — <a href="{{ route('client.patrimoine') }}" class="underline text-[#001a61]">ajouter</a></li>
                    @endforelse
                </ul>
            </div>
            <div class="bg-white border border-[#c5c5d4] rounded-xl p-5">
                <h3 class="font-bold text-[#001a61] mb-3">Tickers marché</h3>
                <ul class="space-y-2 text-sm">
                    @foreach ($tickers as $t)
                        <li class="flex justify-between">
                            <a href="{{ route('marches.action', $t->symbol) }}" class="font-bold text-[#001a61] hover:underline">{{ $t->symbol }}</a>
                            <span>{{ number_format($t->current_price, 0, ',', ' ') }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </aside>

        <div class="lg:col-span-2 space-y-4">
            @forelse ($articles as $article)
                <article class="bg-white border border-[#c5c5d4] rounded-xl p-5 hover:border-[#001a61] transition">
                    <div class="flex items-center gap-2 text-xs text-[#757683] mb-2">
                        <span class="px-2 py-0.5 rounded bg-[#e7eeff] text-[#001a61] font-bold uppercase">{{ $article->categorie ?? 'Analyse' }}</span>
                        <span>{{ optional($article->published_at)->format('d/m/Y H:i') }}</span>
                    </div>
                    <h2 class="text-lg font-bold text-[#001a61]">
                        <a href="{{ route('actualite-detail', $article->slug) }}" class="hover:underline">{{ $article->titre }}</a>
                    </h2>
                    <p class="text-sm text-[#444652] mt-2 line-clamp-2">{{ $article->extrait }}</p>
                    <p class="text-xs text-[#757683] mt-3">Par {{ $article->user?->name ?? 'Rédaction AF' }}</p>
                </article>
            @empty
                <div class="bg-white border border-[#c5c5d4] rounded-xl p-8 text-center text-[#757683]">
                    Aucune actualité liée à votre portefeuille pour le moment.
                    <a href="{{ route('actualites') }}" class="block mt-2 text-[#001a61] font-bold underline">Voir toutes les analyses</a>
                </div>
            @endforelse
        </div>
    </div>
</div>
