@php
    // Libellés humains des sources de données utilisées sur toute la page
    $sourceLabels = [
        'richbourse' => ['label' => 'RichBourse.com', 'url' => 'https://www.richbourse.com', 'badge' => 'bg-emerald-50 text-emerald-800 border-emerald-200'],
        'brvm' => ['label' => 'BRVM.org', 'url' => 'https://www.brvm.org', 'badge' => 'bg-emerald-50 text-emerald-800 border-emerald-200'],
        'database' => ['label' => 'Base locale', 'url' => null, 'badge' => 'bg-blue-50 text-blue-800 border-blue-200'],
        'default' => ['label' => 'Données de démonstration', 'url' => null, 'badge' => 'bg-amber-50 text-amber-800 border-amber-200'],
    ];
    $currentSource = $sourceLabels[$dataSource] ?? ['label' => 'Source inconnue', 'url' => null, 'badge' => 'bg-gray-50 text-gray-800 border-gray-200'];
@endphp

<main class="flex-1 pt-20">
    <!-- Modal Détails Stock (ESC ferme + scroll-lock du body) -->
    @if($showModal && $selectedStock)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-data="{ open: true }"
         x-init="document.body.style.overflow = 'hidden'"
         @keydown.escape.window="$wire.closeModal()"
         x-effect="if (!open) document.body.style.overflow = ''"
         @close-modal.window="document.body.style.overflow = ''"
         aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Overlay -->
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" wire:click="closeModal"></div>

        <!-- Modal -->
        <div class="relative adf-modal-panel bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all w-full max-w-lg border border-border">
                <!-- Header -->
                <div class="bg-primary px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-white" id="modal-title">
                                {{ $selectedStock['symbol'] }}
                            </h3>
                            <p class="text-primary-foreground/80 text-sm">{{ $selectedStock['company_name'] }}</p>
                        </div>
                        <button wire:click="closeModal" class="text-white hover:text-gray-200 transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Body -->
                <div class="px-6 py-5 bg-card">
                    <!-- Prix et Variation -->
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <p class="text-sm text-muted-foreground">Cours actuel</p>
                            <p class="text-3xl font-bold text-foreground">
                                {{ number_format($selectedStock['current_price'] ?? 0, 0, ',', ' ') }} <span class="text-lg">FCFA</span>
                            </p>
                        </div>
                        <div class="text-right">
                            @php
                                $variation = $selectedStock['variation_percent'] ?? 0;
                            @endphp
                            <div class="inline-flex items-center gap-2 px-3 py-2 rounded-lg {{ $variation >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                @if($variation >= 0)
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                    <span class="text-lg font-bold">+{{ number_format($variation, 2) }}%</span>
                                @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"/>
                                    </svg>
                                    <span class="text-lg font-bold">{{ number_format($variation, 2) }}%</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Informations détaillées -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-muted rounded-lg p-4">
                            <p class="text-sm text-muted-foreground">Cours précédent</p>
                            <p class="text-lg font-semibold text-foreground">
                                {{ number_format($selectedStock['previous_price'] ?? 0, 0, ',', ' ') }} FCFA
                            </p>
                        </div>
                        <div class="bg-muted rounded-lg p-4">
                            <p class="text-sm text-muted-foreground">Volume</p>
                            <p class="text-lg font-semibold text-foreground">
                                {{ number_format($selectedStock['volume'] ?? 0, 0, ',', ' ') }}
                            </p>
                        </div>
                        <div class="bg-muted rounded-lg p-4">
                            <p class="text-sm text-muted-foreground">Secteur</p>
                            <p class="text-lg font-semibold text-foreground">
                                {{ $selectedStock['sector'] ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="bg-muted rounded-lg p-4">
                            <p class="text-sm text-muted-foreground">Capitalisation</p>
                            <p class="text-lg font-semibold text-foreground">
                                @if(isset($selectedStock['market_cap']) && $selectedStock['market_cap'] > 0)
                                    @if($selectedStock['market_cap'] >= 1000)
                                        {{ number_format($selectedStock['market_cap'] / 1000, 2, ',', ' ') }} Mrd FCFA
                                    @else
                                        {{ number_format($selectedStock['market_cap'], 0, ',', ' ') }} M FCFA
                                    @endif
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Pays/Région -->
                    @if(isset($selectedStock['country']))
                    <div class="mt-4 bg-muted rounded-lg p-4">
                        <p class="text-sm text-muted-foreground">Pays / Région</p>
                        <p class="text-lg font-semibold text-foreground">{{ $selectedStock['country'] }}</p>
                    </div>
                    @endif

                    <!-- Source -->
                    <div class="mt-4 text-center text-sm text-muted-foreground">
                        Source: {{ ucfirst($selectedStock['source'] ?? 'N/A') }}
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-muted px-6 py-4 flex justify-end gap-3">
                    <button 
                        wire:click="closeModal"
                        class="px-4 py-2 bg-background border border-border rounded-lg text-foreground hover:bg-muted transition-colors"
                    >
                        Fermer
                    </button>
                    <a 
                        href="https://www.brvm.org/fr/cours-actions/0/status/0" 
                        target="_blank"
                        rel="noopener noreferrer"
                        class="px-4 py-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary/90 transition-colors inline-flex items-center gap-2"
                    >
                        Voir sur BRVM.org
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Messages -->

    @if ($errorMessage)
        <div class="container mx-auto px-4 pt-4">
            <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 border border-red-200">
                {{ $errorMessage }}
            </div>
        </div>
    @endif

    @if($dataSource)
    <div class="container mx-auto px-4 pt-4" x-data="{ show: true }" x-show="show">
        @php
            $sourceMap = [
                'richbourse' => ['classes' => 'bg-green-50 text-green-800 border-green-200', 'icon' => '✅', 'label' => 'Données en temps réel', 'text' => 'Cours BRVM via RichBourse.com (cache 5 min).'],
                'brvm' => ['classes' => 'bg-green-50 text-green-800 border-green-200', 'icon' => '✅', 'label' => 'Données en temps réel', 'text' => 'Cours BRVM via BRVM.org (cache 5 min).'],
                'database' => ['classes' => 'bg-blue-50 text-blue-800 border-blue-200', 'icon' => '📊', 'label' => 'Données locales', 'text' => 'Sources en ligne indisponibles — affichage depuis la base locale.'],
                'default' => ['classes' => 'bg-yellow-50 text-yellow-800 border-yellow-200', 'icon' => '⚠️', 'label' => 'Données de démonstration', 'text' => 'Sources en ligne indisponibles — affichage de valeurs par défaut.'],
            ];
            $info = $sourceMap[$dataSource] ?? ['classes' => 'bg-gray-50 text-gray-800 border-gray-200', 'icon' => 'ℹ️', 'label' => 'Source', 'text' => 'Données BRVM chargées.'];
        @endphp
        <div class="mb-4 rounded-lg p-3 text-sm border {{ $info['classes'] }} flex items-start gap-3">
            <span class="text-base">{{ $info['icon'] }}</span>
            <div class="flex-1">
                <strong>{{ $info['label'] }} :</strong> {{ $info['text'] }}
            </div>
            <button @click="show = false" class="opacity-60 hover:opacity-100 transition" aria-label="Fermer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>
    @endif

    <!-- Hero Section -->
    <section class="relative text-primary-foreground py-20 overflow-hidden" style="background: linear-gradient(135deg, #071F5A 0%, #0A2E8C 60%, #1E4AB8 100%);">
        <!-- Motifs décoratifs -->
        <div class="absolute inset-0 z-0 pointer-events-none opacity-20">
            <div class="absolute top-10 left-10 w-72 h-72 bg-secondary/30 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-primary-light/40 rounded-full blur-3xl"></div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-secondary/20 border border-secondary/40 mb-4">
                    <span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
                    <span class="text-sm font-semibold tracking-wide">Marché UEMOA</span>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold mb-4 leading-tight">Investir sur les <span class="text-secondary">Actions BRVM</span></h1>
                <p class="text-lg text-primary-foreground/90">Suivez les cours, indices et analyses de la Bourse Régionale des Valeurs Mobilières.</p>
            </div>
        </div>
    </section>

    <!-- Indices BRVM -->
    <section class="py-12 bg-muted/30">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
                <div class="flex items-center gap-3 flex-wrap">
                    <h2 class="text-2xl font-bold">Indices BRVM</h2>
                    @if($dataSource)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium border {{ $currentSource['badge'] }}">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                            Source : {{ $currentSource['label'] }}
                        </span>
                    @endif
                </div>
                <button wire:click="refresh" wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary text-primary-foreground hover:bg-primary-light transition-smooth text-sm font-medium">
                    <svg wire:loading.remove xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"></path>
                        <path d="M21 3v5h-5"></path>
                    </svg>
                    <svg wire:loading class="animate-spin" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"></path>
                        <path d="M21 3v5h-5"></path>
                    </svg>
                    <span wire:loading.remove>Actualiser</span>
                    <span wire:loading>Actualisation...</span>
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($indices as $indice)
                <div class="rounded-lg border bg-card text-card-foreground shadow-sm p-6 border-border hover:border-primary/30 hover:shadow-elegant transition-smooth">
                    <div class="space-y-2">
                        <p class="text-sm text-muted-foreground font-medium">{{ $indice['name'] }}</p>
                        <p class="text-3xl font-bold">{{ number_format($indice['value'], 2) }}</p>
                        <div class="flex items-center gap-1 {{ ($indice['variation_percent'] ?? 0) >= 0 ? 'text-accent' : 'text-destructive' }}">
                            @if(($indice['variation_percent'] ?? 0) >= 0)
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                                    <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                    <polyline points="16 7 22 7 22 13"></polyline>
                                </svg>
                                <span class="font-semibold">+{{ number_format($indice['variation_percent'], 2) }}%</span>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                                    <polyline points="22 17 13.5 8.5 8.5 13.5 2 7"></polyline>
                                    <polyline points="16 17 22 17 22 11"></polyline>
                                </svg>
                                <span class="font-semibold">{{ number_format($indice['variation_percent'], 2) }}%</span>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-8 text-muted-foreground">
                    Aucun indice disponible
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Principales Valeurs -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                <div class="flex items-center gap-3 flex-wrap">
                    <h2 class="text-2xl font-bold">Principales Valeurs</h2>
                    @if($dataSource)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium border {{ $currentSource['badge'] }}">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                            Source : {{ $currentSource['label'] }}
                        </span>
                    @endif
                </div>
                <span class="text-sm text-muted-foreground">
                    @if($lastUpdate)
                        Dernière mise à jour : {{ $lastUpdate }}
                    @else
                        Chargement...
                    @endif
                </span>
            </div>

            <!-- Résumé du marché -->
            @if(count($allStocks) > 0)
            @php $summary = $this->marketSummary; @endphp
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-6">
                <div class="rounded-lg border bg-card p-4 border-border">
                    <p class="text-xs text-muted-foreground uppercase tracking-wide font-medium">Valeurs cotées</p>
                    <p class="text-2xl font-bold text-foreground mt-1">{{ number_format($summary['total'], 0, ',', ' ') }}</p>
                </div>
                <div class="rounded-lg border bg-card p-4 border-border">
                    <p class="text-xs text-accent uppercase tracking-wide font-semibold">En hausse</p>
                    <p class="text-2xl font-bold text-accent mt-1 flex items-center gap-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        {{ $summary['up'] }}
                    </p>
                </div>
                <div class="rounded-lg border bg-card p-4 border-border">
                    <p class="text-xs text-destructive uppercase tracking-wide font-semibold">En baisse</p>
                    <p class="text-2xl font-bold text-destructive mt-1 flex items-center gap-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"/></svg>
                        {{ $summary['down'] }}
                    </p>
                </div>
                <div class="rounded-lg border bg-card p-4 border-border">
                    <p class="text-xs text-muted-foreground uppercase tracking-wide font-medium">Volume total</p>
                    <p class="text-2xl font-bold text-foreground mt-1">{{ number_format($summary['total_volume'], 0, ',', ' ') }}</p>
                </div>
            </div>
            @endif

            <!-- Filtres -->
            <div class="rounded-lg border bg-card p-4 mb-6 border-border">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Recherche -->
                    <div class="relative">
                        <label class="block text-sm font-medium text-muted-foreground mb-1">Rechercher</label>
                        <div class="relative">
                            <input 
                                type="text" 
                                wire:model.live.debounce.300ms="searchTerm" 
                                placeholder="Symbole ou nom..."
                                class="w-full pl-10 pr-4 py-2 border border-border rounded-lg bg-background text-foreground focus:ring-2 focus:ring-primary focus:border-transparent"
                            >
                            <svg class="absolute left-3 top-2.5 h-5 w-5 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Filtre par secteur -->
                    <div>
                        <label class="block text-sm font-medium text-muted-foreground mb-1">Secteur</label>
                        <select 
                            wire:model.live="sectorFilter"
                            class="w-full px-4 py-2 border border-border rounded-lg bg-background text-foreground focus:ring-2 focus:ring-primary focus:border-transparent"
                        >
                            <option value="">Tous les secteurs</option>
                            @foreach($this->sectors as $sector)
                                <option value="{{ $sector }}">{{ $sector }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtre par variation -->
                    <div>
                        <label class="block text-sm font-medium text-muted-foreground mb-1">Variation</label>
                        <select 
                            wire:model.live="variationFilter"
                            class="w-full px-4 py-2 border border-border rounded-lg bg-background text-foreground focus:ring-2 focus:ring-primary focus:border-transparent"
                        >
                            <option value="">Toutes</option>
                            <option value="up">📈 Hausse</option>
                            <option value="down">📉 Baisse</option>
                            <option value="stable">➡️ Stable</option>
                        </select>
                    </div>

                    <!-- Bouton réinitialiser -->
                    <div class="flex items-end">
                        <button 
                            wire:click="resetFilters"
                            class="w-full px-4 py-2 border border-border rounded-lg bg-muted hover:bg-muted/80 text-foreground transition-colors flex items-center justify-center gap-2"
                        >
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Réinitialiser
                        </button>
                    </div>
                </div>

                <!-- Résumé des filtres -->
                <div class="mt-4 flex items-center gap-4 text-sm text-muted-foreground">
                    <span>
                        <strong class="text-foreground">{{ count($stocks) }}</strong> valeur(s) affichée(s)
                        @if(count($allStocks) > 0)
                            sur <strong class="text-foreground">{{ count($allStocks) }}</strong>
                        @endif
                    </span>
                    @if($searchTerm || $sectorFilter || $variationFilter)
                        <span class="text-primary">• Filtres actifs</span>
                    @endif
                </div>
            </div>
            
            <!-- Vue mobile : cartes -->
            <div class="md:hidden space-y-3">
                @forelse($stocks as $stock)
                    @php
                        $sector = $stock['sector'] ?? 'Autre';
                        $sectorColor = $this->sectorColors[$sector] ?? 'bg-gray-100 text-gray-800';
                        $variation = $stock['variation_percent'] ?? 0;
                    @endphp
                    <button wire:click="showStockDetails('{{ $stock['symbol'] }}')"
                            class="w-full text-left rounded-lg border bg-card p-4 border-border hover:border-primary/40 hover:shadow-elegant transition-all">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <p class="font-bold text-primary text-lg">{{ $stock['symbol'] ?? 'N/A' }}</p>
                                <p class="text-sm text-muted-foreground line-clamp-1">{{ $stock['company_name'] ?? 'N/A' }}</p>
                            </div>
                            <div class="inline-flex items-center gap-1 px-2 py-1 rounded {{ $variation >= 0 ? 'bg-accent/10 text-accent' : 'bg-destructive/10 text-destructive' }}">
                                <span class="font-semibold text-sm">{{ ($variation >= 0 ? '+' : '') . number_format($variation, 2) }}%</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $sectorColor }}">{{ $sector }}</span>
                            <p class="font-bold text-foreground">{{ number_format($stock['current_price'] ?? 0, 0, ',', ' ') }} <span class="text-xs text-muted-foreground">FCFA</span></p>
                        </div>
                    </button>
                @empty
                    <div class="rounded-lg border bg-card p-8 text-center text-muted-foreground border-border">
                        @if($isLoading)
                            Chargement des données...
                        @else
                            Aucune donnée boursière disponible.
                        @endif
                    </div>
                @endforelse
            </div>

            <!-- Vue desktop : tableau -->
            <div class="hidden md:block rounded-lg border bg-card text-card-foreground shadow-sm overflow-hidden border-border">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-muted">
                            <tr>
                                <th class="text-left p-4 font-semibold cursor-pointer hover:bg-muted/80 transition-colors" wire:click="sortByColumn('symbol')">
                                    <div class="flex items-center gap-1">
                                        Symbole
                                        @if($sortBy === 'symbol')
                                            <svg class="w-4 h-4 {{ $sortDirection === 'desc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                            </svg>
                                        @endif
                                    </div>
                                </th>
                                <th class="text-left p-4 font-semibold cursor-pointer hover:bg-muted/80 transition-colors" wire:click="sortByColumn('company_name')">
                                    <div class="flex items-center gap-1">
                                        Nom
                                        @if($sortBy === 'company_name')
                                            <svg class="w-4 h-4 {{ $sortDirection === 'desc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                            </svg>
                                        @endif
                                    </div>
                                </th>
                                <th class="text-left p-4 font-semibold cursor-pointer hover:bg-muted/80 transition-colors" wire:click="sortByColumn('sector')">
                                    <div class="flex items-center gap-1">
                                        Secteur
                                        @if($sortBy === 'sector')
                                            <svg class="w-4 h-4 {{ $sortDirection === 'desc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                            </svg>
                                        @endif
                                    </div>
                                </th>
                                <th class="text-right p-4 font-semibold cursor-pointer hover:bg-muted/80 transition-colors" wire:click="sortByColumn('current_price')">
                                    <div class="flex items-center justify-end gap-1">
                                        Cours (FCFA)
                                        @if($sortBy === 'current_price')
                                            <svg class="w-4 h-4 {{ $sortDirection === 'desc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                            </svg>
                                        @endif
                                    </div>
                                </th>
                                <th class="text-right p-4 font-semibold cursor-pointer hover:bg-muted/80 transition-colors" wire:click="sortByColumn('volume')">
                                    <div class="flex items-center justify-end gap-1">
                                        Volume
                                        @if($sortBy === 'volume')
                                            <svg class="w-4 h-4 {{ $sortDirection === 'desc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                            </svg>
                                        @endif
                                    </div>
                                </th>
                                <th class="text-right p-4 font-semibold">Cap. (Mrd)</th>
                                <th class="text-right p-4 font-semibold cursor-pointer hover:bg-muted/80 transition-colors" wire:click="sortByColumn('variation_percent')">
                                    <div class="flex items-center justify-end gap-1">
                                        Variation
                                        @if($sortBy === 'variation_percent')
                                            <svg class="w-4 h-4 {{ $sortDirection === 'desc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                            </svg>
                                        @endif
                                    </div>
                                </th>
                                <th class="text-right p-4 font-semibold">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stocks as $stock)
                            <tr class="border-t border-border hover:bg-muted/50 transition-smooth">
                                <td class="p-4">
                                    <span class="font-bold text-primary">{{ $stock['symbol'] ?? 'N/A' }}</span>
                                </td>
                                <td class="p-4">{{ $stock['company_name'] ?? 'N/A' }}</td>
                                <td class="p-4">
                                    @php
                                        $sector = $stock['sector'] ?? 'Autre';
                                        $sectorColor = $this->sectorColors[$sector] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $sectorColor }}">
                                        {{ $sector }}
                                    </span>
                                </td>
                                <td class="p-4 text-right font-semibold">
                                    {{ number_format($stock['current_price'] ?? 0, 0, ',', ' ') }}
                                </td>
                                <td class="p-4 text-right text-muted-foreground">
                                    {{ number_format($stock['volume'] ?? 0, 0, ',', ' ') }}
                                </td>
                                <td class="p-4 text-right text-muted-foreground">
                                    @if(isset($stock['market_cap']) && $stock['market_cap'] > 0)
                                        @if($stock['market_cap'] >= 1000)
                                            {{ number_format($stock['market_cap'] / 1000, 2, ',', ' ') }} Mrd
                                        @else
                                            {{ number_format($stock['market_cap'], 0, ',', ' ') }} M
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="p-4 text-right">
                                    @php
                                        $variation = $stock['variation_percent'] ?? 0;
                                    @endphp
                                    <div class="inline-flex items-center gap-1 px-2 py-1 rounded {{ $variation >= 0 ? 'bg-accent/10 text-accent' : 'bg-destructive/10 text-destructive' }}">
                                        @if($variation >= 0)
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3">
                                                <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                                <polyline points="16 7 22 7 22 13"></polyline>
                                            </svg>
                                            <span class="font-semibold text-sm">+{{ number_format($variation, 2) }}%</span>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3">
                                                <polyline points="22 17 13.5 8.5 8.5 13.5 2 7"></polyline>
                                                <polyline points="16 17 22 17 22 11"></polyline>
                                            </svg>
                                            <span class="font-semibold text-sm">{{ number_format($variation, 2) }}%</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="p-4 text-right">
                                    <button 
                                        wire:click="showStockDetails('{{ $stock['symbol'] }}')"
                                        class="text-primary hover:text-primary-light transition-smooth inline-flex items-center gap-1 text-sm font-medium"
                                    >
                                        Détails
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3">
                                            <path d="M5 12h14"></path>
                                            <path d="m12 5 7 7-7 7"></path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="p-8 text-center text-muted-foreground">
                                    @if($isLoading)
                                        <div class="flex items-center justify-center gap-2">
                                            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Chargement des données...
                                        </div>
                                    @else
                                        Aucune donnée boursière disponible. Cliquez sur "Actualiser" pour charger les données.
                                    @endif
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Graphique -->
    <section class="py-12 bg-muted/30">
        <div class="container mx-auto px-4">
            @php
                $chartPoints = $chartData['points_count'] ?? 0;
                $chartSource = $chartData['source'] ?? null;
                $chartIndex = $chartData['index_name'] ?? 'BRVM Composite';
                $sourceLabels = [
                    'richbourse' => 'RichBourse.com',
                    'brvm' => 'BRVM.org',
                    'manual' => 'Saisie manuelle',
                ];
                $sourceLabel = $sourceLabels[$chartSource] ?? ucfirst($chartSource ?? 'inconnue');
            @endphp
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
                <div>
                    <h2 class="text-2xl font-bold">Évolution de l'indice {{ $chartIndex }}</h2>
                    @if($chartSource)
                        <p class="text-sm text-muted-foreground mt-1">
                            Source : <span class="font-semibold text-foreground">{{ $sourceLabel }}</span>
                            · Historique local sur {{ $chartPoints }} jour{{ $chartPoints > 1 ? 's' : '' }}
                        </p>
                    @endif
                </div>
                @if($chartPoints === 0)
                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-800 border border-blue-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Historique en cours de constitution
                    </span>
                @elseif($chartPoints < 5)
                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Historique court ({{ $chartPoints }} jour{{ $chartPoints > 1 ? 's' : '' }})
                    </span>
                @endif
            </div>
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm p-8 border-border">
                @if($chartPoints < 2)
                    @php
                        $currentValue = $chartData['currentValue'] ?? 0;
                        $currentVariation = $chartData['currentVariation'] ?? 0;
                        $isUp = $currentVariation >= 0;
                    @endphp
                    <div class="min-h-80 flex flex-col items-center justify-center text-center px-4 py-10">
                        <p class="text-xs uppercase tracking-widest text-muted-foreground font-semibold mb-3">Valeur actuelle</p>
                        <p class="text-6xl md:text-7xl font-bold text-foreground mb-3 tabular-nums">
                            {{ number_format($currentValue, 2, ',', ' ') }}
                        </p>
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full {{ $isUp ? 'bg-accent/10 text-accent' : 'bg-destructive/10 text-destructive' }} font-semibold text-lg mb-6">
                            @if($isUp)
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                +{{ number_format($currentVariation, 2, ',', ' ') }} %
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"/></svg>
                                {{ number_format($currentVariation, 2, ',', ' ') }} %
                            @endif
                        </div>
                        <div class="max-w-lg border-t border-border pt-6">
                            <div class="flex items-center justify-center gap-2 mb-3">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <h4 class="font-semibold text-foreground">Graphique en cours de constitution</h4>
                            </div>
                            <p class="text-sm text-muted-foreground">
                                Un snapshot quotidien de l'indice <strong>{{ $chartIndex }}</strong> est enregistré automatiquement
                                chaque jour ouvré à <strong>19h00 (heure d'Abidjan)</strong>, juste après la clôture du marché.
                                La courbe s'affichera dès que <strong>2 journées de cotation</strong> auront été collectées.
                            </p>
                        </div>
                    </div>
                @else
                    <div class="h-80">
                        <canvas id="brvmChart"></canvas>
                    </div>
                    <p class="text-xs text-muted-foreground mt-4">
                        📊 {{ $chartPoints }} point{{ $chartPoints > 1 ? 's' : '' }} d'historique local · Source :
                        @if($chartSource && ($sourceLabels[$chartSource]['url'] ?? null))
                            <a href="{{ $sourceLabels[$chartSource]['url'] }}" target="_blank" rel="noopener noreferrer" class="text-primary hover:underline font-medium">{{ $sourceLabel }}</a>
                        @else
                            <span class="font-medium">{{ $sourceLabel }}</span>
                        @endif
                        (gratuit, sans API)
                    </p>
                @endif
            </div>
        </div>
    </section>

    <!-- Comment ça marche (transparence des données) -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto">
                <div class="text-center mb-10">
                    <h2 class="text-2xl md:text-3xl font-bold mb-3">Comment fonctionnent ces données ?</h2>
                    <p class="text-muted-foreground max-w-2xl mx-auto">
                        Toutes les informations boursières affichées sont collectées en temps quasi-réel depuis des
                        <strong>sources publiques gratuites</strong>, sans abonnement ni clé API, et stockées localement.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Étape 1 -->
                    <div class="rounded-lg border bg-card p-6 border-border">
                        <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z"/></svg>
                        </div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xs font-bold text-primary uppercase tracking-wide">Étape 1</span>
                        </div>
                        <h3 class="font-bold text-foreground mb-2">Collecte temps réel</h3>
                        <p class="text-sm text-muted-foreground">
                            Le service interroge <a href="https://www.richbourse.com" target="_blank" rel="noopener noreferrer" class="text-primary hover:underline font-medium">RichBourse.com</a>
                            puis, en cas d'indisponibilité, bascule automatiquement sur
                            <a href="https://www.brvm.org" target="_blank" rel="noopener noreferrer" class="text-primary hover:underline font-medium">BRVM.org</a>.
                            Les cours sont <strong>mis en cache 5 minutes</strong> pour réduire la charge.
                        </p>
                    </div>

                    <!-- Étape 2 -->
                    <div class="rounded-lg border bg-card p-6 border-border">
                        <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/></svg>
                        </div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xs font-bold text-primary uppercase tracking-wide">Étape 2</span>
                        </div>
                        <h3 class="font-bold text-foreground mb-2">Snapshot quotidien</h3>
                        <p class="text-sm text-muted-foreground">
                            Chaque jour ouvré à <strong>19h00 (heure d'Abidjan)</strong>, juste après la clôture du marché,
                            une tâche planifiée enregistre la valeur de clôture des indices
                            dans une table locale <code class="text-xs bg-muted px-1.5 py-0.5 rounded">market_index_history</code>.
                        </p>
                    </div>

                    <!-- Étape 3 -->
                    <div class="rounded-lg border bg-card p-6 border-border">
                        <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                        </div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xs font-bold text-primary uppercase tracking-wide">Étape 3</span>
                        </div>
                        <h3 class="font-bold text-foreground mb-2">Historique & visualisation</h3>
                        <p class="text-sm text-muted-foreground">
                            Au fur et à mesure que les snapshots s'accumulent, le graphique d'évolution se construit
                            <strong>sans génération artificielle</strong> : chaque point correspond à une valeur de clôture réelle.
                        </p>
                    </div>
                </div>

                <!-- Sources utilisées -->
                <div class="rounded-lg border border-border bg-muted/30 p-6">
                    <h3 class="font-bold text-foreground mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        Sources utilisées (gratuites, sans clé API)
                    </h3>
                    <ul class="space-y-2 text-sm">
                        <li class="flex items-start gap-3">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 font-bold text-xs shrink-0">1</span>
                            <div>
                                <a href="https://www.richbourse.com/cours/brvm" target="_blank" rel="noopener noreferrer" class="font-semibold text-primary hover:underline">RichBourse.com</a>
                                <span class="text-muted-foreground"> — agrégateur financier africain, cours BRVM actualisés (source principale)</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 font-bold text-xs shrink-0">2</span>
                            <div>
                                <a href="https://www.brvm.org/fr/cours-actions/0" target="_blank" rel="noopener noreferrer" class="font-semibold text-primary hover:underline">BRVM.org</a>
                                <span class="text-muted-foreground"> — site officiel de la Bourse Régionale des Valeurs Mobilières (source de secours)</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-700 font-bold text-xs shrink-0">3</span>
                            <div>
                                <span class="font-semibold text-foreground">Base locale</span>
                                <span class="text-muted-foreground"> — dernier jeu de cours connu, utilisé si les deux sources ci-dessus sont indisponibles</span>
                            </div>
                        </li>
                    </ul>
                    <p class="text-xs text-muted-foreground mt-4 italic">
                        ⚠️ Les cours affichés sont fournis à titre informatif et peuvent présenter un délai de quelques minutes
                        par rapport aux transactions en cours. Pour toute décision d'investissement, référez-vous à votre SGI.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Formulaire de demande de rendez-vous -->
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
                    <p class="text-lg text-muted-foreground">Nos experts vous accompagnent dans votre stratégie d'investissement sur les actions BRVM</p>
                </div>

                <div class="bg-card rounded-2xl border border-border shadow-elegant p-8">
                    <form wire:submit.prevent="submit" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nom -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-foreground mb-2">Nom complet *</label>
                                <input type="text" id="name" wire:model="name" required
                                    class="w-full px-4 py-3 rounded-lg border border-border bg-background text-foreground focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                                @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-foreground mb-2">Email *</label>
                                <input type="email" id="email" wire:model="email" required
                                    class="w-full px-4 py-3 rounded-lg border border-border bg-background text-foreground focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                                @error('email') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Téléphone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-foreground mb-2">Téléphone *</label>
                                <input type="tel" id="phone" wire:model="phone" required
                                    class="w-full px-4 py-3 rounded-lg border border-border bg-background text-foreground focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                                @error('phone') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Entreprise -->
                            <div>
                                <label for="company" class="block text-sm font-medium text-foreground mb-2">Entreprise (optionnel)</label>
                                <input type="text" id="company" wire:model="company"
                                    class="w-full px-4 py-3 rounded-lg border border-border bg-background text-foreground focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                                @error('company') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Message -->
                        <div>
                            <label for="message" class="block text-sm font-medium text-foreground mb-2">Message (optionnel)</label>
                            <textarea id="message" wire:model="message" rows="4"
                                class="w-full px-4 py-3 rounded-lg border border-border bg-background text-foreground focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                placeholder="Décrivez vos objectifs d'investissement, vos questions ou toute information pertinente..."></textarea>
                            @error('message') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Bouton de soumission -->
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

                <!-- Avantages -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
                    <div class="text-center p-6 bg-gradient-to-br from-primary/5 to-transparent rounded-xl border border-primary/10">
                        <div class="w-12 h-12 bg-gradient-primary rounded-full flex items-center justify-center mx-auto mb-4 shadow-glow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary-foreground">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-foreground mb-2">Accompagnement Expert</h3>
                        <p class="text-sm text-muted-foreground">Nos conseillers certifiés vous guident dans vos choix</p>
                    </div>

                    <div class="text-center p-6 bg-gradient-to-br from-secondary/5 to-transparent rounded-xl border border-secondary/10">
                        <div class="w-12 h-12 bg-gradient-to-br from-secondary to-secondary/70 rounded-full flex items-center justify-center mx-auto mb-4 shadow-glow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white">
                                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-foreground mb-2">Stratégie Personnalisée</h3>
                        <p class="text-sm text-muted-foreground">Plan d'investissement adapté à votre profil</p>
                    </div>

                    <div class="text-center p-6 bg-gradient-to-br from-primary/5 to-transparent rounded-xl border border-primary/10">
                        <div class="w-12 h-12 bg-gradient-primary rounded-full flex items-center justify-center mx-auto mb-4 shadow-glow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary-foreground">
                                <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path>
                                <path d="m9 12 2 2 4-4"></path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-foreground mb-2">Sécurité & Conformité</h3>
                        <p class="text-sm text-muted-foreground">Opérateur agréé AMF-UMOA</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

@push('scripts')
<script>
let brvmChart = null;
let chartJsLoaded = false;
let chartJsLoading = null;

// Charge Chart.js à la demande (évite le CDN si le graphique n'est jamais affiché)
function loadChartJs() {
    if (chartJsLoaded) return Promise.resolve();
    if (chartJsLoading) return chartJsLoading;
    chartJsLoading = new Promise((resolve, reject) => {
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js';
        script.onload = () => { chartJsLoaded = true; resolve(); };
        script.onerror = reject;
        document.head.appendChild(script);
    });
    return chartJsLoading;
}

async function createChart() {
    const ctx = document.getElementById('brvmChart');
    if (!ctx) return;

    await loadChartJs();

    // Détruire l'ancien graphique s'il existe
    if (brvmChart) {
        brvmChart.destroy();
    }

    const chartData = @json($chartData);

    brvmChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels || [],
                datasets: [{
                    label: 'Indice BRVM Composite',
                    data: chartData.data || [],
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                    pointHoverRadius: 6,
                    pointBackgroundColor: 'rgb(16, 185, 129)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 14,
                                weight: '600'
                            },
                            padding: 20,
                            usePointStyle: true,
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('fr-FR', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('fr-FR', {
                                    notation: 'compact',
                                    compactDisplay: 'short'
                                }).format(value);
                            },
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        }
                    },
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 0,
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            display: false,
                            drawBorder: false
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });
}

// Lazy-load : charger Chart.js + créer le graphique uniquement quand visible
function observeChart() {
    const canvas = document.getElementById('brvmChart');
    if (!canvas) return;
    if (!('IntersectionObserver' in window)) { createChart(); return; }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                createChart();
                observer.disconnect();
            }
        });
    }, { rootMargin: '100px' });
    observer.observe(canvas);
}

document.addEventListener('DOMContentLoaded', observeChart);

// Recréer le graphique après chaque mise à jour Livewire si déjà chargé
document.addEventListener('livewire:init', () => {
    Livewire.hook('morph.updated', () => {
        if (chartJsLoaded) {
            setTimeout(createChart, 100);
        }
    });
});
</script>
@endpush
