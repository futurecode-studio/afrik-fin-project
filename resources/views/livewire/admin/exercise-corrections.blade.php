<div>
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#001a61]">Correction des exercices</h1>
            <p class="text-[#444652] mt-2">{{ $pending }} en attente de correction</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <input type="search" wire:model.live.debounce.300ms="q" placeholder="Apprenant, leçon…"
                class="rounded-xl border border-[#c5c5d4] px-3 py-2 text-sm">
            <select wire:model.live="status" class="rounded-xl border border-[#c5c5d4] px-3 py-2 text-sm font-semibold">
                <option value="submitted">À corriger</option>
                <option value="corrected">Corrigés</option>
                <option value="all">Tous</option>
            </select>
        </div>
    </div>

    <div class="adf-card-static overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-[#f0f3ff] text-left text-xs uppercase text-[#757683]">
                <tr>
                    <th class="px-4 py-3">Apprenant</th>
                    <th class="px-4 py-3">Formation / Leçon</th>
                    <th class="px-4 py-3">Envoyé</th>
                    <th class="px-4 py-3">Statut</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($subs as $sub)
                    <tr class="border-t border-[#e7eeff]">
                        <td class="px-4 py-3">
                            <p class="font-bold text-[#001a61]">{{ $sub->user?->name }}</p>
                            <p class="text-xs text-[#757683]">{{ $sub->user?->email }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="font-semibold text-[#001a61]">{{ $sub->lesson?->module?->formation?->titre }}</p>
                            <p class="text-xs text-[#757683]">{{ $sub->lesson?->titre }}</p>
                        </td>
                        <td class="px-4 py-3 text-[#757683]">{{ optional($sub->submitted_at)->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3">
                            @if ($sub->isCorrected())
                                <span class="text-xs font-bold text-green-700">{{ number_format($sub->score, 1) }}/{{ number_format($sub->max_score, 0) }}</span>
                            @else
                                <span class="text-xs font-bold text-amber-700">En attente</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <button type="button" wire:click="open({{ $sub->id }})" class="text-sm font-bold text-[#001a61] underline">
                                {{ $sub->isCorrected() ? 'Voir' : 'Corriger' }}
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-12 text-center text-[#757683]">Aucune soumission.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($editingId)
        @php $current = $subs->firstWhere('id', $editingId) ?? \App\Models\LessonExerciseSubmission::with(['user','lesson'])->find($editingId); @endphp
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
            <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto p-6 shadow-xl">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h2 class="text-xl font-extrabold text-[#001a61]">Correction</h2>
                        <p class="text-sm text-[#757683]">{{ $current?->user?->name }} — {{ $current?->lesson?->titre }}</p>
                    </div>
                    <button type="button" wire:click="close" class="p-1 hover:bg-[#e7eeff] rounded"><span class="material-symbols-outlined">close</span></button>
                </div>

                @if ($current?->answer_text)
                    <div class="mt-4 rounded-xl bg-[#f0f3ff] p-4 text-sm whitespace-pre-line">{{ $current->answer_text }}</div>
                @endif
                @if ($current?->file_path)
                    <a href="{{ asset('storage/'.$current->file_path) }}" target="_blank" class="inline-flex items-center gap-1 mt-3 text-sm font-bold text-[#001a61]">
                        <span class="material-symbols-outlined text-base">download</span> {{ $current->file_name ?? 'Fichier' }}
                    </a>
                @endif

                <form wire:submit.prevent="correct" class="mt-6 space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-bold uppercase text-[#757683]">Note</label>
                            <input type="number" step="0.5" wire:model="score" class="mt-1 w-full rounded-xl border border-[#c5c5d4] px-3 py-2">
                            @error('score') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-xs font-bold uppercase text-[#757683]">Sur</label>
                            <input type="number" step="1" wire:model="maxScore" class="mt-1 w-full rounded-xl border border-[#c5c5d4] px-3 py-2">
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase text-[#757683]">Feedback</label>
                        <textarea wire:model="feedback" rows="4" class="mt-1 w-full rounded-xl border border-[#c5c5d4] px-3 py-2"></textarea>
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase text-[#757683]">Fichier annoté (optionnel)</label>
                        <input type="file" wire:model="annotatedFile" class="mt-1 block w-full text-sm">
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" wire:click="close" class="px-4 py-2 rounded-xl border border-[#c5c5d4] font-bold">Annuler</button>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-[#001a61] text-white font-bold">Enregistrer la correction</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
