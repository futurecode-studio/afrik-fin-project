@php
    $funds = $this->funds;
    $stats = $this->stats;
    $categories = $this->categories;
    $availableCountries = $this->availableCountries;

    $categoryBadge = [
        'Actions' => 'bg-blue-100 text-blue-800 border-blue-200',
        'Mixte' => 'bg-purple-100 text-purple-800 border-purple-200',
        'Obligations' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
        'Monétaire' => 'bg-amber-100 text-amber-800 border-amber-200',
    ];
@endphp

<main class="flex-1 pt-20">
    {{-- Modal détails ─────────────────────────────────────────────── --}}
    @if($showModal && $this->selectedFund)
        @php $fund = $this->selectedFund; @endphp
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
             x-data
             x-init="document.body.style.overflow = 'hidden'"
             @keydown.escape.window="$wire.closeModal()"
             @close-modal.window="document.body.style.overflow = ''"
             aria-labelledby="fund-modal-title" role="dialog" aria-modal="true">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>
            <div class="relative adf-modal-panel bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl border border-border">
                <div class="sticky top-0 bg-card border-b border-border p-5 flex items-start justify-between z-10">
                    <div class="pr-4">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium border {{ $categoryBadge[$fund['category']] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                                {{ $fund['category'] }}
                            </span>
                            <span class="text-xs text-muted-foreground">{{ $fund['country'] }}</span>
                        </div>
                        <h3 id="fund-modal-title" class="text-xl font-bold text-foreground">{{ $fund['name'] }}</h3>
                        <p class="text-sm text-muted-foreground">{{ $fund['company'] }}</p>
                    </div>
                    <button wire:click="closeModal" class="p-2 hover:bg-muted rounded-lg transition-colors" aria-label="Fermer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    {{-- VL principale --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-muted/50 rounded-lg p-4">
                            <p class="text-xs text-muted-foreground uppercase tracking-wider">Valeur liquidative</p>
                            <p class="text-2xl font-bold text-primary mt-1">{{ $fund['nav_value'] }}</p>
                            <p class="text-xs text-muted-foreground mt-1">au {{ \Carbon\Carbon::parse($fund['date'])->format('d/m/Y') }}</p>
                        </div>
                        <div class="bg-muted/50 rounded-lg p-4">
                            <p class="text-xs text-muted-foreground uppercase tracking-wider">Variation</p>
                            <p class="text-2xl font-bold mt-1 {{ $fund['variation_percentage'] > 0 ? 'text-emerald-600' : ($fund['variation_percentage'] < 0 ? 'text-red-500' : 'text-foreground') }}">
                                {{ $fund['variation'] }}
                            </p>
                            <p class="text-xs text-muted-foreground mt-1">
                                @if($fund['variation_percentage'] > 0) Performance positive
                                @elseif($fund['variation_percentage'] < 0) Performance négative
                                @else Stable
                                @endif
                            </p>
                        </div>
                    </div>

                    {{-- Caractéristiques --}}
                    <div>
                        <h4 class="text-sm font-bold uppercase tracking-wider text-muted-foreground mb-3">Identification</h4>
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                            <div>
                                <dt class="text-xs text-muted-foreground">Code ISIN</dt>
                                <dd class="font-mono font-semibold">{{ $fund['isin'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-muted-foreground">Devise</dt>
                                <dd class="font-semibold">{{ $fund['currency'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-muted-foreground">Société de gestion</dt>
                                <dd class="font-semibold">{{ $fund['company'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-muted-foreground">Pays / Zone</dt>
                                <dd class="font-semibold">{{ $fund['country'] }}</dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Traçabilité --}}
                    <div class="border-t border-border pt-4 text-xs text-muted-foreground space-y-1">
                        <p><strong>Source :</strong>
                            <a href="{{ $fund['source_url'] }}" target="_blank" rel="noopener noreferrer" class="text-primary hover:underline">
                                Sikafinance ↗
                            </a>
                            @if(isset($fund['scraped_at'])) · récupéré {{ \Carbon\Carbon::parse($fund['scraped_at'])->diffForHumans() }} @endif
                        </p>
                        <p class="italic">Les VL sont publiées par les sociétés de gestion et référencées par Sikafinance. La fréquence de mise à jour dépend du fonds (quotidienne à hebdomadaire).</p>
                    </div>
                </div>

                <div class="sticky bottom-0 bg-card border-t border-border p-4 flex justify-end gap-3">
                    <button wire:click="closeModal" class="px-4 py-2 rounded-lg border border-border hover:bg-muted transition-colors">Fermer</button>
                    <a href="#rdv" wire:click="closeModal" class="px-4 py-2 rounded-lg bg-primary text-primary-foreground hover:bg-primary/90 transition-colors font-medium">
                        Demander conseil
                    </a>
                </div>
            </div>
        </div>
    @endif

    {{-- Hero local ───────────────────────────────────────────────── --}}
    <section class="relative text-primary-foreground py-20 overflow-hidden" style="background: linear-gradient(135deg, #071F5A 0%, #0A2E8C 50%, #1E4AB8 100%);">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <pattern id="fcpDots" x="0" y="0" width="4" height="4" patternUnits="userSpaceOnUse">
                    <circle cx="2" cy="2" r="0.5" fill="currentColor" />
                </pattern>
                <rect width="100" height="100" fill="url(#fcpDots)" />
            </svg>
        </div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full mb-4 border border-white/20">
                    <span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
                    <span class="text-sm font-semibold tracking-wide">Fonds Communs de Placement · UEMOA</span>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold mb-4 leading-tight">
                    Investir sur les <span class="text-secondary">FCP & OPCVM</span>
                </h1>
                <p class="text-lg text-primary-foreground/90 max-w-2xl">
                    Déléguez la gestion de votre épargne à des professionnels agréés. Valeurs liquidatives suivies en direct depuis Sikafinance.
                </p>
            </div>
        </div>
    </section>

    {{-- Bandeau source ─────────────────────────────────────────────── --}}
    <div x-data="{ visible: true }" x-show="visible" x-transition class="bg-primary/5 border-b border-primary/10">
        <div class="container mx-auto px-4 py-3 flex flex-col sm:flex-row items-start sm:items-center gap-2 justify-between">
            <div class="flex items-center gap-2 text-sm">
                <svg class="w-4 h-4 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>
                    <strong class="text-foreground">Source officielle :</strong>
                    <a href="https://www.sikafinance.com" target="_blank" rel="noopener noreferrer" class="text-primary hover:underline font-medium">Sikafinance</a>
                    · Scraping HTML public, sans API · Cache 1h · 3 synchronisations par jour ouvré
                    @if($lastUpdated) · Dernière MAJ <strong>{{ $lastUpdated }}</strong> @endif
                </span>
            </div>
            <button @click="visible = false" class="p-1 hover:bg-primary/10 rounded transition-colors" aria-label="Fermer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    {{-- Sessions flash ─────────────────────────────────────────────── --}}
    @if($error)
        <div class="container mx-auto px-4 mt-4">
            <div class="p-4 bg-amber-50 border border-amber-200 rounded-lg text-amber-900 text-sm flex items-start justify-between gap-3">
                <div class="flex items-start gap-2">
                    <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M4.93 19h14.14a2 2 0 001.71-3L13.71 4a2 2 0 00-3.42 0L3.22 16a2 2 0 001.71 3z"/></svg>
                    {{ $error }}
                </div>
                <button wire:click="refreshFunds" wire:loading.attr="disabled" class="shrink-0 px-3 py-1 rounded bg-amber-900 text-white text-xs font-medium hover:bg-amber-800 disabled:opacity-50">
                    <span wire:loading.remove wire:target="refreshFunds">Réessayer</span>
                    <span wire:loading wire:target="refreshFunds">…</span>
                </button>
            </div>
        </div>
    @endif

    {{-- Résumé du marché ──────────────────────────────────────────── --}}
    <section class="py-10">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-3 md:gap-4">
                <div class="rounded-lg border border-border bg-card p-4">
                    <p class="text-xs text-muted-foreground uppercase tracking-wider">Fonds suivis</p>
                    <p class="text-2xl font-bold mt-1">{{ $stats['total'] }}</p>
                </div>
                <div class="rounded-lg border border-border bg-card p-4">
                    <p class="text-xs text-muted-foreground uppercase tracking-wider">Variation moyenne</p>
                    <p class="text-2xl font-bold mt-1 {{ ($stats['avg_variation'] ?? 0) >= 0 ? 'text-emerald-600' : 'text-red-500' }}">
                        {{ ($stats['avg_variation'] ?? 0) >= 0 ? '+' : '' }}{{ number_format($stats['avg_variation'] ?? 0, 2) }}%
                    </p>
                </div>
                <div class="rounded-lg border border-border bg-card p-4">
                    <p class="text-xs text-muted-foreground uppercase tracking-wider">En hausse</p>
                    <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $stats['gainers'] ?? 0 }}<span class="text-sm text-muted-foreground font-normal"> / {{ $stats['total'] }}</span></p>
                </div>
                <div class="rounded-lg border border-border bg-card p-4">
                    <p class="text-xs text-muted-foreground uppercase tracking-wider">Pays</p>
                    <p class="text-2xl font-bold mt-1">{{ $stats['countries'] ?? 0 }}</p>
                </div>
                @if(!empty($stats['top_gainer']))
                    <div class="col-span-2 md:col-span-1 rounded-lg border border-emerald-200 bg-emerald-50 p-4">
                        <p class="text-xs text-emerald-700 uppercase tracking-wider font-semibold">🔥 Meilleure perf.</p>
                        <p class="font-bold text-sm mt-1 truncate">{{ $stats['top_gainer']['name'] }}</p>
                        <p class="text-xs text-emerald-600">{{ $stats['top_gainer']['variation'] }}</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- Filtres ────────────────────────────────────────────────────── --}}
    <section class="pb-6">
        <div class="container mx-auto px-4">
            <div class="rounded-xl border border-border bg-card p-4 md:p-5">
                {{-- Ligne 1 : recherche + bouton actualiser --}}
                <div class="flex flex-col md:flex-row gap-3 mb-4">
                    <div class="flex-1 relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="search" wire:model.live.debounce.300ms="searchTerm"
                            placeholder="Rechercher par nom, société, ISIN, pays…"
                            class="w-full pl-9 pr-3 py-2 rounded-lg border border-border bg-background text-sm focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>
                    <button wire:click="refreshFunds" wire:loading.attr="disabled" wire:target="refreshFunds"
                            class="px-4 py-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary/90 transition-colors flex items-center gap-2 disabled:opacity-50 whitespace-nowrap text-sm font-medium">
                        <svg wire:loading.class="animate-spin" wire:target="refreshFunds" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M23 4v6h-6"></path><path d="M1 20v-6h6"></path><path d="M3.51 9a9 9 0 0114.85-3.36M20.49 15a9 9 0 01-14.85 3.36"></path>
                        </svg>
                        <span wire:loading.remove wire:target="refreshFunds">Actualiser</span>
                        <span wire:loading wire:target="refreshFunds">Sync…</span>
                    </button>
                </div>

                {{-- Ligne 2 : chips catégories --}}
                @if(count($categories) > 0)
                    <div class="flex flex-wrap gap-2 mb-3">
                        <button wire:click="filterByCategory('Tous')"
                            class="px-3 py-1.5 rounded-lg text-xs font-medium transition-all {{ $selectedCategory === 'Tous' ? 'bg-primary text-primary-foreground' : 'bg-muted text-muted-foreground hover:bg-muted/80' }}">
                            Tous ({{ $stats['total'] }})
                        </button>
                        @foreach($categories as $cat)
                            @php $catCount = count(array_filter($allFunds, fn($f) => $f['category'] === $cat)); @endphp
                            <button wire:click="filterByCategory('{{ $cat }}')"
                                class="px-3 py-1.5 rounded-lg text-xs font-medium transition-all {{ $selectedCategory === $cat ? 'bg-primary text-primary-foreground' : 'bg-muted text-muted-foreground hover:bg-muted/80' }}">
                                {{ $cat }} ({{ $catCount }})
                            </button>
                        @endforeach
                    </div>
                @endif

                {{-- Ligne 3 : pays / tri --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <select wire:model.live="countryFilter" class="px-3 py-2 rounded-lg border border-border bg-background text-sm focus:ring-2 focus:ring-primary">
                        <option value="all">Tous les pays</option>
                        @foreach($availableCountries as $c)
                            <option value="{{ $c }}">{{ $c }}</option>
                        @endforeach
                    </select>
                    <select wire:model.live="sortBy" class="px-3 py-2 rounded-lg border border-border bg-background text-sm focus:ring-2 focus:ring-primary">
                        <option value="name">Nom</option>
                        <option value="nav_numeric">Valeur liquidative</option>
                        <option value="variation_percentage">Variation</option>
                    </select>
                    <select wire:model.live="sortDirection" class="px-3 py-2 rounded-lg border border-border bg-background text-sm focus:ring-2 focus:ring-primary">
                        <option value="asc">↑ Croissant</option>
                        <option value="desc">↓ Décroissant</option>
                    </select>
                    <button wire:click="resetFilters" type="button"
                        class="px-3 py-2 rounded-lg border border-border hover:bg-muted transition-colors text-sm flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Réinitialiser
                    </button>
                </div>

                <p class="text-xs text-muted-foreground mt-3">
                    <strong>{{ count($funds) }}</strong> fonds affiché{{ count($funds) > 1 ? 's' : '' }}
                </p>
            </div>
        </div>
    </section>

    {{-- Liste (desktop table + mobile cards) ──────────────────────── --}}
    <section class="pb-12">
        <div class="container mx-auto px-4">
            @if($isLoading)
                <div class="p-12 text-center bg-card rounded-xl border border-border">
                    <svg class="animate-spin h-8 w-8 text-primary mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="mt-4 text-muted-foreground text-sm">Chargement des valeurs liquidatives…</p>
                </div>
            @elseif(count($funds) > 0)
                {{-- Table desktop --}}
                <div class="hidden md:block bg-card rounded-xl border border-border overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-muted/50">
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Fonds</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold text-muted-foreground uppercase tracking-wider">Société · Pays</th>
                                    <th class="px-5 py-3 text-center text-xs font-semibold text-muted-foreground uppercase tracking-wider">Catégorie</th>
                                    <th class="px-5 py-3 text-right text-xs font-semibold text-muted-foreground uppercase tracking-wider">VL</th>
                                    <th class="px-5 py-3 text-right text-xs font-semibold text-muted-foreground uppercase tracking-wider">Variation</th>
                                    <th class="px-5 py-3 text-right text-xs font-semibold text-muted-foreground uppercase tracking-wider">Source</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border">
                                @foreach($funds as $fund)
                                    <tr wire:click="showFundDetails('{{ $fund['id'] }}')" class="hover:bg-muted/30 transition-colors cursor-pointer group">
                                        <td class="px-5 py-3">
                                            <p class="font-semibold text-foreground group-hover:text-primary transition-colors">{{ $fund['name'] }}</p>
                                            <p class="text-xs text-muted-foreground font-mono">{{ $fund['isin'] }}</p>
                                        </td>
                                        <td class="px-5 py-3">
                                            <p class="text-sm">{{ $fund['company'] }}</p>
                                            <p class="text-xs text-muted-foreground">{{ $fund['country'] }}</p>
                                        </td>
                                        <td class="px-5 py-3 text-center">
                                            <span class="inline-block px-2 py-0.5 text-xs font-medium rounded border {{ $categoryBadge[$fund['category']] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                                                {{ $fund['category'] }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-3 text-right">
                                            <p class="font-bold">{{ $fund['nav_value'] }}</p>
                                            <p class="text-xs text-muted-foreground">{{ \Carbon\Carbon::parse($fund['date'])->format('d/m/Y') }}</p>
                                        </td>
                                        <td class="px-5 py-3 text-right font-semibold {{ $fund['variation_percentage'] > 0 ? 'text-emerald-600' : ($fund['variation_percentage'] < 0 ? 'text-red-500' : 'text-muted-foreground') }}">
                                            {{ $fund['variation'] }}
                                        </td>
                                        <td class="px-5 py-3 text-right">
                                            <a href="{{ $fund['source_url'] }}" target="_blank" rel="noopener noreferrer"
                                               @click.stop
                                               class="text-xs text-primary hover:underline inline-flex items-center gap-1">
                                                Sikafinance ↗
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Cards mobile --}}
                <div class="md:hidden space-y-3">
                    @foreach($funds as $fund)
                        <div wire:click="showFundDetails('{{ $fund['id'] }}')" class="bg-card rounded-lg border border-border p-4 cursor-pointer hover:border-primary/50 transition-colors">
                            <div class="flex items-start justify-between gap-3 mb-2">
                                <div class="min-w-0">
                                    <p class="font-semibold text-foreground truncate">{{ $fund['name'] }}</p>
                                    <p class="text-xs text-muted-foreground">{{ $fund['company'] }}</p>
                                </div>
                                <span class="shrink-0 inline-block px-2 py-0.5 text-xs font-medium rounded border {{ $categoryBadge[$fund['category']] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                                    {{ $fund['category'] }}
                                </span>
                            </div>
                            <div class="flex items-end justify-between pt-2 border-t border-border">
                                <div>
                                    <p class="text-xs text-muted-foreground">VL</p>
                                    <p class="font-bold">{{ $fund['nav_value'] }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-muted-foreground">Variation</p>
                                    <p class="font-semibold {{ $fund['variation_percentage'] > 0 ? 'text-emerald-600' : ($fund['variation_percentage'] < 0 ? 'text-red-500' : 'text-muted-foreground') }}">
                                        {{ $fund['variation'] }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16 bg-muted/20 rounded-xl border border-dashed border-border">
                    <svg class="mx-auto w-14 h-14 text-muted-foreground opacity-40 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    @if($searchTerm !== '' || $countryFilter !== 'all' || $selectedCategory !== 'Tous')
                        <h3 class="text-lg font-semibold mb-2">Aucun fonds ne correspond aux filtres</h3>
                        <p class="text-sm text-muted-foreground mb-4">Essayez d'élargir votre recherche.</p>
                        <button wire:click="resetFilters" class="px-4 py-2 rounded-lg bg-primary text-primary-foreground hover:bg-primary/90 transition-colors text-sm font-medium">
                            Réinitialiser les filtres
                        </button>
                    @else
                        <h3 class="text-lg font-semibold mb-2">Aucune valeur liquidative disponible</h3>
                        <p class="text-sm text-muted-foreground mb-4">Les données sont synchronisées automatiquement depuis Sikafinance trois fois par jour ouvré.</p>
                        <button wire:click="refreshFunds" wire:loading.attr="disabled" class="px-4 py-2 rounded-lg bg-primary text-primary-foreground hover:bg-primary/90 transition-colors text-sm font-medium">
                            <span wire:loading.remove>Synchroniser maintenant</span>
                            <span wire:loading>Synchronisation…</span>
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </section>

    {{-- Comment ça marche ──────────────────────────────────────────── --}}
    <section class="py-14 bg-muted/30">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto">
                <div class="text-center mb-10">
                    <h2 class="text-2xl md:text-3xl font-bold mb-3">Comment fonctionnent ces données ?</h2>
                    <p class="text-muted-foreground max-w-2xl mx-auto">
                        Toutes les valeurs liquidatives (VL) affichées proviennent de <strong>Sikafinance</strong>, qui centralise les publications officielles des sociétés de gestion agréées par le CREPMF. Aucune saisie manuelle, aucune donnée simulée.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
                    <div class="rounded-lg border border-border bg-card p-6">
                        <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                        </div>
                        <span class="text-xs font-bold text-primary uppercase tracking-wide">Étape 1</span>
                        <h3 class="font-bold text-foreground mt-1 mb-2">Publication par les SGO</h3>
                        <p class="text-sm text-muted-foreground">
                            Chaque société de gestion d'OPCVM (SGO) calcule quotidiennement ou hebdomadairement la valeur liquidative de ses FCP et la publie auprès du CREPMF.
                        </p>
                    </div>
                    <div class="rounded-lg border border-border bg-card p-6">
                        <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/></svg>
                        </div>
                        <span class="text-xs font-bold text-primary uppercase tracking-wide">Étape 2</span>
                        <h3 class="font-bold text-foreground mt-1 mb-2">Synchronisation</h3>
                        <p class="text-sm text-muted-foreground">
                            Notre commande <code class="text-xs bg-muted px-1.5 py-0.5 rounded">fcp:sync</code> récupère les VL de 17 FCP référencés trois fois par jour ouvré (7h, 13h, 19h — Abidjan). Chaque fonds conserve un lien <em>source_url</em> vérifiable.
                        </p>
                    </div>
                    <div class="rounded-lg border border-border bg-card p-6">
                        <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <span class="text-xs font-bold text-primary uppercase tracking-wide">Étape 3</span>
                        <h3 class="font-bold text-foreground mt-1 mb-2">Affichage filtrable</h3>
                        <p class="text-sm text-muted-foreground">
                            Les fonds sont présentés avec leur variation quotidienne, catégorie (Actions, Mixte, Obligations) et pays d'origine. Chaque ligne renvoie vers la page Sikafinance correspondante.
                        </p>
                    </div>
                </div>

                <div class="rounded-lg border border-border bg-card p-6">
                    <h3 class="font-bold text-foreground mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        Acteurs & sources
                    </h3>
                    <ul class="space-y-2 text-sm">
                        <li class="flex items-start gap-3">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary/10 text-primary font-bold text-xs shrink-0">1</span>
                            <div>
                                <a href="https://www.sikafinance.com" target="_blank" rel="noopener noreferrer" class="font-semibold text-primary hover:underline">Sikafinance</a>
                                <span class="text-muted-foreground"> — portail financier UEMOA, centralise les VL publiées par les SGO</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary/10 text-primary font-bold text-xs shrink-0">2</span>
                            <div>
                                <a href="https://www.crepmf.org" target="_blank" rel="noopener noreferrer" class="font-semibold text-primary hover:underline">CREPMF</a>
                                <span class="text-muted-foreground"> — Conseil Régional de l'Épargne Publique et des Marchés Financiers, agrée et supervise les SGO</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary/10 text-primary font-bold text-xs shrink-0">3</span>
                            <div>
                                <span class="font-semibold">Sociétés de gestion (SGO)</span>
                                <span class="text-muted-foreground"> — Société Générale CI, CGF Bourse, Coris AM, BOA AM, Ecobank AM, Attijari AM, etc.</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary/10 text-primary font-bold text-xs shrink-0">4</span>
                            <div>
                                <a href="https://www.brvm.org" target="_blank" rel="noopener noreferrer" class="font-semibold text-primary hover:underline">BRVM</a>
                                <span class="text-muted-foreground"> — les actions sous-jacentes des FCP « Actions » sont cotées sur la BRVM</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- Pourquoi investir ─────────────────────────────────────────── --}}
    <section class="py-14">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto">
                <div class="text-center mb-10">
                    <h2 class="text-2xl md:text-3xl font-bold mb-3">Pourquoi investir dans les <span class="text-primary">FCP</span> ?</h2>
                    <p class="text-lg text-muted-foreground">Une gestion professionnelle pour optimiser votre rendement</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-card rounded-xl p-6 border border-border">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21.21 15.89A10 10 0 118 2.83"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M22 12A10 10 0 0012 2v10z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Diversification</h3>
                        <p class="text-muted-foreground text-sm">Un seul ticket d'entrée donne accès à un portefeuille diversifié d'actions, d'obligations ou d'instruments monétaires.</p>
                    </div>
                    <div class="bg-card rounded-xl p-6 border border-border">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M22 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Gestion professionnelle</h3>
                        <p class="text-muted-foreground text-sm">Des sociétés de gestion agréées par le CREPMF pilotent vos placements selon une politique d'investissement définie.</p>
                    </div>
                    <div class="bg-card rounded-xl p-6 border border-border">
                        <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Liquidité</h3>
                        <p class="text-muted-foreground text-sm">Rachat de parts possible à la VL du jour suivant. Pas d'engagement de durée, flexibilité maximale.</p>
                    </div>
                </div>

                {{-- Disclaimer --}}
                <div class="mt-8 rounded-lg border border-amber-200 bg-amber-50 p-5">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M4.93 19h14.14a2 2 0 001.71-3L13.71 4a2 2 0 00-3.42 0L3.22 16a2 2 0 001.71 3z"/></svg>
                        <div class="text-sm text-amber-900">
                            <strong class="block mb-1">Avertissement risques</strong>
                            La valeur liquidative d'un FCP fluctue avec les marchés financiers. Les performances passées ne préjugent pas des performances futures. Des frais de souscription, de gestion et parfois de rachat s'appliquent selon le fonds. Consultez toujours le <strong>DICI</strong> (document d'information clé) avant toute souscription, et adressez-vous à une <strong>SGI agréée par le CREPMF</strong>.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Formulaire RDV ─────────────────────────────────────────────── --}}
    <section id="rdv" class="py-20 bg-muted/30">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-12">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-primary/10 rounded-full mb-4 border border-primary/20">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span class="text-sm font-medium text-primary">Demande de Rendez-vous</span>
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">Prenez <span class="text-primary">Rendez-vous</span></h2>
                    <p class="text-lg text-muted-foreground">Nos experts vous accompagnent dans le choix du FCP adapté à votre profil</p>
                </div>

                <div class="bg-card rounded-2xl border border-border shadow-sm p-8">
                    <form wire:submit.prevent="submit" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium mb-2">Nom complet *</label>
                                <input type="text" id="name" wire:model="name" required
                                    class="w-full px-4 py-3 rounded-lg border border-border bg-background focus:ring-2 focus:ring-primary focus:border-transparent">
                                @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium mb-2">Email *</label>
                                <input type="email" id="email" wire:model="email" required
                                    class="w-full px-4 py-3 rounded-lg border border-border bg-background focus:ring-2 focus:ring-primary focus:border-transparent">
                                @error('email') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-medium mb-2">Téléphone *</label>
                                <input type="tel" id="phone" wire:model="phone" required placeholder="+229 01 23 45 67"
                                    class="w-full px-4 py-3 rounded-lg border border-border bg-background focus:ring-2 focus:ring-primary focus:border-transparent">
                                @error('phone') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="company" class="block text-sm font-medium mb-2">Entreprise (optionnel)</label>
                                <input type="text" id="company" wire:model="company"
                                    class="w-full px-4 py-3 rounded-lg border border-border bg-background focus:ring-2 focus:ring-primary focus:border-transparent">
                                @error('company') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-medium mb-2">Message (optionnel)</label>
                            <textarea id="message" wire:model="message" rows="4"
                                placeholder="Décrivez vos objectifs d'investissement, vos questions…"
                                class="w-full px-4 py-3 rounded-lg border border-border bg-background focus:ring-2 focus:ring-primary focus:border-transparent"></textarea>
                            @error('message') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex justify-center pt-4">
                            <button type="submit" wire:loading.attr="disabled"
                                class="inline-flex items-center gap-3 px-8 py-4 bg-primary text-primary-foreground rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all hover:scale-[1.02] disabled:opacity-50">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                <span wire:loading.remove>Envoyer ma demande</span>
                                <span wire:loading>Envoi en cours…</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>
