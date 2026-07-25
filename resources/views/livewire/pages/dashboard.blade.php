@php
    $delta = function (?float $v) {
        if ($v === null) {
            return ['—', 'text-[#757683]'];
        }
        if ($v > 0) {
            return ['+'.$v.'%', 'text-emerald-600'];
        }
        if ($v < 0) {
            return [$v.'%', 'text-red-600'];
        }

        return ['0%', 'text-[#757683]'];
    };
@endphp

<div
    wire:key="admin-dash-{{ $period }}"
    x-data="adminDashboardCharts(@js($charts))"
    x-init="init()"
>
    <div class="mb-8 flex flex-col lg:flex-row lg:items-end justify-between gap-4">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#0a2e8c]">Vue analytique</p>
            <h1 class="text-3xl font-extrabold text-[#001a61] mt-1">Tableau de bord</h1>
            <p class="text-[#444652] mt-2">Performance plateforme · {{ $days }} derniers jours</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @foreach ([7 => '7 j', 30 => '30 j', 90 => '90 j'] as $val => $label)
                <button type="button" wire:click="$set('period', '{{ $val }}')"
                    @class([
                        'px-4 py-2 rounded-xl text-sm font-bold transition',
                        'bg-[#001a61] text-white' => (int) $period === $val,
                        'bg-white/80 border border-[#c5c5d4] text-[#001a61] hover:bg-[#e7eeff]' => (int) $period !== $val,
                    ])>{{ $label }}</button>
            @endforeach
        </div>
    </div>

    {{-- KPIs --}}
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
        @php
            [$revDelta, $revCls] = $delta($kpis['revenue_delta']);
            [$usrDelta, $usrCls] = $delta($kpis['users_delta']);
        @endphp
        <div class="adf-card-static p-5 relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-20 h-20 rounded-full bg-[#ffbf00]/15"></div>
            <p class="text-xs font-bold uppercase text-[#757683]">Revenus (période)</p>
            <p class="text-2xl lg:text-3xl font-extrabold text-[#001a61] mt-2 tabular-nums">
                {{ number_format($kpis['revenue_period'], 0, ',', ' ') }}
                <span class="text-sm font-bold text-[#757683]">FCFA</span>
            </p>
            <p class="text-xs mt-2 {{ $revCls }} font-bold">{{ $revDelta }} vs période préc.</p>
            <p class="text-[11px] text-[#757683] mt-1">Total : {{ number_format($kpis['revenue'], 0, ',', ' ') }} FCFA</p>
        </div>
        <div class="adf-card-static p-5">
            <p class="text-xs font-bold uppercase text-[#757683]">Nouveaux utilisateurs</p>
            <p class="text-2xl lg:text-3xl font-extrabold text-[#001a61] mt-2">{{ $kpis['users_period'] }}</p>
            <p class="text-xs mt-2 {{ $usrCls }} font-bold">{{ $usrDelta }} vs période préc.</p>
            <p class="text-[11px] text-[#757683] mt-1">Total : {{ number_format($kpis['users']) }}</p>
        </div>
        <div class="adf-card-static p-5">
            <p class="text-xs font-bold uppercase text-[#757683]">Inscriptions cours</p>
            <p class="text-2xl lg:text-3xl font-extrabold text-[#001a61] mt-2">{{ $kpis['enrollments_period'] }}</p>
            <p class="text-xs mt-2 text-[#757683]">{{ $kpis['enrollments'] }} au total</p>
            <div class="mt-3 h-1.5 bg-[#e7eeff] rounded-full overflow-hidden">
                <div class="h-full bg-[#ffbf00]" style="width: {{ min(100, $kpis['completion_rate']) }}%"></div>
            </div>
            <p class="text-[11px] text-[#757683] mt-1">Complétion {{ $kpis['completion_rate'] }}%</p>
        </div>
        <div class="adf-card-static p-5">
            <p class="text-xs font-bold uppercase text-[#757683]">Quiz / examens</p>
            <p class="text-2xl lg:text-3xl font-extrabold text-[#001a61] mt-2">{{ $kpis['quiz_avg'] }}%</p>
            <p class="text-xs mt-2 text-[#757683]">Score moyen</p>
            <p class="text-[11px] font-bold text-emerald-700 mt-1">Réussite {{ $kpis['quiz_pass'] }}%</p>
        </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-8">
        @foreach ([
            ['Formations', $kpis['formations'], 'school'],
            ['Événements', $kpis['events'], 'event'],
            ['Articles', $kpis['articles'], 'newspaper'],
            ['Messages ouverts', $kpis['contacts_open'], 'mail'],
            ['Paiements OK', $kpis['payments_ok'], 'check_circle'],
            ['En attente', $kpis['payments_pending'], 'hourglass'],
            ['Échoués', $kpis['payments_failed'], 'error'],
            ['Progression moy.', $kpis['avg_progress'].'%', 'trending_up'],
        ] as [$label, $value, $icon])
            <div class="adf-card-static px-4 py-3 flex items-center gap-3">
                <span class="material-symbols-outlined text-[#001a61]">{{ $icon }}</span>
                <div>
                    <p class="text-[11px] text-[#757683] font-semibold uppercase">{{ $label }}</p>
                    <p class="text-lg font-extrabold text-[#001a61] leading-tight">{{ is_numeric($value) ? number_format($value) : $value }}</p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Charts row 1 --}}
    <div class="grid lg:grid-cols-3 gap-5 mb-5">
        <div class="lg:col-span-2 adf-card-static p-5">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="font-extrabold text-[#001a61]">Revenus</h2>
                    <p class="text-xs text-[#757683]">Paiements complétés · FCFA / jour</p>
                </div>
            </div>
            <div class="h-64 relative">
                <canvas id="chartRevenue"></canvas>
            </div>
        </div>
        <div class="adf-card-static p-5">
            <h2 class="font-extrabold text-[#001a61]">Statut paiements</h2>
            <p class="text-xs text-[#757683] mb-4">Répartition globale</p>
            <div class="h-56 relative flex items-center justify-center">
                <canvas id="chartPayments"></canvas>
            </div>
        </div>
    </div>

    {{-- Charts row 2 --}}
    <div class="grid lg:grid-cols-2 gap-5 mb-5">
        <div class="adf-card-static p-5">
            <h2 class="font-extrabold text-[#001a61]">Croissance</h2>
            <p class="text-xs text-[#757683] mb-4">Utilisateurs & inscriptions / jour</p>
            <div class="h-64 relative">
                <canvas id="chartGrowth"></canvas>
            </div>
        </div>
        <div class="adf-card-static p-5">
            <h2 class="font-extrabold text-[#001a61]">Progression des apprenants</h2>
            <p class="text-xs text-[#757683] mb-4">Répartition par tranche</p>
            <div class="h-64 relative">
                <canvas id="chartProgress"></canvas>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-5 gap-5 mb-5">
        <div class="lg:col-span-3 adf-card-static p-5">
            <h2 class="font-extrabold text-[#001a61]">Top formations</h2>
            <p class="text-xs text-[#757683] mb-4">Par nombre d’inscriptions</p>
            <div class="h-72 relative">
                <canvas id="chartFormations"></canvas>
            </div>
        </div>
        <div class="lg:col-span-2 space-y-5">
            <div class="adf-card-static p-5">
                <h2 class="font-extrabold text-[#001a61] mb-3">Derniers paiements</h2>
                <ul class="space-y-3">
                    @forelse ($recentPayments as $p)
                        <li class="flex items-start justify-between gap-2 text-sm">
                            <div class="min-w-0">
                                <p class="font-semibold text-[#001a61] truncate">{{ $p->user?->name ?? '—' }}</p>
                                <p class="text-xs text-[#757683] truncate">{{ $p->formation?->titre ?? $p->provider }}</p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="font-bold text-[#001a61]">{{ number_format((float) $p->amount, 0, ',', ' ') }}</p>
                                <p @class([
                                    'text-[10px] font-bold uppercase',
                                    'text-emerald-600' => $p->status === 'completed',
                                    'text-amber-600' => $p->status === 'pending',
                                    'text-red-600' => $p->status === 'failed',
                                ])>{{ $p->status }}</p>
                            </div>
                        </li>
                    @empty
                        <li class="text-sm text-[#757683]">Aucun paiement.</li>
                    @endforelse
                </ul>
            </div>
            <div class="adf-card-static p-5">
                <h2 class="font-extrabold text-[#001a61] mb-3">Nouveaux comptes</h2>
                <ul class="space-y-2.5">
                    @forelse ($recentUsers as $u)
                        <li class="flex items-center gap-3 text-sm">
                            <span class="w-8 h-8 rounded-full bg-[#001a61] text-white text-xs font-bold flex items-center justify-center">
                                {{ strtoupper(mb_substr($u->name, 0, 1)) }}
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="font-semibold text-[#001a61] truncate">{{ $u->name }}</p>
                                <p class="text-xs text-[#757683] truncate">{{ $u->email }}</p>
                            </div>
                            <span class="text-[11px] text-[#757683] shrink-0">{{ $u->created_at?->diffForHumans() }}</span>
                        </li>
                    @empty
                        <li class="text-sm text-[#757683]">Aucun utilisateur.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

