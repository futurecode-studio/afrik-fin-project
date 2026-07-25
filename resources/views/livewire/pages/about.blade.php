<div class="bg-[#f9f9ff] text-[#131c2a] min-h-screen">
    {{-- Hero --}}
    <section class="relative overflow-hidden bg-[#001a61] text-white">
        <div class="absolute inset-0 opacity-30 bg-[radial-gradient(circle_at_20%_20%,#ffbf00_0%,transparent_40%),radial-gradient(circle_at_80%_0%,#839cfd_0%,transparent_35%)]"></div>
        <div class="relative max-w-[1280px] mx-auto px-5 lg:px-16 py-20 lg:py-28">
            <p class="text-sm font-semibold tracking-widest uppercase text-[#ffbf00] mb-3">À propos</p>
            <h1 class="text-3xl md:text-5xl font-extrabold max-w-3xl leading-tight">
                Bâtir l’avenir de la finance africaine
            </h1>
            <p class="mt-5 text-white/80 max-w-2xl text-lg">
                Africaine des Finances accompagne investisseurs, entreprises et institutions sur les marchés UEMOA —
                formation, marchés et conseil.
            </p>
        </div>
    </section>

    {{-- Mission --}}
    <section class="max-w-[1280px] mx-auto px-5 lg:px-16 py-16">
        <div class="grid lg:grid-cols-2 gap-10 items-center">
            <div>
                <h2 class="text-2xl md:text-3xl font-extrabold text-[#001a61]">Notre mission</h2>
                <p class="mt-4 text-[#444652] leading-relaxed">
                    Démocratiser l’accès au marché financier régional grâce à une pédagogie claire, des données de marché
                    fiables et un accompagnement personnalisé, dans le respect du cadre AMF-UMOA.
                </p>
                <div class="mt-8 grid grid-cols-2 gap-4">
                    @foreach ([
                        ['label' => 'Agrément', 'value' => 'AMF-UMOA'],
                        ['label' => 'Marché', 'value' => 'BRVM / UEMOA'],
                        ['label' => 'Piliers', 'value' => 'Former · Investir · Conseiller'],
                        ['label' => 'Engagement', 'value' => 'Éducation financière'],
                    ] as $kpi)
                        <div class="bg-white border border-[#c5c5d4] rounded-xl p-4">
                            <p class="text-xs text-[#757683] uppercase tracking-wide">{{ $kpi['label'] }}</p>
                            <p class="font-bold text-[#001a61] mt-1">{{ $kpi['value'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="bg-[#001a61] text-white rounded-2xl p-8 lg:p-10">
                <span class="material-symbols-outlined text-[#ffbf00] text-4xl">visibility</span>
                <h3 class="text-xl font-bold mt-4">Vision</h3>
                <p class="mt-3 text-white/80 leading-relaxed">
                    Devenir la référence francophone pour comprendre et investir sur les marchés de capitaux d’Afrique de l’Ouest,
                    en rapprochant expertise institutionnelle et public large.
                </p>
            </div>
        </div>
    </section>

    {{-- Équipe (DB / admin) --}}
    <section class="bg-white border-y border-[#c5c5d4]">
        <div class="max-w-[1280px] mx-auto px-5 lg:px-16 py-16">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-10">
                <div>
                    <p class="text-sm font-semibold tracking-widest uppercase text-[#0a2e8c]">Leadership</p>
                    <h2 class="text-2xl md:text-3xl font-extrabold text-[#001a61] mt-2">Équipe dirigeante</h2>
                    <p class="text-[#444652] mt-2 max-w-xl">Uniquement l’équipe dirigeante — les autres collaborateurs sont sur la page Équipe.</p>
                </div>
                <a href="{{ route('team') }}" class="text-sm font-bold text-[#001a61] hover:underline">Voir toute l’équipe →</a>
            </div>

            @if ($members->isEmpty())
                <div class="border border-dashed border-[#c5c5d4] rounded-xl p-12 text-center">
                    <span class="material-symbols-outlined text-5xl text-[#c5c5d4]">group</span>
                    <h3 class="text-xl font-bold text-[#001a61] mt-4">Aucun membre publié</h3>
                    <p class="text-[#444652] mt-2">Ajoutez des membres actifs dans Admin → Équipe.</p>
                </div>
            @else
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($members as $member)
                        <article class="group bg-[#f9f9ff] border border-[#c5c5d4] rounded-xl overflow-hidden hover:border-[#001a61] transition">
                            <div class="aspect-[4/3] bg-[#e7eeff] overflow-hidden">
                                @if ($member->photo_url)
                                    <img src="{{ $member->photo_url }}" alt="{{ $member->nom }}"
                                        class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span class="material-symbols-outlined text-6xl text-[#001a61]/40">person</span>
                                    </div>
                                @endif
                            </div>
                            <div class="p-5">
                                <h3 class="font-bold text-lg text-[#001a61]">{{ $member->nom }}</h3>
                                <p class="text-sm font-semibold text-[#0a2e8c] mt-1">{{ $member->poste }}</p>
                                @if ($member->attributs)
                                    <div class="flex flex-wrap gap-1 mt-3">
                                        @foreach ($member->attributs_array as $attr)
                                            <span class="text-[11px] px-2 py-0.5 rounded bg-[#e7eeff] text-[#001a61]">{{ $attr }}</span>
                                        @endforeach
                                    </div>
                                @endif
                                @if ($member->description)
                                    <p class="text-sm text-[#444652] mt-3 line-clamp-3">{{ $member->description }}</p>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- CTA --}}
    <section class="max-w-[1280px] mx-auto px-5 lg:px-16 py-16">
        <div class="bg-[#001a61] rounded-2xl p-8 md:p-12 text-white flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="text-2xl font-extrabold">Travaillons ensemble</h2>
                <p class="mt-2 text-white/80 max-w-xl">Formations, marchés ou conseil — parlez-nous de votre besoin.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('contact') }}" class="bg-[#ffbf00] text-[#001a61] font-bold px-6 py-3 rounded hover:bg-white transition">Nous contacter</a>
                <a href="{{ route('services') }}" class="border border-white/40 font-bold px-6 py-3 rounded hover:bg-white/10 transition">Nos services</a>
            </div>
        </div>
    </section>
</div>
