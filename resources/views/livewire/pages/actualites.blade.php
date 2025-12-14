<main class="flex-1 pt-20">
    <section class="bg-gradient-hero text-primary-foreground py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Actualités <span
                        class="text-secondary">Financières</span></h1>
                <p class="text-lg text-primary-foreground/90">Restez informé des dernières tendances,
                    analyses et opportunités des marchés financiers africains</p>
            </div>
        </div>
    </section>
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                @forelse($articles as $article)
                <div
                    class="rounded-lg border bg-card text-card-foreground shadow-sm overflow-hidden border-border hover:border-primary/30 hover:shadow-elegant transition-smooth group">
                    <a href="{{ route('actualite-detail', $article->slug) }}">
                        <div class="relative h-64 overflow-hidden">
                            <img src="{{ $article->image_url }}"
                                alt="{{ $article->titre }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-smooth duration-500">
                            <div class="absolute top-4 left-4">
                                <span class="px-3 py-1 bg-secondary text-secondary-foreground text-xs font-semibold rounded-full shadow-glow">{{ $article->categorie }}</span>
                            </div>
                        </div>
                    </a>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center gap-4 text-sm text-muted-foreground">
                            <div class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar w-4 h-4">
                                    <path d="M8 2v4"></path>
                                    <path d="M16 2v4"></path>
                                    <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                                    <path d="M3 10h18"></path>
                                </svg>
                                <span>{{ $article->published_at->format('d F Y') }}</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-tag w-4 h-4">
                                    <path d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z"></path>
                                    <circle cx="7.5" cy="7.5" r=".5" fill="currentColor"></circle>
                                </svg>
                                <span>{{ $article->categorie }}</span>
                            </div>
                        </div>
                        <a href="{{ route('actualite-detail', $article->slug) }}">
                            <h2 class="text-2xl font-bold group-hover:text-primary transition-smooth">{{ $article->titre }}</h2>
                        </a>
                        <p class="text-muted-foreground leading-relaxed">{{ $article->extrait }}</p>
                        <p class="text-foreground/80 leading-relaxed">{{ Str::limit(strip_tags($article->contenu), 150) }}</p>
                    </div>
                </div>
                @empty
                <div class="col-span-2 text-center py-12">
                    <p class="text-muted-foreground">Aucun article disponible pour le moment.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>
</main>