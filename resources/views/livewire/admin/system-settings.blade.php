<div>
    <div class="mb-8">
        <h2 class="text-2xl font-extrabold text-[#001a61]">Paramètres système</h2>
        <p class="text-[#444652] mt-2">Configuration globale de la plateforme.</p>
    </div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($cards as $card)
            <a href="{{ route($card['route']) }}"
                class="bg-white border border-[#c5c5d4] rounded-xl p-5 hover:border-[#001a61] transition block">
                <span class="material-symbols-outlined text-[#001a61] text-3xl">{{ $card['icon'] }}</span>
                <h3 class="font-bold text-[#001a61] mt-3">{{ $card['title'] }}</h3>
                <p class="text-sm text-[#757683] mt-1">{{ $card['desc'] }}</p>
            </a>
        @endforeach
    </div>
</div>
