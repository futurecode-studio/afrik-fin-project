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
            <img src="{{ asset('storage/'.$event->featured_image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
        </div>
    </section>
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

                    <!-- Galerie photos -->
                    @if($event->galleries->isNotEmpty())
                    <div x-data="{ current: 0, open: false, activeImg: '', total: {{ $event->galleries->count() }} }">
                        <h2 class="text-2xl font-bold mb-4">Galerie photos</h2>
                        <div class="relative">
                            <div class="flex gap-4 overflow-x-auto snap-x snap-mandatory pb-4" id="gallery-slider-{{ $event->id }}">
                                @foreach($event->galleries as $index => $img)
                                <div
                                    class="flex-shrink-0 w-72 h-48 snap-start cursor-pointer rounded-xl overflow-hidden border border-border shadow-sm hover:shadow-md transition-shadow"
                                    @click="open = true; activeImg = '{{ $img->image_url }}'"
                                >
                                    <img src="{{ $img->image_url }}" alt="{{ $img->caption }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                                </div>
                                @endforeach
                            </div>
                            @if($event->galleries->count() > 1)
                            <button
                                @click="document.getElementById('gallery-slider-{{ $event->id }}').scrollBy({ left: -300, behavior: 'smooth' })"
                                class="absolute left-2 top-1/2 -translate-y-1/2 w-8 h-8 bg-white/90 backdrop-blur rounded-full shadow flex items-center justify-center text-foreground hover:bg-white"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
                            </button>
                            <button
                                @click="document.getElementById('gallery-slider-{{ $event->id }}').scrollBy({ left: 300, behavior: 'smooth' })"
                                class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 bg-white/90 backdrop-blur rounded-full shadow flex items-center justify-center text-foreground hover:bg-white"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                            </button>
                            @endif
                        </div>

                        <!-- Lightbox / Zoom -->
                        <div
                            x-show="open"
                            x-transition.opacity.duration.300ms
                            class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4"
                            @click="open = false"
                            style="display: none;"
                        >
                            <button class="absolute top-4 right-4 text-white/80 hover:text-white" @click="open = false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                            </button>
                            <img
                                :src="activeImg"
                                class="max-w-[90vw] max-h-[85vh] object-contain rounded-lg shadow-2xl"
                                @click.stop
                            >
                        </div>
                    </div>
                    @endif

                    <!-- Documents -->
                    @if($event->documents->isNotEmpty())
                    <div>
                        <h2 class="text-2xl font-bold mb-4">Documents</h2>
                        <div class="flex flex-wrap gap-3">
                            @foreach($event->documents as $doc)
                            <a href="{{ $doc->download_url }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-border bg-card hover:border-primary/30 hover:shadow-sm transition-all text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/></svg>
                                {{ $doc->title }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Intervenants -->
                    @if($event->speakers->isNotEmpty())
                    <div>
                        <h2 class="text-2xl font-bold mb-4">Intervenants</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($event->speakers as $speaker)
                            <div class="flex items-center gap-4 p-4 rounded-xl border border-border bg-card">
                                @if($speaker->photo)
                                    <img src="{{ asset('storage/'.$speaker->photo) }}" alt="{{ $speaker->name }}" class="w-14 h-14 rounded-full object-cover">
                                @else
                                    <div class="w-14 h-14 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-lg">{{ substr($speaker->name, 0, 1) }}</div>
                                @endif
                                <div>
                                    <p class="font-semibold">{{ $speaker->name }}</p>
                                    <p class="text-sm text-muted-foreground">{{ $speaker->role }}</p>
                                    <p class="text-xs text-muted-foreground">{{ $speaker->company }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
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

                    <!-- Types de billets -->
                    @if($event->requiresTicketSelection())
                    <div class="rounded-xl border bg-card p-6 border-border">
                        <div class="flex items-center justify-between gap-2 mb-4">
                            <h3 class="font-bold">Tarifs</h3>
                            @if($event->pricingMode() === 'hybrid')
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-[#eef3ff] text-[#001a61]">Gratuit + Payant</span>
                            @endif
                        </div>
                        <div class="space-y-3">
                            @foreach($event->ticketTypes as $tt)
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-3 rounded-lg bg-muted/50 border border-border {{ (int) $selectedTicketTypeId === (int) $tt->id ? 'ring-2 ring-primary' : '' }}">
                                <button type="button" wire:click="selectTicket({{ $tt->id }})" class="text-left flex-1">
                                    <p class="font-semibold text-sm">{{ $tt->name }}</p>
                                    @if($tt->description)
                                        <p class="text-xs text-muted-foreground mt-0.5">{{ plain_text($tt->description, 100) }}</p>
                                    @endif
                                    <p class="text-xs text-muted-foreground mt-1">{{ $tt->quantity > 0 ? $tt->seatsRemaining() . ' places restantes' : 'Places illimitées' }}</p>
                                </button>
                                <div class="flex items-center gap-2 shrink-0">
                                    @if($tt->price > 0)
                                        <span class="font-bold text-primary text-sm whitespace-nowrap">{{ number_format($tt->price, 0, ',', ' ') }} FCFA</span>
                                        <button type="button" wire:click="openRegistrationModal({{ $tt->id }})"
                                            class="px-3 py-2 rounded-lg bg-[#ffbf00] text-[#261a00] text-xs font-extrabold hover:brightness-95">
                                            Payer
                                        </button>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800">Gratuit</span>
                                        <button type="button" wire:click="openRegistrationModal({{ $tt->id }})"
                                            class="px-3 py-2 rounded-lg bg-[#001a61] text-white text-xs font-extrabold hover:bg-[#0a2e8c]">
                                            S’inscrire
                                        </button>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

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

                    <!-- Boutique événement -->
                    @if($event->products->isNotEmpty())
                    <div class="rounded-xl border bg-card p-6 border-border">
                        <h3 class="font-bold mb-4">Boutique événement</h3>
                        <div class="space-y-4">
                            @foreach($event->products as $product)
                            <div class="p-3 rounded-lg bg-muted/50 border border-border">
                                @if($product->image_url)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-32 rounded-lg object-cover mb-3 border border-border">
                                @endif
                                <div class="flex items-start justify-between mb-2">
                                    <div>
                                        <p class="font-semibold text-sm">{{ $product->name }}</p>
                                        <p class="text-xs text-muted-foreground mt-0.5">{{ plain_text($product->description, 100) }}</p>
                                        @if($product->variants->contains(fn($v) => $v->price > 0))
                                            <div class="flex flex-wrap gap-1 mt-1">
                                                @foreach($product->variants as $v)
                                                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-primary/10 text-primary">
                                                        {{ $v->variant_name }}: {{ number_format($v->price ?? $product->price, 0, ',', ' ') }} F
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <span class="font-bold text-primary text-sm whitespace-nowrap ml-2">
                                        @if($product->variants->contains(fn($v) => $v->price > 0))
                                            {{ number_format($product->variants->min('price') ?? $product->price, 0, ',', ' ') }} FCFA
                                        @else
                                            {{ number_format($product->price, 0, ',', ' ') }} FCFA
                                        @endif
                                    </span>
                                </div>
                                <button wire:click="openProductModal({{ $product->id }})" class="w-full mt-2 text-center px-3 py-2 bg-[#ffbf00] text-[#261a00] text-sm font-extrabold rounded-lg hover:brightness-95 transition-colors">
                                    Payer
                                </button>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Inscription multi-étapes -->
    @if($showRegistrationModal)
    @php
        $steps = [
            1 => 'Formule',
            2 => 'Informations',
            3 => 'Boutique',
            4 => 'Paiement',
        ];
        if ($event->products->isEmpty()) {
            unset($steps[3]);
        }
        if (! $event->requiresTicketSelection() || $event->ticketTypes->count() <= 1) {
            unset($steps[1]);
        }
    @endphp
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

            {{-- Stepper --}}
            <div class="px-5 pt-4 flex flex-wrap gap-2">
                @foreach ($steps as $n => $label)
                    <span @class([
                        'text-[10px] font-bold uppercase px-2.5 py-1 rounded-full border',
                        'bg-[#001a61] text-white border-[#001a61]' => $regStep === $n,
                        'bg-[#e7eeff] text-[#001a61] border-[#c5c5d4]' => $regStep > $n,
                        'bg-white text-[#757683] border-[#c5c5d4]' => $regStep < $n,
                    ])>{{ $label }}</span>
                @endforeach
            </div>

            <div class="p-5 space-y-4">
                {{-- 1. Formule --}}
                @if ($regStep === 1)
                    <h3 class="font-bold text-[#001a61]">Choisissez votre formule</h3>
                    <div class="space-y-2">
                        @foreach ($event->ticketTypes as $tt)
                            <label class="flex items-center justify-between gap-3 p-4 rounded-xl border cursor-pointer {{ (int) $selectedTicketTypeId === (int) $tt->id ? 'border-[#001a61] bg-[#e7eeff] ring-1 ring-[#001a61]' : 'border-[#c5c5d4]' }}">
                                <span class="flex items-start gap-3">
                                    <input type="radio" wire:model.live="selectedTicketTypeId" value="{{ $tt->id }}" class="mt-1 text-[#001a61]">
                                    <span>
                                        <span class="block font-bold text-[#001a61]">{{ $tt->name }}</span>
                                        @if ($tt->description)
                                            <span class="block text-xs text-[#757683] mt-0.5">{{ plain_text($tt->description, 140) }}</span>
                                        @endif
                                    </span>
                                </span>
                                @if ($tt->price > 0)
                                    <span class="font-extrabold text-[#001a61] whitespace-nowrap">{{ number_format($tt->price, 0, ',', ' ') }} FCFA</span>
                                @else
                                    <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-800">Gratuit</span>
                                @endif
                            </label>
                        @endforeach
                    </div>
                    @error('selectedTicketTypeId') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                @endif

                {{-- 2. Infos --}}
                @if ($regStep === 2)
                    <h3 class="font-bold text-[#001a61]">Vos informations</h3>
                    @if ($selectedTicket)
                        <p class="text-sm text-[#444652]">Formule : <strong class="text-[#001a61]">{{ $selectedTicket->name }}</strong>
                            @if ($selectedTicket->price > 0)
                                — {{ number_format($selectedTicket->price, 0, ',', ' ') }} FCFA
                            @else
                                — Gratuit
                            @endif
                        </p>
                    @endif
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
                @endif

                {{-- 3. Boutique (facultatif) --}}
                @if ($regStep === 3)
                    <h3 class="font-bold text-[#001a61]">Articles boutique <span class="text-xs font-medium text-[#757683]">(facultatif)</span></h3>
                    <p class="text-sm text-[#444652]">Ajoutez des goodies si vous le souhaitez, puis continuez.</p>
                    <div class="space-y-3 max-h-64 overflow-y-auto">
                        @foreach ($event->products as $product)
                            <div class="rounded-xl border border-[#c5c5d4] p-3">
                                <p class="font-bold text-sm text-[#001a61]">{{ $product->name }}</p>
                                <div class="mt-2 space-y-1.5">
                                    @forelse ($product->variants as $v)
                                        <div class="flex items-center justify-between gap-2 text-sm">
                                            <span class="text-[#444652]">{{ $v->variant_name }}@if($v->size) ({{ $v->size }})@endif — {{ number_format($v->effectivePrice(), 0, ',', ' ') }} FCFA</span>
                                            <button type="button" wire:click="addToCart({{ $v->id }})"
                                                class="px-2.5 py-1 rounded-lg border border-[#001a61] text-[#001a61] text-xs font-bold hover:bg-[#e7eeff]">
                                                + Ajouter @if(($cart[$v->id] ?? 0) > 0)({{ $cart[$v->id] }})@endif
                                            </button>
                                        </div>
                                    @empty
                                        <p class="text-xs text-[#757683]">Aucune variante.</p>
                                    @endforelse
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if (count($cartLines) > 0)
                        <div class="rounded-lg bg-[#e7eeff] p-3 text-sm">
                            <p class="font-bold text-[#001a61] mb-1">Panier</p>
                            @foreach ($cartLines as $line)
                                <div class="flex justify-between gap-2">
                                    <span>{{ $line['qty'] }}× {{ $line['name'] }}</span>
                                    <span class="font-semibold">{{ number_format($line['total'], 0, ',', ' ') }}</span>
                                </div>
                                <button type="button" wire:click="removeFromCart({{ $line['variant_id'] }})" class="text-[11px] text-red-600 font-bold">Retirer</button>
                            @endforeach
                            <p class="mt-2 font-extrabold text-[#001a61]">Sous-total boutique : {{ number_format($cartTotal, 0, ',', ' ') }} FCFA</p>
                        </div>
                    @endif
                @endif

                {{-- 4. Paiement --}}
                @if ($regStep === 4)
                    <h3 class="font-bold text-[#001a61]">Paiement</h3>
                    <div class="rounded-xl border border-[#c5c5d4] divide-y divide-[#e7eeff] text-sm">
                        <div class="p-4">
                            <p class="text-xs uppercase text-[#757683] font-bold">Participant</p>
                            <p class="font-semibold text-[#001a61]">{{ $first_name }} {{ $last_name }}</p>
                            <p class="text-[#444652]">{{ $email }}@if($phone) · {{ $phone }}@endif</p>
                        </div>
                        <div class="p-4 flex justify-between gap-3">
                            <div>
                                <p class="text-xs uppercase text-[#757683] font-bold">Formule</p>
                                <p class="font-semibold text-[#001a61]">{{ $selectedTicket?->name ?? 'Inscription' }}</p>
                            </div>
                            <p class="font-extrabold text-[#001a61] whitespace-nowrap">
                                @if ($ticketPrice > 0)
                                    {{ number_format($ticketPrice, 0, ',', ' ') }} FCFA
                                @else
                                    Gratuit
                                @endif
                            </p>
                        </div>
                        @if (count($cartLines) > 0)
                            <div class="p-4 space-y-2">
                                <p class="text-xs uppercase text-[#757683] font-bold">Articles boutique</p>
                                @foreach ($cartLines as $line)
                                    <div class="flex justify-between gap-2">
                                        <span>{{ $line['qty'] }}× {{ $line['name'] }}</span>
                                        <span class="font-semibold">{{ number_format($line['total'], 0, ',', ' ') }} FCFA</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        <div class="p-4 flex justify-between items-center bg-[#f9f9ff]">
                            <span class="font-extrabold text-[#001a61]">Total à régler</span>
                            <span class="text-xl font-extrabold text-[#001a61]">{{ number_format($grandTotal, 0, ',', ' ') }} FCFA</span>
                        </div>
                    </div>
                @endif
            </div>

            <div class="p-5 border-t border-[#c5c5d4] flex flex-wrap justify-between gap-3 sticky bottom-0 bg-white">
                <button type="button"
                    @if ($regStep <= 1 || ($regStep === 2 && (! $event->requiresTicketSelection() || $event->ticketTypes->count() <= 1))) disabled @endif
                    wire:click="prevStep"
                    class="px-4 py-2 rounded-lg border border-[#c5c5d4] text-sm font-bold text-[#444652] disabled:opacity-40">
                    Retour
                </button>
                <div class="flex gap-2">
                    <button type="button" wire:click="closeRegistrationModal" class="px-4 py-2 rounded-lg border border-[#c5c5d4] text-sm font-medium">Annuler</button>
                    @if ($regStep < 4)
                        <button type="button" wire:click="nextStep"
                            class="px-5 py-2 rounded-lg bg-[#001a61] text-white text-sm font-extrabold hover:bg-[#0a2e8c]">
                            Continuer
                        </button>
                    @else
                        <button type="button" wire:click="submitRegistration" wire:loading.attr="disabled"
                            class="px-5 py-2 rounded-lg bg-[#ffbf00] text-[#261a00] text-sm font-extrabold hover:brightness-95 disabled:opacity-50">
                            @if ($grandTotal > 0)
                                Payer {{ number_format($grandTotal, 0, ',', ' ') }} FCFA
                            @else
                                Confirmer l’inscription
                            @endif
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Commande Produit -->
    @if($showProductModal && $selectedProductId)
    @php $productModal = $event->products->firstWhere('id', $selectedProductId); @endphp
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeProductModal"></div>
        <div class="relative adf-modal-panel bg-white rounded-lg shadow-xl w-full max-w-md max-h-[90vh] overflow-y-auto border border-border">
            <div class="p-6 border-b border-border flex items-center justify-between">
                <h2 class="text-xl font-bold">Payer — {{ $productModal?->name }}</h2>
                <button wire:click="closeProductModal" class="text-muted-foreground hover:text-foreground"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Prénom *</label>
                        <input type="text" wire:model="productFirstName" class="w-full px-3 py-2 border border-border rounded-lg bg-background focus:ring-2 focus:ring-primary">
                        @error('productFirstName')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Nom *</label>
                        <input type="text" wire:model="productLastName" class="w-full px-3 py-2 border border-border rounded-lg bg-background focus:ring-2 focus:ring-primary">
                        @error('productLastName')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Email *</label>
                        <input type="email" wire:model="productEmail" class="w-full px-3 py-2 border border-border rounded-lg bg-background focus:ring-2 focus:ring-primary">
                        @error('productEmail')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Téléphone</label>
                        <input type="tel" wire:model="productPhone" placeholder="+229 XX XX XX XX" class="w-full px-3 py-2 border border-border rounded-lg bg-background focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Variante *</label>
                        <select wire:model="selectedVariantId" class="w-full px-3 py-2 border border-border rounded-lg bg-background focus:ring-2 focus:ring-primary">
                            <option value="">-- Choisir --</option>
                            @foreach($productModal->variants as $v)
                                <option value="{{ $v->id }}">{{ $v->variant_name }} @if($v->price) ({{ number_format($v->price, 0, ',', ' ') }} FCFA) @else ({{ number_format($productModal->price, 0, ',', ' ') }} FCFA) @endif — {{ $v->availableQuantity() }} disp.</option>
                            @endforeach
                        </select>
                        @error('selectedVariantId')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Quantité (max 10)</label>
                        <input type="number" wire:model="productQuantity" min="1" max="10" class="w-full px-3 py-2 border border-border rounded-lg bg-background focus:ring-2 focus:ring-primary">
                    </div>
                </div>
            </div>
            <div class="p-6 border-t border-border flex justify-end gap-3 items-center">
                <button wire:click="closeProductModal" class="px-4 py-2 border border-border rounded-lg text-foreground hover:bg-muted">Annuler</button>
                <button wire:click="submitProductOrder" wire:loading.attr="disabled" class="px-6 py-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary-light font-semibold transition-colors disabled:opacity-50 flex items-center gap-2">
                    @if($selectedVariantId)
                        @php
                            $selVar = $productModal->variants->firstWhere('id', $selectedVariantId);
                            $unit = $selVar->price ?? $productModal->price;
                            $tot = $unit * $productQuantity;
                        @endphp
                        <span>Payer {{ number_format($tot, 0, ',', ' ') }} FCFA</span>
                    @else
                        Payer en ligne
                    @endif
                </button>
            </div>
        </div>
    </div>
    @endif

@include('partials.payment-widget-scripts')
</main>
