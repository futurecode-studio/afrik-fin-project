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
    <section class="bg-gradient-hero text-primary-foreground py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Formations <span class="text-secondary">E-Learning</span></h1>
                <p class="text-lg text-primary-foreground/90 mb-6">Développez vos compétences financières avec nos formations certifiées, conçues par des experts du secteur</p>
                <div class="flex flex-wrap gap-6 pt-4">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-award w-5 h-5 text-secondary">
                            <path d="m15.477 12.89 1.515 8.526a.5.5 0 0 1-.81.47l-3.58-2.687a1 1 0 0 0-1.197 0l-3.586 2.686a.5.5 0 0 1-.81-.469l1.514-8.526"></path>
                            <circle cx="12" cy="8" r="6"></circle>
                        </svg>
                        <span>Certifications reconnues</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users w-5 h-5 text-secondary">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <span>Plus de 500 étudiants</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-book-open w-5 h-5 text-secondary">
                            <path d="M12 7v14"></path>
                            <path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"></path>
                        </svg>
                        <span>Accès à vie</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Filtres -->
    <section class="py-8 border-b border-border">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap gap-4 items-center">
                <!-- Recherche -->
                <div class="flex-1 min-w-[200px]">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Rechercher une formation..." 
                        class="w-full px-4 py-2 rounded-lg border border-border bg-background focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
                
                <!-- Filtre par niveau -->
                <select wire:model.live="filterNiveau" class="px-4 py-2 rounded-lg border border-border bg-background focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="">Tous les niveaux</option>
                    <option value="debutant">Débutant</option>
                    <option value="intermediaire">Intermédiaire</option>
                    <option value="avance">Avancé</option>
                </select>
                
                <!-- Filtre par type -->
                <select wire:model.live="filterType" class="px-4 py-2 rounded-lg border border-border bg-background focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="">Toutes les formations</option>
                    <option value="gratuit">Gratuites</option>
                    <option value="payant">Payantes</option>
                </select>
            </div>
        </div>
    </section>

    <!-- Liste des formations -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            @if($formations->isEmpty())
                <div class="text-center py-12">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-4 text-muted-foreground">
                        <path d="M12 7v14"></path>
                        <path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"></path>
                    </svg>
                    <h3 class="text-xl font-semibold mb-2">Aucune formation disponible</h3>
                    <p class="text-muted-foreground">Revenez bientôt pour découvrir nos nouvelles formations.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($formations as $formation)
                        <div class="rounded-lg border bg-card text-card-foreground shadow-sm overflow-hidden border-border hover:border-primary/30 hover:shadow-elegant transition-smooth group">
                            <a href="{{ route('formation-detail', $formation->slug) }}">
                                <div class="relative h-48 overflow-hidden">
                                    <img src="{{ $formation->image_url ?: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&h=400&fit=crop' }}"
                                        alt="{{ $formation->titre }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-smooth duration-500">
                                    <div class="absolute top-4 left-4 flex gap-2">
                                        <span class="px-3 py-1 bg-secondary text-secondary-foreground text-xs font-semibold rounded-full shadow-glow">
                                            {{ ucfirst($formation->niveau) }}
                                        </span>
                                        @if($formation->isFree())
                                            <span class="px-3 py-1 bg-green-500 text-white text-xs font-semibold rounded-full">
                                                Gratuit
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                            <div class="p-6 space-y-4">
                                <a href="{{ route('formation-detail', $formation->slug) }}">
                                    <h3 class="text-xl font-bold group-hover:text-primary transition-smooth line-clamp-2">
                                        {{ $formation->titre }}
                                    </h3>
                                </a>
                                <p class="text-muted-foreground leading-relaxed line-clamp-2">
                                    {{ strip_tags($formation->description_courte) }}
                                </p>
                                <div class="grid grid-cols-3 gap-4 py-4 border-y border-border">
                                    <div class="text-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock w-5 h-5 mx-auto mb-1 text-primary">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12 6 12 12 16 14"></polyline>
                                        </svg>
                                        <p class="text-sm font-medium">{{ $formation->duree }}</p>
                                    </div>
                                    <div class="text-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users w-5 h-5 mx-auto mb-1 text-primary">
                                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="9" cy="7" r="4"></circle>
                                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                        </svg>
                                        <p class="text-sm font-medium">{{ $formation->students_count }} étudiants</p>
                                    </div>
                                    <div class="text-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-book-open w-5 h-5 mx-auto mb-1 text-primary">
                                            <path d="M12 7v14"></path>
                                            <path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"></path>
                                        </svg>
                                        <p class="text-sm font-medium">{{ $formation->modules_count }} modules</p>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between pt-2">
                                    <div>
                                        <p class="text-sm text-muted-foreground">Prix</p>
                                        @if($formation->isFree())
                                            <p class="text-2xl font-bold text-green-500">Gratuit</p>
                                        @else
                                            <p class="text-2xl font-bold text-primary">{{ number_format($formation->prix, 0, ',', ' ') }} FCFA</p>
                                        @endif
                                    </div>
                                    @auth
                                        @if(auth()->user()->isEnrolledIn($formation))
                                            <a href="{{ route('formation-detail', $formation->slug) }}" 
                                                class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium bg-green-500 text-white hover:bg-green-600 transition-smooth h-12 rounded-lg px-6 text-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="20 6 9 17 4 12"></polyline>
                                                </svg>
                                                Accéder
                                            </a>
                                        @else
                                            <button wire:click="openPaymentModal({{ $formation->id }})"
                                                class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary-light shadow-elegant hover:shadow-glow transition-smooth h-12 rounded-lg px-6 text-sm">
                                                S'inscrire
                                            </button>
                                        @endif
                                    @else
                                        <button wire:click="openPaymentModal({{ $formation->id }})"
                                            class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary-light shadow-elegant hover:shadow-glow transition-smooth h-12 rounded-lg px-6 text-sm">
                                            S'inscrire
                                        </button>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-muted/30">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-4">Prêt à commencer votre <span class="text-primary">formation</span> ?</h2>
            <p class="text-lg text-muted-foreground mb-8 max-w-2xl mx-auto">Rejoignez des centaines d'étudiants qui ont transformé leur carrière grâce à nos formations</p>
        </div>
    </section>

    <!-- Modale de paiement -->
    @if($showPaymentModal && $selectedFormation)
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
                        <p class="text-primary-foreground/80 text-sm">{{ $selectedFormation->titre }}</p>
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
                        <img src="{{ $selectedFormation->image_url ?: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=100&h=100&fit=crop' }}" 
                            alt="{{ $selectedFormation->titre }}" 
                            class="w-20 h-20 rounded-lg object-cover">
                        <div class="flex-1">
                            <h3 class="font-semibold text-foreground">{{ $selectedFormation->titre }}</h3>
                            <p class="text-sm text-muted-foreground">{{ $selectedFormation->duree }} • {{ $selectedFormation->modules_count }} modules</p>
                            <p class="text-lg font-bold text-primary mt-2">
                                {{ number_format($selectedFormation->prix, 0, ',', ' ') }} FCFA
                            </p>
                        </div>
                    </div>
                </div>

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

                <!-- Numéro de téléphone (optionnel) -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-foreground mb-2">Numéro de téléphone (Mobile Money)</label>
                    <input type="tel" id="phone" wire:model="phone" placeholder="+229 XX XX XX XX"
                        class="w-full px-4 py-3 rounded-lg border border-input bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    <p class="text-xs text-muted-foreground mt-1">Optionnel - Pour les paiements Mobile Money</p>
                </div>

                <!-- Bouton de paiement -->
                <button wire:click="initiatePayment" wire:loading.attr="disabled" wire:target="initiatePayment"
                    class="w-full bg-primary text-primary-foreground font-semibold py-4 rounded-lg hover:bg-primary/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                    <span wire:loading.remove wire:target="initiatePayment">
                        <span class="flex items-center gap-2">
                            @if($paymentProvider === 'kkiapay')
                                <span class="w-5 h-5 bg-blue-100 rounded-full flex items-center justify-center text-xs font-bold text-blue-600">K</span>
                            @else
                                <span class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center text-xs font-bold text-green-600">F</span>
                            @endif
                            Payer {{ number_format($selectedFormation->prix, 0, ',', ' ') }} FCFA via {{ $paymentProvider === 'kkiapay' ? 'KKiaPay' : 'FedaPay' }}
                        </span>
                    </span>
                    <span wire:loading wire:target="initiatePayment" class="flex items-center gap-2">
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
                theme: "#1e40af",
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
