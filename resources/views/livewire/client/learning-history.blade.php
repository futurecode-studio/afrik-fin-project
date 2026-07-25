<div>
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#001a61]">Historique d'apprentissage</h1>
            <p class="text-[#444652] mt-2">Quiz, examens, modules validés et certificats.</p>
        </div>
        <a href="{{ route('client.formations') }}" class="text-sm font-bold text-[#001a61] underline">Mes formations</a>
    </div>

    <div class="mb-6 flex flex-wrap gap-3">
        <select wire:model.live="kind" class="rounded-xl border-[#c5c5d4] text-sm font-semibold text-[#001a61]">
            <option value="all">Tous les types</option>
            <option value="quiz">Quiz</option>
            <option value="examen">Examens</option>
            <option value="formation">Formations</option>
            <option value="certificat">Certificats</option>
        </select>
        <select wire:model.live="period" class="rounded-xl border-[#c5c5d4] text-sm font-semibold text-[#001a61]">
            <option value="all">Toute période</option>
            <option value="30">30 derniers jours</option>
            <option value="90">90 derniers jours</option>
            <option value="365">12 derniers mois</option>
        </select>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-3">
            @forelse ($timeline as $event)
                <a href="{{ $event['url'] ?? '#' }}" @class(['block adf-card-static p-4 hover:border-[#001a61] transition', 'pointer-events-none' => !$event['url']])>
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-[#001a61] mt-0.5">
                            @switch($event['kind'])
                                @case('examen') workspace_premium @break
                                @case('certificat') verified @break
                                @case('formation') school @break
                                @default quiz
                            @endswitch
                        </span>
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="font-bold text-[#001a61]">{{ $event['title'] }}</h3>
                                <span class="text-[11px] uppercase font-bold px-2 py-0.5 rounded bg-[#e7eeff] text-[#0a2e8c]">{{ $event['kind'] }}</span>
                            </div>
                            <p class="text-sm text-[#757683] mt-0.5">{{ $event['subtitle'] }}</p>
                            <p class="text-xs text-[#757683] mt-2">{{ optional($event['at'])->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="text-right shrink-0">
                            @if ($event['score'] !== null)
                                <p @class(['font-extrabold', 'text-green-600' => $event['passed'], 'text-red-600' => !$event['passed']])>{{ $event['score'] }}%</p>
                            @endif
                            @if ($event['pts'])
                                <p class="text-xs font-bold text-[#ffbf00]">+{{ $event['pts'] }} pts</p>
                            @endif
                        </div>
                    </div>
                </a>
            @empty
                <div class="bg-white border border-dashed border-[#c5c5d4] rounded-xl p-10 text-center text-[#757683]">
                    Aucune activité pour ces filtres.
                </div>
            @endforelse
        </div>
        <aside class="space-y-4">
            <div class="bg-[#001a61] text-white rounded-xl p-5">
                <p class="text-xs uppercase text-white/60">Parcours actifs</p>
                <p class="text-3xl font-extrabold mt-1">{{ $enrollments->count() }}</p>
            </div>
            @foreach ($enrollments->take(4) as $e)
                <a href="{{ route('client.formation', $e->formation->slug) }}" class="block adf-card-static p-4 hover:border-[#001a61]">
                    <p class="font-bold text-[#001a61] text-sm line-clamp-2">{{ $e->formation->titre }}</p>
                    <div class="mt-2 h-1.5 bg-[#e7eeff] rounded-full overflow-hidden">
                        <div class="h-full bg-[#ffbf00]" style="width:{{ min(100,(int)$e->progress) }}%"></div>
                    </div>
                    <p class="text-xs text-[#757683] mt-1">{{ (int)$e->progress }}%</p>
                </a>
            @endforeach
        </aside>
    </div>
</div>
