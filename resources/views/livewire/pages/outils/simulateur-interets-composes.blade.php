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
                    <div class="flex items-end gap-1 h-28">
                        @php $max = max(1, collect($chart)->max('value') ?: 1); @endphp
                        @foreach ($chart as $bar)
                            <div class="flex-1 bg-[#0a2e8c]/25 hover:bg-[#ffbf00] rounded-t transition" style="height: {{ max(8, ($bar['value'] / $max) * 100) }}%" title="An {{ $bar['year'] }}: {{ number_format($bar['value'], 0, ',', ' ') }}"></div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <p class="text-xs text-[#757683] mt-8">Simulation pédagogique. Les performances passées ne préjugent pas des performances futures. Pour investir, <a href="{{ route('mise-en-relation') }}" class="text-[#001a61] font-semibold underline">demandez une mise en relation</a>.</p>
    </section>
</div>
