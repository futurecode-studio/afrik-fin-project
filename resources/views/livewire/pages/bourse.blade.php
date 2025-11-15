<main class="flex-1 pt-20">
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

    @if (!$apiConfigured)
        <div class="container mx-auto px-4 pt-4">
            <div class="mb-4 rounded-lg bg-yellow-50 p-4 text-sm text-yellow-800 border border-yellow-200">
                <strong>ℹ️ Mode hors ligne :</strong> L'API BRVM n'est pas configurée. Les données affichées proviennent de la base de données locale.
                <br>Pour activer les données en temps réel, configurez les variables BRVM_API_URL et BRVM_API_KEY dans votre fichier .env
            </div>
        </div>
    @endif

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
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Principales Valeurs</h2>
                <span class="text-sm text-muted-foreground">
                    @if($lastUpdate)
                        Dernière mise à jour: {{ $lastUpdate }}
                    @else
                        Chargement...
                    @endif
                </span>
            </div>
            
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm overflow-hidden border-border">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-muted">
                            <tr>
                                <th class="text-left p-4 font-semibold">Symbole</th>
                                <th class="text-left p-4 font-semibold">Nom</th>
                                <th class="text-right p-4 font-semibold">Cours (FCFA)</th>
                                <th class="text-right p-4 font-semibold">Volume</th>
                                <th class="text-right p-4 font-semibold">Cap. (M)</th>
                                <th class="text-right p-4 font-semibold">Variation</th>
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
                                <td class="p-4 text-right font-semibold">
                                    {{ number_format($stock['current_price'] ?? 0, 0, ',', ' ') }}
                                </td>
                                <td class="p-4 text-right text-muted-foreground">
                                    {{ number_format($stock['volume'] ?? 0) }}
                                </td>
                                <td class="p-4 text-right text-muted-foreground">
                                    @if(isset($stock['market_cap']) && $stock['market_cap'] >= 1000)
                                        {{ number_format($stock['market_cap'] / 1000, 1) }}B
                                    @elseif(isset($stock['market_cap']))
                                        {{ number_format($stock['market_cap'], 0) }}M
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
                                    <button class="text-primary hover:text-primary-light transition-smooth inline-flex items-center gap-1 text-sm font-medium">
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
                                <td colspan="7" class="p-8 text-center text-muted-foreground">
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