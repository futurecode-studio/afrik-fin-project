<main class="flex-1 pt-20">
    <!-- Hero Event -->
    <section class="relative text-white py-16 overflow-hidden" style="background: linear-gradient(135deg, #001a61 0%, #0a2e8c 60%, #1E4AB8 100%);">
        <div class="absolute inset-0 z-0 pointer-events-none opacity-20">
            <div class="absolute top-10 left-10 w-72 h-72 bg-secondary/30 rounded-full blur-3xl"></div>
        </div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="flex flex-col md:flex-row gap-8 items-start">
                <div class="flex-1 max-w-3xl">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="px-3 py-1 bg-secondary text-secondary-foreground text-xs font-bold rounded-full">{{ $event->category }}</span>
                        @if($event->is_featured)
                            <span class="px-3 py-1 bg-white/20 text-white text-xs font-bold rounded-full">À la une</span>
                        @endif
                    </div>
                    <h1 class="text-3xl md:text-5xl font-bold mb-4">{{ $event->title }}</h1>
                    <div class="flex flex-wrap items-center gap-4 text-primary-foreground/90 mb-6">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
                            <span>{{ $event->starts_at?->format('l d F Y') }} • {{ $event->starts_at?->format('H:i') }} – {{ $event->ends_at?->format('H:i') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>
                            <span>{{ $event->location_name ?? $event->city ?? 'En ligne' }}</span>
                        </div>
                    </div>
                    <p class="text-lg text-primary-foreground/90 mb-6">{{ plain_text($event->description) }}</p>

                    <div class="flex flex-wrap items-center gap-3 mb-6">
                        <button type="button"
                            onclick="navigator.clipboard.writeText(@js($publicUrl)).then(() => window.adfToast && window.adfToast('success', 'Lien de l\'événement copié'))"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white/20 border border-white/25 rounded-xl text-sm font-medium transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                            Copier le lien
                        </button>
                    </div>

                    @if($event->isRegistrationOpen())
                        @if($isRegistered && Auth::check())
                            <a href="{{ route('client.my-events') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 text-white rounded-xl font-semibold hover:bg-emerald-700 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>
                                Voir mon ticket
                            </a>
                        @else
                            <button wire:click="openRegistrationModal" class="inline-flex items-center gap-2 px-6 py-3 bg-secondary text-secondary-foreground rounded-xl font-semibold hover:bg-secondary-light transition-colors shadow-glow hover:shadow-elegant">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                                Je participe
                            </button>
                        @endif
                    @else
                        <span class="inline-flex items-center gap-2 px-6 py-3 bg-white/10 text-white/80 rounded-xl font-semibold border border-white/20">
                            Inscriptions fermées
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Bannière mise en avant -->
    @if($event->featured_image)
    <section class="relative -mt-8 z-20 container mx-auto px-4">
        <div class="rounded-2xl overflow-hidden shadow-2xl border border-white/10 max-h-[480px]">
            <img src="{{ Str::startsWith($event->featured_image, 'http') ? $event->featured_image : (Str::startsWith($event->featured_image, 'assets/') ? asset($event->featured_image) : asset('storage/'.$event->featured_image)) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
        </div>
    </section>
    @endif

    <!-- Galerie photos — pleine largeur, visible immédiatement -->
    @if($event->galleries->isNotEmpty())
    <section
        class="py-10 bg-[#0a1a3a]"
    >
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-extrabold text-white mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-[#ffbf00]">photo_camera</span>
                Galerie photos
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($event->galleries as $img)
                <button
                    type="button"
                    class="relative block w-full cursor-pointer rounded-xl overflow-hidden bg-[#1a2a4a] group h-[55vh] sm:h-[45vh] lg:h-[50vh] text-left"
                    data-adf-lightbox-src="{{ $img->image_url }}"
                    data-adf-lightbox-caption="{{ $img->caption ?? '' }}"
                    aria-label="Agrandir l'image {{ $img->caption ?: 'de la galerie' }}"
                >
                    <img
                        src="{{ $img->image_url }}"
                        alt="{{ $img->caption }}"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                    >
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/25 transition-colors duration-300 flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-5xl opacity-0 group-hover:opacity-100 transition-opacity duration-300 drop-shadow-lg">zoom_in</span>
                    </div>
                    @if($img->caption)
                        <p class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/70 to-transparent text-white text-sm px-4 py-3">{{ $img->caption }}</p>
                    @endif
                </button>
                @endforeach
            </div>
        </div>
    </section>

    @once
        @push('scripts')
            <script>
                (() => {
                    const lockPageScroll = (locked) => {
                        document.documentElement.classList.toggle('overflow-hidden', locked);
                        document.body.classList.toggle('overflow-hidden', locked);
                    };

                    const closeLightbox = () => {
                        document.getElementById('adf-gallery-lightbox')?.remove();
                        lockPageScroll(false);
                    };

                    const openLightbox = (src, caption = '') => {
                        closeLightbox();

                        const lightbox = document.createElement('div');
                        lightbox.id = 'adf-gallery-lightbox';
                        lightbox.className = 'fixed inset-0 z-[999999] flex items-center justify-center bg-black/95 px-3 py-14 sm:px-8';

                        const closeButton = document.createElement('button');
                        closeButton.type = 'button';
                        closeButton.className = 'fixed right-3 top-3 z-[1000000] inline-flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-white/85 backdrop-blur hover:bg-white/20 hover:text-white';
                        closeButton.setAttribute('aria-label', "Fermer l'image");

                        const closeIcon = document.createElement('span');
                        closeIcon.className = 'material-symbols-outlined text-3xl leading-none';
                        closeIcon.textContent = 'close';
                        closeButton.appendChild(closeIcon);

                        const figure = document.createElement('figure');
                        figure.className = 'm-0 flex h-full w-full flex-col items-center justify-center gap-3';

                        const image = document.createElement('img');
                        image.src = src;
                        image.alt = caption;
                        image.className = 'block max-w-[96vw] rounded-lg object-contain shadow-2xl';
                        image.style.maxHeight = 'calc(100dvh - 8rem)';
                        figure.appendChild(image);

                        if (caption) {
                            const figcaption = document.createElement('figcaption');
                            figcaption.className = 'max-w-[92vw] text-center text-sm text-white/80';
                            figcaption.textContent = caption;
                            figure.appendChild(figcaption);
                        }

                        lightbox.append(closeButton, figure);

                        lightbox.addEventListener('click', (event) => {
                            if (event.target === lightbox || event.target.closest('button')) {
                                closeLightbox();
                            }
                        });

                        document.body.appendChild(lightbox);
                        lockPageScroll(true);
                    };

                    document.addEventListener('click', (event) => {
                        const trigger = event.target.closest('[data-adf-lightbox-src]');
                        if (!trigger) return;

                        event.preventDefault();
                        openLightbox(trigger.dataset.adfLightboxSrc, trigger.dataset.adfLightboxCaption || '');
                    });

                    document.addEventListener('keydown', (event) => {
                        if (event.key === 'Escape') closeLightbox();
                    });
                })();
            </script>
        @endpush
    @endonce
    @endif

    <!-- Content -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-10">
                    <!-- Programme -->
                    @if($event->programItems->isNotEmpty())
                    <div>
                        <h2 class="text-2xl font-bold mb-4">Programme</h2>
                        <div class="space-y-3">
                            @foreach($event->programItems as $item)
                            <div class="flex items-start gap-4 p-4 rounded-xl border border-border bg-card hover:border-primary/30 transition-colors">
                                <div class="w-16 flex-shrink-0 text-center">
                                    <p class="text-sm font-bold text-primary">{{ $item->starts_at_formatted }}</p>
                                    <p class="text-xs text-muted-foreground">{{ $item->ends_at_formatted }}</p>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold">{{ $item->title }}</h3>
                                    @if($item->description)
                                        <p class="text-sm text-muted-foreground mt-1">{{ plain_text($item->description) }}</p>
                                    @endif
                                    @if($item->location_detail)
                                        <p class="text-xs text-muted-foreground mt-1 flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>
                                            {{ $item->location_detail }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Description longue -->
                    @if($event->content)
                    <div class="prose max-w-none text-foreground">
                        {!! rich_html($event->content) !!}
                    </div>
                    @endif

                    {{-- Galerie déplacée en pleine largeur au-dessus --}}

                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Infos pratiques -->
                    <div class="rounded-xl border bg-card p-6 border-border">
                        <h3 class="font-bold mb-4">Informations</h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary flex-shrink-0 mt-0.5"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
                                <div>
                                    <p class="font-medium">{{ $event->starts_at?->format('d/m/Y') }}</p>
                                    <p class="text-muted-foreground">{{ $event->starts_at?->format('H:i') }} – {{ $event->ends_at?->format('H:i') }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary flex-shrink-0 mt-0.5"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>
                                <div>
                                    <p class="font-medium">{{ $event->location_name ?? 'En ligne' }}</p>
                                    <p class="text-muted-foreground">{{ $event->location_address }}<br>{{ $event->city }}, {{ $event->country }}</p>
                                </div>
                            </div>
                            @if($event->capacity > 0)
                            <div class="flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary flex-shrink-0 mt-0.5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                <div>
                                    <p class="font-medium">{{ $event->capacity }} places</p>
                                    <p class="text-muted-foreground">{{ $event->seatsRemaining() }} disponibles</p>
                                </div>
                            </div>
                            @endif
                            @if ($event->isOnlineOrHybrid())
                            <div class="flex items-start gap-3 pt-2 border-t border-border">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-primary flex-shrink-0 mt-0.5"><rect width="20" height="14" x="2" y="5" rx="2"/><path d="M2 10h20"/></svg>
                                <div>
                                    <p class="font-medium">{{ $event->onlinePlatformLabel() }}</p>
                                    @if ($isRegistered && $event->hasOnlineAccess())
                                        <a href="{{ $event->online_meeting_url }}" target="_blank" rel="noopener" class="text-sm font-bold text-[#001a61] underline underline-offset-2">Rejoindre la réunion</a>
                                        @if ($event->online_meeting_id)
                                            <p class="text-xs text-muted-foreground mt-1">ID : {{ $event->online_meeting_id }}</p>
                                        @endif
                                        @if ($event->online_meeting_passcode)
                                            <p class="text-xs text-muted-foreground">Code : {{ $event->online_meeting_passcode }}</p>
                                        @endif
                                    @else
                                        <p class="text-muted-foreground text-xs">Le lien d’accès sera envoyé par e-mail après inscription.</p>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Sponsors -->
                    @if($event->sponsors->isNotEmpty())
                    <div class="rounded-xl border bg-card p-6 border-border">
                        <h3 class="font-bold mb-4">Partenaires</h3>
                        <div class="space-y-3">
                            @foreach($event->sponsors as $sponsor)
                            <div class="flex items-center gap-3">
                                @if($sponsor->logo)
                                    <img src="{{ asset('storage/'.$sponsor->logo) }}" alt="{{ $sponsor->nom }}" class="w-10 h-10 object-contain">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-muted flex items-center justify-center text-xs font-bold">{{ substr($sponsor->nom, 0, 1) }}</div>
                                @endif
                                <div>
                                    <p class="text-sm font-medium">{{ $sponsor->nom }}</p>
                                    <p class="text-xs text-muted-foreground capitalize">{{ $sponsor->pivot->sponsorship_level }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Inscription -->
    @if($showRegistrationModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeRegistrationModal"></div>
        <div class="relative adf-modal-panel bg-white rounded-2xl shadow-xl w-full max-w-xl max-h-[92vh] overflow-y-auto border border-[#c5c5d4]">
            <div class="p-5 border-b border-[#c5c5d4] flex items-center justify-between sticky top-0 bg-white z-10">
                <div>
                    <p class="text-[10px] font-extrabold uppercase tracking-wider text-[#0a2e8c]">Inscription</p>
                    <h2 class="text-lg font-extrabold text-[#001a61]">{{ $event->title }}</h2>
                </div>
                <button wire:click="closeRegistrationModal" type="button" class="text-[#757683] hover:text-[#001a61]">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <div class="p-5 space-y-4">
                <h3 class="font-bold text-[#001a61]">Vos informations</h3>
                <div class="grid sm:grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-bold uppercase text-[#757683]">Prénom *</label>
                        <input type="text" wire:model="first_name" class="w-full mt-1 rounded-lg border-[#c5c5d4]">
                        @error('first_name') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase text-[#757683]">Nom *</label>
                        <input type="text" wire:model="last_name" class="w-full mt-1 rounded-lg border-[#c5c5d4]">
                        @error('last_name') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="text-xs font-bold uppercase text-[#757683]">Email *</label>
                        <input type="email" wire:model="email" class="w-full mt-1 rounded-lg border-[#c5c5d4]">
                        @error('email') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase text-[#757683]">Téléphone</label>
                        <input type="tel" wire:model="phone" class="w-full mt-1 rounded-lg border-[#c5c5d4]">
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase text-[#757683]">Institution</label>
                        <input type="text" wire:model="institution_name" class="w-full mt-1 rounded-lg border-[#c5c5d4]">
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase text-[#757683]">Fonction</label>
                        <input type="text" wire:model="job_title" class="w-full mt-1 rounded-lg border-[#c5c5d4]">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="text-xs font-bold uppercase text-[#757683]">Contact d’urgence</label>
                        <div class="grid grid-cols-2 gap-2 mt-1">
                            <input type="text" wire:model="emergency_contact_name" placeholder="Nom" class="rounded-lg border-[#c5c5d4]">
                            <input type="tel" wire:model="emergency_contact_phone" placeholder="Téléphone" class="rounded-lg border-[#c5c5d4]">
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="text-xs font-bold uppercase text-[#757683]">Remarques médicales</label>
                        <textarea wire:model="medical_notes" rows="2" class="w-full mt-1 rounded-lg border-[#c5c5d4]"></textarea>
                    </div>
                </div>
            </div>

            <div class="p-5 border-t border-[#c5c5d4] flex flex-wrap justify-end gap-3 sticky bottom-0 bg-white">
                <button type="button" wire:click="closeRegistrationModal" class="px-4 py-2 rounded-lg border border-[#c5c5d4] text-sm font-medium">Annuler</button>
                <button type="button" wire:click="submitRegistration" wire:loading.attr="disabled"
                    class="px-5 py-2 rounded-lg bg-[#ffbf00] text-[#261a00] text-sm font-extrabold hover:brightness-95 disabled:opacity-50">
                    Confirmer l’inscription
                </button>
            </div>
        </div>
    </div>
    @endif
</main>
