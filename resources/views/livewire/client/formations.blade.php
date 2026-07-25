<div>
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#001a61]">Mes formations</h1>
            <p class="text-[#444652] mt-2">Retrouvez vos cours et continuez votre progression.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            @if ($continue)
                <a href="{{ route('client.formation', $continue->formation->slug) }}"
                    class="inline-flex items-center gap-2 bg-[#001a61] text-white font-bold px-5 py-3 rounded-xl hover:bg-[#0a2e8c]">
                    <span class="material-symbols-outlined">play_arrow</span> Continuer le cours
                </a>
            @endif
            <a href="{{ route('formations') }}" class="inline-flex items-center gap-2 border border-[#c5c5d4] text-[#001a61] font-bold px-5 py-3 rounded-xl hover:bg-[#e7eeff]">Catalogue</a>
        </div>
    </div>

    <div class="mb-4">
        <input type="search" wire:model.live.debounce.300ms="q" placeholder="Rechercher une formation, un formateur…"
            class="admin-input w-full max-w-md text-sm rounded-xl border border-[#c5c5d4] bg-white/80 px-4 py-2.5">
    </div>

    <div class="flex flex-wrap gap-1 border-b border-[#c5c5d4] mb-6">
        @foreach ([
            'en_cours' => 'En cours',
            'a_commencer' => 'À commencer',
            'terminees' => 'Terminées',
            'certificats' => 'Certificats',
        ] as $key => $label)
            <button type="button" wire:click="$set('tab','{{ $key }}')"
                @class([
                    'px-4 py-2.5 text-sm font-bold border-b-2 -mb-px transition',
                    'border-[#ffbf00] text-[#001a61]' => $tab === $key,
                    'border-transparent text-[#757683] hover:text-[#001a61]' => $tab !== $key,
                ])>{{ $label }}</button>
        @endforeach
    </div>

    <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse ($rows as $row)
            @php
                $enrollment = $row['enrollment'];
                $f = $row['formation'];
            @endphp
            <article class="adf-card-static overflow-hidden flex flex-col">
                <div class="h-40 bg-[#e7eeff] relative">
                    @if ($f->image_url)
                        <img src="{{ $f->image_url }}" alt="" class="w-full h-full object-cover">
                    @endif
                    <div class="absolute top-3 left-3 flex gap-2">
                        @if ($f->niveau)
                            <span class="px-2 py-0.5 rounded bg-white/90 text-[#001a61] text-[11px] font-bold uppercase">{{ $f->niveau }}</span>
                        @endif
                    </div>
                </div>
                <div class="p-5 flex flex-col flex-1">
                    <p class="text-[11px] font-bold uppercase tracking-wide text-[#0a2e8c]">{{ $f->categorie ?? 'Formation' }}</p>
                    <h2 class="font-extrabold text-lg text-[#001a61] mt-1 line-clamp-2">{{ $f->titre }}</h2>
                    <p class="text-xs text-[#757683] mt-1">{{ $f->user->name ?? 'Africaine des Finances' }}</p>

                    <div class="mt-4">
                        <div class="flex justify-between text-xs mb-1">
                            <span class="font-semibold text-[#001a61]">Progression</span>
                            <span class="font-bold">{{ (int) $enrollment->progress }}%</span>
                        </div>
                        <div class="h-2 bg-[#e7eeff] rounded-full overflow-hidden">
                            <div class="h-full {{ (int)$enrollment->progress >= 70 ? 'bg-[#ffbf00]' : 'bg-[#001a61]' }}" style="width: {{ min(100, (int)$enrollment->progress) }}%"></div>
                        </div>
                        <p class="text-xs text-[#757683] mt-2">{{ $row['done'] }} leçons sur {{ $row['total'] }} terminées</p>
                        <p class="text-xs text-[#757683] mt-1">Dernière activité : {{ $enrollment->updated_at?->diffForHumans() ?? '—' }}</p>
                        <p class="text-xs text-[#757683]">Temps restant estimé : {{ $row['remaining'] }} min</p>
                    </div>

                    <div class="mt-auto pt-5 flex items-center justify-between gap-2">
                        <a href="{{ route('client.formation.progress', $f->slug) }}" class="text-xs font-bold text-[#757683] hover:text-[#001a61]">Détail</a>
                        <a href="{{ route('client.formation', $f->slug) }}"
                            class="inline-flex items-center gap-1 text-sm font-bold bg-[#001a61] text-white px-3 py-2 rounded-xl hover:bg-[#0a2e8c]">
                            {{ $row['cta'] }}
                            <span class="material-symbols-outlined text-base">chevron_right</span>
                        </a>
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-full bg-white border border-dashed border-[#c5c5d4] rounded-2xl p-12 text-center">
                <span class="material-symbols-outlined text-5xl text-[#c5c5d4]">school</span>
                <p class="mt-4 text-[#444652]">Aucune formation dans cet onglet.</p>
                <a href="{{ route('formations') }}" class="inline-block mt-4 font-bold text-[#001a61] underline">Découvrir le catalogue</a>
            </div>
        @endforelse
    </div>

    @if ($allCount > 0)
        <p class="text-center text-sm text-[#757683] mt-8">{{ $rows->count() }} formation(s) affichée(s) · {{ $allCount }} au total</p>
    @endif
</div>
