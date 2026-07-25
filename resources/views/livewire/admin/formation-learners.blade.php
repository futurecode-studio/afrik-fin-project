<div class="p-6 lg:p-8 space-y-6">
    <div>
        <nav class="flex items-center gap-2 text-[#757683] mb-2 text-xs font-semibold tracking-wider uppercase">
            <a href="{{ route('admin.formations') }}" class="hover:text-[#001a61]" wire:navigate.hover>Formations</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <a href="{{ route('admin.formations.show', $formation) }}" class="hover:text-[#001a61]" wire:navigate.hover>{{ Str::limit($formation->titre, 40) }}</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <span class="text-[#001a61]">Apprenants</span>
        </nav>
        <h1 class="text-2xl font-extrabold text-[#001a61]">Apprenants — {{ $formation->titre }}</h1>
        <p class="text-sm text-[#444652] mt-1">{{ $stats['total'] }} inscrits · {{ $stats['completed'] }} terminés · progression moy. {{ $stats['avg'] }}%</p>
    </div>

    @include('livewire.admin.partials.formation-admin-nav', ['formation' => $formation])

    <div class="flex flex-wrap gap-2">
        <input wire:model.live.debounce.300ms="q" type="search" placeholder="Nom ou email…" class="admin-input text-sm min-w-[14rem]">
        <select wire:model.live="status" class="admin-input text-sm">
            <option value="">Tous statuts</option>
            <option value="active">Actif</option>
            <option value="completed">Terminé</option>
            <option value="pending">En attente</option>
        </select>
        <select wire:model.live="sort" class="admin-input text-sm">
            <option value="progress_desc">Progression ↓</option>
            <option value="progress_asc">Progression ↑</option>
            <option value="login">Dernière connexion</option>
            <option value="recent">Inscription récente</option>
            <option value="name">Nom A–Z</option>
        </select>
    </div>

    <div class="admin-card overflow-hidden">
        <table class="admin-table w-full text-sm">
            <thead>
                <tr>
                    <th class="text-left px-4 py-3">Apprenant</th>
                    <th class="text-left px-4 py-3">Progression</th>
                    <th class="text-left px-4 py-3">Quiz moy.</th>
                    <th class="text-left px-4 py-3">Connexions</th>
                    <th class="text-left px-4 py-3">Dernière connexion</th>
                    <th class="text-left px-4 py-3">Statut</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($learners as $e)
                    <tr class="border-t border-[#e7eeff]">
                        <td class="px-4 py-3">
                            <p class="font-semibold text-[#001a61]">{{ $e->user?->name }}</p>
                            <p class="text-xs text-[#757683]">{{ $e->user?->email }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2 min-w-[8rem]">
                                <div class="flex-1 h-1.5 bg-[#e7eeff] rounded-full overflow-hidden">
                                    <div class="h-full bg-[#001a61]" style="width:{{ min(100, (int) $e->progress) }}%"></div>
                                </div>
                                <span class="font-bold">{{ (int) $e->progress }}%</span>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            @php $avg = $quizAvgs[$e->user_id] ?? null; @endphp
                            {{ $avg !== null ? round($avg, 1).'%' : '—' }}
                        </td>
                        <td class="px-4 py-3 font-semibold">{{ $loginCounts[$e->user_id] ?? 0 }}</td>
                        <td class="px-4 py-3 text-[#757683]">
                            {{ $e->user?->last_login_at?->format('d/m/Y H:i') ?? '—' }}
                        </td>
                        <td class="px-4 py-3 capitalize">{{ $e->status }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.formations.learners.show', [$formation, $e->user_id]) }}"
                                class="text-xs font-bold text-[#001a61] underline" wire:navigate.hover>Stats & logs</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-[#757683]">Aucun apprenant.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $learners->links() }}</div>
    </div>
</div>
