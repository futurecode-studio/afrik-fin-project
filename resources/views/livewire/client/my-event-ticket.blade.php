<div class="max-w-lg mx-auto">
    <a href="{{ route('client.my-events') }}" class="text-sm font-bold text-[#001a61] hover:underline">← Mes événements</a>
    <h1 class="text-3xl font-extrabold text-[#001a61] mt-4">Votre billet</h1>

    <div class="mt-8 bg-white border border-[#c5c5d4] rounded-2xl overflow-hidden shadow-sm">
        <div class="bg-[#001a61] text-white p-6">
            <p class="text-xs uppercase tracking-widest text-[#ffbf00] font-bold">Africaine des Finances</p>
            <h2 class="text-xl font-extrabold mt-2">{{ $registration->event->title }}</h2>
            <p class="text-sm text-white/80 mt-2">
                {{ optional($registration->event->starts_at)->format('d/m/Y H:i') }}
                @if ($registration->event->location_name || $registration->event->city)
                    · {{ $registration->event->location_name ?? $registration->event->city }}
                @endif
            </p>
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-[#757683]">Participant</p>
                    <p class="font-bold text-[#001a61]">{{ $registration->fullName() }}</p>
                </div>
                <div>
                    <p class="text-[#757683]">Type</p>
                    <p class="font-bold text-[#001a61]">{{ $registration->ticketType->name ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-[#757683]">Statut</p>
                    <p class="font-bold">{{ $registration->statusLabel() }}</p>
                </div>
                <div>
                    <p class="text-[#757683]">Code</p>
                    <p class="font-mono text-xs font-bold">{{ $registration->qr_code }}</p>
                </div>
            </div>

            @if ($registration->qr_code)
                <div class="flex justify-center py-4">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode($registration->qr_code) }}"
                        alt="QR Code" class="rounded-lg border border-[#c5c5d4]">
                </div>
            @endif

            <button type="button" wire:click="downloadTicket"
                class="w-full bg-[#001a61] text-white font-bold py-3 rounded hover:bg-[#0a2e8c]">
                Télécharger le PDF
            </button>
            <a href="{{ route('event.ticket.public', $registration->qr_code) }}"
                class="block text-center text-sm font-bold text-[#001a61] hover:underline">
                Lien public du billet
            </a>
        </div>
    </div>
</div>
