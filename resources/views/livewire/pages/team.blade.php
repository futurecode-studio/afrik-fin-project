<main class="flex-1 pt-20">
    <!-- Hero Section -->
    <section class="relative py-16 overflow-hidden" style="background: linear-gradient(135deg, #071F5A 0%, #0A2E8C 50%, #071F5A 100%);">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-10 left-10 w-72 h-72 bg-secondary/20 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-accent/20 rounded-full blur-3xl animate-pulse delay-1000"></div>
        </div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">Notre <span class="text-secondary">Équipe</span></h1>
                <p class="text-xl text-white/80">Des experts passionnés à votre service pour vos investissements en Afrique</p>
            </div>
        </div>
    </section>

    <!-- Team Grid -->
    <section class="py-20">
        <div class="container mx-auto px-4">
            @if($members->isEmpty())
                <div class="text-center py-16">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto h-16 w-16 text-muted-foreground mb-4">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <h3 class="text-xl font-semibold text-foreground mb-2">Aucune équipe</h3>
                    <p class="text-muted-foreground">Aucun membre de l'équipe n'est enregistré pour le moment.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    @foreach($members as $member)
                        <div class="bg-card rounded-xl border border-border overflow-hidden hover:shadow-elegant hover:border-primary/30 transition-smooth group">
                            <div class="aspect-square overflow-hidden bg-gradient-to-br from-primary/5 to-secondary/5 relative">
                                @if($member->photo_url)
                                    <img src="{{ $member->photo_url }}" alt="{{ $member->nom }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-24 h-24 text-muted-foreground/30">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="p-6">
                                <h3 class="font-bold text-lg text-foreground mb-1">{{ $member->nom }}</h3>
                                <p class="text-primary font-medium mb-2">{{ $member->poste }}</p>
                                
                                @if($member->attributs)
                                    <div class="flex flex-wrap gap-1 mb-3">
                                        @foreach(array_map('trim', explode(',', $member->attributs)) as $attr)
                                            <span class="inline-flex items-center px-2 py-0.5 bg-secondary/10 text-secondary text-xs font-medium rounded-full">
                                                {{ trim($attr) }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                                
                                @if($member->description)
                                    <p class="text-sm text-muted-foreground line-clamp-3">{{ $member->description }}</p>
                                @endif
                                
                                <div class="flex items-center gap-3 mt-4 pt-4 border-t border-border">
                                    @if($member->email)
                                        <a href="mailto:{{ $member->email }}" class="text-muted-foreground hover:text-primary transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                                            </svg>
                                        </a>
                                    @endif
                                    @if($member->contact)
                                        <a href="tel:{{ $member->contact }}" class="text-muted-foreground hover:text-primary transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</main>