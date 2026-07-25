<div class="p-6 lg:p-8 space-y-6">
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-[#757683] mb-2 text-xs font-semibold tracking-wider uppercase">
                <a href="{{ route('admin.formations') }}" class="hover:text-[#001a61]" wire:navigate.hover>Formations</a>
                <span class="material-symbols-outlined text-sm">chevron_right</span>
                <span class="text-[#001a61]">{{ $formation->titre }}</span>
            </nav>
            <h1 class="text-2xl lg:text-3xl font-extrabold text-[#001a61]">{{ $formation->titre }}</h1>
            <p class="text-sm text-[#444652] mt-1">
                {{ ucfirst($formation->niveau) }} · {{ $formation->modules_count }} modules · {{ $totalLessons }} leçons ·
                <span class="capitalize">{{ $formation->statut }}</span>
            </p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.formations.modules', $formation) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#001a61] text-white text-sm font-bold hover:bg-[#0a2e8c]" wire:navigate.hover>
                <span class="material-symbols-outlined text-[18px]">view_module</span> Gérer les modules
            </a>
            <a href="{{ route('admin.formations.learners', $formation) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-[#c5c5d4] text-[#001a61] text-sm font-bold hover:bg-[#e7eeff]" wire:navigate.hover>
                <span class="material-symbols-outlined text-[18px]">groups</span> Voir les apprenants
            </a>
        </div>
    </div>

    @include('livewire.admin.partials.formation-admin-nav', ['formation' => $formation])

    <div class="grid sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="admin-card p-5">
            <p class="text-xs uppercase text-[#757683]">Inscrits actifs</p>
            <p class="text-3xl font-extrabold text-[#001a61] mt-1">{{ $enrolled }}</p>
            @if ($pending)<p class="text-xs text-[#757683] mt-1">{{ $pending }} en attente</p>@endif
        </div>
        <div class="admin-card p-5">
            <p class="text-xs uppercase text-[#757683]">Taux de complétion</p>
            <p class="text-3xl font-extrabold text-[#001a61] mt-1">{{ $completionRate }}%</p>
            <p class="text-xs text-[#757683] mt-1">{{ $completedCount }} terminée(s)</p>
        </div>
        <div class="admin-card p-5">
            <p class="text-xs uppercase text-[#757683]">Progression moyenne</p>
            <p class="text-3xl font-extrabold text-[#001a61] mt-1">{{ $avgProgress }}%</p>
        </div>
        <div class="admin-card p-5">
            <p class="text-xs uppercase text-[#757683]">Connexions élèves (30j)</p>
            <p class="text-3xl font-extrabold text-[#001a61] mt-1">{{ $studentLogins30d }}</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-4">
        <div class="admin-card p-5">
            <p class="text-xs uppercase text-[#757683]">Score quiz moyen</p>
            <p class="text-2xl font-extrabold text-[#001a61] mt-1">{{ $avgQuiz !== null ? $avgQuiz.'%' : '—' }}</p>
            <p class="text-xs text-[#757683] mt-1">{{ $quizAttempts }} tentative(s)</p>
        </div>
        <div class="admin-card p-5">
            <p class="text-xs uppercase text-[#757683]">Note moyenne</p>
            <p class="text-2xl font-extrabold text-[#001a61] mt-1">{{ $rating !== null ? $rating.'/5' : '—' }}</p>
            <p class="text-xs text-[#757683] mt-1">{{ $reviewsCount }} avis</p>
        </div>
        <div class="admin-card p-5">
            <p class="text-xs uppercase text-[#757683]">Contenu</p>
            <p class="text-2xl font-extrabold text-[#001a61] mt-1">{{ $formation->modules_count }}</p>
            <p class="text-xs text-[#757683] mt-1">modules · {{ $totalLessons }} leçons</p>
        </div>
    </div>

    <div class="admin-card p-5">
        <h2 class="font-bold text-[#001a61] mb-1">Répartition des progressions</h2>
        <p class="text-sm text-[#444652] mb-4">Nombre d’apprenants par tranche.</p>
        <div class="grid grid-cols-5 gap-2">
            @foreach ($buckets as $label => $count)
                <div class="rounded-lg bg-[#f0f3ff] p-3 text-center">
                    <p class="text-[11px] font-bold text-[#757683]">{{ $label }}%</p>
                    <p class="text-xl font-extrabold text-[#001a61]">{{ $count }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        <div class="admin-card overflow-hidden">
            <div class="px-5 py-4 border-b border-[#e7eeff] flex items-center justify-between">
                <h2 class="font-bold text-[#001a61]">Modules</h2>
                <a href="{{ route('admin.formations.modules', $formation) }}" class="text-xs font-bold text-[#001a61] underline" wire:navigate.hover>Gérer</a>
            </div>
            <ul class="divide-y divide-[#e7eeff]">
                @forelse ($formation->modules as $module)
                    <li class="px-5 py-3 flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <p class="font-semibold text-[#001a61] truncate">{{ $module->ordre }}. {{ $module->titre }}</p>
                            <p class="text-xs text-[#757683]">{{ $module->lessons_count }} leçon(s) · {{ $module->duree_estimee ?: '—' }}</p>
                        </div>
                        <div class="flex gap-2 shrink-0">
                            <a href="{{ route('admin.formations.modules.lessons', [$formation, $module]) }}"
                                class="text-xs font-bold px-2.5 py-1.5 rounded-lg border border-[#c5c5d4] hover:bg-[#e7eeff]" wire:navigate.hover>Leçons</a>
                            <a href="{{ route('admin.formations.modules.quiz', [$formation, $module]) }}"
                                class="text-xs font-bold px-2.5 py-1.5 rounded-lg border border-[#c5c5d4] hover:bg-[#e7eeff]" wire:navigate.hover>Quiz</a>
                        </div>
                    </li>
                @empty
                    <li class="px-5 py-8 text-center text-[#757683]">Aucun module — créez-en depuis « Gérer les modules ».</li>
                @endforelse
            </ul>
        </div>

        <div class="admin-card overflow-hidden">
            <div class="px-5 py-4 border-b border-[#e7eeff] flex items-center justify-between">
                <h2 class="font-bold text-[#001a61]">Meilleurs progressions</h2>
                <a href="{{ route('admin.formations.learners', $formation) }}" class="text-xs font-bold text-[#001a61] underline" wire:navigate.hover>Tous</a>
            </div>
            <ul class="divide-y divide-[#e7eeff]">
                @forelse ($topLearners as $e)
                    <li class="px-5 py-3 flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <a href="{{ route('admin.formations.learners.show', [$formation, $e->user_id]) }}"
                                class="font-semibold text-[#001a61] hover:underline truncate block" wire:navigate.hover>{{ $e->user?->name }}</a>
                            <p class="text-xs text-[#757683]">Dernière connexion :
                                {{ $e->user?->last_login_at?->diffForHumans() ?? 'jamais' }}</p>
                        </div>
                        <span class="font-extrabold text-[#001a61]">{{ (int) $e->progress }}%</span>
                    </li>
                @empty
                    <li class="px-5 py-8 text-center text-[#757683]">Aucun apprenant inscrit.</li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="admin-card overflow-hidden">
        <div class="px-5 py-4 border-b border-[#e7eeff]">
            <h2 class="font-bold text-[#001a61]">Activité récente (formation)</h2>
        </div>
        <ul class="divide-y divide-[#e7eeff]">
            @forelse ($recentActivity as $log)
                <li class="px-5 py-3 flex items-start gap-3">
                    <span class="material-symbols-outlined text-[#001a61] mt-0.5">{{ $log->icon() }}</span>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm text-[#131c2a]">
                            <span class="font-semibold">{{ $log->user?->name }}</span>
                            — {{ $log->label() }}
                            @if ($log->description)
                                <span class="text-[#757683]">· {{ $log->description }}</span>
                            @endif
                        </p>
                        <p class="text-xs text-[#757683] mt-0.5">
                            {{ $log->created_at?->format('d/m/Y H:i') }}
                            @if ($log->ip_address) · IP {{ $log->ip_address }} @endif
                        </p>
                    </div>
                </li>
            @empty
                <li class="px-5 py-8 text-center text-[#757683]">Aucune activité enregistrée pour cette formation.</li>
            @endforelse
        </ul>
    </div>
</div>
