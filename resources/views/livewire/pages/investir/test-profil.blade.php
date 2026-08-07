<div class="bg-[#f9f9ff] text-[#131c2a] min-h-screen">
    @include('partials.investir-nav')

    <section class="px-5 lg:px-16 max-w-[720px] mx-auto pt-12 pb-20">
        <p class="text-sm font-semibold tracking-widest uppercase text-[#0a2e8c] mb-3">Profil investisseur</p>
        <h1 class="text-3xl font-extrabold text-[#001a61]">Découvrez votre profil investisseur</h1>
        <p class="mt-2 text-[#444652]">{{ $total }} questions pour estimer votre profil — résultat stocké sur cet appareil.</p>

        <div class="mt-6 h-2 bg-[#e7eeff] rounded-full overflow-hidden">
            <div class="h-full bg-[#001a61] transition-all duration-300" style="width: {{ $progress }}%"></div>
        </div>
        <p class="text-xs text-[#757683] mt-2">Question {{ $stepNumber }} / {{ $total }}</p>

        @if ($question)
            <div class="mt-8 bg-white border border-[#c5c5d4] rounded-xl p-6 md:p-8">
                <h2 class="text-xl font-bold text-[#001a61]">{{ $question['title'] }}</h2>
                <p class="text-[#444652] mt-2">{{ $question['subtitle'] }}</p>

                <div class="mt-6 space-y-3">
                    @foreach ($question['options'] as $opt)
                        <button type="button" wire:click="select('{{ $opt['id'] }}')"
                            class="w-full text-left px-4 py-4 rounded-xl border border-[#c5c5d4] hover:border-[#001a61] hover:bg-[#e7eeff] transition font-medium text-[#131c2a]">
                            {{ $opt['label'] }}
                        </button>
                    @endforeach
                </div>

                @if ($step > 0)
                    <button type="button" wire:click="back" class="mt-6 text-sm font-bold text-[#001a61] hover:underline">
                        ← Question précédente
                    </button>
                @endif
            </div>
        @endif
    </section>
</div>
