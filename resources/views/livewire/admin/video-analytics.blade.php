<div>
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#001a61]">Suivi vidéos</h1>
            <p class="text-[#444652] mt-2">Positions de lecture et taux de visionnage estimé</p>
        </div>
        <select wire:model.live="formationId" class="rounded-xl border border-[#c5c5d4] px-3 py-2 text-sm font-semibold">
            <option value="">Toutes les formations</option>
            @foreach ($formations as $f)
                <option value="{{ $f->id }}">{{ $f->titre }}</option>
            @endforeach
        </select>
    </div>

    <div class="adf-card-static overflow-hidden mb-8">
        <div class="px-5 py-4 border-b border-[#e7eeff]"><h2 class="font-bold text-[#001a61]">Par leçon</h2></div>
        <table class="w-full text-sm">
            <thead class="bg-[#f0f3ff] text-left text-xs uppercase text-[#757683]">
                <tr>
                    <th class="px-4 py-3">Leçon</th>
                    <th class="px-4 py-3">Spectateurs</th>
                    <th class="px-4 py-3">Temps moyen</th>
                    <th class="px-4 py-3">Complétion approx.</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($byLesson as $row)
                    <tr class="border-t border-[#e7eeff]">
                        <td class="px-4 py-3">
                            <p class="font-semibold text-[#001a61]">{{ $row['lesson']?->titre }}</p>
                            <p class="text-xs text-[#757683]">{{ $row['lesson']?->module?->formation?->titre }}</p>
                        </td>
                        <td class="px-4 py-3">{{ $row['viewers'] }}</td>
                        <td class="px-4 py-3">{{ gmdate('i:s', $row['avg_watched']) }}</td>
                        <td class="px-4 py-3 font-bold">{{ $row['completion'] }}%</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-12 text-center text-[#757683]">Aucune donnée vidéo pour l’instant.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="adf-card-static overflow-hidden">
        <div class="px-5 py-4 border-b border-[#e7eeff]"><h2 class="font-bold text-[#001a61]">Activité récente</h2></div>
        <ul>
            @forelse ($rows as $row)
                <li class="px-5 py-3 border-t border-[#e7eeff] flex flex-wrap justify-between gap-2 text-sm">
                    <div>
                        <p class="font-semibold text-[#001a61]">{{ $row->user?->name }}</p>
                        <p class="text-xs text-[#757683]">{{ $row->lesson?->titre }}</p>
                    </div>
                    <div class="text-right text-[#757683]">
                        <p>Position {{ gmdate('i:s', $row->video_position) }}</p>
                        <p class="text-xs">{{ $row->last_watched_at?->diffForHumans() }}</p>
                    </div>
                </li>
            @empty
                <li class="px-5 py-12 text-center text-[#757683]">Pas encore de lecture enregistrée.</li>
            @endforelse
        </ul>
    </div>
</div>
