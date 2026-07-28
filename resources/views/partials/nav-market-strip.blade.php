{{-- Panneau marché BRVM : indices + graphique + hausses/baisses --}}
@php
    $navIndices = collect($navMarket['indices'] ?? []);
    $navGainers = collect($navMarket['gainers'] ?? []);
    $navLosers = collect($navMarket['losers'] ?? []);
    $indicesUrl = route('marches.indices');
@endphp
@if($navIndices->isNotEmpty() || $navGainers->isNotEmpty())
<div
    class="bg-[#f2f5fb] border-t border-[#c5c5d4]/70 text-[#131c2a]"
    x-data="navMarketPanel(@js([
        'indices' => $navIndices->values()->all(),
        'periods' => [
            ['id' => 7, 'label' => '7j'],
            ['id' => 30, 'label' => '1m'],
            ['id' => 90, 'label' => '3m'],
            ['id' => 180, 'label' => '6m'],
        ],
        'ficheBase' => $indicesUrl,
    ]))"
>
    <div class="max-w-[1280px] mx-auto px-3 lg:px-16 py-1">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-1.5 items-stretch">
            {{-- Indices (même style que hausses / baisses) --}}
            <div class="lg:col-span-2 rounded-md border border-[#c5c5d4] bg-[#e8eefc] px-1 py-1 flex flex-col">
                <p class="text-[8px] font-extrabold uppercase tracking-wide text-[#001a61] mb-0.5 flex items-center gap-0.5 leading-none">
                    <span class="material-symbols-outlined text-[11px]">monitoring</span>
                    Indices
                </p>
                <div class="flex flex-col gap-0.5 flex-1">
                    <template x-for="idx in indices" :key="idx.key">
                        <button
                            type="button"
                            @click="selectIndex(idx.key)"
                            class="w-full text-left rounded border px-1 py-0.5 transition-colors leading-tight"
                            :class="activeKey === idx.key
                                ? 'bg-[#001a61] border-[#001a61] text-white'
                                : 'bg-white border-[#c5c5d4] hover:border-[#001a61]/40'"
                        >
                            <div class="flex items-center justify-between gap-1">
                                <span
                                    class="text-[9px] font-extrabold"
                                    :class="activeKey === idx.key ? 'text-[#ffbf00]' : 'text-[#001a61]'"
                                    x-text="idx.label"
                                ></span>
                                <span
                                    class="text-[9px] font-bold tabular-nums shrink-0"
                                    :class="activeKey === idx.key
                                        ? (idx.variation >= 0 ? 'text-emerald-300' : 'text-red-300')
                                        : (idx.variation >= 0 ? 'text-emerald-600' : 'text-red-600')"
                                    x-text="(idx.variation >= 0 ? '+' : '') + Number(idx.variation).toFixed(2) + '%'"
                                ></span>
                            </div>
                            <p
                                class="text-[8px] tabular-nums leading-none"
                                :class="activeKey === idx.key ? 'text-white/70' : 'text-[#757683]'"
                                x-text="formatPts(idx.value)"
                            ></p>
                        </button>
                    </template>
                </div>
            </div>

            {{-- Graphique principal --}}
            <div class="lg:col-span-6 rounded-md border border-[#c5c5d4] bg-white overflow-hidden flex flex-col min-h-[88px]">
                <div class="flex flex-wrap items-center justify-between gap-1 px-2 pt-1 pb-0">
                    <div class="min-w-0 flex flex-wrap items-baseline gap-x-1.5 gap-y-0">
                        <h3 class="text-[10px] font-extrabold text-[#001a61] truncate" x-text="active?.label"></h3>
                        <span class="text-[10px] font-bold tabular-nums text-[#131c2a]" x-text="formatPts(active?.value)"></span>
                        <span
                            class="text-[9px] font-bold tabular-nums"
                            :class="(active?.variation ?? 0) >= 0 ? 'text-emerald-600' : 'text-red-600'"
                            x-text="active ? ((active.variation >= 0 ? '+' : '') + Number(active.variation).toFixed(2) + '%') : ''"
                        ></span>
                    </div>
                    <div class="flex items-center gap-1">
                        <div class="inline-flex rounded border border-[#c5c5d4] bg-[#f4f6fc] p-px">
                            <template x-for="p in periods" :key="p.id">
                                <button
                                    type="button"
                                    @click="setPeriod(p.id)"
                                    class="px-1 py-px text-[8px] font-bold rounded-sm transition-colors leading-none"
                                    :class="period === p.id ? 'bg-[#001a61] text-white' : 'text-[#444652] hover:text-[#001a61]'"
                                    x-text="p.label"
                                ></button>
                            </template>
                        </div>
                        <a
                            :href="ficheUrl"
                            class="inline-flex items-center gap-0.5 rounded bg-[#ffbf00] text-[#001a61] text-[8px] font-extrabold uppercase tracking-wide px-1.5 py-0.5 hover:bg-[#fbbc00] transition-colors whitespace-nowrap leading-none"
                        >
                            Fiche
                            <span class="material-symbols-outlined text-[11px]">open_in_new</span>
                        </a>
                    </div>
                </div>
                <div class="relative flex-1 min-h-[56px] px-1 pb-1">
                    <canvas x-ref="chart" class="w-full h-full" style="display:block;width:100%;height:100%;" aria-label="Graphique indice BRVM"></canvas>
                </div>
            </div>

            {{-- Hausses / Baisses en colonnes --}}
            <div class="lg:col-span-4 grid grid-cols-2 gap-1.5">
                <div class="rounded-md border border-emerald-100 bg-emerald-50/70 px-1 py-1 flex flex-col">
                    <p class="text-[8px] font-extrabold uppercase tracking-wide text-emerald-800 mb-0.5 flex items-center gap-0.5 leading-none">
                        <span class="material-symbols-outlined text-[11px]">trending_up</span>
                        Hausses
                    </p>
                    <div class="flex flex-col gap-0.5 flex-1">
                        @forelse($navGainers as $s)
                            <a href="{{ route('marches.action', $s['symbol']) }}"
                                class="rounded border border-emerald-100 bg-white px-1 py-0.5 hover:border-emerald-300 transition-colors leading-tight">
                                <div class="flex items-center justify-between gap-1">
                                    <span class="text-[9px] font-extrabold text-[#001a61]">{{ $s['symbol'] }}</span>
                                    <span class="text-[9px] font-bold text-emerald-600 tabular-nums">+{{ number_format((float) $s['variation_percent'], 2) }}%</span>
                                </div>
                            </a>
                        @empty
                            <p class="text-[9px] text-[#757683]">—</p>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-md border border-red-100 bg-red-50/70 px-1 py-1 flex flex-col">
                    <p class="text-[8px] font-extrabold uppercase tracking-wide text-red-800 mb-0.5 flex items-center gap-0.5 leading-none">
                        <span class="material-symbols-outlined text-[11px]">trending_down</span>
                        Baisses
                    </p>
                    <div class="flex flex-col gap-0.5 flex-1">
                        @forelse($navLosers as $s)
                            <a href="{{ route('marches.action', $s['symbol']) }}"
                                class="rounded border border-red-100 bg-white px-1 py-0.5 hover:border-red-300 transition-colors leading-tight">
                                <div class="flex items-center justify-between gap-1">
                                    <span class="text-[9px] font-extrabold text-[#001a61]">{{ $s['symbol'] }}</span>
                                    <span class="text-[9px] font-bold text-red-600 tabular-nums">{{ number_format((float) $s['variation_percent'], 2) }}%</span>
                                </div>
                            </a>
                        @empty
                            <p class="text-[9px] text-[#757683]">—</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@once
