<main class="flex-1 pt-20">
    <section class="bg-gradient-hero text-primary-foreground py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Valeurs Liquidatives <span class="text-secondary">(VL / FCP)</span></h1>
                <p class="text-lg text-primary-foreground/90">Suivez en temps réel l'évolution quotidienne des Fonds Communs de Placement de la zone UEMOA et d'Afrique</p>
            </div>
        </div>
    </section>

    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="bg-card rounded-xl border border-border shadow-sm overflow-hidden">
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
        </div>
    </section>
</main>
