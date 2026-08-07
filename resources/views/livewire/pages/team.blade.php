<main class="flex-1 pt-20 bg-[#f9f9ff] text-[#131c2a]">
    <section class="relative overflow-hidden bg-[#071F5A] py-16 lg:py-20">
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl">
                <p class="mb-4 text-sm font-semibold uppercase tracking-[0.18em] text-[#ffbf00]">Africaine des Finances</p>
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">Une équipe proche des investisseurs</h1>
                <p class="text-lg md:text-xl text-white/80 leading-relaxed">{{ $company['description'] }}</p>
            </div>
        </div>
    </section>

    <section class="border-b border-[#c5c5d4] bg-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid gap-6 lg:grid-cols-[1.4fr_1fr]">
                <div>
                    <h2 class="text-2xl font-extrabold text-[#001a61]">Notre mission</h2>
                    <p class="mt-3 text-[#444652] leading-relaxed">{{ $company['mission'] }}</p>
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

    <section class="py-16 lg:py-20">
        <div class="container mx-auto px-4">
            <div class="mb-10 max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-[#0a2e8c]">L'équipe</p>
                <h2 class="mt-2 text-3xl font-extrabold text-[#001a61]">Conseil, relation client et accompagnement patrimonial</h2>
                <p class="mt-3 text-[#444652]">Ces profils sont affichés directement depuis le code du site afin de ne pas dépendre d'un enregistrement backend.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
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
    </section>
</main>
