<main class="flex-1 pt-20 bg-[#f9f9ff] text-[#131c2a]">
    <section class="relative overflow-hidden bg-[#071F5A] py-16 lg:py-24">
        <div class="container mx-auto px-4 relative z-10">
            <div class="grid gap-10 lg:grid-cols-[1.2fr_0.8fr] lg:items-end">
                <div class="max-w-4xl">
                    <p class="mb-4 text-sm font-semibold uppercase tracking-[0.18em] text-[#ffbf00]">À propos</p>
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">Rapprocher les épargnants du marché financier régional</h1>
                    <p class="text-lg md:text-xl text-white/80 leading-relaxed">{{ $company['description'] }}</p>
                </div>
                <div class="rounded-lg border border-white/20 bg-white/10 p-6 text-white backdrop-blur">
                    <p class="text-sm font-semibold uppercase tracking-[0.16em] text-[#ffbf00]">Agrément</p>
                    <p class="mt-3 text-2xl font-extrabold">AA/2022-03</p>
                    <p class="mt-2 text-sm leading-relaxed text-white/75">{{ $company['approval'] }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="border-b border-[#c5c5d4] bg-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($facts as $fact)
                    <div class="rounded-lg border border-[#c5c5d4] bg-[#f9f9ff] p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-[#757683]">{{ $fact['label'] }}</p>
                        <p class="mt-2 text-lg font-extrabold text-[#001a61]">{{ $fact['value'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-16 lg:py-20">
        <div class="container mx-auto px-4">
            <div class="grid gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:items-start">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-[#0a2e8c]">Notre rôle</p>
                    <h2 class="mt-2 text-3xl font-extrabold text-[#001a61]">Conseil, ingénierie financière et orientation responsable</h2>
                </div>
                <div class="space-y-5 text-[#444652] leading-relaxed">
                    <p>{{ $company['mission'] }}</p>
                    <p>{{ $company['positioning'] }}</p>
                    <p>Sur le marché financier régional, un apporteur d'affaires met en relation les clients avec des acteurs habilités comme les Sociétés de Gestion et d'Intermédiation ou les Sociétés de Gestion d'OPCVM. Africaine des Finances inscrit son accompagnement dans cette logique: expliquer, préparer, orienter et suivre sans se substituer aux intervenants agréés chargés de l'exécution.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white py-16 lg:py-20">
        <div class="container mx-auto px-4">
            <div class="mb-10 max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-[#0a2e8c]">Ce que nous faisons</p>
                <h2 class="mt-2 text-3xl font-extrabold text-[#001a61]">Trois leviers pour rendre l'investissement plus lisible</h2>
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                @foreach($pillars as $pillar)
                    <article class="rounded-lg border border-[#c5c5d4] bg-[#f9f9ff] p-6">
                        <span class="material-symbols-outlined text-4xl text-[#ffbf00]">{{ $pillar['icon'] }}</span>
                        <h3 class="mt-4 text-xl font-extrabold text-[#001a61]">{{ $pillar['title'] }}</h3>
                        <p class="mt-3 text-sm leading-relaxed text-[#444652]">{{ $pillar['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-16 lg:py-20">
        <div class="container mx-auto px-4">
            <div class="grid gap-10 lg:grid-cols-[1fr_1fr]">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-[#0a2e8c]">Méthode</p>
                    <h2 class="mt-2 text-3xl font-extrabold text-[#001a61]">Un accompagnement structuré, pas une promesse de rendement</h2>
                    <div class="mt-6 space-y-4">
                        @foreach($steps as $index => $step)
                            <div class="flex gap-4 rounded-lg border border-[#c5c5d4] bg-white p-5">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#001a61] text-sm font-bold text-white">{{ $index + 1 }}</div>
                                <div>
                                    <h3 class="font-bold text-[#001a61]">{{ $step['title'] }}</h3>
                                    <p class="mt-1 text-sm leading-relaxed text-[#444652]">{{ $step['text'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-lg bg-[#071F5A] p-6 lg:p-8 text-white">
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-[#ffbf00]">Engagements</p>
                    <h2 class="mt-2 text-2xl font-extrabold">Ce que le client doit pouvoir attendre de nous</h2>
                    <div class="mt-6 space-y-4">
                        @foreach($commitments as $commitment)
                            <div class="flex gap-3">
                                <span class="material-symbols-outlined mt-0.5 text-[#ffbf00]">check_circle</span>
                                <p class="text-sm leading-relaxed text-white/80">{{ $commitment }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="border-y border-[#c5c5d4] bg-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid gap-6 lg:grid-cols-[1.4fr_1fr]">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-[#0a2e8c]">Implantation</p>
                    <h2 class="mt-2 text-2xl font-extrabold text-[#001a61]">Un cabinet basé à Cotonou, tourné vers le marché UEMOA</h2>
                    <p class="mt-3 text-[#444652] leading-relaxed">Africaine des Finances accompagne une clientèle de particuliers, d'entreprises, d'institutions et d'associations intéressée par l'épargne, l'investissement et la valorisation de patrimoine via le marché financier régional.</p>
                </div>
                <div class="grid gap-3 text-sm">
                    <div class="rounded-lg border border-[#c5c5d4] bg-[#f9f9ff] p-4">
                        <p class="font-bold text-[#001a61]">Adresse</p>
                        <p class="mt-1 text-[#444652]">{{ $company['address'] }}</p>
                    </div>
                    <div class="rounded-lg border border-[#c5c5d4] bg-[#f9f9ff] p-4">
                        <p class="font-bold text-[#001a61]">Informations légales</p>
                        <p class="mt-1 text-[#444652]">{{ $company['legal'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 lg:py-20" x-data="{ tab: window.location.hash === '#nous-rejoindre' ? 'nous-rejoindre' : 'equipe' }" id="equipe">
        <div class="container mx-auto px-4">
            <div class="grid gap-10 lg:grid-cols-[0.85fr_1.15fr] lg:items-start">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-[#0a2e8c]">L'équipe</p>
                    <h2 class="mt-2 text-3xl font-extrabold text-[#001a61]">Des interlocuteurs identifiés pour accompagner les clients</h2>
                    <p class="mt-3 text-[#444652] leading-relaxed">La relation client est au centre du dispositif : écouter, expliquer, orienter et suivre. Découvrez nos conseillers ou envoyez votre candidature pour rejoindre l'aventure.</p>
                </div>
                <div class="rounded-lg border border-[#c5c5d4] bg-white p-6">
                    <h3 class="text-lg font-extrabold text-[#001a61]">Domaines couverts</h3>
                    <div class="mt-4 flex flex-wrap gap-2">
                        @foreach(['Épargne', 'BRVM', 'FCP / OPCVM', 'Patrimoine', 'Relation client', 'Éducation financière', 'Mise en relation SGI / SGO'] as $tag)
                            <span class="rounded-full bg-[#e7eeff] px-3 py-1 text-xs font-semibold text-[#001a61]">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-10 inline-flex flex-wrap gap-2 rounded-xl border border-[#c5c5d4] bg-white p-1.5">
                <button type="button"
                    @click="tab = 'equipe'; history.replaceState(null, '', '#equipe')"
                    :class="tab === 'equipe' ? 'bg-[#001a61] text-white' : 'text-[#444652] hover:bg-[#e7eeff] hover:text-[#001a61]'"
                    class="px-4 py-2.5 rounded-lg text-sm font-bold transition">
                    L'équipe
                </button>
                <button type="button"
                    @click="tab = 'nous-rejoindre'; history.replaceState(null, '', '#nous-rejoindre')"
                    :class="tab === 'nous-rejoindre' ? 'bg-[#001a61] text-white' : 'text-[#444652] hover:bg-[#e7eeff] hover:text-[#001a61]'"
                    class="px-4 py-2.5 rounded-lg text-sm font-bold transition">
                    Nous rejoindre
                </button>
            </div>

            <div x-show="tab === 'equipe'" x-cloak>
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($members as $member)
                    @php
                        $parts = explode(' ', $member['name']);
                        $initials = collect($parts)->filter()->take(2)->map(fn ($part) => mb_substr($part, 0, 1))->implode('');
                    @endphp
                    <article class="bg-white rounded-lg border border-[#c5c5d4] overflow-hidden hover:border-[#001a61] transition">
                        <div class="p-6">
                            <div class="flex items-start gap-4">
                                @if(!empty($member['image']))
                                    <img src="{{ asset($member['image']) }}" alt="{{ $member['name'] }}" class="h-20 w-20 shrink-0 rounded-full object-cover object-center ring-4 ring-[#e7eeff]">
                                @else
                                    <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-[#e7eeff] text-lg font-extrabold text-[#001a61]">
                                        {{ $initials }}
                                    </div>
                                @endif
                                <div>
                                    <h3 class="font-bold text-lg text-[#001a61]">{{ $member['name'] }}</h3>
                                    <p class="text-sm font-semibold text-[#0a2e8c] mt-1">{{ $member['role'] }}</p>
                                </div>
                            </div>
                            <p class="mt-5 text-sm leading-relaxed text-[#444652]">{{ $member['bio'] }}</p>
                            <div class="mt-5 flex flex-wrap gap-2">
                                @foreach($member['tags'] as $tag)
                                    <span class="rounded-full bg-[#e7eeff] px-3 py-1 text-xs font-semibold text-[#001a61]">{{ $tag }}</span>
                                @endforeach
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
            </div>

            <div x-show="tab === 'nous-rejoindre'" x-cloak id="nous-rejoindre" class="mt-8">
                <article class="max-w-3xl rounded-2xl border border-[#c5c5d4] bg-white p-8 lg:p-10">
                    <span class="material-symbols-outlined text-4xl text-[#ffbf00]">groups</span>
                    <h3 class="mt-4 text-2xl font-extrabold text-[#001a61]">Rejoignez Africaine des Finances</h3>
                    <p class="mt-3 text-[#444652] leading-relaxed">
                        Vous souhaitez évoluer dans la finance de marché, la relation client ou l'éducation financière ?
                        Envoyez votre candidature spontanée ou votre demande de stage à notre équipe Capital Humain.
                    </p>
                    <a href="mailto:capital.humain@africainedesfinances.com"
                        class="mt-6 inline-flex items-center gap-2 bg-[#001a61] text-white font-bold px-6 py-3.5 rounded-xl hover:bg-[#0a2e8c] transition">
                        <span class="material-symbols-outlined text-[20px]">mail</span>
                        capital.humain@africainedesfinances.com
                    </a>
                    <p class="mt-4 text-sm text-[#757683]">Joignez votre CV et une courte lettre de motivation.</p>
                </article>
            </div>
        </div>
    </section>
</main>
