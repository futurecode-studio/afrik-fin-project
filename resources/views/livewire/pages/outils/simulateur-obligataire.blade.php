<div class="bg-[#f9f9ff] min-h-[70vh]">
    <section class="max-w-[1100px] mx-auto px-5 lg:px-8 py-14 lg:py-20">
        <p class="text-xs font-semibold tracking-widest uppercase text-[#0a2e8c]">Outils</p>
        <h1 class="text-3xl lg:text-4xl font-extrabold text-[#001a61] mt-2">Simulateur de Rendement Obligataire</h1>
        <p class="text-[#444652] mt-2">Calendrier de flux simplifié (coupon + remboursement du nominal).</p>

        <div class="mt-10 grid lg:grid-cols-5 gap-8">
            <div class="lg:col-span-2 bg-white border border-[#c5c5d4] rounded-xl p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold tracking-wider uppercase text-[#757683] mb-1">Valeur nominale (FCFA)</label>
                    <input type="number" wire:model.live="nominal" min="1000" class="w-full rounded-lg border border-[#c5c5d4] px-3 py-2.5 bg-[#f9f9ff] outline-none focus:border-[#001a61]">
                </div>
                <div>
                    <label class="block text-xs font-semibold tracking-wider uppercase text-[#757683] mb-1">Taux coupon (%)</label>
                    <input type="number" step="0.1" wire:model.live="coupon" min="0" class="w-full rounded-lg border border-[#c5c5d4] px-3 py-2.5 bg-[#f9f9ff] outline-none focus:border-[#001a61]">
                </div>
                <div>
                    <label class="block text-xs font-semibold tracking-wider uppercase text-[#757683] mb-1">Prix d’achat (%)</label>
                    <input type="number" step="0.1" wire:model.live="prixPct" min="1" class="w-full rounded-lg border border-[#c5c5d4] px-3 py-2.5 bg-[#f9f9ff] outline-none focus:border-[#001a61]">
                </div>
                <div>
                    <label class="block text-xs font-semibold tracking-wider uppercase text-[#757683] mb-1">Fréquence de paiement</label>
                    <select wire:model.live="frequence" class="w-full rounded-lg border border-[#c5c5d4] px-3 py-2.5 bg-[#f9f9ff] outline-none focus:border-[#001a61]">
                        <option value="annuel">Annuel</option>
                        <option value="semestriel">Semestriel</option>
                        <option value="trimestriel">Trimestriel</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold tracking-wider uppercase text-[#757683] mb-1">Maturité (années)</label>
                    <input type="number" wire:model.live="maturite" min="1" max="40" class="w-full rounded-lg border border-[#c5c5d4] px-3 py-2.5 bg-[#f9f9ff] outline-none focus:border-[#001a61]">
                </div>
            </div>

            <div class="lg:col-span-3 space-y-4">
                <div class="grid sm:grid-cols-3 gap-3">
                    <div class="bg-white border border-[#c5c5d4] rounded-xl p-4">
                        <p class="text-xs text-[#757683]">Prix d’achat</p>
                        <p class="font-bold text-[#001a61] text-lg">{{ number_format($purchase, 0, ',', ' ') }}</p>
                    </div>
                    <div class="bg-white border border-[#c5c5d4] rounded-xl p-4">
                        <p class="text-xs text-[#757683]">Coupons totaux</p>
                        <p class="font-bold text-[#001a61] text-lg">{{ number_format($totalCoupons, 0, ',', ' ') }}</p>
                    </div>
                    <div class="bg-white border border-[#c5c5d4] rounded-xl p-4">
                        <p class="text-xs text-[#757683]">Gain estimé</p>
                        <p class="font-bold text-[#0a2e8c] text-lg">{{ number_format($profit, 0, ',', ' ') }}</p>
                    </div>
                </div>
                <div class="bg-[#001a61] text-white rounded-xl p-5 flex justify-between items-end">
                    <div>
                        <p class="text-sm text-white/70">Rendement coupon approx. / an</p>
                        <p class="text-3xl font-extrabold">{{ number_format($yieldApprox, 2, ',', ' ') }}%</p>
                    </div>
                    <p class="text-sm text-white/70">Total encaissé {{ number_format($totalReceived, 0, ',', ' ') }} FCFA</p>
                </div>

                <div class="bg-white border border-[#c5c5d4] rounded-xl overflow-hidden">
                    <div class="px-5 py-3 border-b border-[#c5c5d4]">
                        <h2 class="font-bold text-[#001a61]">Calendrier des flux de trésorerie</h2>
                    </div>
                    <div class="overflow-x-auto max-h-80">
                        <table class="w-full text-sm">
                            <thead class="bg-[#eef3ff] sticky top-0">
                                <tr>
                                    <th class="text-left px-4 py-3 text-[#001a61] text-xs uppercase tracking-wider">Période</th>
                                    <th class="text-right px-4 py-3 text-[#001a61] text-xs uppercase tracking-wider">Coupon</th>
                                    <th class="text-right px-4 py-3 text-[#001a61] text-xs uppercase tracking-wider">Principal</th>
                                    <th class="text-right px-4 py-3 text-[#001a61] text-xs uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($flows as $f)
                                    <tr class="border-t border-[#c5c5d4]">
                                        <td class="px-4 py-2.5">{{ $f['label'] }}</td>
                                        <td class="px-4 py-2.5 text-right">{{ number_format($f['coupon'], 0, ',', ' ') }}</td>
                                        <td class="px-4 py-2.5 text-right">{{ number_format($f['principal'], 0, ',', ' ') }}</td>
                                        <td class="px-4 py-2.5 text-right font-semibold text-[#001a61]">{{ number_format($f['total'], 0, ',', ' ') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <p class="text-xs text-[#757683] mt-6">Outil pédagogique. Pour investir sur les obligations UEMOA, <a class="underline font-semibold text-[#001a61]" href="{{ route('mise-en-relation') }}">demandez une mise en relation</a>.</p>
    </section>
</div>
