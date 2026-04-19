<main class="flex-1 pt-20">
    <!-- Messages Flash -->
    @if (session()->has('success'))
        <div class="fixed top-24 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="fixed top-24 right-4 z-50 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg">
            {{ session('error') }}
        </div>
    @endif
    @if (session()->has('info'))
        <div class="fixed top-24 right-4 z-50 bg-blue-500 text-white px-6 py-3 rounded-lg shadow-lg">
            {{ session('info') }}
        </div>
    @endif

    <!-- Hero Section -->
    <section class="relative">
        <div class="relative h-96 overflow-hidden">
            <img src="{{ $formation->image_url ?: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=1200&h=600&fit=crop' }}"
                alt="{{ $formation->titre }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-background via-background/60 to-transparent"></div>
        </div>
        <div class="container mx-auto px-4 -mt-32 relative z-10">
            <a class="inline-flex items-center gap-2 mb-6 text-primary-foreground hover:text-primary transition-smooth"
                href="{{ route('formations') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left w-4 h-4">
                    <path d="m12 19-7-7 7-7"></path>
                    <path d="M19 12H5"></path>
                </svg>
                Retour aux formations
            </a>
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm p-8">
                <div class="flex flex-col lg:flex-row gap-8">
                    <div class="flex-1">
                        <div class="mb-4 flex gap-2">
                            <span class="px-3 py-1 bg-secondary text-secondary-foreground text-sm font-semibold rounded-full">
                                {{ ucfirst($formation->niveau) }}
                            </span>
                            @if($formation->isFree())
                                <span class="px-3 py-1 bg-green-500 text-white text-sm font-semibold rounded-full">
                                    Gratuit
                                </span>
                            @endif
                        </div>
                        <h1 class="text-4xl font-bold mb-4">{{ $formation->titre }}</h1>
                        <p class="text-lg text-muted-foreground mb-6">{{ strip_tags($formation->description_courte) }}</p>
                        <div class="flex flex-wrap gap-6">
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock w-5 h-5 text-primary">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                <span>{{ $formation->duree }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-book-open w-5 h-5 text-primary">
                                    <path d="M12 7v14"></path>
                                    <path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"></path>
                                </svg>
                                <span>{{ $formation->modules->count() }} modules</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users w-5 h-5 text-primary">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                                <span>{{ $formation->enrollments()->where('status', 'active')->count() }} étudiants</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-award w-5 h-5 text-primary">
                                    <path d="m15.477 12.89 1.515 8.526a.5.5 0 0 1-.81.47l-3.58-2.687a1 1 0 0 0-1.197 0l-3.586 2.686a.5.5 0 0 1-.81-.469l1.514-8.526"></path>
                                    <circle cx="12" cy="8" r="6"></circle>
                                </svg>
                                <span>Certification incluse</span>
                            </div>
                        </div>
                    </div>
                    <!-- Card Prix -->
                    <div class="rounded-lg border bg-card text-card-foreground shadow-sm p-6 lg:w-80 h-fit">
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-muted-foreground mb-1">Prix</p>
                                @if($formation->isFree())
                                    <p class="text-3xl font-bold text-green-500">Gratuit</p>
                                @else
                                    <p class="text-3xl font-bold text-primary">{{ number_format($formation->prix, 0, ',', ' ') }} FCFA</p>
                                @endif
                            </div>
                            
                            @if($isEnrolled)
                                <a href="{{ route('formation-detail', $formation->slug) }}" 
                                    class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium bg-green-500 text-white hover:bg-green-600 transition-smooth h-14 rounded-lg px-10 text-base w-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    Accéder à la formation
                                </a>
                            @else
                                <button wire:click="openPaymentModal"
                                    class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary-light shadow-elegant hover:shadow-glow transition-smooth h-14 rounded-lg px-10 text-base w-full">
                                    S'inscrire maintenant
                                </button>
                            @endif
                            
                            <div class="pt-4 border-t border-border space-y-3 text-sm">
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check w-4 h-4 text-primary">
                                        <path d="M20 6 9 17l-5-5"></path>
                                    </svg>
                                    <span>Accès à vie</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check w-4 h-4 text-primary">
                                        <path d="M20 6 9 17l-5-5"></path>
                                    </svg>
                                    <span>Support 7j/7</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check w-4 h-4 text-primary">
                                        <path d="M20 6 9 17l-5-5"></path>
                                    </svg>
                                    <span>Certificat de fin de formation</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check w-4 h-4 text-primary">
                                        <path d="M20 6 9 17l-5-5"></path>
                                    </svg>
                                    <span>Mises à jour gratuites</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contenu -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-12">
                    <!-- Description complète -->
                    @if($formation->description_complete)
                    <div>
                        <h2 class="text-3xl font-bold mb-6">À propos de cette <span class="text-primary">formation</span></h2>
                        <div class="prose prose-lg max-w-none text-muted-foreground">
                            {!! $formation->description_complete !!}
                        </div>
                    </div>
                    @endif

                    <!-- Programme détaillé -->
                    <div>
                        <h2 class="text-3xl font-bold mb-6">Programme <span class="text-primary">détaillé</span></h2>
                        <div class="space-y-4" x-data="{ openModule: null }">
                            @forelse($formation->modules as $index => $module)
                                <div class="border border-border rounded-lg overflow-hidden">
                                    <button 
                                        @click="openModule = openModule === {{ $index }} ? null : {{ $index }}"
                                        class="w-full flex items-center justify-between p-4 hover:bg-muted/50 transition-colors"
                                    >
                                        <div class="flex items-center gap-4 text-left">
                                            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-play w-5 h-5 text-primary">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <polygon points="10 8 16 12 10 16 10 8"></polygon>
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="font-semibold">Module {{ $module->ordre }} : {{ $module->titre }}</h3>
                                                <p class="text-sm text-muted-foreground">{{ $module->lessons->count() }} leçons</p>
                                            </div>
                                        </div>
                                        <svg 
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                                            class="h-5 w-5 shrink-0 transition-transform duration-200"
                                            :class="openModule === {{ $index }} ? 'rotate-180' : ''"
                                        >
                                            <path d="m6 9 6 6 6-6"></path>
                                        </svg>
                                    </button>
                                    <div 
                                        x-show="openModule === {{ $index }}"
                                        x-collapse
                                        class="border-t border-border"
                                    >
                                        <div class="p-4 bg-muted/30">
                                            @if($module->description)
                                                <p class="text-sm text-muted-foreground mb-4">{{ $module->description }}</p>
                                            @endif
                                            @if($module->lessons->count() > 0)
                                                <ul class="space-y-2">
                                                    @foreach($module->lessons as $lesson)
                                                        <li class="flex items-center gap-3 text-sm">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary">
                                                                @if($lesson->type === 'video')
                                                                    <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                                                @elseif($lesson->type === 'quiz')
                                                                    <path d="M9 11l3 3L22 4"></path>
                                                                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                                                                @else
                                                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                                    <polyline points="14 2 14 8 20 8"></polyline>
                                                                @endif
                                                            </svg>
                                                            <span>{{ $lesson->titre }}</span>
                                                            @if($lesson->duree)
                                                                <span class="text-muted-foreground text-xs">({{ $lesson->duree }})</span>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p class="text-sm text-muted-foreground italic">Contenu à venir...</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-muted-foreground">
                                    <p>Le programme détaillé sera bientôt disponible.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="rounded-lg border bg-card text-card-foreground shadow-sm p-6 sticky top-24 space-y-6">
                        <h3 class="text-xl font-bold">Prérequis</h3>
                        <ul class="space-y-3">
                            <li class="flex items-start gap-2 text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check w-4 h-4 text-primary mt-0.5 flex-shrink-0">
                                    <path d="M20 6 9 17l-5-5"></path>
                                </svg>
                                <span>Aucun prérequis</span>
                            </li>
                            <li class="flex items-start gap-2 text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check w-4 h-4 text-primary mt-0.5 flex-shrink-0">
                                    <path d="M20 6 9 17l-5-5"></path>
                                </svg>
                                <span>Connexion internet stable</span>
                            </li>
                            <li class="flex items-start gap-2 text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check w-4 h-4 text-primary mt-0.5 flex-shrink-0">
                                    <path d="M20 6 9 17l-5-5"></path>
                                </svg>
                                <span>Motivation à apprendre</span>
                            </li>
                        </ul>
                        <div class="pt-6 border-t border-border">
                            <h3 class="text-xl font-bold mb-4">Cette formation inclut</h3>
                            <ul class="space-y-3 text-sm">
                                <li class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-play w-4 h-4 text-primary">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polygon points="10 8 16 12 10 16 10 8"></polygon>
                                    </svg>
                                    {{ $formation->modules->count() }} modules
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text w-4 h-4 text-primary">
                                        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                                        <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                                        <path d="M10 9H8"></path>
                                        <path d="M16 13H8"></path>
                                        <path d="M16 17H8"></path>
                                    </svg>
                                    Documents téléchargeables
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-award w-4 h-4 text-primary">
                                        <path d="m15.477 12.89 1.515 8.526a.5.5 0 0 1-.81.47l-3.58-2.687a1 1 0 0 0-1.197 0l-3.586 2.686a.5.5 0 0 1-.81-.469l1.514-8.526"></path>
                                        <circle cx="12" cy="8" r="6"></circle>
                                    </svg>
                                    Certificat de réussite
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users w-4 h-4 text-primary">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                    </svg>
                                    Accès communauté
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-muted/30">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-4">Prêt à commencer votre formation ?</h2>
            <p class="text-lg text-muted-foreground mb-8 max-w-2xl mx-auto">
                Rejoignez {{ $formation->enrollments()->where('status', 'active')->count() }} étudiants qui ont déjà commencé leur parcours
            </p>
            @if($isEnrolled)
                <a href="{{ route('formation-detail', $formation->slug) }}" 
                    class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-semibold bg-green-500 text-white hover:bg-green-600 transition-smooth h-14 rounded-lg px-10 text-base">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Accéder à la formation
                </a>
            @else
                <button wire:click="openPaymentModal"
                    class="inline-flex items-center justify-center gap-2 whitespace-nowrap ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-gradient-hero text-primary-foreground border-2 border-secondary/30 hover:border-secondary shadow-elegant hover:shadow-glow transition-smooth font-semibold h-14 rounded-lg px-10 text-base">
                    @if($formation->isFree())
                        S'inscrire gratuitement
                    @else
                        S'inscrire pour {{ number_format($formation->prix, 0, ',', ' ') }} FCFA
                    @endif
                </button>
            @endif
        </div>
    </section>

    <!-- Modale de paiement -->
    @if($showPaymentModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closePaymentModal"></div>

        <!-- Modal -->
        <div class="relative bg-card rounded-lg text-left overflow-hidden shadow-xl transform transition-all w-full max-w-lg border border-border">
            <!-- Header -->
            <div class="bg-primary px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-white" id="modal-title">Inscription à la formation</h3>
                        <p class="text-primary-foreground/80 text-sm">{{ $formation->titre }}</p>
                    </div>
                    <button wire:click="closePaymentModal" class="text-white hover:text-gray-200 transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="px-6 py-5 bg-card space-y-6">
                <!-- Résumé de la formation -->
                <div class="bg-muted rounded-lg p-4">
                    <div class="flex items-start gap-4">
                        <img src="{{ $formation->image_url ?: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=100&h=100&fit=crop' }}" 
                            alt="{{ $formation->titre }}" 
                            class="w-20 h-20 rounded-lg object-cover">
                        <div class="flex-1">
                            <h3 class="font-semibold text-foreground">{{ $formation->titre }}</h3>
                            <p class="text-sm text-muted-foreground">{{ $formation->duree }} • {{ $formation->modules->count() }} modules</p>
                            <p class="text-lg font-bold text-primary mt-2">
                                @if($formation->isFree())
                                    Gratuit
                                @else
                                    {{ number_format($formation->prix, 0, ',', ' ') }} FCFA
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                @if(!$formation->isFree())
                <!-- Choix du moyen de paiement -->
                <div>
                    <label class="block text-sm font-medium text-foreground mb-3">Choisissez votre moyen de paiement</label>
                    <div class="grid grid-cols-2 gap-4">
                        <!-- KKiaPay -->
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
                        
                        <!-- FedaPay -->
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
                    
                    <!-- Affichage du moyen sélectionné -->
                    <div class="mt-3 p-3 bg-muted/50 rounded-lg">
                        <p class="text-sm text-center">
                            <span class="text-muted-foreground">Moyen de paiement :</span>
                            <span class="font-semibold text-foreground ml-1">
                                @if($paymentProvider === 'kkiapay')
                                    KKiaPay
                                @elseif($paymentProvider === 'fedapay')
                                    FedaPay
                                @else
                                    Non sélectionné
                                @endif
                            </span>
                        </p>
                    </div>
                </div>

                <!-- Numéro de téléphone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-foreground mb-2">Numéro de téléphone (Mobile Money)</label>
                    <input type="tel" id="phone" wire:model="phone" placeholder="+229 XX XX XX XX"
                        class="w-full px-4 py-3 rounded-lg border border-input bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    <p class="text-xs text-muted-foreground mt-1">Optionnel - Pour les paiements Mobile Money</p>
                </div>
                @endif

                <!-- Bouton de paiement -->
                <button wire:click="@if($formation->isFree()) enrollFree @else initiatePayment @endif" wire:loading.attr="disabled" wire:target="initiatePayment, enrollFree"
                    class="w-full bg-primary text-primary-foreground font-semibold py-4 rounded-lg hover:bg-primary/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                    <span wire:loading.remove wire:target="initiatePayment, enrollFree">
                        @if($formation->isFree())
                            S'inscrire gratuitement
                        @else
                            <span class="flex items-center gap-2">
                                @if($paymentProvider === 'kkiapay')
                                    <span class="w-5 h-5 bg-blue-100 rounded-full flex items-center justify-center text-xs font-bold text-blue-600">K</span>
                                @else
                                    <span class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center text-xs font-bold text-green-600">F</span>
                                @endif
                                Payer {{ number_format($formation->prix, 0, ',', ' ') }} FCFA via {{ $paymentProvider === 'kkiapay' ? 'KKiaPay' : 'FedaPay' }}
                            </span>
                        @endif
                    </span>
                    <span wire:loading wire:target="initiatePayment, enrollFree" class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Traitement en cours...
                    </span>
                </button>

                <!-- Sécurité -->
                <div class="flex items-center justify-center gap-2 text-sm text-muted-foreground">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    <span>Paiement sécurisé</span>
                </div>
            </div>
        </div>
    </div>
    @endif
</main>

@push('scripts')
<!-- Alpine.js Collapse Plugin -->
<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>

<!-- KKiaPay SDK -->
<script src="https://cdn.kkiapay.me/k.js"></script>

<!-- FedaPay SDK -->
<script src="https://cdn.fedapay.com/checkout.js?v=1.1.7"></script>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('openPaymentWidget', (data) => {
            const paymentData = data[0];
            
            if (paymentData.provider === 'kkiapay') {
                initKkiapayPayment(paymentData);
            } else if (paymentData.provider === 'fedapay') {
                initFedapayPayment(paymentData);
            }
        });
    });

    function initKkiapayPayment(data) {
        try {
            // Vérifier que le SDK KKiaPay est chargé
            if (typeof openKkiapayWidget === 'undefined') {
                console.error('SDK KKiaPay non chargé');
                alert('Le service de paiement KKiaPay n\'est pas disponible. Veuillez rafraîchir la page.');
                return;
            }
            
            openKkiapayWidget({
                amount: data.amount,
                position: "center",
                callback: "",
                data: data.reference,
                theme: "#0A2E8C",
                key: "{{ config('services.kkiapay.public_key') }}",
                sandbox: {{ config('services.kkiapay.sandbox') ? 'true' : 'false' }},
            });

            addSuccessListener(response => {
                Livewire.dispatch('paymentSuccess', [{
                    transactionId: response.transactionId,
                    reference: data.reference,
                    status: 'SUCCESS'
                }]);
            });
        } catch (error) {
            console.error('Erreur KKiaPay:', error);
            alert('Erreur lors de l\'ouverture du widget de paiement KKiaPay. Veuillez réessayer.');
        }
    }

    function initFedapayPayment(data) {
        try {
            // Vérifier que le SDK FedaPay est chargé
            if (typeof FedaPay === 'undefined') {
                console.error('SDK FedaPay non chargé');
                alert('Le service de paiement FedaPay n\'est pas disponible. Veuillez rafraîchir la page.');
                return;
            }
            
            // S'assurer que le montant est un entier
            const amount = parseInt(data.amount, 10);
            
            if (isNaN(amount) || amount <= 0) {
                alert('Montant invalide. Veuillez réessayer.');
                return;
            }
            
            FedaPay.init({
                public_key: "{{ config('services.fedapay.public_key') }}",
                transaction: {
                    amount: amount,
                    description: 'Formation: ' + data.formation + ' - REF:' + data.reference
                },
                customer: {
                    email: data.email,
                    firstname: data.name
                },
                onComplete: function(response) {
                    if (response.reason === 'SUCCESSFUL') {
                        Livewire.dispatch('paymentSuccess', [{
                            transactionId: response.id,
                            reference: data.reference,
                            status: 'approved'
                        }]);
                    }
                }
            }).open();
        } catch (error) {
            console.error('Erreur FedaPay:', error);
            alert('Erreur lors de l\'ouverture du widget de paiement FedaPay. Veuillez réessayer.');
        }
    }
</script>
@endpush
