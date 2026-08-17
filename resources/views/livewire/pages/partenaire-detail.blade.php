<div class="bg-[#f9f9ff]">
    <section class="max-w-[1100px] mx-auto px-5 lg:px-8 py-12 lg:py-16">
        <nav class="text-xs font-semibold tracking-wider uppercase text-[#757683] mb-6 flex items-center gap-2">
            <a href="{{ route('investir.partenaires') }}" class="hover:text-[#001a61]">Partenaires</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <span class="text-[#001a61]">{{ $partner->type }}</span>
        </nav>

        <div class="grid lg:grid-cols-5 gap-8 items-start">
            <div class="lg:col-span-3 bg-white border border-[#c5c5d4] rounded-xl p-6 lg:p-8">
                <div class="flex items-start gap-4">
                    @if ($partner->logo_url)
                        <img src="{{ $partner->logo_url }}" alt="{{ $partner->nom }}" class="w-16 h-16 object-contain rounded-lg border border-[#c5c5d4] bg-white">
                    @else
                        <div class="w-16 h-16 rounded-lg bg-[#e7eeff] flex items-center justify-center">
                            <span class="material-symbols-outlined text-[#001a61] text-3xl">account_balance</span>
                        </div>
                    @endif
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-[#0a2e8c]">{{ $partner->getTypeLabel() }}</p>
                        <h1 class="text-3xl font-extrabold text-[#001a61] mt-1">{{ $partner->nom }}</h1>
                    </div>
                </div>

                <div class="mt-6 prose prose-sm max-w-none text-[#444652]">
                    {!! rich_html($partner->description ?: '<p>Partenaire agréé accompagnant les investisseurs sur les marchés de l’UEMOA.</p>') !!}
                </div>

                <dl class="mt-8 grid sm:grid-cols-2 gap-4 text-sm">
                    @if ($partner->contact)
                        <div class="p-4 rounded-xl bg-[#f0f3ff] border border-[#c5c5d4]">
                            <dt class="text-[#757683] text-xs uppercase tracking-wider">Contact</dt>
                            <dd class="font-semibold text-[#001a61] mt-1">{{ $partner->contact }}</dd>
                        </div>
                    @endif
                    @if ($partner->email)
                        <div class="p-4 rounded-xl bg-[#f0f3ff] border border-[#c5c5d4]">
                            <dt class="text-[#757683] text-xs uppercase tracking-wider">Email</dt>
                            <dd class="font-semibold text-[#001a61] mt-1"><a href="mailto:{{ $partner->email }}">{{ $partner->email }}</a></dd>
                        </div>
                    @endif
                    @if ($partner->website)
                        <div class="p-4 rounded-xl bg-[#f0f3ff] border border-[#c5c5d4] sm:col-span-2">
                            <dt class="text-[#757683] text-xs uppercase tracking-wider">Site web</dt>
                            <dd class="font-semibold text-[#001a61] mt-1"><a href="{{ $partner->website }}" target="_blank" rel="noopener" class="underline">{{ $partner->website }}</a></dd>
                        </div>
                    @endif
                </dl>
            </div>

            <aside class="lg:col-span-2 space-y-4">
                <div class="bg-[#001a61] text-white rounded-xl p-6">
                    <h2 class="text-xl font-extrabold">Prêt à investir dans votre avenir ?</h2>
                    <p class="text-white/75 text-sm mt-2">Demandez une mise en relation avec {{ $partner->nom }}.</p>
                    <a href="{{ route('mise-en-relation', ['partner' => $partner->id]) }}"
                        class="mt-5 inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-[#ffbf00] text-[#261a00] font-bold">
                        Demander une mise en relation
                        <span class="material-symbols-outlined text-base">arrow_forward</span>
                    </a>
                </div>
                @if ($related->isNotEmpty())
                    <div class="bg-white border border-[#c5c5d4] rounded-xl p-5">
                        <h3 class="font-bold text-[#001a61] mb-3">Autres {{ $partner->type }}</h3>
                        <ul class="space-y-3">
                            @foreach ($related as $r)
                                <li>
                                    <a href="{{ route('partenaires.show', $r->id) }}" class="flex items-center gap-3 text-sm font-medium text-[#0a2e8c] hover:underline">
                                        @if ($r->logo_url)
                                            <img src="{{ $r->logo_url }}" alt="" class="h-8 w-8 object-contain rounded border border-[#c5c5d4] bg-white p-0.5">
                                        @endif
                                        <span>{{ $r->nom }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </aside>
        </div>
    </section>
</div>
