<div class="bg-[#f9f9ff] min-h-screen">
    @include('partials.markets-nav')
    <section class="max-w-[1100px] mx-auto px-5 lg:px-8 py-12">
        <nav class="text-sm text-[#757683] flex flex-wrap items-center gap-1 mb-6">
            <a href="{{ route('investir.hub') }}" class="hover:text-[#001a61]">Investissements</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <a href="{{ route('marches.produits-structures') }}" class="hover:text-[#001a61]">Certificats</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <span class="text-[#001a61] font-medium">{{ $product->mnemonic ?? $product->slug }}</span>
        </nav>

        <div class="flex flex-wrap items-center gap-2 mb-3">
            <span class="px-2.5 py-1 rounded-full bg-[#e7eeff] text-[#001a61] text-xs font-bold uppercase">{{ str_replace('_',' ', $product->product_type) }}</span>
            <span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-800 text-xs font-bold">Risque {{ $product->risk_level }}/7</span>
        </div>
        <h1 class="text-3xl lg:text-4xl font-extrabold text-[#001a61]">{{ $product->name }}</h1>
        <p class="text-[#757683] mt-2">ISIN: {{ $product->isin ?? '—' }} | Mnémonique: {{ $product->mnemonic ?? '—' }}</p>

        <div class="mt-6 flex flex-wrap gap-3">
            <a href="{{ route('mise-en-relation') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-[#001a61] text-white font-bold">
                <span class="material-symbols-outlined">account_balance_wallet</span> Investir maintenant
            </a>
            <a href="{{ route('marches.produits-structures') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl border border-[#c5c5d4] font-bold text-[#001a61]">Retour catalogue</a>
        </div>

        <div class="mt-8 grid sm:grid-cols-3 gap-4">
            <div class="bg-white border border-[#c5c5d4] rounded-xl p-5">
                <p class="text-xs uppercase text-[#757683]">Cours actuel</p>
                <p class="text-2xl font-extrabold text-[#001a61] mt-1">{{ number_format($product->current_price ?? 0, 2, ',', ' ') }}</p>
                <p @class(['text-sm font-semibold mt-1', 'text-green-600' => ($product->variation_percent ?? 0) >= 0, 'text-red-600' => ($product->variation_percent ?? 0) < 0])>
                    {{ ($product->variation_percent ?? 0) >= 0 ? '+' : '' }}{{ number_format($product->variation_percent ?? 0, 2, ',', ' ') }}%
                </p>
            </div>
            <div class="bg-white border border-[#c5c5d4] rounded-xl p-5">
                <p class="text-xs uppercase text-[#757683]">Date d'échéance</p>
                <p class="text-2xl font-extrabold text-[#001a61] mt-1">{{ optional($product->maturity_date)->format('d/m/Y') ?? '—' }}</p>
            </div>
            <div class="bg-white border border-[#c5c5d4] rounded-xl p-5">
                <p class="text-xs uppercase text-[#757683]">Barrière</p>
                <p class="text-2xl font-extrabold text-[#001a61] mt-1">{{ number_format($product->barrier ?? 0, 2, ',', ' ') }}</p>
                <p class="text-sm text-[#757683] mt-1">Distance {{ number_format($product->distance_to_barrier_pct ?? 0, 1, ',', ' ') }}%</p>
            </div>
        </div>

        <div class="mt-8 grid lg:grid-cols-2 gap-6">
            <div class="bg-white border border-[#c5c5d4] rounded-xl p-6">
                <h2 class="font-bold text-[#001a61] text-lg mb-3">Description</h2>
                <p class="text-[#444652] leading-relaxed">{{ plain_text($product->description) }}</p>
                <p class="text-sm text-[#757683] mt-4">Sous-jacent : {{ $product->underlying ?? '—' }}</p>
            </div>
            <div class="bg-white border border-[#c5c5d4] rounded-xl p-6 space-y-4">
                <h2 class="font-bold text-[#001a61] text-lg">Caractéristiques</h2>
                <div class="flex gap-3">
                    <span class="material-symbols-outlined text-[#001a61]">verified_user</span>
                    <div>
                        <p class="font-bold text-[#001a61]">Protection du capital</p>
                        <p class="text-sm text-[#444652]">Barrière active (observation continue) au niveau {{ number_format($product->barrier ?? 0, 2, ',', ' ') }}.</p>
                    </div>
                </div>
                @if ($product->cap)
                    <div class="flex gap-3">
                        <span class="material-symbols-outlined text-[#001a61]">trending_up</span>
                        <div>
                            <p class="font-bold text-[#001a61]">Rendement plafonné</p>
                            <p class="text-sm text-[#444652]">Cap fixé à {{ number_format($product->cap, 2, ',', ' ') }}.</p>
                        </div>
                    </div>
                @endif
                <div class="text-sm grid grid-cols-2 gap-2 pt-2 border-t border-[#e7eeff]">
                    <div><span class="text-[#757683]">Strike</span><p class="font-bold">{{ number_format($product->strike ?? 0, 2, ',', ' ') }}</p></div>
                    <div><span class="text-[#757683]">Autocall</span><p class="font-bold">{{ optional($product->next_autocall_date)->format('d/m/Y') ?? '—' }}</p></div>
                </div>
            </div>
        </div>

        @if ($related->isNotEmpty())
            <div class="mt-10">
                <h2 class="font-bold text-[#001a61] mb-4">Autres certificats</h2>
                <div class="grid sm:grid-cols-3 gap-4">
                    @foreach ($related as $r)
                        <a href="{{ route('marches.certificat', $r->slug) }}" class="bg-white border border-[#c5c5d4] rounded-xl p-4 hover:border-[#001a61]">
                            <p class="font-bold text-[#001a61] line-clamp-2">{{ $r->name }}</p>
                            <p class="text-sm text-[#757683] mt-1">{{ number_format($r->current_price ?? 0, 2, ',', ' ') }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </section>
</div>
