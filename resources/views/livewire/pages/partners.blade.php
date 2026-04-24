<main class="flex-1 pt-20">
    <!-- Hero Section -->
    <section class="relative py-16 overflow-hidden" style="background: linear-gradient(135deg, #071F5A 0%, #0A2E8C 50%, #071F5A 100%);">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-10 left-10 w-72 h-72 bg-secondary/20 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-accent/20 rounded-full blur-3xl animate-pulse delay-1000"></div>
        </div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">Nos <span class="text-secondary">Partenaires</span></h1>
                <p class="text-xl text-white/80">Ils nous font confiance pour nos services de formation, d'analyse et de conseil financier</p>
            </div>
        </div>
    </section>

    <!-- Partenaires Grid -->
    <section class="py-20">
        <div class="container mx-auto px-4">
            @if($partners->isEmpty())
                <div class="text-center py-16">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto h-16 w-16 text-muted-foreground mb-4">
                        <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"></path>
                        <path d="M12 16v-4"></path>
                        <path d="M12 8h.01"></path>
                    </svg>
                    <h3 class="text-xl font-semibold text-foreground mb-2">Aucun partenaire</h3>
                    <p class="text-muted-foreground">Aucun partenaire n'est enregistré pour le moment.</p>
                </div>
            @else
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                    @foreach($partners as $partner)
                        <div class="bg-card rounded-xl border border-border p-6 hover:shadow-elegant hover:border-primary/30 transition-smooth group flex items-center justify-center">
                            @if($partner->logo)
                                <img src="{{ $partner->logo ? '/storage/' . $partner->logo : '' }}" alt="{{ $partner->nom }}" class="max-h-16 max-w-full object-contain">
                            @else
                                <div class="text-center">
                                    <p class="font-semibold text-foreground">{{ $partner->nom }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-16 bg-gradient-to-br from-primary/5 to-secondary/5">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-2xl md:text-3xl font-bold mb-4">Devenez partenaire</h2>
                <p class="text-lg text-muted-foreground mb-8">Vous souhaitez collaborer avec Africaine des Finances ? N'hésitez pas à nous contacter pour explorer les opportunités de partenariat.</p>
                <a href="{{ route('contact') }}" class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary-light shadow-elegant hover:shadow-glow transition-smooth h-12 px-8 rounded-lg">
                    Nous contacter
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right w-4 h-4">
                        <path d="M5 12h14"></path>
                        <path d="m12 5 7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>
</main>