<div class="bg-[#f9f9ff] text-[#131c2a] min-h-screen">
    @include('partials.investir-nav')

    <section class="relative overflow-hidden px-5 lg:px-16 max-w-[1280px] mx-auto pt-12 pb-16">
        <div class="absolute -right-10 top-0 w-72 h-72 rounded-full bg-[#ffbf00]/20 blur-3xl pointer-events-none"></div>
        <p class="text-sm font-semibold tracking-widest uppercase text-[#0a2e8c] mb-3">Investir</p>
        <h1 class="text-3xl md:text-5xl font-extrabold text-[#001a61] max-w-3xl leading-tight">
            Investissez avec méthode, pas au hasard.
        </h1>
        <p class="mt-4 text-[#444652] max-w-2xl text-lg">
            Actions BRVM, obligations UEMOA et FCP/OPCVM — données de marché réelles pour construire votre parcours.
        </p>
        <div class="mt-8 flex flex-wrap gap-3">
            <a href="{{ config('services.diaspora_funnel.url') }}" target="_blank" rel="noopener noreferrer"
                class="bg-[#ffbf00] text-[#261a00] font-extrabold px-6 py-3 rounded hover:brightness-95 transition inline-flex items-center gap-2">
                {{ config('services.diaspora_funnel.label') }}
                <span class="material-symbols-outlined text-[18px]">open_in_new</span>
            </a>
            <a href="{{ route('guide-bourse') }}"
                class="bg-[#001a61] text-white font-bold px-6 py-3 rounded hover:bg-[#0a2e8c] transition">Guide Complet de la Bourse</a>
            <a href="{{ route('investir.profil-test') }}"
                class="border border-[#001a61] text-[#001a61] font-bold px-6 py-3 rounded hover:bg-[#e7eeff] transition">Évaluer mon profil</a>
            <a href="{{ route('investir.comment') }}"
                class="border border-[#c5c5d4] text-[#001a61] font-bold px-6 py-3 rounded hover:bg-[#e7eeff] transition">Comment investir</a>
        </div>
        <p class="mt-3 text-sm text-[#757683]">Vous êtes de la diaspora ? Évaluez votre profil investisseur en quelques minutes pour structurer vos investissements en Afrique.</p>
    </section>

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pb-12">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach ([
                ['label' => 'Actions BRVM', 'value' => $stockCount, 'href' => route('marches.cotations')],
                ['label' => 'Obligations', 'value' => $bondCount, 'href' => route('marches.obligations')],
                ['label' => 'FCP / OPCVM', 'value' => $fundCount, 'href' => route('investir.opcvm')],
                ['label' => 'BRVM Composite', 'value' => $index ? number_format((float)$index->value, 2, ',', ' ') : '—', 'href' => route('marches.indices')],
            ] as $kpi)
                <a href="{{ $kpi['href'] }}" class="bg-white border border-[#c5c5d4] rounded-xl p-5 hover:border-[#001a61] transition block">
                    <p class="text-sm text-[#757683]">{{ $kpi['label'] }}</p>
                    <p class="text-2xl font-extrabold text-[#001a61] mt-1">{{ $kpi['value'] }}</p>
                </a>
            @endforeach
        </div>
    </section>

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pb-16">
        <div class="grid md:grid-cols-3 gap-6">
            <a href="{{ route('marches.cotations') }}" class="group bg-white border border-[#c5c5d4] rounded-xl p-6 hover:shadow-md transition">
                <span class="material-symbols-outlined text-[#001a61] text-3xl">candlestick_chart</span>
                <h2 class="text-xl font-bold text-[#001a61] mt-3">Actions BRVM</h2>
                <p class="text-[#444652] mt-2 text-sm">Cotations, palmarès et fiches titres synchronisées.</p>
                <span class="inline-flex items-center gap-1 mt-4 text-[#001a61] font-bold text-sm group-hover:gap-2 transition-all">Explorer <span class="material-symbols-outlined text-sm">arrow_forward</span></span>
            </a>
            <a href="{{ route('investir.opcvm') }}" class="group bg-white border border-[#c5c5d4] rounded-xl p-6 hover:shadow-md transition">
                <span class="material-symbols-outlined text-[#001a61] text-3xl">account_balance</span>
                <h2 class="text-xl font-bold text-[#001a61] mt-3">FCP &amp; OPCVM</h2>
                <p class="text-[#444652] mt-2 text-sm">FCP du Bénin — VL du bulletin officiel BRVM.</p>
                <span class="inline-flex items-center gap-1 mt-4 text-[#001a61] font-bold text-sm group-hover:gap-2 transition-all">Voir les fonds <span class="material-symbols-outlined text-sm">arrow_forward</span></span>
            </a>
            <a href="{{ route('marches.obligations') }}" class="group bg-white border border-[#c5c5d4] rounded-xl p-6 hover:shadow-md transition">
                <span class="material-symbols-outlined text-[#001a61] text-3xl">receipt_long</span>
                <h2 class="text-xl font-bold text-[#001a61] mt-3">Obligations</h2>
                <p class="text-[#444652] mt-2 text-sm">Bons et obligations du Trésor UMOA-Titres.</p>
                <span class="inline-flex items-center gap-1 mt-4 text-[#001a61] font-bold text-sm group-hover:gap-2 transition-all">Marché obligataire <span class="material-symbols-outlined text-sm">arrow_forward</span></span>
            </a>
        </div>
    </section>

    @if($gainers->isNotEmpty())
        <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pb-20">
            <div class="flex items-end justify-between mb-4">
                <h2 class="text-2xl font-extrabold text-[#001a61]">Top hausses du jour</h2>
                <a href="{{ route('marches.palmares') }}" class="text-sm font-bold text-[#001a61] hover:underline">Palmarès</a>
            </div>
            <div class="grid sm:grid-cols-3 gap-4">
                @foreach ($gainers as $s)
                    <a href="{{ route('marches.action', $s->symbol) }}"
                        class="bg-white border border-[#c5c5d4] rounded-xl p-4 hover:border-[#001a61] transition">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-bold text-[#001a61]">{{ $s->symbol }}</p>
                                <p class="text-xs text-[#757683] line-clamp-1">{{ $s->company_name }}</p>
                            </div>
                            <span class="text-green-600 font-bold text-sm">+{{ number_format((float)$s->variation_percent, 2) }}%</span>
                        </div>
                        <p class="mt-3 text-lg font-semibold">{{ number_format((float)$s->current_price, 0, ',', ' ') }} FCFA</p>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
</div>
