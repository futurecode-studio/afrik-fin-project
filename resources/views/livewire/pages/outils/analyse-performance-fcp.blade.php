<div class="bg-[#f9f9ff] min-h-screen">
    <section class="max-w-[1100px] mx-auto px-5 lg:px-8 py-12">
        <h1 class="text-3xl font-extrabold text-[#001a61]">Analyse Performance FCP</h1>
        <p class="text-[#444652] mt-2">Données VL réelles (Sikafinance via MutualFundsApiService).</p>

        <div class="mt-6">
            <label class="text-sm font-medium">Fonds</label>
            <select wire:model.live="fundId" class="w-full md:w-96 mt-1 rounded-lg border-[#c5c5d4]">
                @foreach ($funds as $f)
                    <option value="{{ $f['id'] }}">{{ $f['name'] }} — {{ $f['company'] ?? '' }}</option>
                @endforeach
            </select>
        </div>

        @if ($fund)
            <div class="mt-8 bg-white border border-[#c5c5d4] rounded-xl p-6 lg:p-8">
                <p class="text-xs uppercase tracking-wider text-[#0a2e8c]">{{ $fund['category'] ?? 'OPCVM' }} · {{ $fund['country'] ?? 'UEMOA' }}</p>
                <h2 class="text-2xl font-extrabold text-[#001a61] mt-1">{{ $fund['name'] }}</h2>
                <p class="text-sm text-[#757683] mt-1">{{ $fund['company'] ?? '' }} @if(!empty($fund['isin'])) · ISIN {{ $fund['isin'] }}@endif</p>

                <div class="mt-6 grid sm:grid-cols-3 gap-4">
                    <div class="p-4 rounded-xl bg-[#f0f3ff] border border-[#c5c5d4]">
                        <p class="text-xs text-[#757683]">Valeur liquidative</p>
                        <p class="text-2xl font-extrabold text-[#001a61]">{{ isset($fund['nav_numeric']) ? number_format($fund['nav_numeric'], 2, ',', ' ') : (isset($fund['nav']) ? number_format($fund['nav'], 2, ',', ' ') : '—') }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-[#f0f3ff] border border-[#c5c5d4]">
                        <p class="text-xs text-[#757683]">Variation</p>
                        @php $v = $fund['variation_percentage'] ?? $fund['change_percent'] ?? null; @endphp
                        <p class="text-2xl font-extrabold {{ (float)($v??0) >= 0 ? 'text-green-700' : 'text-red-700' }}">{{ $v !== null ? number_format((float)$v, 2, ',', ' ').'%' : '—' }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-[#f0f3ff] border border-[#c5c5d4]">
                        <p class="text-xs text-[#757683]">Date VL</p>
                        <p class="text-lg font-bold text-[#001a61]">{{ $fund['date'] ?? '—' }}</p>
                    </div>
                </div>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('investir.fcp-detail', $fund['id']) }}" class="px-5 py-3 rounded-xl bg-[#001a61] text-white font-bold">Fiche détaillée</a>
                    <a href="{{ route('mise-en-relation') }}" class="px-5 py-3 rounded-xl border border-[#001a61] text-[#001a61] font-bold">Investir via SGI/SGO</a>
                </div>
            </div>
        @else
            <p class="mt-8 text-[#757683]">Aucune VL disponible pour le moment.</p>
        @endif
    </section>
</div>
