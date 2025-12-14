<main class="flex-1 pt-20">
    <!-- Modal Détails Stock -->
    @if($showModal && $selectedStock)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>

        <!-- Modal -->
        <div class="relative bg-card rounded-lg text-left overflow-hidden shadow-xl transform transition-all w-full max-w-lg border border-border">
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
                                        {{ number_format($selectedStock['market_cap'] / 1000, 1, ',', ' ') }} Mrd FCFA
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
    @if (session()->has('success'))
        <div class="container mx-auto px-4 pt-4">
            <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800 border border-green-200">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if ($errorMessage)
        <div class="container mx-auto px-4 pt-4">
            <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 border border-red-200">
                {{ $errorMessage }}
            </div>
        </div>
    @endif

    <div class="container mx-auto px-4 pt-4">
        @if ($dataSource === 'richbourse')
            <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800 border border-green-200">
                <strong>✅ Données en temps réel :</strong> Les cours BRVM proviennent de RichBourse.com (cache: 5 minutes).
            </div>
        @elseif ($dataSource === 'brvm')
            <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800 border border-green-200">
                <strong>✅ Données en temps réel :</strong> Les cours BRVM proviennent directement de BRVM.org (cache: 5 minutes).
            </div>
        @elseif ($dataSource === 'database')
            <div class="mb-4 rounded-lg bg-blue-50 p-4 text-sm text-blue-800 border border-blue-200">
                <strong>📊 Données locales :</strong> Les cours BRVM proviennent de la base de données locale. Les sources en ligne ne sont pas accessibles actuellement.
            </div>
        @elseif ($dataSource === 'default')
            <div class="mb-4 rounded-lg bg-yellow-50 p-4 text-sm text-yellow-800 border border-yellow-200">
                <strong>⚠️ Données par défaut :</strong> Les cours BRVM affichés sont des données de démonstration. Les sources en ligne ne sont pas accessibles.
            </div>
        @else
            <div class="mb-4 rounded-lg bg-gray-50 p-4 text-sm text-gray-800 border border-gray-200">
                <strong>ℹ️ Source :</strong> Données BRVM chargées avec succès.
            </div>
        @endif
    </div>

    <section class="bg-gradient-hero text-primary-foreground py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Bourse <span
                        class="text-secondary">BRVM</span></h1>
                <p class="text-lg text-primary-foreground/90">Suivez en temps réel les cours, indices et
                    analyses de la Bourse Régionale des Valeurs Mobilières</p>
            </div>
        </div>
    </section>

    <section class="py-12 bg-muted/30">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold">Indices BRVM</h2>
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
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                <h2 class="text-2xl font-bold">Principales Valeurs</h2>
                <span class="text-sm text-muted-foreground">
                    @if($lastUpdate)
                        Dernière mise à jour: {{ $lastUpdate }}
                    @else
                        Chargement...
                    @endif
                </span>
            </div>

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
            
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm overflow-hidden border-border">
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
                                        $sectorColors = [
                                            'Finance' => 'bg-blue-100 text-blue-800',
                                            'Banque' => 'bg-blue-100 text-blue-800',
                                            'Télécommunications' => 'bg-purple-100 text-purple-800',
                                            'Agriculture' => 'bg-green-100 text-green-800',
                                            'Industrie' => 'bg-orange-100 text-orange-800',
                                            'Distribution' => 'bg-yellow-100 text-yellow-800',
                                            'Services Publics' => 'bg-cyan-100 text-cyan-800',
                                            'Transport' => 'bg-indigo-100 text-indigo-800',
                                        ];
                                        $sectorColor = $sectorColors[$sector] ?? 'bg-gray-100 text-gray-800';
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
                                            {{ number_format($stock['market_cap'] / 1000, 1, ',', ' ') }} B
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
    <section class="py-12 bg-muted/30">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold mb-6">Évolution de l'indice BRVM Composite</h2>
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm p-8 border-border">
                <div class="h-80">
                    <canvas id="brvmChart"></canvas>
                </div>
            </div>
        </div>
    </section>
</main>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
let brvmChart = null;

function createChart() {
    const ctx = document.getElementById('brvmChart');
    if (ctx) {
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
}

// Créer le graphique au chargement de la page
document.addEventListener('DOMContentLoaded', createChart);

// Recréer le graphique après chaque mise à jour Livewire
document.addEventListener('livewire:init', () => {
    Livewire.hook('morph.updated', ({ component }) => {
        // Attendre que le DOM soit mis à jour
        setTimeout(() => {
            createChart();
        }, 100);
    });
});
</script>
@endpush