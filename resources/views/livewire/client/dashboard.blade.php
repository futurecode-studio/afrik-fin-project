<div>
    <div class="mb-8">
        <p class="text-sm font-semibold tracking-widest uppercase text-[#0a2e8c]">Portail client</p>
        <h1 class="text-3xl font-extrabold text-[#001a61] mt-1">Bonjour, {{ Auth::user()->name }}</h1>
        <p class="text-[#444652] mt-2">Votre activité formations, événements et marchés.</p>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
        @foreach ([
            ['label' => 'Formations en cours', 'value' => $activeEnrollments->count(), 'icon' => 'school', 'href' => route('client.formations')],
            ['label' => 'Terminées', 'value' => $completedEnrollments->count(), 'icon' => 'verified', 'href' => route('client.certificates')],
            ['label' => 'Événements', 'value' => $eventsCount, 'icon' => 'event', 'href' => route('client.my-events')],
            ['label' => 'Liste de suivi', 'value' => $watchlistCount, 'icon' => 'visibility', 'href' => route('client.watchlist')],
        ] as $kpi)
            <a href="{{ $kpi['href'] }}" class="bg-white border border-[#c5c5d4] rounded-xl p-5 hover:border-[#001a61] transition block">
                <span class="material-symbols-outlined text-[#001a61]">{{ $kpi['icon'] }}</span>
                <p class="text-xs text-[#757683] mt-3">{{ $kpi['label'] }}</p>
                <p class="text-2xl font-extrabold text-[#001a61] mt-1">{{ $kpi['value'] }}</p>
            </a>
        @endforeach
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <section class="lg:col-span-2 bg-white border border-[#c5c5d4] rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-lg text-[#001a61]">Formations récentes</h2>
                <a href="{{ route('client.formations') }}" class="text-sm font-bold text-[#001a61] hover:underline">Tout voir</a>
            </div>
            @forelse ($enrollments->take(4) as $enrollment)
                <a href="{{ route('client.formation', $enrollment->formation->slug) }}"
                    class="flex items-center gap-4 py-3 border-t border-[#c5c5d4] first:border-0 hover:bg-[#f9f9ff] -mx-2 px-2 rounded">
                    <div class="w-12 h-12 rounded-lg bg-[#e7eeff] overflow-hidden shrink-0">
                        @if ($enrollment->formation->image_url)
                            <img src="{{ $enrollment->formation->image_url }}" alt="" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-[#001a61] truncate">{{ $enrollment->formation->titre }}</p>
                        <p class="text-xs text-[#757683]">{{ ucfirst($enrollment->status) }} · {{ (int) $enrollment->progress }}%</p>
                        <div class="mt-1 h-1.5 bg-[#e7eeff] rounded-full overflow-hidden">
                            <div class="h-full bg-[#001a61]" style="width: {{ min(100, (int)$enrollment->progress) }}%"></div>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-[#001a61]">chevron_right</span>
                </a>
            @empty
                <p class="text-[#757683] text-sm py-6">Aucune formation. <a href="{{ route('formations') }}" class="font-bold text-[#001a61] underline">Explorer le catalogue</a></p>
            @endforelse
        </section>

        <aside class="space-y-4">
            <div class="bg-[#001a61] text-white rounded-xl p-6">
                <p class="text-sm text-white/70">Progression moyenne</p>
                <p class="text-4xl font-extrabold mt-2">{{ $totalProgress }}%</p>
                <a href="{{ route('client.formations') }}" class="inline-block mt-4 text-sm font-bold text-[#ffbf00]">Continuer d’apprendre →</a>
            </div>
            <div class="bg-white border border-[#c5c5d4] rounded-xl p-5 space-y-3">
                <h3 class="font-bold text-[#001a61]">Raccourcis</h3>
                <a href="{{ route('guide-bourse') }}" class="flex items-center gap-2 text-sm text-[#444652] hover:text-[#001a61]"><span class="material-symbols-outlined text-base">menu_book</span> Guide Bourse</a>
                <a href="{{ route('client.watchlist') }}" class="flex items-center gap-2 text-sm text-[#444652] hover:text-[#001a61]"><span class="material-symbols-outlined text-base">visibility</span> Ma liste de suivi</a>
                <a href="{{ route('events-list') }}" class="flex items-center gap-2 text-sm text-[#444652] hover:text-[#001a61]"><span class="material-symbols-outlined text-base">event</span> Événements</a>
                <a href="{{ route('client.profile') }}" class="flex items-center gap-2 text-sm text-[#444652] hover:text-[#001a61]"><span class="material-symbols-outlined text-base">settings</span> Paramètres</a>
            </div>
        </aside>
    </div>
</div>
