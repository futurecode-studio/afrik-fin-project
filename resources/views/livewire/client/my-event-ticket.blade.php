<div class="container mx-auto px-4 py-8 max-w-2xl">
    <a href="{{ route('client.my-events') }}" class="inline-flex items-center gap-2 text-sm text-muted-foreground hover:text-foreground mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
        Retour à mes événements
    </a>

    <div class="rounded-2xl border bg-card p-8 border-border shadow-elegant text-center space-y-6">
        <div>
            <p class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2">{{ $registration->event->title }}</p>
            <h1 class="text-2xl font-bold">Ticket d'accès</h1>
        </div>

        <div class="flex justify-center">
            <div class="w-48 h-48 bg-white rounded-xl p-3 flex items-center justify-center">
                {!! QrCode::size(180)->generate($registration->qr_code) !!}
            </div>
        </div>

        <div class="space-y-2">
            <p class="text-2xl font-bold">{{ $registration->fullName() }}</p>
            <p class="text-muted-foreground">{{ $registration->email }}</p>
            @if($registration->institution_name)
                <p class="text-sm font-medium">{{ $registration->institution_name }}</p>
            @endif
            @if($registration->ticketType)
                <span class="inline-block px-3 py-1 bg-primary/10 text-primary text-sm font-semibold rounded-full mt-2">{{ $registration->ticketType->name }}</span>
            @endif
        </div>

        <div class="border-t border-border pt-6 grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-muted-foreground">Date</p>
                <p class="font-semibold">{{ $registration->event->starts_at?->format('d/m/Y') }}</p>
            </div>
            <div>
                <p class="text-muted-foreground">Heure</p>
                <p class="font-semibold">{{ $registration->event->starts_at?->format('H:i') }}</p>
            </div>
            <div class="col-span-2">
                <p class="text-muted-foreground">Lieu</p>
                <p class="font-semibold">{{ $registration->event->location_name ?? $registration->event->city ?? 'En ligne' }}</p>
            </div>
        </div>

        <div class="flex justify-center gap-3 pt-4">
            <button wire:click="downloadTicket" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-primary-foreground rounded-xl font-semibold hover:bg-primary-light transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                Télécharger le PDF
            </button>
        </div>
    </div>
</div>
