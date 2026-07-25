<div class="bg-[#f9f9ff] text-[#131c2a] min-h-screen">
    @include('partials.investir-nav')

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pt-10 pb-6">
        <a href="{{ route('investir.opcvm') }}" class="text-sm font-bold text-[#001a61] hover:underline">← FCP / OPCVM</a>
    </section>

    @if ($loading)
        <p class="text-center py-20 text-[#757683]">Chargement du fonds…</p>
    @elseif ($error || ! $fund)
        <section class="px-5 lg:px-16 max-w-[720px] mx-auto pb-20 text-center">
            <h1 class="text-2xl font-extrabold text-[#001a61]">Fonds indisponible</h1>
            <p class="mt-3 text-[#444652]">{{ $error ?? 'Aucune donnée.' }}</p>
            <button type="button" wire:click="refresh" class="mt-6 font-bold text-[#001a61] underline">Réessayer</button>
        </section>
    @else
        <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pb-8">
            <p class="text-sm font-semibold text-[#0a2e8c]">{{ $fund['category'] }} · {{ $fund['country'] }}</p>
            <h1 class="text-3xl md:text-4xl font-extrabold text-[#001a61] mt-2">{{ $fund['name'] }}</h1>
            <p class="text-[#444652] mt-2">{{ $fund['company'] }} @if(!empty($fund['isin'])) · ISIN {{ $fund['isin'] }}@endif</p>
        </section>

        <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pb-12">
            <div class="grid sm:grid-cols-3 gap-4">
                <div class="bg-white border border-[#c5c5d4] rounded-xl p-6">
                    <p class="text-sm text-[#757683]">Valeur liquidative</p>
                    <p class="text-3xl font-extrabold text-[#001a61] mt-2">{{ $fund['nav_value'] }}</p>
                </div>
                <div class="bg-white border border-[#c5c5d4] rounded-xl p-6">
                    <p class="text-sm text-[#757683]">Variation</p>
                    <p @class(['text-3xl font-extrabold mt-2', 'text-green-600' => ($fund['variation_percentage'] ?? 0) >= 0, 'text-red-600' => ($fund['variation_percentage'] ?? 0) < 0])>
                        {{ $fund['variation'] ?? '—' }}
                    </p>
                </div>
                <div class="bg-white border border-[#c5c5d4] rounded-xl p-6">
                    <p class="text-sm text-[#757683]">Date VL</p>
                    <p class="text-3xl font-extrabold text-[#001a61] mt-2">{{ \Carbon\Carbon::parse($fund['date'])->format('d/m/Y') }}</p>
                </div>
            </div>
            <div class="mt-4 flex flex-wrap gap-3">
                <button type="button" wire:click="refresh" class="text-sm font-bold border border-[#001a61] text-[#001a61] px-4 py-2 rounded hover:bg-[#e7eeff]">Actualiser</button>
                @if (!empty($fund['source_url']))
                    <a href="{{ $fund['source_url'] }}" target="_blank" rel="noopener" class="text-sm font-bold text-[#001a61] hover:underline py-2">Source Sikafinance ↗</a>
                @endif
                <a href="{{ route('investir.partenaires') }}" class="text-sm font-bold bg-[#001a61] text-white px-4 py-2 rounded hover:bg-[#0a2e8c]">Souscrire via un partenaire</a>
            </div>
        </section>

        @if (count($related))
            <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pb-20">
                <h2 class="text-xl font-bold text-[#001a61] mb-4">Autres fonds {{ $fund['category'] }}</h2>
                <div class="grid sm:grid-cols-3 gap-4">
                    @foreach ($related as $f)
                        <a href="{{ route('investir.fcp-detail', $f['id']) }}" class="bg-white border border-[#c5c5d4] rounded-xl p-4 hover:border-[#001a61] block">
                            <p class="font-bold text-[#001a61] line-clamp-2">{{ $f['name'] }}</p>
                            <p class="text-sm mt-2">{{ $f['nav_value'] }}</p>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    @endif
</div>
