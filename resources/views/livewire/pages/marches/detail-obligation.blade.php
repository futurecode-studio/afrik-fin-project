<div class="bg-[#f9f9ff] text-[#131c2a] min-h-screen">
    @include('partials.markets-nav')

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pt-8 pb-4">
        <a href="{{ route('marches.obligations') }}" class="inline-flex items-center gap-1 text-sm text-[#001a61] font-medium hover:underline">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span> Marché obligataire
        </a>
    </section>

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pb-16 grid lg:grid-cols-12 gap-6">
        <div class="lg:col-span-8 space-y-6">
            <div class="bg-white border border-[#c5c5d4] rounded-2xl p-6 md:p-8">
                <p class="text-sm uppercase tracking-wide text-[#757683]">{{ $bond->country }} · {{ $bond->interest_type_label }}</p>
                <h1 class="text-2xl md:text-3xl font-extrabold text-[#001a61] mt-2">{{ $bond->name }}</h1>
                <p class="text-[#444652] mt-1">{{ $bond->issuer }}</p>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
                    <div class="bg-[#f0f3ff] rounded-xl p-4">
                        <p class="text-xs text-[#757683] uppercase">Taux</p>
                        <p class="text-xl font-extrabold text-[#001a61]">{{ number_format((float)$bond->interest_rate, 2) }}%</p>
                    </div>
                    <div class="bg-[#f0f3ff] rounded-xl p-4">
                        <p class="text-xs text-[#757683] uppercase">Prix actuel</p>
                        <p class="text-xl font-extrabold text-[#001a61]">{{ number_format((float)$bond->current_price, 0, ',', ' ') }}</p>
                    </div>
                    <div class="bg-[#f0f3ff] rounded-xl p-4">
                        <p class="text-xs text-[#757683] uppercase">Maturité</p>
                        <p class="text-xl font-extrabold text-[#001a61]">{{ $bond->maturity_years }} an(s)</p>
                    </div>
                    <div class="bg-[#f0f3ff] rounded-xl p-4">
                        <p class="text-xs text-[#757683] uppercase">Risque</p>
                        <p class="text-xl font-extrabold text-[#001a61]">{{ $bond->risk_level_label }}</p>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-4 mt-6 text-sm">
                    <div class="border border-[#c5c5d4] rounded-xl p-4">
                        <p class="text-[#757683]">ISIN</p>
                        <p class="font-semibold">{{ $bond->isin_code ?: '—' }}</p>
                    </div>
                    <div class="border border-[#c5c5d4] rounded-xl p-4">
                        <p class="text-[#757683]">Nominal</p>
                        <p class="font-semibold">{{ number_format((float)$bond->nominal_value, 0, ',', ' ') }} {{ $bond->currency }}</p>
                    </div>
                    <div class="border border-[#c5c5d4] rounded-xl p-4">
                        <p class="text-[#757683]">Émission</p>
                        <p class="font-semibold">{{ optional($bond->issue_date)->format('d/m/Y') }}</p>
                    </div>
                    <div class="border border-[#c5c5d4] rounded-xl p-4">
                        <p class="text-[#757683]">Échéance</p>
                        <p class="font-semibold">{{ optional($bond->maturity_date)->format('d/m/Y') }}</p>
                    </div>
                    <div class="border border-[#c5c5d4] rounded-xl p-4">
                        <p class="text-[#757683]">Fréquence</p>
                        <p class="font-semibold">{{ $bond->payment_frequency_label }}</p>
                    </div>
                    <div class="border border-[#c5c5d4] rounded-xl p-4">
                        <p class="text-[#757683]">Invest. min.</p>
                        <p class="font-semibold">{{ number_format((float)$bond->minimum_investment, 0, ',', ' ') }} FCFA</p>
                    </div>
                </div>

                @if($bond->description)
                    <div class="mt-6 prose prose-sm max-w-none text-[#444652]">
                        {!! nl2br(e(plain_text($bond->description))) !!}
                    </div>
                @endif
            </div>
        </div>

        <aside class="lg:col-span-4 space-y-4">
            <div class="bg-[#001a61] text-white rounded-2xl p-6">
                <h3 class="font-bold text-lg mb-2">Souscrire / Conseiller</h3>
                <p class="text-sm text-white/80 mb-4">Un conseiller Africaine des Finances peut vous accompagner sur ce titre.</p>
                <a href="{{ route('contact') }}" class="inline-flex bg-[#ffbf00] text-[#001a61] font-bold px-4 py-3 rounded-lg w-full justify-center">
                    Prendre rendez-vous
                </a>
                @if($bond->source_url)
                    <a href="{{ $bond->source_url }}" target="_blank" rel="noopener"
                        class="inline-flex mt-3 border border-white/40 text-white font-medium px-4 py-2 rounded-lg w-full justify-center text-sm hover:bg-white/10">
                        Source officielle
                    </a>
                @endif
            </div>

            <div class="bg-white border border-[#c5c5d4] rounded-2xl p-6">
                <h3 class="font-bold text-[#001a61] mb-3">Obligations similaires</h3>
                <div class="space-y-2">
                    @forelse ($similar as $s)
                        <a href="{{ route('marches.obligation', $s->id) }}" class="block p-2 rounded hover:bg-[#f0f3ff]">
                            <p class="font-semibold text-sm text-[#001a61]">{{ $s->name }}</p>
                            <p class="text-xs text-[#757683]">{{ number_format((float)$s->interest_rate, 2) }}% · {{ $s->maturity_years }} an(s)</p>
                        </a>
                    @empty
                        <p class="text-sm text-[#757683]">Aucune similarité</p>
                    @endforelse
                </div>
            </div>
        </aside>
    </section>
</div>