@script
<script>
    Alpine.data('adminDashboardCharts', (charts) => ({
        charts,
        instances: [],
        init() {
            this.$nextTick(() => this.build());
            this.$watch('$wire.period', () => {
                // Livewire will re-render; Alpine reinits via x-init
            });
        },
        destroy() {
            this.instances.forEach(c => { try { c.destroy(); } catch (e) {} });
            this.instances = [];
        },
        build() {
            this.destroy();
            if (typeof Chart === 'undefined') return;

            const navy = '#001a61';
            const gold = '#ffbf00';
            const soft = '#0a2e8c';
            const grid = 'rgba(197,197,212,0.35)';
            const labels = this.charts.labels || [];

            const mk = (id, config) => {
                const el = document.getElementById(id);
                if (!el) return;
                const c = new Chart(el, config);
                this.instances.push(c);
            };

            mk('chartRevenue', {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label: 'Revenus',
                        data: this.charts.revenue || [],
                        borderColor: navy,
                        backgroundColor: 'rgba(0,26,97,0.12)',
                        fill: true,
                        tension: 0.35,
                        pointRadius: 0,
                        pointHoverRadius: 4,
                        borderWidth: 2.5,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false }, ticks: { maxTicksLimit: 8, color: '#757683', font: { size: 10 } } },
                        y: { grid: { color: grid }, ticks: { color: '#757683', font: { size: 10 }, callback: v => new Intl.NumberFormat('fr-FR', { notation: 'compact' }).format(v) } }
                    }
                }
            });

            mk('chartPayments', {
                type: 'doughnut',
                data: {
                    labels: this.charts.payment_status?.labels || [],
                    datasets: [{
                        data: this.charts.payment_status?.values || [],
                        backgroundColor: [navy, gold, '#ef4444'],
                        borderWidth: 0,
                        hoverOffset: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '68%',
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 11 }, color: '#444652' } }
                    }
                }
            });

            mk('chartGrowth', {
                type: 'bar',
                data: {
                    labels,
                    datasets: [
                        {
                            label: 'Utilisateurs',
                            data: this.charts.users || [],
                            backgroundColor: navy,
                            borderRadius: 4,
                            barPercentage: 0.7,
                        },
                        {
                            label: 'Inscriptions',
                            data: this.charts.enrollments || [],
                            backgroundColor: gold,
                            borderRadius: 4,
                            barPercentage: 0.7,
                        },
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'top', align: 'end', labels: { boxWidth: 10, font: { size: 11 } } } },
                    scales: {
                        x: { grid: { display: false }, ticks: { maxTicksLimit: 8, color: '#757683', font: { size: 10 } } },
                        y: { beginAtZero: true, grid: { color: grid }, ticks: { precision: 0, color: '#757683', font: { size: 10 } } }
                    }
                }
            });

            mk('chartProgress', {
                type: 'bar',
                data: {
                    labels: this.charts.progress_labels || [],
                    datasets: [{
                        label: 'Apprenants',
                        data: this.charts.progress_values || [],
                        backgroundColor: [soft, navy, '#3d5a9e', gold, '#5c6700'],
                        borderRadius: 8,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { beginAtZero: true, grid: { color: grid }, ticks: { precision: 0, color: '#757683' } },
                        y: { grid: { display: false }, ticks: { color: '#001a61', font: { weight: '600', size: 11 } } }
                    }
                }
            });

            mk('chartFormations', {
                type: 'bar',
                data: {
                    labels: this.charts.top_formations?.labels || [],
                    datasets: [{
                        label: 'Inscriptions',
                        data: this.charts.top_formations?.values || [],
                        backgroundColor: 'rgba(0,26,97,0.85)',
                        borderRadius: 8,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { beginAtZero: true, grid: { color: grid }, ticks: { precision: 0 } },
                        y: { grid: { display: false }, ticks: { color: '#001a61', font: { size: 11 } } }
                    }
                }
            });
        }
    }));
</script>
@endscript
