<main class="flex-1 pt-20">
    <section class="relative bg-gradient-hero text-primary-foreground py-20 overflow-hidden">
        <!-- Image de fond -->
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=2015&auto=format&fit=crop" 
                 alt="Fonds Communs de Placement" 
                 class="w-full h-full object-cover opacity-20">
            <div class="absolute inset-0 bg-gradient-to-r from-primary/90 via-primary/80 to-secondary/90"></div>
        </div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-secondary/20 backdrop-blur-sm rounded-full mb-4 border border-secondary/30">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-secondary">
                        <path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path>
                        <path d="M22 12A10 10 0 0 0 12 2v10z"></path>
                    </svg>
                    <span class="text-sm font-medium text-secondary">Investissement</span>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Investir sur les <span class="text-secondary">Fonds Communs de Placement</span></h1>
                <p class="text-lg text-primary-foreground/90">Diversifiez votre portefeuille avec les meilleurs FCP de la zone UEMOA</p>
            </div>
        </div>
    </section>

    <!-- Section des données FCP -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <!-- Informations complémentaires -->
            <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-card p-6 rounded-xl border border-border shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trending-up w-5 h-5 text-primary">
                            <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                            <polyline points="16 7 22 7 22 13"></polyline>
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Données en Temps Réel</h3>
                    <p class="text-sm text-muted-foreground">Les valeurs liquidatives sont mises à jour quotidiennement pour vous offrir les données les plus actuelles.</p>
                </div>
                <div class="bg-card p-6 rounded-xl border border-border shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-10 h-10 bg-secondary/10 rounded-lg flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pie-chart w-5 h-5 text-secondary">
                            <path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path>
                            <path d="M22 12A10 10 0 0 0 12 2v10z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Diversification</h3>
                    <p class="text-sm text-muted-foreground">Accédez à une large gamme de fonds (Actions, Obligations, Mixtes, Monétaires) pour diversifier votre portefeuille.</p>
                </div>
                <div class="bg-card p-6 rounded-xl border border-border shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-10 h-10 bg-accent/10 rounded-lg flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text w-5 h-5 text-accent">
                            <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" x2="8" y1="13" y2="13"></line>
                            <line x1="16" x2="8" y1="17" y2="17"></line>
                            <line x1="10" x2="8" y1="9" y2="9"></line>
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Informations Détaillées</h3>
                    <p class="text-sm text-muted-foreground">Consultez les notices d'information et les rapports de gestion des différents fonds pour prendre les meilleures décisions.</p>
                </div>
            </div>

            <!-- Note sur les données -->
            <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-lg text-blue-800 text-sm">
                <p class="font-semibold mb-2">📊 Note sur les données</p>
                <p>Les données affichées proviennent de sources fiables actualisées quotidiennement. Les variations sont calculées par rapport à la veille. Pour les investissements importants, consultez votre conseiller financier.</p>
            </div>

            
            <div class="bg-card rounded-xl border border-border shadow-sm overflow-hidden mt-8">
                <div class="p-6 border-b border-border">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-6">
                        <div>
                            <h2 class="text-xl font-bold">Cotations en Temps Réel</h2>
                            <p class="text-sm text-muted-foreground mt-1">Données actualisées quotidiennement</p>
                            @if($lastUpdated)
                                <p class="text-xs text-muted-foreground mt-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock inline mr-1">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                    Dernière mise à jour: {{ $lastUpdated }}
                                </p>
                            @endif
                        </div>
                        <button 
                            wire:click="refreshFunds" 
                            wire:loading.attr="disabled"
                            class="px-4 py-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary/90 transition-colors flex items-center gap-2 disabled:opacity-50 whitespace-nowrap"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" wire:loading.class="animate-spin" class="lucide lucide-refresh-cw">
                                <path d="M23 4v6h-6"></path>
                                <path d="M1 20v-6h6"></path>
                                <path d="M3.51 9a9 9 0 0 1 14.85-3.36M20.49 15a9 9 0 0 1-14.85 3.36"></path>
                            </svg>
                            <span wire:loading.remove>Actualiser</span>
                            <span wire:loading>Chargement...</span>
                        </button>
                    </div>

                    <!-- Filtres par catégorie -->
                    <div class="flex flex-wrap gap-2">
                        <button 
                            wire:click="filterByCategory('Tous')"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ $selectedCategory === 'Tous' ? 'bg-primary text-primary-foreground' : 'bg-muted text-muted-foreground hover:bg-muted/80' }}"
                        >
                            Tous les fonds
                        </button>
                        @foreach($categories as $category)
                            <button 
                                wire:click="filterByCategory('{{ $category }}')"
                                class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ $selectedCategory === $category ? 'bg-primary text-primary-foreground' : 'bg-muted text-muted-foreground hover:bg-muted/80' }}"
                            >
                                {{ $category }}
                            </button>
                        @endforeach
                    </div>
                </div>

                @if($error)
                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded text-yellow-800 m-4">
                        <p class="text-sm">⚠️ {{ $error }}</p>
                    </div>
                @endif

                <div class="overflow-x-auto">
                    @if($isLoading)
                        <div class="p-8 text-center">
                            <div class="inline-block">
                                <svg class="animate-spin h-8 w-8 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                            <p class="mt-4 text-muted-foreground">Chargement des données en temps réel...</p>
                        </div>
                    @elseif(count($mutualFunds) > 0)
                        <table class="w-full">
                            <thead class="bg-muted/50">
                                <tr> 
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Fonds</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Société de Gestion</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-muted-foreground uppercase tracking-wider">Catégorie</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-muted-foreground uppercase tracking-wider">VL</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-muted-foreground uppercase tracking-wider">Variation</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-muted-foreground uppercase tracking-wider">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border">
                                @foreach($mutualFunds as $fund)
                                    <tr class="hover:bg-muted/30 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div>
                                                <p class="font-semibold text-foreground">{{ $fund['name'] }}</p>
                                                <p class="text-xs text-muted-foreground">ID: {{ $fund['id'] }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-muted-foreground text-sm">
                                            {{ $fund['company'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="inline-block px-3 py-1 bg-secondary/20 text-secondary text-xs font-medium rounded-full">
                                                {{ $fund['category'] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <p class="font-bold text-foreground">{{ $fund['nav_value'] }}</p>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right font-medium {{ $fund['variation_percentage'] >= 0 ? 'text-green-600' : 'text-red-500' }}">
                                            <div class="flex items-center justify-end gap-1">
                                                @if($fund['variation_percentage'] >= 0)
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trend-up">
                                                        <polyline points="23 6 13.5 15.5 8.5 10.5 2 17"></polyline>
                                                        <polyline points="17 6 23 6 23 12"></polyline>
                                                    </svg>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trend-down">
                                                        <polyline points="23 18 13.5 8.5 8.5 13.5 2 7"></polyline>
                                                        <polyline points="17 18 23 18 23 12"></polyline>
                                                    </svg>
                                                @endif
                                                <span>{{ $fund['variation'] }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-muted-foreground text-sm">
                                            {{ $fund['date'] }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="p-8 text-center text-muted-foreground">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-inbox mx-auto mb-4 opacity-50">
                                <polyline points="22 12 18 12 15 21 9 21 6 12 2 12"></polyline>
                                <path d="M9 11V7a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4"></path>
                            </svg>
                            <p>Aucune donnée disponible pour le moment</p>
                        </div>
                    @endif
                </div>
            </div>

            
        </div>
    </section>

    <!-- Section Pourquoi investir -->
    <section class="py-16 bg-muted/30">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">Pourquoi investir dans les <span class="text-primary">FCP</span> ?</h2>
                    <p class="text-lg text-muted-foreground">Une gestion professionnelle pour optimiser votre rendement</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                    <div class="bg-card rounded-xl p-6 border border-border shadow-sm hover:shadow-elegant transition-smooth">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-purple-600">
                                <path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path>
                                <path d="M22 12A10 10 0 0 0 12 2v10z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Diversification</h3>
                        <p class="text-muted-foreground">Accès à un portefeuille diversifié géré par des experts</p>
                    </div>

                    <div class="bg-card rounded-xl p-6 border border-border shadow-sm hover:shadow-elegant transition-smooth">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Gestion Professionnelle</h3>
                        <p class="text-muted-foreground">Sociétés de gestion agréées et expérimentées</p>
                    </div>

                    <div class="bg-card rounded-xl p-6 border border-border shadow-sm hover:shadow-elegant transition-smooth">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600">
                                <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                <polyline points="16 7 22 7 22 13"></polyline>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Liquidité</h3>
                        <p class="text-muted-foreground">Rachat de parts possible à tout moment</p>
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
                    <p class="text-lg text-muted-foreground">Nos experts vous accompagnent dans le choix du FCP adapté à votre profil</p>
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
