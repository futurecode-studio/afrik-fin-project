<div class="bg-[#f9f9ff] min-h-screen">
    @include('partials.markets-nav')
    <section class="max-w-[1200px] mx-auto px-5 lg:px-8 py-12">
        <h1 class="text-3xl font-extrabold text-[#001a61]">Suivi des Produits Structurés</h1>
        <p class="text-[#444652] mt-2">Encours, barrières, coupons mémorisés et dates d’autocall.</p>

        <div class="mt-8 grid sm:grid-cols-2 gap-4">
            <div class="bg-white border border-[#c5c5d4] rounded-xl p-6">
                <h2 class="font-bold text-[#001a61] mb-1">Encours indicatif</h2>
                <p class="text-3xl font-extrabold text-[#001a61]">{{ number_format($encours, 0, ',', ' ') }} <span class="text-base font-medium">FCFA</span></p>
                <p class="text-sm text-green-600 mt-1 flex items-center gap-1"><span class="material-symbols-outlined text-base">trending_up</span> Suivi live catalogue AF</p>
            </div>
            <div class="bg-white border border-[#c5c5d4] rounded-xl p-6">
                <h2 class="font-bold text-[#001a61] mb-1">Lignes actives</h2>
                <p class="text-3xl font-extrabold text-[#001a61]">{{ $products->count() }} <span class="text-base font-medium">Produits</span></p>
            </div>
        </div>

        <div class="mt-8 grid lg:grid-cols-2 gap-6">
            <div class="bg-white border border-[#c5c5d4] rounded-xl p-5">
                <h2 class="font-bold text-[#001a61] mb-4">Points de vigilance — Barrières</h2>
                <div class="space-y-3">
                    @forelse ($alerts as $p)
                        <a href="{{ route('marches.certificat', $p->slug) }}" class="block border border-red-100 bg-red-50/50 rounded-lg p-4 hover:border-red-300">
                            <div class="flex justify-between gap-2">
                                <p class="font-bold text-[#001a61]">{{ $p->name }}</p>
                                <span class="text-xs font-bold text-red-700">Alerte proximité</span>
                            </div>
                            <p class="text-sm text-[#444652] mt-1">Distance barrière : {{ number_format($p->distance_to_barrier_pct, 1, ',', ' ') }}% · Strike {{ number_format($p->strike ?? 0, 0, ',', ' ') }} · Barrière {{ number_format($p->barrier ?? 0, 0, ',', ' ') }}</p>
                        </a>
                    @empty
                        <p class="text-sm text-[#757683]">Aucune barrière critique.</p>
                    @endforelse
                </div>
            </div>
            <div class="bg-white border border-[#c5c5d4] rounded-xl p-5">
                <h2 class="font-bold text-[#001a61] mb-4 flex items-center gap-2"><span class="material-symbols-outlined">payments</span> Coupons mémorisés</h2>
                <ul class="space-y-3">
                    @forelse ($coupons as $p)
                        <li class="flex justify-between border-b border-[#e7eeff] pb-2">
                            <a href="{{ route('marches.certificat', $p->slug) }}" class="font-semibold text-[#001a61] hover:underline">{{ $p->name }}</a>
                            <span class="font-bold">{{ number_format($p->coupon_memorized, 0, ',', ' ') }}</span>
                        </li>
                    @empty
                        <li class="text-sm text-[#757683]">Aucun coupon mémorisé.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="mt-6 grid lg:grid-cols-2 gap-6">
            <div class="bg-white border border-[#c5c5d4] rounded-xl p-5">
                <h2 class="font-bold text-[#001a61] mb-4 flex items-center gap-2"><span class="material-symbols-outlined">alarm_on</span> Dates de rappel (Autocall)</h2>
                <ul class="space-y-3">
                    @forelse ($autocalls as $p)
                        <li class="flex items-center justify-between gap-2">
                            <div>
                                <a href="{{ route('marches.certificat', $p->slug) }}" class="font-semibold text-[#001a61] hover:underline">{{ $p->name }}</a>
                                <p class="text-xs text-[#757683]">Seuil {{ number_format($p->autocall_threshold_pct ?? 0, 0) }}% du strike</p>
                            </div>
                            <span class="text-sm font-bold">{{ optional($p->next_autocall_date)->format('d/m/Y') }}</span>
                        </li>
                    @empty
                        <li class="text-sm text-[#757683]">Pas de date d’autocall.</li>
                    @endforelse
                </ul>
            </div>
            <div class="bg-white border border-[#c5c5d4] rounded-xl p-5">
                <h2 class="font-bold text-[#001a61] mb-4">Catalogue</h2>
                <ul class="space-y-2">
                    @foreach ($products as $p)
                        <li>
                            <a href="{{ route('marches.certificat', $p->slug) }}" class="flex justify-between items-center py-2 border-b border-[#e7eeff] hover:bg-[#f0f3ff] px-1 rounded">
                                <span class="font-semibold text-[#001a61]">{{ $p->mnemonic ?? $p->name }}</span>
                                <span class="text-sm">{{ number_format($p->current_price ?? 0, 2, ',', ' ') }} · risque {{ $p->risk_level }}/7</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>
</div>
