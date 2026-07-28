<main class="flex-1 pt-20">
    <section class="py-12">
        <div class="container mx-auto px-4 max-w-2xl">
            @if ($awaitingPayment ?? false)
                <div class="rounded-2xl border border-amber-200 bg-amber-50 p-8 text-center">
                    <span class="material-symbols-outlined text-5xl text-amber-700">pending</span>
                    <h1 class="text-xl font-extrabold text-[#001a61] mt-3">Paiement en attente</h1>
                    <p class="text-[#444652] mt-2">Votre inscription à <strong>{{ $registration->event->title }}</strong> n’est pas confirmée tant que le paiement n’est pas validé.</p>
                    <a href="{{ route('event-detail', $registration->event->slug) }}" class="inline-flex mt-6 px-5 py-2.5 rounded-lg bg-[#001a61] text-white font-bold text-sm">Retour à l’événement</a>
                </div>
            @else
            <div class="rounded-2xl border border-border bg-card shadow-elegant overflow-hidden">
                <div class="bg-primary text-primary-foreground p-8 text-center">
                    <h1 class="text-xl font-bold">{{ $registration->event->title }}</h1>
                    <p class="text-sm opacity-90 mt-1">Ticket officiel — Africaine des Finances</p>
                </div>
                <div class="p-8 text-center">
                    <p class="text-xs text-muted-foreground mb-4">N° {{ $registration->qr_code }}</p>
                    <div class="mx-auto w-48 h-48 mb-6">
                        <img src="data:image/svg+xml;base64,{{ base64_encode(\QrCode::format('svg')->size(192)->generate($registration->qr_code)) }}" alt="QR Code" class="w-full h-full">
                    </div>
                    <p class="text-2xl font-bold text-foreground">{{ $registration->fullName() }}</p>
                    <p class="text-muted-foreground">{{ $registration->email }}</p>
                    @if($registration->institution_name)
                        <p class="text-sm text-muted-foreground mt-1">{{ $registration->institution_name }}</p>
                    @endif
                    @if($registration->ticketType)
                        <span class="inline-block mt-3 px-4 py-1 bg-primary/10 text-primary text-sm font-semibold rounded-full">{{ $registration->ticketType->name }}</span>
                    @endif

                    <div class="grid grid-cols-3 gap-4 mt-8 pt-6 border-t border-border text-sm">
                        <div>
                            <p class="text-muted-foreground">Date</p>
                            <p class="font-semibold text-foreground">{{ $registration->event->starts_at?->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-muted-foreground">Heure</p>
                            <p class="font-semibold text-foreground">{{ $registration->event->starts_at?->format('H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-muted-foreground">Lieu</p>
                            <p class="font-semibold text-foreground">{{ $registration->event->location_name ?? $registration->event->city ?? '-' }}</p>
                        </div>
                    </div>

                    @if ($registration->event->hasOnlineAccess())
                        <div class="mt-6 rounded-xl border border-[#001a61]/20 bg-[#e7eeff] p-5 text-left">
                            <p class="text-sm font-extrabold text-[#001a61] mb-3">Accès {{ $registration->event->onlinePlatformLabel() }}</p>
                            <a href="{{ $registration->event->online_meeting_url }}" target="_blank" rel="noopener"
                               class="inline-flex items-center justify-center w-full px-4 py-2.5 rounded-lg bg-[#ffbf00] text-[#261a00] font-extrabold text-sm hover:brightness-95">
                                Rejoindre la réunion
                            </a>
                            <dl class="mt-4 space-y-2 text-sm">
                                @if ($registration->event->online_meeting_id)
                                    <div class="flex justify-between gap-3">
                                        <dt class="text-[#757683]">ID réunion</dt>
                                        <dd class="font-semibold text-[#001a61]">{{ $registration->event->online_meeting_id }}</dd>
                                    </div>
                                @endif
                                @if ($registration->event->online_meeting_passcode)
                                    <div class="flex justify-between gap-3">
                                        <dt class="text-[#757683]">Code secret</dt>
                                        <dd class="font-semibold text-[#001a61]">{{ $registration->event->online_meeting_passcode }}</dd>
                                    </div>
                                @endif
                            </dl>
                            @if ($registration->event->online_access_instructions)
                                <p class="mt-3 text-xs text-[#444652]">{{ $registration->event->online_access_instructions }}</p>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="bg-muted/50 p-6 text-center border-t border-border">
                    <button wire:click="downloadTicket" class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-primary-foreground rounded-lg hover:bg-primary-light transition-colors font-semibold text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                        Télécharger mon ticket PDF
                    </button>
                    <p class="text-xs text-muted-foreground mt-3">
                        @if ($registration->event->hasOnlineAccess())
                            Conservez ce lien et l’e-mail de confirmation — le lien de connexion y est aussi.
                        @else
                            Présentez ce QR code à l'entrée le jour J. Conservez ce lien pour y accéder à tout moment.
                        @endif
                    </p>
                </div>
            </div>
            @endif
        </div>
    </section>
</main>
