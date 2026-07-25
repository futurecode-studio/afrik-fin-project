<div>
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#001a61]">Résultats quiz & examens</h1>
            <p class="text-[#444652] mt-2">Scores moyens, réussite et analyse par question</p>
        </div>
        <select wire:model.live="quizId" class="rounded-xl border border-[#c5c5d4] px-3 py-2 text-sm font-semibold max-w-md">
            <option value="">Sélectionner un quiz…</option>
            @foreach ($quizzes as $qz)
                <option value="{{ $qz->id }}">{{ $qz->module?->formation?->titre }} — {{ $qz->titre }}</option>
            @endforeach
        </select>
    </div>

    <div class="grid sm:grid-cols-3 gap-4 mb-8">
        <div class="adf-card-static p-5">
            <p class="text-xs uppercase text-[#757683]">Tentatives</p>
            <p class="text-3xl font-extrabold text-[#001a61] mt-1">{{ $attempts->count() }}</p>
        </div>
        <div class="adf-card-static p-5">
            <p class="text-xs uppercase text-[#757683]">Score moyen</p>
            <p class="text-3xl font-extrabold text-[#001a61] mt-1">{{ $avgScore }}%</p>
        </div>
        <div class="adf-card-static p-5">
            <p class="text-xs uppercase text-[#757683]">Taux de réussite</p>
            <p class="text-3xl font-extrabold text-[#001a61] mt-1">{{ $passRate }}%</p>
        </div>
    </div>

    @if ($questionStats->isNotEmpty())
        <div class="adf-card-static overflow-hidden mb-8">
            <div class="px-5 py-4 border-b border-[#e7eeff]">
                <h2 class="font-bold text-[#001a61]">Analyse par question {{ $selectedQuiz ? '— '.$selectedQuiz->titre : '' }}</h2>
                <p class="text-sm text-[#757683]">Triées de la plus difficile à la plus réussie</p>
            </div>
            <ul>
                @foreach ($questionStats as $row)
                    <li class="px-5 py-3 border-t border-[#e7eeff] flex flex-wrap items-center justify-between gap-3">
                        <p class="text-sm text-[#444652] flex-1 min-w-0 line-clamp-2">{{ $row['question'] }}</p>
                        <div class="text-right shrink-0">
                            <p @class([
                                'font-extrabold',
                                'text-red-600' => $row['rate'] < 40,
                                'text-amber-700' => $row['rate'] >= 40 && $row['rate'] < 70,
                                'text-green-700' => $row['rate'] >= 70,
                            ])>{{ $row['rate'] }}%</p>
                            <p class="text-xs text-[#757683]">{{ $row['correct'] }}/{{ $row['total'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="adf-card-static overflow-hidden">
        <div class="px-5 py-4 border-b border-[#e7eeff]"><h2 class="font-bold text-[#001a61]">Dernières tentatives</h2></div>
        <table class="w-full text-sm">
            <thead class="bg-[#f0f3ff] text-left text-xs uppercase text-[#757683]">
                <tr>
                    <th class="px-4 py-3">Apprenant</th>
                    <th class="px-4 py-3">Quiz</th>
                    <th class="px-4 py-3">Score</th>
                    <th class="px-4 py-3">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($attempts as $attempt)
                    <tr class="border-t border-[#e7eeff]">
                        <td class="px-4 py-3 font-semibold text-[#001a61]">{{ $attempt->user?->name }}</td>
                        <td class="px-4 py-3">{{ $attempt->quiz?->titre }}</td>
                        <td class="px-4 py-3">
                            <span class="font-bold {{ $attempt->is_passed ? 'text-green-700' : 'text-red-600' }}">{{ (int) $attempt->score }}%</span>
                        </td>
                        <td class="px-4 py-3 text-[#757683]">{{ optional($attempt->completed_at)->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-12 text-center text-[#757683]">Aucune tentative.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
