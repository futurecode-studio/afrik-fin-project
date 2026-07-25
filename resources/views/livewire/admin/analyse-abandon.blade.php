<div>
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#001a61]">Analyse d'abandon</h1>
            <p class="text-[#444652] mt-2">Points de friction par module · {{ $totalEnrollments }} inscriptions</p>
        </div>
        <select wire:model.live="formationId" class="rounded-xl border border-[#c5c5d4] px-3 py-2 text-sm font-semibold">
            <option value="">Toutes les formations</option>
            @foreach ($formations as $f)
                <option value="{{ $f->id }}">{{ $f->titre }}</option>
            @endforeach
        </select>
    </div>

    <div class="adf-card-static overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-[#f0f3ff] text-left text-xs uppercase text-[#757683]">
                <tr>
                    <th class="px-4 py-3">Formation</th>
                    <th class="px-4 py-3">Module</th>
                    <th class="px-4 py-3">Entrés</th>
                    <th class="px-4 py-3">Terminés</th>
                    <th class="px-4 py-3">Bloqués</th>
                    <th class="px-4 py-3">Taux d'abandon</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($moduleStats as $row)
                    <tr class="border-t border-[#e7eeff]">
                        <td class="px-4 py-3 text-[#444652]">{{ $row['formation'] }}</td>
                        <td class="px-4 py-3 font-semibold text-[#001a61]">{{ $row['module'] }}</td>
                        <td class="px-4 py-3">{{ $row['started'] }}</td>
                        <td class="px-4 py-3">{{ $row['finished'] }}</td>
                        <td class="px-4 py-3">{{ $row['stuck'] }}</td>
                        <td class="px-4 py-3">
                            <span @class([
                                'font-extrabold',
                                'text-red-600' => $row['drop_rate'] >= 50,
                                'text-amber-700' => $row['drop_rate'] >= 25 && $row['drop_rate'] < 50,
                                'text-green-700' => $row['drop_rate'] < 25,
                            ])>{{ $row['drop_rate'] }}%</span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-12 text-center text-[#757683]">Pas assez de données.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
