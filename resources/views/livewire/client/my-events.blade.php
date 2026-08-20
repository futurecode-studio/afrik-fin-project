<div>
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-[#001a61]">Mes événements</h1>
        <p class="text-[#444652] mt-2">Inscriptions et billets à venir.</p>
    </div>

    <div class="space-y-4">
        @forelse ($registrations as $reg)
            <article class="bg-white border border-[#c5c5d4] rounded-xl p-5 flex flex-col md:flex-row md:items-center gap-4">
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap gap-2 items-center text-xs mb-1">
                        <span class="font-bold text-[#0a2e8c]">{{ $reg->event->category }}</span>
                        <span class="px-2 py-0.5 rounded bg-[#e7eeff] text-[#001a61] font-semibold">{{ $reg->statusLabel() }}</span>
                    </div>
                    <h2 class="font-bold text-lg text-[#001a61]">{{ $reg->event->title }}</h2>
                    <p class="text-sm text-[#757683] mt-1">
                        {{ optional($reg->event->starts_at)->format('d/m/Y H:i') }}
                        @if ($reg->event->city) · {{ $reg->event->city }}@endif
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    @if (!in_array($reg->status, ['cancelled', 'no_show'], true))
                        <a href="{{ route('client.event.ticket', $reg->id) }}"
                            class="inline-flex items-center gap-1 bg-[#001a61] text-white font-bold px-4 py-2 rounded text-sm hover:bg-[#0a2e8c]">
                            <span class="material-symbols-outlined text-base">confirmation_number</span>
                            Voir le billet
                        </a>
                        <button type="button" wire:click="downloadTicket({{ $reg->id }})"
                            class="inline-flex items-center gap-1 border border-[#001a61] text-[#001a61] font-bold px-4 py-2 rounded text-sm hover:bg-[#e7eeff]">
                            PDF
                        </button>
                        <button type="button" wire:click="cancelRegistration({{ $reg->id }})"
                            wire:confirm="Annuler cette inscription ?"
                            class="text-sm font-bold text-red-600 px-2">Annuler</button>
                    @endif
                </div>
            </article>
        @empty
            <div class="bg-white border border-dashed border-[#c5c5d4] rounded-xl p-12 text-center">
                <span class="material-symbols-outlined text-5xl text-[#c5c5d4]">event</span>
                <p class="mt-4 text-[#444652]">Aucune inscription événement.</p>
                <a href="{{ route('events-list') }}" class="inline-block mt-4 font-bold text-[#001a61] underline">Voir le calendrier</a>
            </div>
        @endforelse
    </div>
</div>
