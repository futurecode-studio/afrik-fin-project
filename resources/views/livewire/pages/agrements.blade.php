<div class="max-w-4xl mx-auto px-4 py-16">
    <p class="text-xs font-bold uppercase tracking-widest text-[#757683]">Confiance & régulation</p>
    <h1 class="text-3xl md:text-4xl font-extrabold text-[#001a61] mt-2">Agréments & conformité</h1>
    <p class="text-[#444652] mt-3 max-w-2xl">Africaine des Finances opère dans un cadre réglementé et délivre des parcours pédagogiques traçables.</p>

    <div class="mt-12 space-y-5">
        @foreach ($items as $item)
            <article class="adf-card-static p-6">
                <p class="text-xs font-bold uppercase text-[#0a2e8c]">{{ $item['ref'] }}</p>
                <h2 class="text-xl font-extrabold text-[#001a61] mt-1">{{ $item['title'] }}</h2>
                <p class="text-[#444652] mt-2">{{ $item['body'] }}</p>
            </article>
        @endforeach
    </div>

    <div class="mt-10 flex flex-wrap gap-3">
        <a href="{{ route('certificate.verify.show') }}" class="px-5 py-3 rounded-xl bg-[#001a61] text-white font-bold">Vérifier un certificat</a>
        <a href="{{ route('legal.show', 'disclaimer') }}" class="px-5 py-3 rounded-xl border border-[#c5c5d4] font-bold text-[#001a61]">Avertissement investissement</a>
    </div>
</div>
