@php
    $stats = $this->stats;
    $bonds = $this->bonds;
    $availableCountries = $this->availableCountries;

    $riskBadge = [
        'low' => ['label' => 'Faible', 'class' => 'bg-emerald-100 text-emerald-800 border-emerald-200'],
        'medium' => ['label' => 'Moyen', 'class' => 'bg-amber-100 text-amber-800 border-amber-200'],
        'high' => ['label' => 'Élevé', 'class' => 'bg-red-100 text-red-800 border-red-200'],
    ];

    $typeBadge = [
        'BAT' => ['label' => 'Bon du Trésor', 'class' => 'bg-blue-100 text-blue-800 border-blue-200'],
        'OAT' => ['label' => 'Obligation', 'class' => 'bg-purple-100 text-purple-800 border-purple-200'],
        'OATR' => ['label' => 'Obligation (réouverture)', 'class' => 'bg-indigo-100 text-indigo-800 border-indigo-200'],
        'OATI' => ['label' => 'Sukuk', 'class' => 'bg-teal-100 text-teal-800 border-teal-200'],
    ];
@endphp

<main class="flex-1 pt-20">
    {{-- Modal détails ─────────────────────────────────────────────── --}}
    @if($showModal && $this->selectedBond)
        @php $bond = $this->selectedBond; $proj = $this->yieldProjection; $bondType = explode(' ', $bond->name)[0] ?? 'OAT'; @endphp
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
             x-data="{ open: true }"
             x-init="document.body.style.overflow = 'hidden'"
             @keydown.escape.window="$wire.closeModal()"
             @close-modal.window="document.body.style.overflow = ''"
             aria-labelledby="bond-modal-title" role="dialog" aria-modal="true">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>
            <div class="relative adf-modal-panel bg-white rounded-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto shadow-2xl border border-border">
                <div class="sticky top-0 bg-card border-b border-border p-5 flex items-start justify-between z-10">
                    <div class="pr-4">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium border {{ $typeBadge[$bondType]['class'] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                                {{ $typeBadge[$bondType]['label'] ?? $bondType }}
                            </span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium border {{ $riskBadge[$bond->risk_level]['class'] ?? 'bg-gray-100' }}">
                                Risque {{ $bond->risk_level_label }}
                            </span>
                        </div>
                        <h3 id="bond-modal-title" class="text-xl font-bold text-foreground">{{ $bond->name }}</h3>
                        <p class="text-sm text-muted-foreground">{{ $bond->issuer }}</p>
                    </div>
                    <button wire:click="closeModal" class="p-2 hover:bg-muted rounded-lg transition-colors" aria-label="Fermer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    {{-- Caractéristiques --}}
                    <div>
                        <h4 class="text-sm font-bold uppercase tracking-wider text-muted-foreground mb-3">Caractéristiques</h4>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            <div class="bg-muted/50 rounded-lg p-3">
                                <p class="text-xs text-muted-foreground">Taux d'intérêt</p>
                                <p class="text-lg font-bold text-primary">{{ number_format($bond->interest_rate, 2) }}%</p>
                            </div>
                            @if($bond->yield_to_maturity)
                            <div class="bg-muted/50 rounded-lg p-3">
                                <p class="text-xs text-muted-foreground">Rendement échéance</p>
                                <p class="text-lg font-bold {{ $bond->yield_to_maturity >= $bond->interest_rate ? 'text-emerald-600' : 'text-amber-600' }}">
                                    {{ number_format($bond->yield_to_maturity, 2) }}%
                                </p>
                            </div>
                            @endif
                            <div class="bg-muted/50 rounded-lg p-3">
                                <p class="text-xs text-muted-foreground">Maturité</p>
                                <p class="text-lg font-bold">{{ $bond->maturity_years }} an{{ $bond->maturity_years > 1 ? 's' : '' }}</p>
                            </div>
                            <div class="bg-muted/50 rounded-lg p-3">
                                <p class="text-xs text-muted-foreground">Valeur nominale</p>
                                <p class="text-sm font-bold">{{ number_format($bond->nominal_value, 0, ',', ' ') }} {{ $bond->currency }}</p>
                            </div>
                            <div class="bg-muted/50 rounded-lg p-3">
                                <p class="text-xs text-muted-foreground">Fréquence coupons</p>
                                <p class="text-sm font-bold">{{ $bond->payment_frequency_label }}</p>
                            </div>
                            <div class="bg-muted/50 rounded-lg p-3">
                                <p class="text-xs text-muted-foreground">Type d'intérêt</p>
                                <p class="text-sm font-bold">{{ $bond->interest_type_label }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Dates --}}
                    <div>
                        <h4 class="text-sm font-bold uppercase tracking-wider text-muted-foreground mb-3">Calendrier</h4>
                        <div class="grid grid-cols-3 gap-3 text-sm">
                            <div>
                                <p class="text-xs text-muted-foreground">Adjudication</p>
                                <p class="font-semibold">{{ $bond->auction_date?->format('d/m/Y') ?? '—' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-muted-foreground">Date d'émission</p>
                                <p class="font-semibold">{{ $bond->issue_date?->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-muted-foreground">Échéance</p>
                                <p class="font-semibold">{{ $bond->maturity_date?->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Calculateur --}}
                    <div class="bg-primary/5 border border-primary/20 rounded-xl p-5">
                        <h4 class="text-sm font-bold uppercase tracking-wider text-primary mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            Calculateur de rendement
                        </h4>
                        <div class="flex flex-col sm:flex-row gap-3 mb-4">
                            <div class="flex-1">
                                <label class="text-xs text-muted-foreground block mb-1">Montant investi (FCFA)</label>
                                <input type="number" wire:model.live.debounce.300ms="calculatorAmount" min="{{ $bond->minimum_investment ?: 10000 }}" step="10000"
                                    class="w-full px-3 py-2 rounded-lg border border-border bg-background focus:ring-2 focus:ring-primary focus:border-transparent">
                                <p class="text-xs text-muted-foreground mt-1">Minimum : {{ number_format($bond->minimum_investment ?: 10000, 0, ',', ' ') }} FCFA</p>
                            </div>
                        </div>

                        @if($proj && $proj['amount'] > 0)
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-3">
                                <div class="bg-background rounded-lg p-3 border border-border">
                                    <p class="text-xs text-muted-foreground">Capital investi</p>
                                    <p class="font-bold text-sm">{{ number_format($proj['amount'], 0, ',', ' ') }}</p>
                                </div>
                                <div class="bg-background rounded-lg p-3 border border-border">
                                    <p class="text-xs text-muted-foreground">Intérêts totaux</p>
                                    <p class="font-bold text-sm text-emerald-600">+{{ number_format($proj['total_interest'], 0, ',', ' ') }}</p>
                                </div>
                                <div class="bg-background rounded-lg p-3 border border-border">
                                    <p class="text-xs text-muted-foreground">Total à l'échéance</p>
                                    <p class="font-bold text-sm text-primary">{{ number_format($proj['total_return'], 0, ',', ' ') }}</p>
                                </div>
                                @if(!$proj['is_bat'])
                                <div class="bg-background rounded-lg p-3 border border-border">
                                    <p class="text-xs text-muted-foreground">Coupon annuel</p>
                                    <p class="font-bold text-sm">{{ number_format($proj['coupon_value'], 0, ',', ' ') }}</p>
                                </div>
                                @else
                                <div class="bg-background rounded-lg p-3 border border-border">
                                    <p class="text-xs text-muted-foreground">Structure</p>
                                    <p class="font-bold text-sm">Zéro coupon</p>
                                </div>
                                @endif
                            </div>
                            <p class="text-xs text-muted-foreground italic">
                                💡 {{ $proj['is_bat']
                                    ? 'Pour un BAT (zéro coupon), les intérêts sont précomptés à la souscription. Vous recevez la valeur nominale à l\'échéance.'
                                    : "Pour une OAT, vous recevez {$proj['coupon_count']} coupon(s) sur la durée + le capital remboursé à l'échéance." }}
                            </p>
                        @endif
                    </div>

                    {{-- Description --}}
                    @if($bond->description)
                        <div>
                            <h4 class="text-sm font-bold uppercase tracking-wider text-muted-foreground mb-2">Description</h4>
                            <p class="text-sm text-muted-foreground leading-relaxed">{{ plain_text($bond->description) }}</p>
                        </div>
                    @endif

                    {{-- Traçabilité source --}}
                    <div class="border-t border-border pt-4 text-xs text-muted-foreground space-y-1">
                        <p><strong>ISIN :</strong> {{ $bond->isin_code }}</p>
                        @if($bond->source_url)
                            <p>
                                <strong>Source :</strong>
                                <a href="{{ $bond->source_url }}" target="_blank" rel="noopener noreferrer" class="text-primary hover:underline">
                                    UMOA-Titres ↗
                                </a>
                                @if($bond->last_synced_at) · synchronisé {{ $bond->last_synced_at->diffForHumans() }} @endif
                            </p>
                        @endif
                    </div>
                </div>

                <div class="sticky bottom-0 bg-card border-t border-border p-4 flex justify-end gap-3">
                    <button wire:click="closeModal" class="px-4 py-2 rounded-lg border border-border hover:bg-muted transition-colors">Fermer</button>
                    <a href="#rdv" wire:click="closeModal" class="px-4 py-2 rounded-lg bg-primary text-primary-foreground hover:bg-primary/90 transition-colors font-medium">
                        Prendre rendez-vous
                    </a>
                </div>
            </div>
        </div>
    @endif

    {{-- Hero local ───────────────────────────────────────────────── --}}
    <section class="relative text-primary-foreground py-20 overflow-hidden" style="background: linear-gradient(135deg, #071F5A 0%, #0A2E8C 50%, #1E4AB8 100%);">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <pattern id="bondsDots" x="0" y="0" width="4" height="4" patternUnits="userSpaceOnUse">
                    <circle cx="2" cy="2" r="0.5" fill="currentColor" />
                </pattern>
                <rect width="100" height="100" fill="url(#bondsDots)" />
            </svg>
        </div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full mb-4 border border-white/20">
                    <span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
                    <span class="text-sm font-semibold tracking-wide">Obligations souveraines UEMOA</span>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold mb-4 leading-tight">
                    Investir sur les <span class="text-secondary">Obligations d'États</span>
                </h1>
                <p class="text-lg text-primary-foreground/90 max-w-2xl">
                    Diversifiez votre portefeuille avec des titres publics émis par les 8 États de l'UEMOA, suivis en temps réel depuis UMOA-Titres.
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
                    <a href="https://www.umoatitres.org" target="_blank" rel="noopener noreferrer" class="text-primary hover:underline font-medium">UMOA-Titres</a>
                    · Gratuit, sans API · Synchronisation automatique 2×/jour ouvré
                    @if($lastSyncAt) · Dernière synchro <strong>{{ $lastSyncAt }}</strong> @endif
                </span>
            </div>
            <button @click="visible = false" class="p-1 hover:bg-primary/10 rounded transition-colors" aria-label="Fermer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    {{-- Session success / error ───────────────────────────────────── --}}

    {{-- Résumé du marché ──────────────────────────────────────────── --}}
    <section class="py-10">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-3 md:gap-4">
                <div class="rounded-lg border border-border bg-card p-4">
                    <p class="text-xs text-muted-foreground uppercase tracking-wider">Titres actifs</p>
                    <p class="text-2xl font-bold mt-1">{{ $stats['total'] }}</p>
                </div>
                <div class="rounded-lg border border-border bg-card p-4">
                    <p class="text-xs text-muted-foreground uppercase tracking-wider">Taux moyen</p>
                    <p class="text-2xl font-bold text-primary mt-1">{{ number_format($stats['avg_rate'] ?? 0, 2) }}%</p>
                </div>
                <div class="rounded-lg border border-border bg-card p-4">
                    <p class="text-xs text-muted-foreground uppercase tracking-wider">Taux max</p>
                    <p class="text-2xl font-bold text-emerald-600 mt-1">{{ number_format($stats['max_rate'] ?? 0, 2) }}%</p>
                </div>
                <div class="rounded-lg border border-border bg-card p-4">
                    <p class="text-xs text-muted-foreground uppercase tracking-wider">Émetteurs</p>
                    <p class="text-2xl font-bold mt-1">{{ $stats['countries'] }}</p>
                </div>
                <div class="col-span-2 md:col-span-1 rounded-lg border border-primary/30 bg-primary/5 p-4">
                    <p class="text-xs text-primary uppercase tracking-wider font-semibold">Récents (30j)</p>
                    <p class="text-2xl font-bold text-primary mt-1">{{ $stats['recent'] }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Filtres + recherche + tri ─────────────────────────────────── --}}
    <section class="pb-6">
        <div class="container mx-auto px-4">
            <div class="rounded-xl border border-border bg-card p-4 md:p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 mb-3">
                    {{-- Recherche --}}
                    <div class="lg:col-span-2 relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="search" wire:model.live.debounce.300ms="searchTerm"
                            placeholder="Rechercher par nom, émetteur, pays, ISIN…"
                            class="w-full pl-9 pr-3 py-2 rounded-lg border border-border bg-background text-sm focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>
                    <select wire:model.live="countryFilter" class="px-3 py-2 rounded-lg border border-border bg-background text-sm focus:ring-2 focus:ring-primary">
                        <option value="all">Tous les pays</option>
                        @foreach($availableCountries as $c)
                            <option value="{{ $c }}">{{ $c }}</option>
                        @endforeach
                    </select>
                    <select wire:model.live="typeFilter" class="px-3 py-2 rounded-lg border border-border bg-background text-sm focus:ring-2 focus:ring-primary">
                        <option value="all">Tous les types</option>
                        <option value="BAT">BAT (Bon du Trésor)</option>
                        <option value="OAT">OAT (Obligation)</option>
                        <option value="OATR">OATR (Réouverture)</option>
                        <option value="OATI">OATI (Sukuk)</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <select wire:model.live="maturityFilter" class="px-3 py-2 rounded-lg border border-border bg-background text-sm focus:ring-2 focus:ring-primary">
                        <option value="all">Toutes maturités</option>
                        <option value="short">Court (≤ 1 an)</option>
                        <option value="medium">Moyen (2-5 ans)</option>
                        <option value="long">Long (≥ 6 ans)</option>
                    </select>
                    <select wire:model.live="riskFilter" class="px-3 py-2 rounded-lg border border-border bg-background text-sm focus:ring-2 focus:ring-primary">
                        <option value="all">Tous risques</option>
                        <option value="low">Risque faible</option>
                        <option value="medium">Risque moyen</option>
                        <option value="high">Risque élevé</option>
                    </select>
                    <select wire:model.live="sortBy" class="px-3 py-2 rounded-lg border border-border bg-background text-sm focus:ring-2 focus:ring-primary">
                        <option value="auction_date">Date d'adjudication</option>
                        <option value="interest_rate">Taux d'intérêt</option>
                        <option value="maturity_date">Échéance</option>
                        <option value="yield_to_maturity">Rendement</option>
                    </select>
                    <div class="flex gap-2">
                        <select wire:model.live="sortDirection" class="flex-1 px-3 py-2 rounded-lg border border-border bg-background text-sm focus:ring-2 focus:ring-primary">
                            <option value="desc">↓ Décroissant</option>
                            <option value="asc">↑ Croissant</option>
                        </select>
                        <button wire:click="resetFilters" type="button"
                            class="px-3 py-2 rounded-lg border border-border hover:bg-muted transition-colors text-sm"
                            title="Réinitialiser les filtres">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </button>
                    </div>
                </div>
                <p class="text-xs text-muted-foreground mt-3">
                    <strong>{{ $bonds->count() }}</strong> titre{{ $bonds->count() > 1 ? 's' : '' }} correspondant aux filtres
                </p>
            </div>
        </div>
    </section>

    {{-- Liste des obligations ──────────────────────────────────────── --}}
    <section class="pb-12">
        <div class="container mx-auto px-4">
            @if($bonds->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($bonds as $bond)
                        @php
                            $bondType = explode(' ', $bond->name)[0] ?? 'OAT';
                            $isRecent = $bond->auction_date && $bond->auction_date->gte(now()->subDays(30));
                        @endphp
                        <div wire:click="showBondDetails({{ $bond->id }})"
                             class="cursor-pointer group bg-card rounded-xl border border-border hover:border-primary/50 hover:shadow-lg transition-all overflow-hidden">
                            <div class="p-5">
                                <div class="flex items-start justify-between mb-3 gap-2">
                                    <div class="flex flex-wrap gap-1.5">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium border {{ $typeBadge[$bondType]['class'] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $bondType }}
                                        </span>
                                        @if($isRecent)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-primary/10 text-primary border border-primary/20">
                                                🔥 Récent
                                            </span>
                                        @endif
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium border {{ $riskBadge[$bond->risk_level]['class'] ?? 'bg-gray-100' }}">
                                        {{ $riskBadge[$bond->risk_level]['label'] ?? $bond->risk_level }}
                                    </span>
                                </div>

                                <h3 class="font-bold text-foreground mb-1 line-clamp-2 group-hover:text-primary transition-colors">
                                    {{ $bond->country }} — {{ $bond->maturity_years }} an{{ $bond->maturity_years > 1 ? 's' : '' }}
                                </h3>
                                <p class="text-xs text-muted-foreground mb-4 line-clamp-1">{{ $bond->issuer }}</p>

                                <div class="grid grid-cols-2 gap-3 mb-4">
                                    <div>
                                        <p class="text-xs text-muted-foreground">Taux</p>
                                        <p class="text-xl font-bold text-primary">{{ number_format($bond->interest_rate, 2) }}%</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-muted-foreground">Échéance</p>
                                        <p class="text-sm font-semibold">{{ $bond->maturity_date?->format('d/m/Y') }}</p>
                                        <p class="text-xs text-muted-foreground">{{ $bond->remaining_years }} ans restants</p>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between pt-3 border-t border-border text-xs">
                                    <span class="text-muted-foreground">
                                        Min : {{ number_format($bond->minimum_investment ?? 10000, 0, ',', ' ') }} FCFA
                                    </span>
                                    <span class="text-primary font-medium inline-flex items-center gap-1 group-hover:underline">
                                        Détails
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16 bg-muted/20 rounded-xl border border-dashed border-border">
                    <svg class="mx-auto w-14 h-14 text-muted-foreground opacity-40 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    @if($searchTerm !== '' || $countryFilter !== 'all' || $typeFilter !== 'all' || $riskFilter !== 'all' || $maturityFilter !== 'all')
                        <h3 class="text-lg font-semibold mb-2">Aucun titre ne correspond aux filtres</h3>
                        <p class="text-sm text-muted-foreground mb-4">Essayez d'élargir votre recherche.</p>
                        <button wire:click="resetFilters" class="px-4 py-2 rounded-lg bg-primary text-primary-foreground hover:bg-primary/90 transition-colors text-sm font-medium">
                            Réinitialiser les filtres
                        </button>
                    @else
                        <h3 class="text-lg font-semibold mb-2">Aucune obligation synchronisée pour le moment</h3>
                        <p class="text-sm text-muted-foreground mb-4">
                            Les données sont synchronisées automatiquement depuis UMOA-Titres deux fois par jour ouvré.
                        </p>
                        <button wire:click="refreshData" wire:loading.attr="disabled"
                            class="px-4 py-2 rounded-lg bg-primary text-primary-foreground hover:bg-primary/90 transition-colors text-sm font-medium">
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
                        Toutes les obligations affichées proviennent des <strong>adjudications officielles UMOA-Titres</strong>, l'agence régionale d'appui à l'émission des titres publics de l'UEMOA. Aucune saisie manuelle, aucune donnée synthétique.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
                    <div class="rounded-lg border border-border bg-card p-6">
                        <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-xs font-bold text-primary uppercase tracking-wide">Étape 1</span>
                        <h3 class="font-bold text-foreground mt-1 mb-2">Émission primaire</h3>
                        <p class="text-sm text-muted-foreground">
                            Les 8 États UEMOA (Bénin, Burkina Faso, Côte d'Ivoire, Guinée-Bissau, Mali, Niger, Sénégal, Togo) lèvent des fonds via des adjudications hebdomadaires organisées par
                            <a href="https://www.umoatitres.org" target="_blank" rel="noopener noreferrer" class="text-primary hover:underline font-medium">UMOA-Titres</a>.
                        </p>
                    </div>
                    <div class="rounded-lg border border-border bg-card p-6">
                        <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/></svg>
                        </div>
                        <span class="text-xs font-bold text-primary uppercase tracking-wide">Étape 2</span>
                        <h3 class="font-bold text-foreground mt-1 mb-2">Synchronisation</h3>
                        <p class="text-sm text-muted-foreground">
                            Notre tâche planifiée <code class="text-xs bg-muted px-1.5 py-0.5 rounded">umoa:sync</code> scanne chaque jour ouvré (8h et 20h, heure d'Abidjan) le calendrier des émissions et les résultats d'adjudications. Elle met à jour automatiquement la base <code class="text-xs bg-muted px-1.5 py-0.5 rounded">government_bonds</code>.
                        </p>
                    </div>
                    <div class="rounded-lg border border-border bg-card p-6">
                        <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <span class="text-xs font-bold text-primary uppercase tracking-wide">Étape 3</span>
                        <h3 class="font-bold text-foreground mt-1 mb-2">Affichage & simulation</h3>
                        <p class="text-sm text-muted-foreground">
                            Les titres sont filtrés (échus masqués), présentés avec leur source d'origine et un calculateur intégré. Chaque carte renvoie vers la page UMOA-Titres correspondante pour vérification.
                        </p>
                    </div>
                </div>

                <div class="rounded-lg border border-border bg-card p-6">
                    <h3 class="font-bold text-foreground mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        Acteurs institutionnels
                    </h3>
                    <ul class="space-y-2 text-sm">
                        <li class="flex items-start gap-3">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary/10 text-primary font-bold text-xs shrink-0">1</span>
                            <div>
                                <a href="https://www.umoatitres.org" target="_blank" rel="noopener noreferrer" class="font-semibold text-primary hover:underline">UMOA-Titres</a>
                                <span class="text-muted-foreground"> — agence régionale d'appui à l'émission et à la gestion des titres publics (source primaire)</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary/10 text-primary font-bold text-xs shrink-0">2</span>
                            <div>
                                <a href="https://www.bceao.int" target="_blank" rel="noopener noreferrer" class="font-semibold text-primary hover:underline">BCEAO</a>
                                <span class="text-muted-foreground"> — Banque Centrale des États de l'Afrique de l'Ouest, organise les adjudications</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary/10 text-primary font-bold text-xs shrink-0">3</span>
                            <div>
                                <a href="https://www.crepmf.org" target="_blank" rel="noopener noreferrer" class="font-semibold text-primary hover:underline">CREPMF</a>
                                <span class="text-muted-foreground"> — Conseil Régional de l'Épargne Publique et des Marchés Financiers (autorité de régulation)</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary/10 text-primary font-bold text-xs shrink-0">4</span>
                            <div>
                                <a href="https://www.brvm.org" target="_blank" rel="noopener noreferrer" class="font-semibold text-primary hover:underline">BRVM</a>
                                <span class="text-muted-foreground"> — Bourse Régionale des Valeurs Mobilières, marché secondaire de négociation</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- Avantages ─────────────────────────────────────────────────── --}}
    <section class="py-14">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto">
                <div class="text-center mb-10">
                    <h2 class="text-2xl md:text-3xl font-bold mb-3">Pourquoi investir dans les <span class="text-primary">obligations UEMOA</span> ?</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-card rounded-xl p-6 border border-border">
                        <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <h3 class="font-bold mb-2">Garantie souveraine</h3>
                        <p class="text-sm text-muted-foreground">Remboursement garanti par l'État émetteur et la monnaie commune FCFA (arrimage à l'euro).</p>
                    </div>
                    <div class="bg-card rounded-xl p-6 border border-border">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="font-bold mb-2">Rendement compétitif</h3>
                        <p class="text-sm text-muted-foreground">Taux typiques de 3% à 7% selon la durée et le pays, souvent supérieurs aux livrets bancaires classiques.</p>
                    </div>
                    <div class="bg-card rounded-xl p-6 border border-border">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                        <h3 class="font-bold mb-2">Revenus prévisibles</h3>
                        <p class="text-sm text-muted-foreground">Coupons fixes (OAT) ou intérêts précomptés (BAT) versés à échéances connues dès la souscription.</p>
                    </div>
                </div>

                {{-- Disclaimer --}}
                <div class="mt-8 rounded-lg border border-amber-200 bg-amber-50 p-5">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M4.93 19h14.14a2 2 0 001.71-3L13.71 4a2 2 0 00-3.42 0L3.22 16a2 2 0 001.71 3z"/></svg>
                        <div class="text-sm text-amber-900">
                            <strong class="block mb-1">Avertissement risques</strong>
                            Tout investissement comporte des risques : défaut souverain, risque de taux, risque de liquidité sur le marché secondaire, inflation. Les performances passées ne préjugent pas des performances futures. Pour toute décision, consultez une <strong>SGI agréée par le CREPMF</strong> et vérifiez les informations sur <a href="https://www.umoatitres.org" target="_blank" rel="noopener noreferrer" class="underline font-medium">umoatitres.org</a>.
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
                    <p class="text-lg text-muted-foreground">Nos experts vous accompagnent dans votre stratégie d'investissement obligataire</p>
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
