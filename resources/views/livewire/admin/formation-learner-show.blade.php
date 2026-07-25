<div class="p-6 lg:p-8 space-y-6">
    <div>
        <nav class="flex items-center gap-2 text-[#757683] mb-2 text-xs font-semibold tracking-wider uppercase flex-wrap">
            <a href="{{ route('admin.formations') }}" class="hover:text-[#001a61]" wire:navigate.hover>Formations</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <a href="{{ route('admin.formations.show', $formation) }}" class="hover:text-[#001a61]" wire:navigate.hover>{{ Str::limit($formation->titre, 28) }}</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <a href="{{ route('admin.formations.learners', $formation) }}" class="hover:text-[#001a61]" wire:navigate.hover>Apprenants</a>
            <span class="material-symbols-outlined text-sm">chevron_right</span>
            <span class="text-[#001a61]">{{ $learner->name }}</span>
        </nav>
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-3">
            <div>
                <h1 class="text-2xl font-extrabold text-[#001a61]">{{ $learner->name }}</h1>
                <p class="text-sm text-[#444652] mt-1">{{ $learner->email }} · inscrit le {{ $enrollment->enrolled_at?->format('d/m/Y') ?? '—' }}</p>
            </div>
            <a href="{{ route('admin.formations.learners', $formation) }}" class="text-sm font-bold text-[#001a61] underline" wire:navigate.hover>← Retour liste</a>
        </div>
    </div>

    @include('livewire.admin.partials.formation-admin-nav', ['formation' => $formation])

    <div class="grid sm:grid-cols-2 xl:grid-cols-5 gap-4">
        <div class="admin-card p-5">
            <p class="text-xs uppercase text-[#757683]">Progression</p>
            <p class="text-3xl font-extrabold text-[#001a61] mt-1">{{ (int) $enrollment->progress }}%</p>
            <p class="text-xs text-[#757683] mt-1">{{ $doneCount }}/{{ $totalLessons }} leçons</p>
        </div>
        <div class="admin-card p-5">
            <p class="text-xs uppercase text-[#757683]">Quiz moyen</p>
            <p class="text-3xl font-extrabold text-[#001a61] mt-1">{{ $avgQuiz !== null ? $avgQuiz.'%' : '—' }}</p>
            <p class="text-xs text-[#757683] mt-1">{{ $attempts->count() }} tentative(s)</p>
        </div>
        <div class="admin-card p-5">
            <p class="text-xs uppercase text-[#757683]">Connexions</p>
            <p class="text-3xl font-extrabold text-[#001a61] mt-1">{{ $loginCount }}</p>
        </div>
        <div class="admin-card p-5">
            <p class="text-xs uppercase text-[#757683]">Dernière connexion</p>
            <p class="text-lg font-extrabold text-[#001a61] mt-1">{{ $lastLogin ? \Illuminate\Support\Carbon::parse($lastLogin)->format('d/m/Y H:i') : '—' }}</p>
        </div>
        <div class="admin-card p-5">
            <p class="text-xs uppercase text-[#757683]">Notes / exercices</p>
            <p class="text-3xl font-extrabold text-[#001a61] mt-1">{{ $notesCount }} / {{ $exercises->count() }}</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        <div class="admin-card overflow-hidden">
            <div class="px-5 py-4 border-b border-[#e7eeff]">
                <h2 class="font-bold text-[#001a61]">Progression par module</h2>
            </div>
            <div class="divide-y divide-[#e7eeff] max-h-[28rem] overflow-y-auto">
                @foreach ($modulesProgress as $row)
                    <div class="px-5 py-4">
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <p class="font-semibold text-[#001a61]">{{ $row['module']->titre }}</p>
                            <span class="text-sm font-bold">{{ $row['done'] }}/{{ $row['total'] }} · {{ $row['pct'] }}%</span>
                        </div>
                        <div class="h-1.5 bg-[#e7eeff] rounded-full overflow-hidden mb-3">
                            <div class="h-full bg-[#ffbf00]" style="width:{{ $row['pct'] }}%"></div>
                        </div>
                        <ul class="space-y-1">
                            @foreach ($row['lessons'] as $item)
                                <li class="flex items-center gap-2 text-sm">
                                    <span class="material-symbols-outlined text-[16px] {{ $item['done'] ? 'text-[#5c6700]' : 'text-[#c5c5d4]' }}">
                                        {{ $item['done'] ? 'check_circle' : 'radio_button_unchecked' }}
                                    </span>
                                    <span @class(['text-[#757683]' => ! $item['done'], 'text-[#131c2a]' => $item['done']])>{{ $item['lesson']->titre }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="space-y-6">
            <div class="admin-card overflow-hidden">
                <div class="px-5 py-4 border-b border-[#e7eeff]">
                    <h2 class="font-bold text-[#001a61]">Résultats quiz</h2>
                </div>
                <ul class="divide-y divide-[#e7eeff]">
                    @forelse ($attempts as $a)
                        <li class="px-5 py-3 flex justify-between gap-3 text-sm">
                            <div>
                                <p class="font-semibold text-[#001a61]">{{ $a->quiz?->titre ?? 'Quiz' }}</p>
                                <p class="text-xs text-[#757683]">{{ $a->completed_at?->format('d/m/Y H:i') }}</p>
                            </div>
                            <span class="font-extrabold text-[#001a61]">{{ round($a->score, 1) }}%</span>
                        </li>
                    @empty
                        <li class="px-5 py-6 text-center text-[#757683] text-sm">Aucun quiz passé.</li>
                    @endforelse
                </ul>
            </div>

            @if ($exercises->isNotEmpty())
                <div class="admin-card overflow-hidden">
                    <div class="px-5 py-4 border-b border-[#e7eeff]">
                        <h2 class="font-bold text-[#001a61]">Exercices soumis</h2>
                    </div>
                    <ul class="divide-y divide-[#e7eeff]">
                        @foreach ($exercises as $ex)
                            <li class="px-5 py-3 text-sm">
                                <p class="font-semibold text-[#001a61]">{{ $ex->lesson?->titre ?? 'Exercice' }}</p>
                                <p class="text-xs text-[#757683] capitalize">{{ $ex->status }} · {{ $ex->submitted_at?->format('d/m/Y H:i') ?? $ex->created_at?->format('d/m/Y H:i') }}</p>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <div class="admin-card overflow-hidden">
        <div class="px-5 py-4 border-b border-[#e7eeff] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <h2 class="font-bold text-[#001a61]">Journal d’activité</h2>
            <select wire:model.live="logFilter" class="admin-input text-sm">
                <option value="">Tous les événements</option>
                <option value="login">Connexions</option>
                <option value="logout">Déconnexions</option>
                <option value="lesson_view">Consultations leçon</option>
                <option value="lesson_complete">Leçons terminées</option>
                <option value="quiz_submit">Quiz</option>
                <option value="exercise_submit">Exercices</option>
            </select>
        </div>
        <ul class="divide-y divide-[#e7eeff]">
            @forelse ($logs as $log)
                <li class="px-5 py-3 flex items-start gap-3">
                    <span class="material-symbols-outlined text-[#001a61] mt-0.5">{{ $log->icon() }}</span>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm">
                            <span class="font-semibold text-[#001a61]">{{ $log->label() }}</span>
                            @if ($log->description)
                                <span class="text-[#444652]">— {{ $log->description }}</span>
                            @endif
                        </p>
                        <p class="text-xs text-[#757683] mt-0.5">
                            {{ $log->created_at?->format('d/m/Y H:i:s') }}
                            @if ($log->ip_address) · IP {{ $log->ip_address }} @endif
                            @if ($log->user_agent)
                                · {{ Str::limit($log->user_agent, 60) }}
                            @endif
                        </p>
                    </div>
                </li>
            @empty
                <li class="px-5 py-8 text-center text-[#757683]">Aucun log pour ce filtre.</li>
            @endforelse
        </ul>
        <div class="p-4">{{ $logs->links() }}</div>
    </div>
</div>
