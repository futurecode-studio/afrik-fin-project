<div class="bg-[#f9f9ff] text-[#131c2a] min-h-screen">

    <section class="bg-[#001a61] text-white">
        <div class="max-w-[1280px] mx-auto px-5 lg:px-16 py-16">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold tracking-widest uppercase text-[#ffbf00] mb-3">Catalogue</p>
                    <h1 class="text-3xl md:text-5xl font-extrabold">Excellence académique &amp; financière</h1>
                    <p class="mt-4 text-white/80 max-w-2xl">Formations e-learning conçues pour maîtriser les marchés UEMOA.</p>
                </div>
                <a href="{{ route('panier') }}" class="inline-flex items-center gap-2 border border-white/30 px-4 py-2 rounded font-bold hover:bg-white/10">
                    <span class="material-symbols-outlined">shopping_cart</span> Panier
                </a>
            </div>
        </div>
    </section>

    <section class="max-w-[1280px] mx-auto px-5 lg:px-16 py-8">
        <div class="bg-white border border-[#c5c5d4] rounded-xl p-4 flex flex-col md:flex-row gap-3">
            <input type="search" wire:model.live.debounce.300ms="search" placeholder="Rechercher une formation…"
                class="flex-1 rounded-lg border-[#c5c5d4] focus:border-[#001a61] focus:ring-[#001a61]">
            <select wire:model.live="filterNiveau" class="rounded-lg border-[#c5c5d4]">
                <option value="">Tous les niveaux</option>
                <option value="debutant">Débutant</option>
                <option value="intermediaire">Intermédiaire</option>
                <option value="avance">Avancé</option>
            </select>
            <select wire:model.live="filterType" class="rounded-lg border-[#c5c5d4]">
                <option value="">Toutes</option>
                <option value="gratuit">Gratuites</option>
                <option value="payant">Payantes</option>
            </select>
        </div>
    </section>

    <section class="max-w-[1280px] mx-auto px-5 lg:px-16 pb-20">
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($formations as $formation)
                <article class="bg-white border border-[#c5c5d4] rounded-xl overflow-hidden flex flex-col hover:border-[#001a61] transition">
                    <a href="{{ route('formation-detail', $formation->slug) }}" class="block h-44 bg-[#e7eeff]">
                        @if ($formation->image_url)
                            <img src="{{ $formation->image_url }}" alt="" class="w-full h-full object-cover">
                        @endif
                    </a>
                    <div class="p-5 flex flex-col flex-1">
                        <div class="flex items-center gap-2 text-xs">
                            <span class="font-bold text-[#0a2e8c] uppercase">{{ $formation->niveau }}</span>
                            @if ($formation->isFree())
                                <span class="px-2 py-0.5 rounded bg-green-100 text-green-800 font-semibold">Gratuit</span>
                            @endif
                        </div>
                        <h2 class="font-bold text-lg text-[#001a61] mt-2">
                            <a href="{{ route('formation-detail', $formation->slug) }}">{{ $formation->titre }}</a>
                        </h2>
                        <p class="text-sm text-[#444652] mt-2 line-clamp-3 flex-1">{{ $formation->description_courte }}</p>
                        <div class="mt-4 flex items-center justify-between gap-2">
                            <p class="font-extrabold text-[#001a61]">
                                @if ($formation->isFree()) Gratuit
                                @else {{ number_format($formation->prix, 0, ',', ' ') }} FCFA @endif
                            </p>
                            <div class="flex gap-2">
                                <button type="button" wire:click="addToCart({{ $formation->id }})"
                                    class="text-sm font-bold border border-[#001a61] text-[#001a61] px-3 py-2 rounded hover:bg-[#e7eeff]" title="Ajouter au panier">
                                    <span class="material-symbols-outlined text-base align-middle">add_shopping_cart</span>
                                </button>
                                <button type="button" wire:click="openPaymentModal({{ $formation->id }})"
                                    class="text-sm font-bold bg-[#001a61] text-white px-3 py-2 rounded hover:bg-[#0a2e8c]">
                                    S’inscrire
                                </button>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <p class="col-span-full text-center py-16 text-[#757683]">Aucune formation disponible.</p>
            @endforelse
        </div>
    </section>
</div>

@if($showPaymentModal && $selectedFormation)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closePaymentModal"></div>

        <!-- Modal -->
        <div class="relative adf-modal-panel bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all w-full max-w-lg border border-border">
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
