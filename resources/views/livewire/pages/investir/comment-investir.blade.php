<div class="bg-[#f9f9ff] text-[#131c2a] min-h-screen">
    @include('partials.investir-nav')

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pt-12 pb-8">
        <p class="text-sm font-semibold tracking-widest uppercase text-[#0a2e8c] mb-3">Parcours</p>
        <h1 class="text-3xl md:text-4xl font-extrabold text-[#001a61]">Comment investir</h1>
        <p class="mt-3 text-[#444652] max-w-2xl">Quatre étapes pour passer de la curiosité à une décision éclairée sur les marchés UEMOA.</p>
    </section>

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pb-20 space-y-6">
        @foreach ($steps as $step)
            <article class="bg-white border border-[#c5c5d4] rounded-xl p-6 md:p-8 flex flex-col md:flex-row md:items-center gap-6">
                <div class="shrink-0 w-16 h-16 rounded-xl bg-[#e7eeff] flex items-center justify-center">
                    <span class="material-symbols-outlined text-[#001a61] text-3xl">{{ $step['icon'] }}</span>
                </div>
                <div class="flex-1">
                    <p class="text-xs font-bold text-[#ffbf00] tracking-widest">ÉTAPE {{ $step['n'] }}</p>
                    <h2 class="text-xl font-bold text-[#001a61] mt-1">{{ $step['title'] }}</h2>
                    <p class="text-[#444652] mt-2">{{ $step['text'] }}</p>
                </div>
                <a href="{{ route($step['route']) }}"
                    class="shrink-0 inline-flex items-center justify-center bg-[#001a61] text-white font-bold px-5 py-3 rounded hover:bg-[#0a2e8c] transition">
                    {{ $step['cta'] }}
                </a>
            </article>
        @endforeach

        <div class="bg-[#001a61] text-white rounded-xl p-8 md:p-10 mt-10">
            <h2 class="text-2xl font-extrabold">Besoin d’un accompagnement ?</h2>
            <p class="mt-2 text-white/80 max-w-xl">Nos conseillers vous orientent vers les SGI / SGO adaptées à votre profil.</p>
            <a href="{{ route('contact') }}" class="inline-block mt-6 bg-[#ffbf00] text-[#001a61] font-bold px-6 py-3 rounded hover:bg-white transition">Nous contacter</a>
        </div>
    </section>
</div>
