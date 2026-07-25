<div class="p-6 lg:p-8 space-y-6">
    <div>
        <h1 class="text-2xl font-extrabold text-[#001a61]">Analyses par formation</h1>
        <p class="text-sm text-[#444652] mt-1">Performances pédagogiques et taux d'engagement.</p>
    </div>
    <div class="admin-card overflow-hidden">
        <table class="admin-table w-full text-sm">
            <thead><tr>
                <th class="text-left px-4 py-3">Formation</th>
                <th class="text-right px-4 py-3">Inscrits</th>
                <th class="text-right px-4 py-3">Terminés</th>
                <th class="text-right px-4 py-3">Complétion</th>
                <th class="text-right px-4 py-3">Prog. moy.</th>
                <th class="text-right px-4 py-3">Quiz moy.</th>
                <th class="text-right px-4 py-3">Note</th>
            </tr></thead>
            <tbody>
                @foreach ($rows as $row)
                    <tr class="border-t border-[#e7eeff]">
                        <td class="px-4 py-3 font-semibold text-[#001a61]">
                            <a href="{{ route('admin.formations.show', $row['formation']) }}" class="hover:underline" wire:navigate.hover>{{ $row['formation']->titre }}</a>
                        </td>
                        <td class="px-4 py-3 text-right">{{ $row['enrolled'] }}</td>
                        <td class="px-4 py-3 text-right">{{ $row['completed'] }}</td>
                        <td class="px-4 py-3 text-right font-bold">{{ $row['completion'] }}%</td>
                        <td class="px-4 py-3 text-right">{{ $row['avg'] }}%</td>
                        <td class="px-4 py-3 text-right">{{ $row['avg_quiz'] !== null ? $row['avg_quiz'].'%' : '—' }}</td>
                        <td class="px-4 py-3 text-right">{{ $row['rating'] !== null ? $row['rating'].'/5' : '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
