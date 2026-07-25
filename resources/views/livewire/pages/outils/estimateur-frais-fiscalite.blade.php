<div class="bg-[#f9f9ff] min-h-[70vh]">
    <section class="max-w-[900px] mx-auto px-5 lg:px-8 py-14">
        <h1 class="text-3xl font-extrabold text-[#001a61]">Estimateur de Frais et Fiscalité</h1>
        <p class="text-[#444652] mt-2">Simulation indicative (UEMOA) — les barèmes réels dépendent de votre SGI / pays.</p>

        <div class="mt-8 grid lg:grid-cols-2 gap-6">
            <div class="bg-white border border-[#c5c5d4] rounded-xl p-6 space-y-4">
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wider text-[#757683]">Montant de l’opération (FCFA)</label>
                    <input type="number" wire:model.live="montant" class="w-full mt-1 rounded-lg border-[#c5c5d4]">
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wider text-[#757683]">Type d’opération</label>
                    <select wire:model.live="operation" class="w-full mt-1 rounded-lg border-[#c5c5d4]">
                        <option value="achat_actions">Achat d’actions</option>
                        <option value="vente_actions">Vente d’actions</option>
                        <option value="souscription_fcp">Souscription FCP</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div><label class="text-xs font-semibold uppercase text-[#757683]">Courtage %</label><input type="number" step="0.1" wire:model.live="frais_courtage_pct" class="w-full mt-1 rounded-lg border-[#c5c5d4]"></div>
                    <div><label class="text-xs font-semibold uppercase text-[#757683]">Frais SGI %</label><input type="number" step="0.1" wire:model.live="frais_sgi_pct" class="w-full mt-1 rounded-lg border-[#c5c5d4]"></div>
                    <div><label class="text-xs font-semibold uppercase text-[#757683]">TVA %</label><input type="number" step="0.1" wire:model.live="tva_pct" class="w-full mt-1 rounded-lg border-[#c5c5d4]"></div>
                </div>
            </div>
            <div class="space-y-3">
                <div class="bg-[#001a61] text-white rounded-xl p-6">
                    <p class="text-sm text-white/70">Total frais estimés</p>
                    <p class="text-3xl font-extrabold mt-1">{{ number_format($totalFrais, 0, ',', ' ') }} FCFA</p>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-white border border-[#c5c5d4] rounded-xl p-4"><p class="text-xs text-[#757683]">Courtage</p><p class="font-bold text-[#001a61]">{{ number_format($courtage,0,',',' ') }}</p></div>
                    <div class="bg-white border border-[#c5c5d4] rounded-xl p-4"><p class="text-xs text-[#757683]">Frais SGI</p><p class="font-bold text-[#001a61]">{{ number_format($sgi,0,',',' ') }}</p></div>
                    <div class="bg-white border border-[#c5c5d4] rounded-xl p-4"><p class="text-xs text-[#757683]">TVA</p><p class="font-bold text-[#001a61]">{{ number_format($tva,0,',',' ') }}</p></div>
                    <div class="bg-white border border-[#c5c5d4] rounded-xl p-4"><p class="text-xs text-[#757683]">Montant net op.</p><p class="font-bold text-[#001a61]">{{ number_format($net,0,',',' ') }}</p></div>
                </div>
                <p class="text-xs text-[#757683]">Outil pédagogique. Pour un chiffrage contractuel, <a href="{{ route('mise-en-relation') }}" class="underline font-semibold text-[#001a61]">demandez une mise en relation</a>.</p>
            </div>
        </div>
    </section>
</div>
