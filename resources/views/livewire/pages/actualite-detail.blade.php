<main class="flex-1 pt-20">
    <section class="relative">
        <div class="relative h-96 overflow-hidden">
            <img src="{{ $article->image_url }}" alt="{{ $article->titre }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-background via-background/60 to-transparent"></div>
        </div>
        <div class="container mx-auto px-4 -mt-32 relative z-10">
            <a class="inline-flex items-center gap-2 mb-6 text-primary-foreground hover:text-primary transition-smooth" href="{{ route('actualites') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left w-4 h-4">
                    <path d="m12 19-7-7 7-7"></path>
                    <path d="M19 12H5"></path>
                </svg>
                Retour aux actualités
            </a>
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm p-8 max-w-4xl mx-auto">
                <div class="mb-4">
                    <span class="px-3 py-1 bg-secondary text-secondary-foreground text-sm font-semibold rounded-full">{{ $article->categorie }}</span>
                </div>
                <h1 class="text-4xl font-bold mb-4">{{ $article->titre }}</h1>
                <div class="flex flex-wrap items-center gap-4 text-sm text-muted-foreground mb-6">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar w-4 h-4">
                            <path d="M8 2v4"></path>
                            <path d="M16 2v4"></path>
                            <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                            <path d="M3 10h18"></path>
                        </svg>
                        <span>{{ $article->published_at->format('d F Y') }}</span>
                    </div>
                    <span>•</span>
                    <span>Par {{ $article->user->name ?? 'Équipe Afri-Fin' }}</span>
                    <span>•</span>
                    <span>{{ ceil(str_word_count(strip_tags($article->contenu)) / 200) }} min de lecture</span>
                </div>
                <p class="text-lg text-muted-foreground">{{ $article->extrait }}</p>
            </div>
        </div>
    </section>

    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <div class="lg:col-span-3">
                    <div class="rounded-lg border bg-card text-card-foreground shadow-sm p-8">
                        <article class="prose prose-lg max-w-none">
                            {!! $article->contenu !!}
                        </article>
                        <div class="mt-8 pt-6 border-t border-border">
                            <div class="flex flex-wrap gap-2">
                                <span class="px-3 py-1 bg-muted text-muted-foreground text-sm rounded-full">#{{ $article->categorie }}</span>
                                <span class="px-3 py-1 bg-muted text-muted-foreground text-sm rounded-full">#BRVM</span>
                                <span class="px-3 py-1 bg-muted text-muted-foreground text-sm rounded-full">#Investissement</span>
                            </div>
                        </div>
                        <div class="mt-6 flex gap-4">
                            <button class="inline-flex items-center justify-center whitespace-nowrap font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border-2 border-primary bg-background text-primary hover:bg-primary hover:text-primary-foreground transition-smooth h-14 rounded-lg px-10 text-base gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-share2 w-4 h-4">
                                    <circle cx="18" cy="5" r="3"></circle>
                                    <circle cx="6" cy="12" r="3"></circle>
                                    <circle cx="18" cy="19" r="3"></circle>
                                    <line x1="8.59" x2="15.42" y1="13.51" y2="17.49"></line>
                                    <line x1="15.41" x2="8.59" y1="6.51" y2="10.49"></line>
                                </svg>
                                Partager
                            </button>
                            <button class="inline-flex items-center justify-center whitespace-nowrap font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border-2 border-primary bg-background text-primary hover:bg-primary hover:text-primary-foreground transition-smooth h-14 rounded-lg px-10 text-base gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bookmark w-4 h-4">
                                    <path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"></path>
                                </svg>
                                Sauvegarder
                            </button>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-1">
                    <div class="rounded-lg border bg-card text-card-foreground shadow-sm p-6 sticky top-24 space-y-6">
                        <div>
                            <h3 class="font-bold mb-4 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trending-up w-5 h-5 text-primary">
                                    <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                    <polyline points="16 7 22 7 22 13"></polyline>
                                </svg>
                                Articles similaires
                            </h3>
                            <div class="space-y-4">
                                @foreach($relatedArticles as $related)
                                <a class="block group" href="{{ route('actualite-detail', $related->slug) }}">
                                    <div class="space-y-2">
                                        <span class="text-xs text-primary">{{ $related->categorie }}</span>
                                        <h4 class="text-sm font-medium line-clamp-2 group-hover:text-primary transition-smooth">{{ $related->titre }}</h4>
                                        <p class="text-xs text-muted-foreground">{{ $related->published_at->format('d F Y') }}</p>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                        <div class="pt-6 border-t border-border">
                            <h3 class="font-bold mb-4">Newsletter</h3>
                            <p class="text-sm text-muted-foreground mb-4">Recevez nos analyses directement par email</p>
                            <a href="{{ route('newsletter') }}">
                                <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary-light shadow-elegant hover:shadow-glow transition-smooth h-11 px-6 py-3 w-full">S'abonner</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 bg-muted/30">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-4">Besoin d'un accompagnement personnalisé ?</h2>
            <p class="text-lg text-muted-foreground mb-8 max-w-2xl mx-auto">Nos experts en investissement sont là pour vous conseiller</p>
            <a href="{{ route('contact') }}">
                <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-gradient-hero text-primary-foreground border-2 border-secondary/30 hover:border-secondary shadow-elegant hover:shadow-glow transition-smooth font-semibold h-14 rounded-lg px-10 text-base">Prendre rendez-vous</button>
            </a>
        </div>
    </section>
</main>