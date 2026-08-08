<div class="bg-[#f9f9ff] min-h-[70vh]">
    <section class="max-w-[1100px] mx-auto px-5 lg:px-8 py-14 lg:py-20">
        <p class="text-xs font-semibold tracking-widest uppercase text-[#0a2e8c]">Outils</p>
        <h1 class="text-3xl lg:text-4xl font-extrabold text-[#001a61] mt-2">Simulateur de Croissance du Patrimoine</h1>
        <p class="text-[#444652] mt-2">Intérêts composés — estimation indicative, hors fiscalité et frais.</p>

        <div class="mt-10 grid lg:grid-cols-2 gap-8">
            <div class="bg-white border border-[#c5c5d4] rounded-xl p-6 space-y-5">
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
                        <label class="block text-xs font-semibold tracking-wider uppercase text-[#757683] mb-1">Taux annuel (%)</label>
                        <input type="number" step="0.1" wire:model.live="taux" min="0" max="50" class="w-full rounded-lg border border-[#c5c5d4] px-3 py-2.5 bg-[#f9f9ff] focus:border-[#001a61] outline-none">
                    </div>
                </div>
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
                    <p class="text-sm font-bold text-[#001a61] mb-3">Projection annuelle</p>
                    <div class="relative h-56">
                        <canvas id="compoundGrowthChart" class="w-full h-full" data-chart='@json($chart)' aria-label="Projection annuelle du patrimoine"></canvas>
                    </div>
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
        if (!data.length) return;
        if (chart) chart.destroy();
        chart = new Chart(canvas, {
        type: 'line',
        data: {
            labels: data.map(point => 'An ' + point.year),
            datasets: [{
                label: 'Patrimoine estimé',
                data: data.map(point => point.value),
                borderColor: '#0a2e8c',
                backgroundColor: 'rgba(10, 46, 140, 0.12)',
                borderWidth: 2.5,
                pointRadius: 3,
                pointHoverRadius: 5,
                pointBackgroundColor: '#ffbf00',
                tension: 0.3,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: item => ' ' + Number(item.raw).toLocaleString('fr-FR') + ' FCFA' } }
            },
            scales: {
                x: { grid: { display: false } },
                y: { grid: { color: 'rgba(197,197,212,0.4)' }, ticks: { callback: value => Number(value).toLocaleString('fr-FR'), maxTicksLimit: 5 } }
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
