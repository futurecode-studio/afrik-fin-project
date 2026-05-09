<main class="flex-1 pt-20">
    <section class="relative text-primary-foreground py-16 overflow-hidden" style="background: linear-gradient(135deg, #071F5A 0%, #0A2E8C 60%, #1E4AB8 100%);">
        <div class="absolute inset-0 z-0 pointer-events-none opacity-20">
            <div class="absolute top-10 left-10 w-72 h-72 bg-secondary/30 rounded-full blur-3xl"></div>
        </div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-3xl">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Nos <span class="text-secondary">Événements</span></h1>
                <p class="text-lg text-primary-foreground/90">Rejoignez nos rencontres sportives, conférences et formations en présentiel à travers la zone UEMOA.</p>
            </div>
        </div>
    </section>

    <section class="py-12 bg-muted/30">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row gap-4 mb-8">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Rechercher un événement..." class="w-full md:max-w-md px-4 py-2 border border-border rounded-lg bg-background focus:ring-2 focus:ring-primary">
                <select wire:model.live="filterCategory" class="px-4 py-2 border border-border rounded-lg bg-background focus:ring-2 focus:ring-primary">
                    <option value="">Toutes catégories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
                <select wire:model.live="filterCity" class="px-4 py-2 border border-border rounded-lg bg-background focus:ring-2 focus:ring-primary">
                    <option value="">Toutes villes</option>
                    @foreach($cities as $c)
                        <option value="{{ $c }}">{{ $c }}</option>
                    @endforeach
                </select>
                <select wire:model.live="filterType" class="px-4 py-2 border border-border rounded-lg bg-background focus:ring-2 focus:ring-primary">
                    <option value="">Tous</option>
                    <option value="upcoming">À venir</option>
                    <option value="past">Passés</option>
                    <option value="featured">Mis en avant</option>
                </select>
            </div>

            @if($events->isEmpty())
                <div class="text-center py-16">
                    <p class="text-muted-foreground">Aucun événement ne correspond à votre recherche.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($events as $event)
                    <a href="{{ route('event-detail', $event->slug) }}" class="group block rounded-2xl border bg-card text-card-foreground shadow-sm overflow-hidden border-border hover:border-primary/30 hover:shadow-elegant transition-all duration-300">
                        <div class="relative h-48 overflow-hidden">
                            @if($event->featured_image)
                                <img src="{{ asset('storage/'.$event->featured_image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-primary to-primary-light flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
                                </div>
                            @endif
                            @if($event->is_featured)
                                <div class="absolute top-3 left-3">
                                    <span class="px-3 py-1 bg-secondary text-secondary-foreground text-xs font-bold rounded-full shadow-glow">À la une</span>
                                </div>
                            @endif
                        </div>
                        <div class="p-6 space-y-3">
                            <div class="flex items-center gap-2 text-sm text-muted-foreground">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
                                <span>{{ $event->starts_at?->format('d M Y') }}</span>
                                <span>•</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>
                                <span>{{ $event->city ?? $event->location_name ?? 'En ligne' }}</span>
                            </div>
                            <h3 class="text-xl font-bold group-hover:text-primary transition-colors line-clamp-2">{{ $event->title }}</h3>
                            <p class="text-sm text-muted-foreground line-clamp-2">{{ $event->description }}</p>
                            <div class="flex items-center justify-between pt-2">
                                <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-primary/10 text-primary">{{ $event->category }}</span>
                                @if($event->isRegistrationOpen())
                                    <span class="text-sm font-semibold text-emerald-600">Inscriptions ouvertes</span>
                                @else
                                    <span class="text-sm text-muted-foreground">Inscriptions fermées</span>
                                @endif
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</main>
