<div class="bg-[#f9f9ff] min-h-[70vh]">
    <section class="max-w-[1100px] mx-auto px-5 lg:px-8 py-14 lg:py-20">
        <p class="text-xs font-semibold tracking-widest uppercase text-[#0a2e8c]">Outils</p>
        <h1 class="text-3xl lg:text-4xl font-extrabold text-[#001a61] mt-2">Simulateur de Croissance du Patrimoine</h1>
        <p class="text-[#444652] mt-2">Intérêts composés — estimation indicative, hors fiscalité et frais.</p>

        <div class="mt-10 grid lg:grid-cols-2 gap-8">
            <div class="bg-white border border-[#c5c5d4] rounded-xl p-6 space-y-5">
                <div>
                    <label class="block text-xs font-semibold tracking-wider uppercase text-[#757683] mb-1">Type de FCP</label>
                    <select wire:model.live="fcpType" class="w-full rounded-lg border border-[#c5c5d4] px-3 py-2.5 bg-[#f9f9ff] focus:border-[#001a61] outline-none">
                        @foreach ($availableTypes as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                    <p class="mt-2 text-xs text-[#757683]">{{ $typeDescription }} Rendement indicatif: {{ number_format($typeRate, 1, ',', ' ') }}% / an.</p>
                </div>
                <div>
                    <label class="block text-xs font-semibold tracking-wider uppercase text-[#757683] mb-1">Capital initial (FCFA)</label>
                    <input type="number" wire:model.live="capital" min="0" class="w-full rounded-lg border border-[#c5c5d4] px-3 py-2.5 bg-[#f9f9ff] focus:border-[#001a61] outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold tracking-wider uppercase text-[#757683] mb-1">Versement mensuel (FCFA)</label>
                    <input type="number" wire:model.live="versement" min="0" class="w-full rounded-lg border border-[#c5c5d4] px-3 py-2.5 bg-[#f9f9ff] focus:border-[#001a61] outline-none">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold tracking-wider uppercase text-[#757683] mb-1">Durée (années)</label>
                        <input type="number" wire:model.live="annees" min="0" max="50" class="w-full rounded-lg border border-[#c5c5d4] px-3 py-2.5 bg-[#f9f9ff] focus:border-[#001a61] outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold tracking-wider uppercase text-[#757683] mb-1">FCP SGO affichés</label>
                        <div class="rounded-lg border border-[#c5c5d4] px-3 py-2.5 bg-[#f9f9ff] text-[#001a61] font-bold">
                            {{ count($selectedFunds) }}
                        </div>
                    </div>
                </div>
                @if ($error)
                    <p class="rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm px-3 py-2">{{ $error }}</p>
                @endif
            </div>

            <div class="space-y-4">
                <div class="bg-[#001a61] text-white rounded-xl p-6">
                    <p class="text-sm text-white/70">Capital estimé à terme</p>
                    <p class="text-3xl font-extrabold mt-1">{{ number_format($future, 0, ',', ' ') }} <span class="text-base font-semibold">FCFA</span></p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white border border-[#c5c5d4] rounded-xl p-5">
                        <p class="text-xs text-[#757683]">Montant investi</p>
                        <p class="text-xl font-bold text-[#001a61] mt-1">{{ number_format($invested, 0, ',', ' ') }}</p>
                    </div>
                    <div class="bg-white border border-[#c5c5d4] rounded-xl p-5">
                        <p class="text-xs text-[#757683]">Intérêts estimés</p>
                        <p class="text-xl font-bold text-[#0a2e8c] mt-1">{{ number_format($gain, 0, ',', ' ') }}</p>
                    </div>
                </div>
                <div class="bg-white border border-[#c5c5d4] rounded-xl p-5">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div>
                            <p class="text-sm font-bold text-[#001a61]">Projection par FCP {{ $fcpType }}</p>
                            <p class="text-xs text-[#757683]">Horizon: {{ $annees }} ans - fréquence: 12 versements/an</p>
                        </div>
                    </div>
                    <div class="relative h-72">
                        <canvas
                            id="compoundGrowthChart"
                            class="w-full h-full"
                            data-chart='@json($chart)'
                            data-summary='@json($summary)'
                            aria-label="Projection par type de FCP"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 grid lg:grid-cols-2 gap-6">
            <div class="bg-white border border-[#c5c5d4] rounded-xl p-5">
                <h2 class="text-lg font-bold text-[#001a61] mb-3">Récapitulatif</h2>
                <div class="border border-[#e0e2ee] rounded-lg overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-[#f9f9ff]">
                            <tr>
                                <th class="text-left px-4 py-3 text-sm font-semibold text-[#444652]">Fonds</th>
                                <th class="text-right px-4 py-3 text-sm font-semibold text-[#444652]">Valeur finale</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($summary as $line)
                                <tr class="border-t border-[#e0e2ee]">
                                    <td class="px-4 py-3">
                                        <p class="text-sm font-semibold text-[#131c2a]">{{ $line['name'] }}</p>
                                        <p class="text-xs text-[#757683]">{{ $line['company'] }}</p>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-right font-semibold">{{ number_format($line['value'], 0, ',', ' ') }}</td>
                                </tr>
                            @empty
                                <tr class="border-t border-[#e0e2ee]">
                                    <td colspan="2" class="px-4 py-8 text-sm text-center text-[#757683]">Aucun FCP disponible pour ce type.</td>
                                </tr>
                            @endforelse
                            <tr class="border-t border-[#e0e2ee] bg-[#f9f9ff]">
                                <td class="px-4 py-3 text-sm font-extrabold text-[#001a61]">Total</td>
                                <td class="px-4 py-3 text-sm text-right font-extrabold text-[#001a61]">{{ number_format($future, 0, ',', ' ') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white border border-[#c5c5d4] rounded-xl p-5">
                <h2 class="text-lg font-bold text-[#001a61] mb-3">Lecture de la simulation</h2>
                <div class="space-y-3 text-sm text-[#444652]">
                    <p>Le capital initial et les versements mensuels sont répartis équitablement entre les FCP du type sélectionné.</p>
                    <p>Chaque courbe représente un FCP référencé chez nos partenaires SGO. La courbe noire en pointillés représente le total simulé.</p>
                    <p>Les taux sont indicatifs par classe d'actifs et ne remplacent pas la documentation officielle de chaque fonds.</p>
                </div>
            </div>
        </div>

        <p class="text-xs text-[#757683] mt-8">Simulation pédagogique. Les performances passées ne préjugent pas des performances futures. Pour investir, <a href="{{ route('mise-en-relation') }}" class="text-[#001a61] font-semibold underline">demandez une mise en relation</a>.</p>
    </section>
</div>

@push('scripts')
<script>
(() => {
    let chart = null;
    function renderChart() {
        const canvas = document.getElementById('compoundGrowthChart');
        if (!canvas || typeof Chart === 'undefined') return;
        const data = JSON.parse(canvas.dataset.chart || '[]');
        const summary = JSON.parse(canvas.dataset.summary || '[]');
        if (!data.length) return;
        if (chart) chart.destroy();
        const colors = ['#0f62fe', '#ef4444', '#059669', '#f59e0b', '#7c3aed', '#0891b2', '#db2777', '#4f46e5', '#16a34a', '#ea580c', '#475569', '#be123c'];
        const datasets = summary.map((fund, index) => ({
            label: fund.name,
            data: data.map(point => point[fund.key] || 0),
            borderColor: colors[index % colors.length],
            backgroundColor: colors[index % colors.length],
            borderWidth: 2,
            pointRadius: 0,
            pointHoverRadius: 4,
            tension: 0.35,
            fill: false,
        }));
        datasets.push({
            label: 'Total',
            data: data.map(point => point.total || 0),
            borderColor: '#111827',
            backgroundColor: '#111827',
            borderWidth: 2.5,
            borderDash: [6, 4],
            pointRadius: 0,
            pointHoverRadius: 4,
            tension: 0.35,
            fill: false,
        });
        chart = new Chart(canvas, {
        type: 'line',
        data: {
            labels: data.map(point => point.year),
            datasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { boxWidth: 12, boxHeight: 12, usePointStyle: true }
                },
                tooltip: {
                    callbacks: {
                        title: items => items.length ? Number(items[0].label).toLocaleString('fr-FR') + ' an(s)' : '',
                        label: item => ' ' + item.dataset.label + ': ' + Number(item.raw).toLocaleString('fr-FR') + ' FCFA'
                    }
                }
            },
            scales: {
                x: {
                    title: { display: true, text: 'Années' },
                    grid: { color: 'rgba(197,197,212,0.28)' },
                    ticks: { maxTicksLimit: 8 }
                },
                y: {
                    title: { display: true, text: 'Valeur (FCFA)' },
                    grid: { color: 'rgba(197,197,212,0.45)' },
                    ticks: {
                        callback: value => Number(value).toLocaleString('fr-FR', { notation: 'compact', compactDisplay: 'short' }),
                        maxTicksLimit: 5
                    }
                }
            }
        }
        });
    }
    renderChart();
    document.addEventListener('livewire:init', () => {
        Livewire.hook('morph.updated', () => setTimeout(renderChart, 50));
    }, { once: true });
})();
</script>
@endpush
