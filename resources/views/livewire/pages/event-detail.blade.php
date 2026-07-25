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
                    <p class="text-lg text-primary-foreground/90 mb-6">{{ $event->description }}</p>

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
                                    <p class="text-sm font-bold text-primary">{{ $item->starts_at?->format('H:i') }}</p>
                                    <p class="text-xs text-muted-foreground">{{ $item->ends_at?->format('H:i') ?? '' }}</p>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold">{{ $item->title }}</h3>
                                    @if($item->description)
                                        <p class="text-sm text-muted-foreground mt-1">{{ $item->description }}</p>
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
                        {!! $event->content !!}
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
                        </div>
                    </div>

                    <!-- Types de billets -->
                    @if($event->ticketTypes->isNotEmpty())
                    <div class="rounded-xl border bg-card p-6 border-border">
                        <h3 class="font-bold mb-4">Tarifs</h3>
                        <div class="space-y-3">
                            @foreach($event->ticketTypes as $tt)
                            <div class="flex items-center justify-between p-3 rounded-lg bg-muted/50 border border-border {{ $selectedTicketTypeId == $tt->id ? 'ring-2 ring-primary' : '' }}" wire:click="selectTicket({{ $tt->id }})">
                                <div>
                                    <p class="font-semibold text-sm">{{ $tt->name }}</p>
                                    <p class="text-xs text-muted-foreground">{{ $tt->seatsRemaining() }} places restantes</p>
                                </div>
                                <span class="font-bold text-primary">{{ $tt->price > 0 ? number_format($tt->price, 0, ',', ' ') . ' FCFA' : 'Gratuit' }}</span>
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
                                        <p class="text-xs text-muted-foreground mt-0.5">{{ $product->description }}</p>
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
                                <button wire:click="openProductModal({{ $product->id }})" class="w-full mt-2 text-center px-3 py-2 bg-primary text-primary-foreground text-sm font-semibold rounded-lg hover:bg-primary-light transition-colors">
                                    Commander
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

    <!-- Modal Inscription -->
    @if($showRegistrationModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeRegistrationModal"></div>
        <div class="relative adf-modal-panel bg-white rounded-lg shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto border border-border">
            <div class="p-6 border-b border-border flex items-center justify-between">
                <h2 class="text-xl font-bold">Inscription</h2>
                <button wire:click="closeRegistrationModal" class="text-muted-foreground hover:text-foreground"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Prénom *</label>
                        <input type="text" wire:model="first_name" class="w-full px-3 py-2 border border-border rounded-lg bg-background focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Nom *</label>
                        <input type="text" wire:model="last_name" class="w-full px-3 py-2 border border-border rounded-lg bg-background focus:ring-2 focus:ring-primary">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1">Email *</label>
                        <input type="email" wire:model="email" class="w-full px-3 py-2 border border-border rounded-lg bg-background focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Téléphone</label>
                        <input type="tel" wire:model="phone" class="w-full px-3 py-2 border border-border rounded-lg bg-background focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Institution</label>
                        <input type="text" wire:model="institution_name" placeholder="Banque, SGI, etc." class="w-full px-3 py-2 border border-border rounded-lg bg-background focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Fonction</label>
                        <input type="text" wire:model="job_title" class="w-full px-3 py-2 border border-border rounded-lg bg-background focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Taille T-shirt</label>
                        <select wire:model="t_shirt_size" class="w-full px-3 py-2 border border-border rounded-lg bg-background focus:ring-2 focus:ring-primary">
                            <option value="">--</option>
                            <option value="S">S</option>
                            <option value="M">M</option>
                            <option value="L">L</option>
                            <option value="XL">XL</option>
                            <option value="XXL">XXL</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1">Contact d'urgence</label>
                        <div class="grid grid-cols-2 gap-3">
                            <input type="text" wire:model="emergency_contact_name" placeholder="Nom" class="w-full px-3 py-2 border border-border rounded-lg bg-background focus:ring-2 focus:ring-primary">
                            <input type="tel" wire:model="emergency_contact_phone" placeholder="Téléphone" class="w-full px-3 py-2 border border-border rounded-lg bg-background focus:ring-2 focus:ring-primary">
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1">Remarques médicales (optionnel)</label>
                        <textarea wire:model="medical_notes" rows="2" class="w-full px-3 py-2 border border-border rounded-lg bg-background focus:ring-2 focus:ring-primary"></textarea>
                    </div>
                </div>
            </div>
            <div class="p-6 border-t border-border flex justify-end gap-3">
                <button wire:click="closeRegistrationModal" class="px-4 py-2 border border-border rounded-lg text-foreground hover:bg-muted">Annuler</button>
                <button wire:click="submitRegistration" class="px-6 py-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary-light font-semibold transition-colors">Confirmer mon inscription</button>
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
                <h2 class="text-xl font-bold">Commander — {{ $productModal?->name }}</h2>
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

                    <!-- Choix du moyen de paiement -->
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-3">Moyen de paiement</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative cursor-pointer block">
                                <input type="radio" wire:model.live="paymentProvider" value="kkiapay" class="sr-only">
                                <div class="border-2 rounded-lg p-4 text-center transition-all {{ $paymentProvider === 'kkiapay' ? 'border-primary bg-primary/10 ring-2 ring-primary ring-offset-2' : 'border-border hover:border-muted-foreground bg-card' }}">
                                    <div class="w-12 h-12 mx-auto mb-2 bg-blue-100 rounded-full flex items-center justify-center">
                                        <span class="text-blue-600 font-bold">KK</span>
                                    </div>
                                    <span class="font-medium text-foreground">KKiaPay</span>
                                    <p class="text-xs text-muted-foreground mt-1">Mobile Money, Carte</p>
                                    @if($paymentProvider === 'kkiapay')
                                        <div class="mt-2">
                                            <span class="inline-flex items-center gap-1 text-xs text-primary font-medium">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                Sélectionné
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </label>
                            <label class="relative cursor-pointer block">
                                <input type="radio" wire:model.live="paymentProvider" value="fedapay" class="sr-only">
                                <div class="border-2 rounded-lg p-4 text-center transition-all {{ $paymentProvider === 'fedapay' ? 'border-primary bg-primary/10 ring-2 ring-primary ring-offset-2' : 'border-border hover:border-muted-foreground bg-card' }}">
                                    <div class="w-12 h-12 mx-auto mb-2 bg-green-100 rounded-full flex items-center justify-center">
                                        <span class="text-green-600 font-bold">FP</span>
                                    </div>
                                    <span class="font-medium text-foreground">FedaPay</span>
                                    <p class="text-xs text-muted-foreground mt-1">Mobile Money, Carte</p>
                                    @if($paymentProvider === 'fedapay')
                                        <div class="mt-2">
                                            <span class="inline-flex items-center gap-1 text-xs text-primary font-medium">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                Sélectionné
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </label>
                        </div>
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
                        <span class="flex items-center gap-2">
                            @if($paymentProvider === 'kkiapay')
                                <span class="w-5 h-5 bg-blue-100 rounded-full flex items-center justify-center text-xs font-bold text-blue-600">K</span>
                            @else
                                <span class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center text-xs font-bold text-green-600">F</span>
                            @endif
                            Payer {{ number_format($tot, 0, ',', ' ') }} FCFA
                        </span>
                    @else
                        Payer en ligne
                    @endif
                </button>
            </div>
        </div>
    </div>
    @endif

<script src="https://cdn.kkiapay.me/k.js"></script>
<script src="https://cdn.fedapay.com/checkout.js?v=1.1.7"></script>
<script>
document.addEventListener('livewire:init', () => {
    Livewire.on('openPaymentWidget', (data) => {
        const paymentData = data[0];
        if (paymentData.provider === 'kkiapay') {
            if (typeof openKkiapayWidget === 'undefined') { alert('KKiaPay non disponible.'); return; }
            openKkiapayWidget({
                amount: paymentData.amount,
                position: "center",
                callback: "",
                data: paymentData.reference,
                theme: "#0A2E8C",
                key: "{{ config('services.kkiapay.public_key') }}",
                sandbox: {{ config('services.kkiapay.sandbox') ? 'true' : 'false' }},
            });
            addSuccessListener(response => {
                Livewire.dispatch('paymentSuccess', [{ transactionId: response.transactionId, reference: paymentData.reference, status: 'SUCCESS' }]);
            });
        } else if (paymentData.provider === 'fedapay') {
            if (typeof FedaPay === 'undefined') { alert('FedaPay non disponible.'); return; }
            const amount = parseInt(paymentData.amount, 10);
            if (isNaN(amount) || amount <= 0) { alert('Montant invalide.'); return; }
            FedaPay.init({
                public_key: "{{ config('services.fedapay.public_key') }}",
                transaction: {
                    amount: amount,
                    description: paymentData.formation || 'Commande événement',
                },
                customer: {
                    email: paymentData.email || '',
                    lastname: paymentData.name || '',
                    phone: paymentData.phone || '',
                },
                onComplete: function(transaction) {
                    Livewire.dispatch('paymentSuccess', [{ transactionId: transaction.id, reference: paymentData.reference, status: transaction.status }]);
                },
            });
        }
    });
});
</script>
</main>