<script>
window.navMarketPanel = function (payload) {
    return {
        indices: payload.indices || [],
        periods: payload.periods || [],
        ficheBase: payload.ficheBase || '/marches/indices',
        activeKey: (payload.indices && payload.indices[0]) ? payload.indices[0].key : null,
        period: 30,
        chart: null,

        get active() {
            return this.indices.find(i => i.key === this.activeKey) || this.indices[0] || null;
        },

        get ficheUrl() {
            const key = this.active?.key || '';
            return this.ficheBase + (key ? '#' + key.toLowerCase() : '');
        },

        init() {
            this.$nextTick(() => this.renderChart());
        },

        selectIndex(key) {
            if (this.activeKey === key) return;
            this.activeKey = key;
            this.renderChart();
        },

        setPeriod(days) {
            this.period = days;
            this.renderChart();
        },

        formatPts(v) {
            if (v == null || Number.isNaN(Number(v))) return '—';
            return Number(v).toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },

        sparkPath(values) {
            const pts = (values || []).map(Number).filter(n => !Number.isNaN(n));
            if (pts.length < 2) return 'M0 14 L100 14';
            const min = Math.min(...pts);
            const max = Math.max(...pts);
            const span = Math.max(max - min, 0.0001);
            return pts.map((v, i) => {
                const x = (i / (pts.length - 1)) * 100;
                const y = 26 - ((v - min) / span) * 22;
                return (i === 0 ? 'M' : 'L') + x.toFixed(2) + ' ' + y.toFixed(2);
            }).join(' ');
        },

        filteredSeries() {
            const series = this.active?.series || [];
            if (!series.length) return [];
            const cutoff = new Date();
            cutoff.setDate(cutoff.getDate() - this.period);
            const cutStr = cutoff.toISOString().slice(0, 10);
            const filtered = series.filter(p => p.d >= cutStr);
            return filtered.length ? filtered : series.slice(-Math.min(series.length, this.period));
        },

        renderChart() {
            if (typeof Chart === 'undefined' || !this.$refs.chart) return;

            const rows = this.filteredSeries();
            const labels = rows.map(r => {
                const d = new Date(r.d + 'T00:00:00');
                return d.toLocaleDateString('fr-FR', { day: '2-digit', month: 'short' });
            });
            const values = rows.map(r => r.v);
            const up = values.length > 1 ? values[values.length - 1] >= values[0] : (this.active?.variation ?? 0) >= 0;

            if (this.chart) {
                try { this.chart.destroy(); } catch (_) {}
                this.chart = null;
            }

            const canvas = this.$refs.chart;
            canvas.style.width = '100%';
            canvas.style.height = '100%';
            canvas.removeAttribute('width');
            canvas.removeAttribute('height');

            this.chart = new Chart(canvas, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label: this.active?.label || 'Indice',
                        data: values,
                        borderColor: up ? '#001a61' : '#b91c1c',
                        backgroundColor: (ctx) => {
                            const { chart } = ctx;
                            const { ctx: c, chartArea } = chart;
                            if (!chartArea) return 'rgba(0,26,97,0.08)';
                            const g = c.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
                            g.addColorStop(0, up ? 'rgba(0,26,97,0.22)' : 'rgba(185,28,28,0.16)');
                            g.addColorStop(1, 'rgba(0,26,97,0)');
                            return g;
                        },
                        borderWidth: 2,
                        pointRadius: 0,
                        pointHoverRadius: 3,
                        pointHoverBackgroundColor: '#ffbf00',
                        tension: 0.35,
                        fill: true,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: { duration: 280 },
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#001a61',
                            callbacks: {
                                label: (item) => ' ' + Number(item.raw).toLocaleString('fr-FR', { minimumFractionDigits: 2 }) + ' pts'
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: '#757683', maxRotation: 0, autoSkip: true, maxTicksLimit: 5, font: { size: 10 } }
                        },
                        y: {
                            grid: { color: 'rgba(197,197,212,0.4)' },
                            ticks: { color: '#757683', font: { size: 10 }, maxTicksLimit: 4 }
                        }
                    }
                }
            });
        }
    };
};
</script>
@endonce
@endif
