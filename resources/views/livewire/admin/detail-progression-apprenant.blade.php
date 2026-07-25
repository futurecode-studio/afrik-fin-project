<div class="p-6 lg:p-8 space-y-6">
    <a href="{{ route('admin.learners') }}" class="text-sm font-bold text-[#001a61] underline" wire:navigate.hover>← Suivi apprenants</a>
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-[#001a61]">{{ $learner->name }}</h1>
            <p class="text-sm text-[#757683]">{{ $learner->email }} · inscrit le {{ optional($learner->created_at)->format('d/m/Y') }}</p>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div class="admin-card px-4 py-3"><p class="text-[11px] uppercase text-[#757683]">Progression moy.</p><p class="text-xl font-extrabold text-[#001a61]">{{ $avgProgress }}%</p></div>
            <div class="admin-card px-4 py-3"><p class="text-[11px] uppercase text-[#757683]">Score quiz moy.</p><p class="text-xl font-extrabold text-[#001a61]">{{ $avgQuiz !== null ? $avgQuiz.'%' : '—' }}</p></div>
        </div>
    </div>

    <div class="admin-card overflow-hidden">
        <div class="px-5 py-4 border-b border-[#e7eeff] font-bold text-[#001a61]">Formations</div>
        @foreach ($enrollments as $e)
            <div class="px-5 py-4 border-t border-[#e7eeff]">
                <div class="flex justify-between gap-3">
                    <p class="font-semibold text-[#001a61]">{{ $e->formation?->titre }}</p>
                    <span class="font-bold">{{ (int)$e->progress }}%</span>
                </div>
                <div class="mt-2 h-2 bg-[#e7eeff] rounded-full overflow-hidden"><div class="h-full bg-[#ffbf00]" style="width:{{ min(100,(int)$e->progress) }}%"></div></div>
                <p class="text-xs text-[#757683] mt-2">{{ count($e->completed_lessons ?? []) }} leçons terminées · statut {{ $e->status }}</p>
            </div>
        @endforeach
    </div>

    <div class="admin-card overflow-hidden">
        <div class="px-5 py-4 border-b border-[#e7eeff] font-bold text-[#001a61]">Quiz récents</div>
        @forelse ($attempts as $a)
            <div class="px-5 py-3 border-t border-[#e7eeff] flex justify-between text-sm">
                <span>{{ $a->quiz?->titre }}</span>
                <span class="font-bold {{ $a->is_passed ? 'text-green-700' : 'text-red-600' }}">{{ (int)$a->score }}%</span>
            </div>
        @empty
            <p class="px-5 py-6 text-sm text-[#757683]">Aucune tentative.</p>
        @endforelse
    </div>
</div>
