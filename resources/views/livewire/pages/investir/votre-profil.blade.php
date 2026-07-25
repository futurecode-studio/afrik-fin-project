<div class="bg-[#f9f9ff] text-[#131c2a] min-h-screen">
    @include('partials.investir-nav')

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pt-12 pb-8">
        <p class="text-sm font-semibold tracking-widest uppercase text-[#0a2e8c] mb-3">Profil investisseur</p>
        <h1 class="text-3xl md:text-4xl font-extrabold text-[#001a61]">
            Votre profil : <span style="color: {{ $profile['color'] }}">{{ $profile['label'] }}</span>
        </h1>
        <p class="mt-3 text-[#444652] max-w-2xl">{{ $profile['description'] }}</p>
        <p class="mt-2 text-sm text-[#757683]">Score {{ $profile['score'] }} / {{ $profile['max_score'] }}</p>
        <button type="button" wire:click="remake" class="mt-4 text-sm font-bold text-[#001a61] hover:underline">
            Refaire le test
        </button>
    </section>

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pb-12">
        <h2 class="text-xl font-bold text-[#001a61] mb-4">Allocation indicative</h2>
        <div class="bg-white border border-[#c5c5d4] rounded-xl p-6 space-y-4">
            @foreach ($profile['allocation'] as $label => $pct)
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-medium">{{ $label }}</span>
                        <span class="font-bold text-[#001a61]">{{ $pct }}%</span>
                    </div>
                    <div class="h-3 bg-[#e7eeff] rounded-full overflow-hidden">
                        <div class="h-full rounded-full" style="width: {{ $pct }}%; background: {{ $profile['color'] }}"></div>
                    </div>
                </div>
            @endforeach
        </div>
        <p class="text-xs text-[#757683] mt-3">À titre éducatif uniquement — ne constitue pas un conseil en investissement.</p>
    </section>

    @if (count($suggested))
        <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pb-20">
            <div class="flex items-end justify-between mb-4">
                <h2 class="text-xl font-bold text-[#001a61]">FCP susceptibles de vous correspondre</h2>
                <a href="{{ route('investir.opcvm') }}" class="text-sm font-bold text-[#001a61] hover:underline">Tous les fonds</a>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach ($suggested as $f)
                    <a href="{{ route('investir.fcp-detail', $f['id']) }}"
                        class="bg-white border border-[#c5c5d4] rounded-xl p-4 hover:border-[#001a61] transition block">
                        <p class="text-xs text-[#757683]">{{ $f['category'] }}</p>
                        <p class="font-bold text-[#001a61] mt-1 line-clamp-2">{{ $f['name'] }}</p>
                        <p class="text-sm mt-3">{{ $f['nav_value'] }}</p>
                        <p @class(['text-sm font-semibold', 'text-green-600' => ($f['variation_percentage'] ?? 0) >= 0, 'text-red-600' => ($f['variation_percentage'] ?? 0) < 0])>
                            {{ $f['variation'] ?? '' }}
                        </p>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
</div>
