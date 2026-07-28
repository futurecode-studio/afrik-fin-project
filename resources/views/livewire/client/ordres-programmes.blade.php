<x-client.coming-soon
    title="Bientôt disponible"
    feature="Ordres programmés (seuil, OCO, trailing)"
    icon="bolt"
    description="Cette page prépare le relais d’intentions d’ordres vers une SGI agréée. Aucun ordre n’est exécuté ni transmis pour le moment — le branchement partenaire arrive bientôt."
>
    <div>
        <h1 class="text-3xl font-extrabold text-[#001a61]">Stratégies d'Ordres Programmés</h1>
        <p class="text-[#444652] mt-2 max-w-2xl">Automatisez vos intentions d’ordres (seuil, OCO, trailing). L’exécution sera relayée vers une SGI agréée.</p>

        <div class="mt-8 grid lg:grid-cols-2 gap-6">
            <div class="bg-white border border-[#c5c5d4] rounded-xl p-6">
                @if ($stock)
                    <p class="text-xs uppercase tracking-wider text-[#757683]">Instrument</p>
                    <h2 class="text-xl font-extrabold text-[#001a61]">{{ $stock->company_name }}</h2>
                    <p class="text-sm text-[#444652]">BRVM: {{ $stock->symbol }}</p>
                    <p class="mt-3 text-2xl font-bold text-[#001a61]">{{ number_format($stock->current_price, 2, ',', ' ') }} XOF
                        <span @class(['text-sm font-semibold ml-2', 'text-green-600' => $stock->variation_percent >= 0, 'text-red-600' => $stock->variation_percent < 0])>
                            {{ $stock->variation_percent >= 0 ? '+' : '' }}{{ number_format($stock->variation_percent, 2, ',', ' ') }}%
                        </span>
                    </p>
                    <div class="mt-4 flex items-center gap-2 text-sm text-green-700 bg-green-50 rounded-lg px-3 py-2">
                        <span class="material-symbols-outlined text-base">monitoring</span> Flux marché connecté
                    </div>
                @endif
            </div>

            <div class="bg-white border border-[#c5c5d4] rounded-xl p-6 space-y-4">
                <h3 class="font-bold text-[#001a61] text-lg">Nouvel Ordre</h3>
                <div class="h-10 rounded-lg bg-[#f0f3ff]"></div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="h-10 rounded-lg bg-[#f0f3ff]"></div>
                    <div class="h-10 rounded-lg bg-[#f0f3ff]"></div>
                </div>
                <div class="h-10 rounded-lg bg-[#f0f3ff]"></div>
                <div class="h-12 rounded-xl bg-[#001a61]/40"></div>
            </div>
        </div>

        <div class="mt-8 bg-white border border-[#c5c5d4] rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-[#e7eeff]"><h2 class="font-bold text-[#001a61]">Stratégies Actives</h2></div>
            <p class="px-4 py-8 text-center text-[#757683]">Aucun ordre programmé.</p>
        </div>
    </div>
</x-client.coming-soon>
