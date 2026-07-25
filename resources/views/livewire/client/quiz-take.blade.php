<div class="max-w-4xl mx-auto px-4 py-6">
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
            <p class="text-xs font-bold uppercase text-[#757683]">{{ $quiz->is_final ? 'Examen final' : 'Quiz' }}</p>
            <h1 class="text-xl font-extrabold text-[#001a61]">{{ $quiz->titre }}</h1>
        </div>
        <div class="text-sm font-bold text-[#001a61]">
            Question {{ $currentIndex + 1 }} / {{ $total }}
            <span class="text-[#757683] font-medium ml-2">· {{ $answered }} répondues</span>
        </div>
    </div>

    <div class="h-2 bg-[#e7eeff] rounded-full overflow-hidden mb-6">
        <div class="h-full bg-[#001a61]" style="width: {{ $total ? round((($currentIndex+1)/$total)*100) : 0 }}%"></div>
    </div>

    @if ($question)
        <div class="bg-white border border-[#c5c5d4] rounded-2xl p-6 md:p-8">
            <p class="text-lg font-bold text-[#001a61] leading-snug">{{ $question->question }}</p>
            <p class="text-xs text-[#757683] mt-2 uppercase">{{ str_replace('_',' ', $question->type) }} · {{ $question->points }} pt(s)</p>

            <div class="mt-6 space-y-3">
                @foreach ($question->answers as $answer)
                    @php
                        $selected = $answers[$question->id] ?? null;
                        $isSelected = is_array($selected) ? in_array($answer->id, $selected) : (int)$selected === (int)$answer->id;
                    @endphp
                    <button type="button" wire:click="selectAnswer({{ $question->id }}, {{ $answer->id }})"
                        @class([
                            'w-full text-left px-4 py-3 rounded-xl border transition',
                            'border-[#001a61] bg-[#e7eeff]' => $isSelected,
                            'border-[#c5c5d4] hover:bg-[#f0f3ff]' => !$isSelected,
                        ])>
                        <span class="font-medium text-[#131c2a]">{{ $answer->reponse }}</span>
                    </button>
                @endforeach
            </div>
        </div>

        <div class="mt-6 flex flex-wrap items-center justify-between gap-3">
            <button type="button" wire:click="previous" @disabled($currentIndex===0) class="px-4 py-2 rounded-lg border border-[#c5c5d4] font-bold text-[#001a61] disabled:opacity-40">← Précédente</button>
            <div class="flex gap-2">
                @if ($currentIndex < $total - 1)
                    <button type="button" wire:click="skip" class="px-4 py-2 rounded-lg text-[#757683] font-medium">Passer</button>
                    <button type="button" wire:click="next" class="px-5 py-2 rounded-xl bg-[#001a61] text-white font-bold">Suivante →</button>
                @else
                    <div x-data="{ confirmOpen: false }">
                        <button type="button" @click="confirmOpen = true" class="px-5 py-2 rounded-xl bg-[#ffbf00] text-[#261a00] font-extrabold">
                            Terminer {{ $quiz->is_final ? "l'examen" : 'le quiz' }}
                        </button>
                        <div x-show="confirmOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
                            <div class="bg-white rounded-2xl p-6 max-w-md w-full shadow-xl" @click.outside="confirmOpen = false">
                                <h3 class="text-lg font-extrabold text-[#001a61]">Confirmer l'envoi ?</h3>
                                <p class="text-sm text-[#444652] mt-2">
                                    {{ $answered }} / {{ $total }} questions répondues.
                                    @if ($answered < $total)
                                        Les questions sans réponse seront comptées comme incorrectes.
                                    @endif
                                </p>
                                <div class="mt-5 flex gap-2 justify-end">
                                    <button type="button" @click="confirmOpen = false" class="px-4 py-2 rounded-xl border border-[#c5c5d4] font-bold text-[#001a61]">Annuler</button>
                                    <button type="button" wire:click="submit" @click="confirmOpen = false"
                                        class="px-4 py-2 rounded-xl bg-[#001a61] text-white font-bold"
                                        wire:loading.attr="disabled" wire:target="submit">
                                        <span wire:loading.remove wire:target="submit">Envoyer</span>
                                        <span wire:loading wire:target="submit">Correction…</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
