<div>
    {{-- Indicateur de chargement Livewire --}}
    <div wire:loading class="fixed top-0 left-0 right-0 bg-blue-500 text-white text-center py-2 z-50">
        Chargement en cours...
    </div>

    <main class="container mx-auto px-4 py-8">
        {{-- Header avec sélecteur de période --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">Vue d'ensemble</h2>
                <p class="text-muted-foreground">Statistiques clés de la plateforme</p>
            </div>
            <div class="mt-4 md:mt-0">
                <select wire:model.live="period" 
                    class="flex h-10 rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                    <option value="7days">7 derniers jours</option>
                    <option value="30days">30 derniers jours</option>
                    <option value="90days">90 derniers jours</option>
                    <option value="year">Cette année</option>
                </select>
            </div>
        </div>

        {{-- Statistiques principales --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            {{-- Utilisateurs --}}
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                    <h3 class="tracking-tight text-sm font-medium">Utilisateurs</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="h-5 w-5 text-blue-600">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <div class="p-6 pt-0">
                    <div class="text-3xl font-bold">{{ number_format($stats['totalUsers']) }}</div>
                    <p class="text-xs text-muted-foreground mt-1">
                        <span class="text-blue-600 font-semibold">+{{ $stats['newUsers'] }}</span> nouveaux
                    </p>
                </div>
            </div>

            {{-- Articles --}}
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                    <h3 class="tracking-tight text-sm font-medium">Articles</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="h-5 w-5 text-green-600">
                        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                        <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                        <path d="M10 9H8"></path>
                        <path d="M16 13H8"></path>
                        <path d="M16 17H8"></path>
                    </svg>
                </div>
                <div class="p-6 pt-0">
                    <div class="text-3xl font-bold">{{ number_format($stats['totalArticles']) }}</div>
                    <p class="text-xs text-muted-foreground mt-1">
                        <span class="text-green-600 font-semibold">{{ $stats['publishedArticles'] }}</span> publiés
                    </p>
                </div>
            </div>

            {{-- Formations --}}
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                    <h3 class="tracking-tight text-sm font-medium">Formations</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="h-5 w-5 text-purple-600">
                        <path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"></path>
                        <path d="M22 10v6"></path>
                        <path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"></path>
                    </svg>
                </div>
                <div class="p-6 pt-0">
                    <div class="text-3xl font-bold">{{ number_format($stats['totalFormations']) }}</div>
                    <p class="text-xs text-muted-foreground mt-1">Formations disponibles</p>
                </div>
            </div>

            {{-- Transactions --}}
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                    <h3 class="tracking-tight text-sm font-medium">Transactions</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="h-5 w-5 text-emerald-600">
                        <rect width="20" height="14" x="2" y="5" rx="2"></rect>
                        <line x1="2" x2="22" y1="10" y2="10"></line>
                    </svg>
                </div>
                <div class="p-6 pt-0">
                    <div class="text-3xl font-bold">{{ number_format($stats['totalTransactions']) }}</div>
                    <p class="text-xs text-muted-foreground mt-1">
                        <span class="text-emerald-600 font-semibold">{{ $stats['successfulTransactions'] }}</span> réussies
                    </p>
                </div>
            </div>

            {{-- Revenus --}}
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                    <h3 class="tracking-tight text-sm font-medium">Revenus</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="h-5 w-5 text-yellow-600">
                        <line x1="12" x2="12" y1="2" y2="22"></line>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                </div>
                <div class="p-6 pt-0">
                    <div class="text-3xl font-bold">{{ number_format($stats['totalRevenue'], 0, ',', ' ') }}</div>
                    <p class="text-xs text-muted-foreground mt-1">XOF de revenus</p>
                </div>
            </div>

            {{-- Contacts --}}
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                    <h3 class="tracking-tight text-sm font-medium">Contacts</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="h-5 w-5 text-pink-600">
                        <path d="M8 2v4"></path>
                        <path d="M16 2v4"></path>
                        <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                        <path d="M3 10h18"></path>
                    </svg>
                </div>
                <div class="p-6 pt-0">
                    <div class="text-3xl font-bold">{{ number_format($stats['totalContacts']) }}</div>
                    <p class="text-xs text-muted-foreground mt-1">
                        <span class="text-pink-600 font-semibold">{{ $stats['pendingContacts'] }}</span> en attente
                    </p>
                </div>
            </div>

            {{-- Abonnés Newsletter --}}
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                    <h3 class="tracking-tight text-sm font-medium">Abonnés</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="h-5 w-5 text-indigo-600">
                        <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                    </svg>
                </div>
                <div class="p-6 pt-0">
                    <div class="text-3xl font-bold">{{ number_format($stats['totalSubscribers']) }}</div>
                    <p class="text-xs text-muted-foreground mt-1">Newsletter</p>
                </div>
            </div>
        </div>

        {{-- Graphiques --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            {{-- Graphique des revenus --}}
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6">
                    <h3 class="text-xl font-semibold leading-none tracking-tight">Évolution des revenus</h3>
                    <p class="text-sm text-muted-foreground">Revenus quotidiens sur la période sélectionnée</p>
                </div>
                <div class="p-6 pt-0">
                    <canvas id="revenueChart" height="250"></canvas>
                </div>
            </div>

            {{-- Graphique des transactions --}}
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6">
                    <h3 class="text-xl font-semibold leading-none tracking-tight">Transactions quotidiennes</h3>
                    <p class="text-sm text-muted-foreground">Nombre de transactions par jour</p>
                </div>
                <div class="p-6 pt-0">
                    <canvas id="transactionsChart" height="250"></canvas>
                </div>
            </div>
        </div>
        {{-- KPIs et activités récentes --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- KPIs --}}
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6">
                    <h3 class="text-xl font-semibold leading-none tracking-tight">Indicateurs de Performance</h3>
                    <p class="text-sm text-muted-foreground">KPIs clés de la plateforme</p>
                </div>
                <div class="p-6 pt-0">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center border-b pb-3">
                            <span class="text-sm font-medium">Taux de conversion</span>
                            <span class="text-lg font-bold text-accent">{{ $stats['conversionRate'] }}%</span>
                        </div>
                        <div class="flex justify-between items-center border-b pb-3">
                            <span class="text-sm font-medium">Revenu moyen par transaction</span>
                            <span class="text-lg font-bold text-emerald-600">{{ number_format($stats['avgTransactionAmount'], 0, ',', ' ') }} XOF</span>
                        </div>
                        <div class="flex justify-between items-center border-b pb-3">
                            <span class="text-sm font-medium">Utilisateurs actifs</span>
                            <span class="text-lg font-bold text-blue-600">{{ number_format($stats['activeUsers']) }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b pb-3">
                            <span class="text-sm font-medium">Revenus nets (après frais)</span>
                            <span class="text-lg font-bold text-green-600">{{ number_format($stats['netRevenue'], 0, ',', ' ') }} XOF</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium">Total frais</span>
                            <span class="text-lg font-bold text-red-600">{{ number_format($stats['totalFees'], 0, ',', ' ') }} XOF</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Dernières transactions --}}
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6">
                    <h3 class="text-xl font-semibold leading-none tracking-tight">Transactions récentes</h3>
                    <p class="text-sm text-muted-foreground">5 dernières transactions</p>
                </div>
                <div class="p-6 pt-0">
                    <div class="space-y-3">
                        @forelse($recentTransactions as $transaction)
                            <div class="flex items-center justify-between border-b pb-3">
                                <div class="flex-1">
                                    <p class="text-sm font-medium">{{ $transaction->fullname ?? 'N/A' }}</p>
                                    <p class="text-xs text-muted-foreground">{{ $transaction->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold">{{ number_format($transaction->amount, 0, ',', ' ') }} {{ $transaction->currency }}</p>
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold 
                                        {{ $transaction->status === 'succeeded' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                        {{ $transaction->status }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-muted-foreground py-4">Aucune transaction</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistiques du jour --}}
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="rounded-lg border bg-gradient-to-br from-blue-50 to-blue-100 p-6">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="text-sm font-medium text-blue-900">Aujourd'hui</h4>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600">
                        <path d="M8 2v4"></path>
                        <path d="M16 2v4"></path>
                        <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                        <path d="M3 10h18"></path>
                        <path d="M8 14h.01"></path>
                        <path d="M12 14h.01"></path>
                        <path d="M16 14h.01"></path>
                        <path d="M8 18h.01"></path>
                        <path d="M12 18h.01"></path>
                        <path d="M16 18h.01"></path>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-blue-900">{{ $stats['todayUsers'] }}</div>
                <p class="text-xs text-blue-700 mt-1">Nouveaux utilisateurs</p>
            </div>

            <div class="rounded-lg border bg-gradient-to-br from-green-50 to-green-100 p-6">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="text-sm font-medium text-green-900">Transactions</h4>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600">
                        <rect width="20" height="14" x="2" y="5" rx="2"></rect>
                        <line x1="2" x2="22" y1="10" y2="10"></line>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-green-900">{{ $stats['todayTransactions'] }}</div>
                <p class="text-xs text-green-700 mt-1">Transactions aujourd'hui</p>
            </div>

            <div class="rounded-lg border bg-gradient-to-br from-yellow-50 to-yellow-100 p-6">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="text-sm font-medium text-yellow-900">Revenus</h4>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-yellow-600">
                        <line x1="12" x2="12" y1="2" y2="22"></line>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-yellow-900">{{ number_format($stats['todayRevenue'], 0, ',', ' ') }}</div>
                <p class="text-xs text-yellow-700 mt-1">XOF aujourd'hui</p>
            </div>
        </div>
    </main>
</div>

@script
<script>
    let revenueChart = null;
    let transactionsChart = null;

    function createCharts() {
        const chartData = @json($chartData);
        
        // Détruire les graphiques existants s'ils existent
        if (revenueChart) {
            revenueChart.destroy();
        }
        if (transactionsChart) {
            transactionsChart.destroy();
        }

        // Graphique des revenus
        const revenueCtx = document.getElementById('revenueChart');
        if (revenueCtx) {
            revenueChart = new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Revenus (XOF)',
                        data: chartData.revenue,
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + 
                                           context.parsed.y.toLocaleString('fr-FR') + ' XOF';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString('fr-FR');
                                }
                            }
                        }
                    }
                }
            });
        }

        // Graphique des transactions
        const transactionsCtx = document.getElementById('transactionsChart');
        if (transactionsCtx) {
            transactionsChart = new Chart(transactionsCtx, {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Nombre de transactions',
                        data: chartData.transactions,
                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }
    }

    // Créer les graphiques au chargement
    document.addEventListener('DOMContentLoaded', createCharts);

    // Recréer les graphiques après une mise à jour Livewire
    Livewire.hook('morph.updated', () => {
        createCharts();
    });

    // Écouter les événements Livewire
    $wire.on('statistics-updated', () => {
        createCharts();
    });
</script>
@endscript