<div class="bg-[#f9f9ff] text-[#131c2a] min-h-screen">
    @include('partials.markets-nav')

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pt-10 pb-10">
        <h1 class="text-3xl md:text-4xl font-extrabold text-[#001a61] text-center">Recherche globale</h1>
        <p class="text-[#444652] text-center mt-2 mb-8">Actions BRVM et obligations d'États</p>

        <div class="max-w-2xl mx-auto">
            <div class="bg-white border border-[#c5c5d4] rounded-2xl shadow-sm flex items-center gap-3 px-4 py-3">
                <span class="material-symbols-outlined text-[#001a61]">search</span>
                <input type="search" wire:model.live.debounce.250ms="q"
                    placeholder="Symbole, société, obligation, ISIN…"
                    class="flex-1 border-0 focus:ring-0 text-base bg-transparent"
                    autofocus>
            </div>
        </div>
    </section>

    @if(!$hasQuery)
        <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pb-20">
            <h2 class="font-bold text-[#001a61] mb-4">Suggestions populaires</h2>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach ($popular as $s)
                    <a href="{{ route('marches.action', $s->symbol) }}"
                        class="bg-white border border-[#c5c5d4] rounded-xl p-4 hover:border-[#001a61] transition">
                        <p class="font-bold text-[#001a61]">{{ $s->symbol }}</p>
                        <p class="text-sm text-[#444652]">{{ $s->company_name }}</p>
                        <p class="text-sm mt-1 {{ $s->variation_percent >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format((float)$s->current_price, 0, ',', ' ') }} FCFA
                            ({{ $s->variation_percent >= 0 ? '+' : '' }}{{ number_format((float)$s->variation_percent, 2) }}%)
                        </p>
                    </a>
                @endforeach
            </div>
        </section>
    @else
        <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pb-20 grid lg:grid-cols-2 gap-8">
            <div>
                <h2 class="font-bold text-[#001a61] mb-4">Actions ({{ $stocks->count() }})</h2>
                <div class="space-y-2">
                    @forelse ($stocks as $s)
                        <a href="{{ route('marches.action', $s->symbol) }}"
                            class="flex justify-between items-center bg-white border border-[#c5c5d4] rounded-xl p-4 hover:border-[#001a61]">
                            <div>
                                <p class="font-bold text-[#001a61]">{{ $s->symbol }}</p>
                                <p class="text-sm text-[#444652]">{{ $s->company_name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold">{{ number_format((float)$s->current_price, 0, ',', ' ') }}</p>
                                <p class="text-sm {{ $s->variation_percent >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $s->variation_percent >= 0 ? '+' : '' }}{{ number_format((float)$s->variation_percent, 2) }}%
                                </p>
                            </div>
                        </a>
                    @empty
                        <p class="text-[#757683]">Aucune action</p>
                    @endforelse
                </div>
            </div>
            <div>
                <h2 class="font-bold text-[#001a61] mb-4">Obligations ({{ $bonds->count() }})</h2>
                <div class="space-y-2">
                    @forelse ($bonds as $b)
                        <a href="{{ route('marches.obligation', $b->id) }}"
                            class="block bg-white border border-[#c5c5d4] rounded-xl p-4 hover:border-[#001a61]">
                            <p class="font-bold text-[#001a61]">{{ $b->name }}</p>
                            <p class="text-sm text-[#444652]">{{ $b->issuer }} · {{ $b->country }}</p>
                            <p class="text-sm mt-1 font-semibold">{{ number_format((float)$b->interest_rate, 2) }}%</p>
                        </a>
                    @empty
                        <p class="text-[#757683]">Aucune obligation</p>
                    @endforelse
                </div>
            </div>
        </section>
    @endif
</div>
