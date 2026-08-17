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
        </section>
    @else
        <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pb-8 flex items-start gap-4">
            @if (!empty($fund['logo']))
                <img src="{{ $fund['logo'] }}" alt="" class="w-14 h-14 object-contain bg-white border border-[#c5c5d4] rounded-xl p-1 shrink-0">
            @endif
            <div>
                <p class="text-sm font-semibold text-[#0a2e8c]">{{ $fund['category'] }} · {{ $fund['country'] }}</p>
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#001a61] mt-2">{{ $fund['name'] }}</h1>
                <p class="text-[#444652] mt-2">{{ $fund['company'] }}</p>
            </div>
        </section>

        <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pb-12">
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white border border-[#c5c5d4] rounded-xl p-6">
                    <p class="text-sm text-[#757683]">VL actuelle</p>
                    <p class="text-2xl font-extrabold text-[#001a61] mt-2">{{ $fund['nav_value'] }}</p>
                </div>
                <div class="bg-white border border-[#c5c5d4] rounded-xl p-6">
                    <p class="text-sm text-[#757683]">VL d’origine</p>
                    <p class="text-2xl font-extrabold text-[#001a61] mt-2">{{ $fund['origin_nav_value'] ?? '—' }}</p>
                </div>
                <div class="bg-white border border-[#c5c5d4] rounded-xl p-6">
                    <p class="text-sm text-[#757683]">Variation depuis l’origine</p>
                    @php $v = $fund['variation_percentage'] ?? null; @endphp
                    <p @class(['text-2xl font-extrabold mt-2', 'text-[#757683]' => $v === null, 'text-green-600' => $v !== null && $v >= 0, 'text-red-600' => $v !== null && $v < 0])>
                        {{ $fund['variation'] ?? 'ND' }}
                    </p>
                </div>
                <div class="bg-white border border-[#c5c5d4] rounded-xl p-6">
                    <p class="text-sm text-[#757683]">Date VL</p>
                    <p class="text-2xl font-extrabold text-[#001a61] mt-2">{{ \Carbon\Carbon::parse($fund['date'])->format('d/m/Y') }}</p>
                </div>
            </div>
            @if (!empty($fund['source']) || !empty($fund['source_note']))
                <p class="mt-4 text-xs text-[#757683]">
                    {{ $fund['source'] ?? '' }}
                    @if (!empty($fund['source_note'])) — {{ $fund['source_note'] }} @endif
                </p>
            @endif
            <div class="mt-6 flex flex-wrap gap-3">
                @if (!empty($fund['flyer_url']))
                    <a href="{{ $fund['flyer_url'] }}" target="_blank" rel="noopener" class="text-sm font-bold border border-[#001a61] text-[#001a61] px-4 py-2 rounded hover:bg-[#e7eeff]">Télécharger le flyer</a>
                @endif
                <a href="{{ route('investir.partenaires') }}" class="text-sm font-bold bg-[#001a61] text-white px-4 py-2 rounded hover:bg-[#0a2e8c]">Souscrire via un partenaire</a>
                <a href="{{ route('mise-en-relation') }}" class="text-sm font-bold text-[#001a61] hover:underline py-2">Demander un accompagnement</a>
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
