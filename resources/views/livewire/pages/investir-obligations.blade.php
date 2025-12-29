<main class="flex-1 pt-20">
    <section class="relative bg-gradient-hero text-primary-foreground py-20 overflow-hidden">
        <!-- Image de fond -->
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?q=80&w=2070&auto=format&fit=crop" 
                 alt="Obligations d'États" 
                 class="w-full h-full object-cover opacity-20">
            <div class="absolute inset-0 bg-gradient-to-r from-primary/90 via-primary/80 to-secondary/90"></div>
        </div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-secondary/20 backdrop-blur-sm rounded-full mb-4 border border-secondary/30">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-secondary">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                    </svg>
                    <span class="text-sm font-medium text-secondary">Investissement</span>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Investir sur les <span class="text-secondary">Obligations d'États</span></h1>
                <p class="text-lg text-primary-foreground/90">Sécurisez votre patrimoine avec les obligations souveraines de la zone UEMOA</p>
            </div>
        </div>
    </section>

    <!-- Section des obligations disponibles -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">Obligations <span class="text-primary">Disponibles</span></h2>
                    <p class="text-lg text-muted-foreground">Découvrez notre sélection d'obligations d'États de la zone UEMOA</p>
                </div>

                @if(count($bonds) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                        @foreach($bonds as $bond)
                            <div class="bg-card rounded-xl border border-border shadow-sm hover:shadow-elegant transition-smooth overflow-hidden">
                                <div class="p-6">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex-1">
                                            <h3 class="text-xl font-bold text-foreground mb-1">{{ $bond->name }}</h3>
                                            <p class="text-sm text-muted-foreground">{{ $bond->issuer }} • {{ $bond->country }}</p>
                                        </div>
                                        @if($bond->rating)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-primary/10 text-primary">
                                                {{ $bond->rating }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div class="bg-muted/50 rounded-lg p-3">
                                            <p class="text-xs text-muted-foreground mb-1">Taux d'intérêt</p>
                                            <p class="text-lg font-bold text-primary">{{ number_format($bond->interest_rate, 2) }}%</p>
                                            <p class="text-xs text-muted-foreground">{{ $bond->interest_type_label }}</p>
                                        </div>
                                        <div class="bg-muted/50 rounded-lg p-3">
                                            <p class="text-xs text-muted-foreground mb-1">Maturité</p>
                                            <p class="text-lg font-bold text-foreground">{{ $bond->maturity_years }} ans</p>
                                            <p class="text-xs text-muted-foreground">{{ $bond->maturity_date->format('d/m/Y') }}</p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <p class="text-xs text-muted-foreground mb-1">Valeur nominale</p>
                                            <p class="text-sm font-semibold text-foreground">{{ number_format($bond->nominal_value, 0, ',', ' ') }} {{ $bond->currency }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-muted-foreground mb-1">Paiement</p>
                                            <p class="text-sm font-semibold text-foreground">{{ $bond->payment_frequency_label }}</p>
                                        </div>
                                    </div>

                                    @if($bond->yield_to_maturity)
                                        <div class="mb-4">
                                            <p class="text-xs text-muted-foreground mb-1">Rendement à l'échéance</p>
                                            <p class="text-sm font-semibold text-green-600">{{ number_format($bond->yield_to_maturity, 2) }}%</p>
                                        </div>
                                    @endif

                                    <div class="flex items-center justify-between pt-4 border-t border-border">
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $bond->risk_level === 'low' ? 'bg-green-100 text-green-800' : ($bond->risk_level === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                Risque: {{ $bond->risk_level_label }}
                                            </span>
                                        </div>
                                        @if($bond->remaining_years > 0)
                                            <span class="text-xs text-muted-foreground">
                                                {{ $bond->remaining_years }} ans restants
                                            </span>
                                        @endif
                                    </div>

                                    @if($bond->description)
                                        <div class="mt-4 pt-4 border-t border-border">
                                            <p class="text-sm text-muted-foreground">{{ Str::limit($bond->description, 150) }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 bg-muted/30 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-4 text-muted-foreground opacity-50">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                        </svg>
                        <p class="text-muted-foreground">Aucune obligation disponible pour le moment</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="py-16 bg-muted/30">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">Pourquoi investir dans les <span class="text-primary">Obligations</span> ?</h2>
                    <p class="text-lg text-muted-foreground">Un placement sûr et rentable pour diversifier votre portefeuille</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                    <div class="bg-card rounded-xl p-6 border border-border shadow-sm hover:shadow-elegant transition-smooth">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600">
                                <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path>
                                <path d="m9 12 2 2 4-4"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Sécurité Garantie</h3>
                        <p class="text-muted-foreground">Obligations émises par les États de l'UEMOA avec garantie souveraine</p>
                    </div>

                    <div class="bg-card rounded-xl p-6 border border-border shadow-sm hover:shadow-elegant transition-smooth">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600">
                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Rendements Attractifs</h3>
                        <p class="text-muted-foreground">Taux d'intérêt compétitifs de 5% à 7% selon la maturité</p>
                    </div>

                    <div class="bg-card rounded-xl p-6 border border-border shadow-sm hover:shadow-elegant transition-smooth">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-purple-600">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Revenus Réguliers</h3>
                        <p class="text-muted-foreground">Coupons versés semestriellement ou annuellement</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-12">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-primary/10 backdrop-blur-sm rounded-full mb-4 border border-primary/20">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <span class="text-sm font-medium text-primary">Demande de Rendez-vous</span>
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">Prenez <span class="text-primary">Rendez-vous</span></h2>
                    <p class="text-lg text-muted-foreground">Nos experts vous accompagnent dans votre stratégie d'investissement obligataire</p>
                </div>

                @if (session()->has('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                            <p class="font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                <div class="bg-card rounded-2xl border border-border shadow-elegant p-8">
                    <form wire:submit.prevent="submit" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-foreground mb-2">Nom complet *</label>
                                <input type="text" id="name" wire:model="name" required
                                    class="w-full px-4 py-3 rounded-lg border border-border bg-background text-foreground focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                                @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-foreground mb-2">Email *</label>
                                <input type="email" id="email" wire:model="email" required
                                    class="w-full px-4 py-3 rounded-lg border border-border bg-background text-foreground focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                                @error('email') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-foreground mb-2">Téléphone *</label>
                                <input type="tel" id="phone" wire:model="phone" required
                                    class="w-full px-4 py-3 rounded-lg border border-border bg-background text-foreground focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                                @error('phone') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="company" class="block text-sm font-medium text-foreground mb-2">Entreprise (optionnel)</label>
                                <input type="text" id="company" wire:model="company"
                                    class="w-full px-4 py-3 rounded-lg border border-border bg-background text-foreground focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                                @error('company') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-foreground mb-2">Message (optionnel)</label>
                            <textarea id="message" wire:model="message" rows="4"
                                class="w-full px-4 py-3 rounded-lg border border-border bg-background text-foreground focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                placeholder="Décrivez vos objectifs d'investissement, vos questions ou toute information pertinente..."></textarea>
                            @error('message') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex justify-center pt-4">
                            <button type="submit" wire:loading.attr="disabled"
                                class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-primary text-primary-foreground rounded-xl font-semibold shadow-glow hover:shadow-xl transition-all duration-300 hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="17 8 12 3 7 8"></polyline>
                                    <line x1="12" y1="3" x2="12" y2="15"></line>
                                </svg>
                                <span wire:loading.remove>Envoyer ma demande</span>
                                <span wire:loading>Envoi en cours...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>
