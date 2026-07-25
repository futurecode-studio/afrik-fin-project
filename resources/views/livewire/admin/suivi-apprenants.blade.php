<div class="p-6 lg:p-8 space-y-6">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-[#001a61]">Suivi des apprenants</h1>
            <p class="text-sm text-[#444652] mt-1">Supervision de {{ $activeCount }} élèves actifs.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <input wire:model.live.debounce.300ms="q" type="search" placeholder="Rechercher…" class="admin-input text-sm">
            <select wire:model.live="formationId" class="admin-input text-sm">
                <option value="">Toutes</option>
                @foreach ($formations as $f)<option value="{{ $f->id }}">{{ $f->titre }}</option>@endforeach
            </select>
        </div>
    </div>

    <div class="admin-card overflow-hidden">
        <table class="admin-table w-full text-sm">
            <thead><tr>
                <th class="text-left px-4 py-3">Apprenant</th>
                <th class="text-left px-4 py-3">Formation</th>
                <th class="text-left px-4 py-3">Progression</th>
                <th class="text-left px-4 py-3">Statut</th>
                <th class="px-4 py-3"></th>
            </tr></thead>
            <tbody>
                @forelse ($learners as $e)
                    <tr class="border-t border-[#e7eeff]">
                        <td class="px-4 py-3">
                            <p class="font-semibold text-[#001a61]">{{ $e->user?->name }}</p>
                            <p class="text-xs text-[#757683]">{{ $e->user?->email }}</p>
                        </td>
                        <td class="px-4 py-3">{{ $e->formation?->titre }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 h-1.5 bg-[#e7eeff] rounded-full overflow-hidden max-w-[8rem]"><div class="h-full bg-[#001a61]" style="width:{{ min(100,(int)$e->progress) }}%"></div></div>
                                <span class="font-bold">{{ (int)$e->progress }}%</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 capitalize">{{ $e->status }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.learners.show', $e->user_id) }}" class="text-xs font-bold text-[#001a61] underline" wire:navigate.hover>Détail</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-[#757683]">Aucun apprenant.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $learners->links() }}</div>
    </div>
</div>
