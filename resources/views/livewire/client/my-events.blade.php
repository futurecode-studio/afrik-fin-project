<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Mes Événements</h1>

    @if (session()->has('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800 border border-green-200">{{ session('success') }}</div>
    @endif
    @if (session()->has('error'))
        <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 border border-red-200">{{ session('error') }}</div>
    @endif

    @if($registrations->isEmpty())
        <div class="rounded-xl border bg-card p-12 text-center border-border">
            <p class="text-muted-foreground mb-4">Vous n'êtes inscrit à aucun événement pour le moment.</p>
            <a href="{{ route('events-list') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-primary-foreground rounded-xl font-semibold hover:bg-primary-light transition-colors">
                Découvrir les événements
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($registrations as $reg)
            <div class="rounded-xl border bg-card p-6 border-border hover:border-primary/30 transition-colors">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-start gap-4">
                        @if($reg->event->featured_image)
                            <img src="{{ asset('storage/'.$reg->event->featured_image) }}" alt="" class="w-16 h-16 rounded-lg object-cover flex-shrink-0">
                        @else
                            <div class="w-16 h-16 rounded-lg bg-muted flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-muted-foreground"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
                            </div>
                        @endif
                        <div>
                            <h3 class="font-bold text-lg">{{ $reg->event->title }}</h3>
                            <p class="text-sm text-muted-foreground">{{ $reg->event->starts_at?->format('d/m/Y H:i') }} • {{ $reg->event->city ?? $reg->event->location_name }}</p>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $reg->statusColorClasses() }}">
                                    {{ $reg->statusLabel() }}
                                </span>
                                @if($reg->ticketType)
                                    <span class="text-xs text-muted-foreground">{{ $reg->ticketType->name }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        @if(!in_array($reg->status, ['cancelled','no_show']))
                            <a href="{{ route('client.event.ticket', $reg->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary-light transition-colors text-sm font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
                                Mon ticket
                            </a>
                            <button wire:click="downloadTicket({{ $reg->id }})" class="inline-flex items-center gap-2 px-4 py-2 border border-border rounded-lg text-sm hover:bg-muted transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                                PDF
                            </button>
                            @if($reg->event->starts_at > now())
                                <button wire:click="cancelRegistration({{ $reg->id }})" wire:confirm="Êtes-vous sûr de vouloir annuler votre inscription ?" class="inline-flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                    Annuler
                                </button>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
