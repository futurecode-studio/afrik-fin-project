<div class="bg-[#f9f9ff] min-h-[70vh]">
    <section class="max-w-[1100px] mx-auto px-5 lg:px-8 py-14 lg:py-20">
        <p class="text-xs font-semibold tracking-widest uppercase text-[#0a2e8c]">Outils</p>
        <h1 class="text-3xl lg:text-4xl font-extrabold text-[#001a61] mt-2">Simulateur de Croissance du Patrimoine</h1>
        <p class="text-[#444652] mt-2">Intérêts composés — estimation indicative, hors fiscalité et frais.</p>

        <div class="mt-10 grid lg:grid-cols-2 gap-8">
            <div class="bg-white border border-[#c5c5d4] rounded-xl p-6 space-y-5">
                <div>
                    <label class="block text-xs font-semibold tracking-wider text-[#757683] mb-1">Simulez votre investissement parmi une sélection de nos FCP : Actions 13% prévisionnel, Obligations 6% et Diversifiés 8%.</label>
                    <select wire:model.live="fcpType" class="w-full rounded-lg border border-[#c5c5d4] px-3 py-2.5 bg-[#f9f9ff] focus:border-[#001a61] outline-none">
                        @foreach ($availableTypes as $type)
                            <option value="{{ $type }}">FCP {{ $type }} — {{ number_format($rates[$type] ?? 0, 0) }} %{{ $type === 'Actions' ? ' prévisionnel' : '' }}</option>
                        @endforeach
                    </select>
                    <p class="mt-2 text-xs text-[#757683]">{{ $typeDescription }} Taux indicatif : {{ number_format($typeRate, 0, ',', ' ') }} % / an.</p>
                </div>
                <div>
                    <label class="block text-xs font-semibold tracking-wider uppercase text-[#757683] mb-1">Capital initial (FCFA)</label>
                    <input type="number" wire:model.live="capital" min="0" class="w-full rounded-lg border border-[#c5c5d4] px-3 py-2.5 bg-[#f9f9ff] focus:border-[#001a61] outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold tracking-wider uppercase text-[#757683] mb-1">Versement mensuel (FCFA)</label>
                    <input type="number" wire:model.live="versement" min="0" class="w-full rounded-lg border border-[#c5c5d4] px-3 py-2.5 bg-[#f9f9ff] focus:border-[#001a61] outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold tracking-wider uppercase text-[#757683] mb-1">Durée (années)</label>
                    <input type="number" wire:model.live="annees" min="0" max="50" class="w-full rounded-lg border border-[#c5c5d4] px-3 py-2.5 bg-[#f9f9ff] focus:border-[#001a61] outline-none">
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
                    <div class="mb-3">
                        <p class="text-sm font-bold text-[#001a61]">Projection FCP {{ $fcpType }}</p>
                        <p class="text-xs text-[#757683]">Horizon : {{ $annees }} ans — {{ number_format($typeRate, 0) }} %{{ $fcpType === 'Actions' ? ' prévisionnel' : '' }} / an</p>
                    </div>
                    <div class="relative h-72">
                        <canvas
                            id="compoundGrowthChart"
                            class="w-full h-full"
                            data-chart='@json($chart)'
                            aria-label="Projection FCP"></canvas>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('inscription') }}"
                        class="inline-flex items-center gap-2 bg-[#ffbf00] text-[#261a00] font-extrabold px-5 py-3 rounded-xl hover:brightness-95 transition">
                        S’inscrire
                    </a>
                    <a href="{{ route('mise-en-relation') }}"
                        class="inline-flex items-center gap-2 bg-[#001a61] text-white font-bold px-5 py-3 rounded-xl hover:bg-[#0a2e8c] transition">
                        Être accompagné
                    </a>
                </div>
            </div>
        </div>

        <div class="mt-8 bg-white border border-[#c5c5d4] rounded-xl p-5">
            <h2 class="text-lg font-bold text-[#001a61] mb-3">Lecture de la simulation</h2>
            <div class="space-y-3 text-sm text-[#444652]">
                <p>Le simulateur utilise des taux indicatifs par classe d’actifs : <strong>FCP Actions 13 % prévisionnel</strong>, <strong>FCP Obligations 6 %</strong> et <strong>FCP Diversifiés 8 %</strong>.</p>
                <p>Ces taux sont indicatifs et ne correspondent pas aux fonds d’une SGO particulière. Africaine des Finances travaille avec plusieurs partenaires agréés.</p>
                <p>Les performances passées ne préjugent pas des performances futures. Consultez toujours la documentation officielle du fonds avant toute souscription.</p>
            </div>
        </div>

        <p class="text-xs text-[#757683] mt-8">Simulation pédagogique. Pour investir, <a href="{{ route('mise-en-relation') }}" class="text-[#001a61] font-semibold underline">demandez une mise en relation</a>.</p>
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
                labels: data.map(point => point.year),
                datasets: [{
                    label: 'Capital projeté',
                    data: data.map(point => point.total || 0),
                    borderColor: '#001a61',
                    backgroundColor: 'rgba(0,26,97,0.12)',
                    borderWidth: 2.5,
                    pointRadius: 0,
                    pointHoverRadius: 4,
                    tension: 0.05,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            title: items => items.length ? Number(items[0].label).toLocaleString('fr-FR') + ' an(s)' : '',
                            label: item => ' ' + Number(item.raw).toLocaleString('fr-FR') + ' FCFA'
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
