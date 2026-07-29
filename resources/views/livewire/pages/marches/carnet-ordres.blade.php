<div class="bg-[#f9f9ff] min-h-screen">
    @include('partials.markets-nav')
    <section class="max-w-[1200px] mx-auto px-5 lg:px-8 py-12">
        <h1 class="text-3xl font-extrabold text-[#001a61]">Carnet d’Ordres Direct</h1>
        <p class="text-[#444652] mt-2">Intention d’ordre relayée vers une SGI — <strong>aucune exécution</strong> sur la plateforme.</p>

        <div class="mt-8 grid lg:grid-cols-5 gap-6">
            <div class="lg:col-span-3 bg-white border border-[#c5c5d4] rounded-xl p-6 space-y-4">
                @if ($stock)
                    <div>
                        <p class="text-xs uppercase tracking-wider text-[#757683]">Titre sélectionné</p>
                        <h2 class="text-xl font-extrabold text-[#001a61]">{{ $stock->company_name }} ({{ $stock->symbol }})</h2>
                        <p class="text-sm text-[#444652] mt-1">{{ number_format($stock->current_price, 0, ',', ' ') }} FCFA · {{ number_format($stock->variation_percent, 2, ',', ' ') }}%</p>
                    </div>
                @endif
                <form wire:submit.prevent="prepareSubmit" class="space-y-4">
                    <div>
                        <label class="text-sm font-medium">Symbole</label>
                        <select wire:model.live="symbol" class="w-full mt-1 rounded-lg border-[#c5c5d4]">
                            @foreach ($stocks as $s)
                                <option value="{{ $s->symbol }}">{{ $s->symbol }} — {{ $s->company_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid sm:grid-cols-3 gap-3">
                        <div>
                            <label class="text-sm font-medium">Sens</label>
                            <select wire:model="side" class="w-full mt-1 rounded-lg border-[#c5c5d4]"><option value="buy">Achat</option><option value="sell">Vente</option></select>
                        </div>
                        <div>
                            <label class="text-sm font-medium">Type</label>
                            <select wire:model="order_type" class="w-full mt-1 rounded-lg border-[#c5c5d4]"><option value="limit">Limite</option><option value="market">Marché</option></select>
                        </div>
                        <div>
                            <label class="text-sm font-medium">Quantité</label>
                            <input type="number" wire:model="quantity" min="1" class="w-full mt-1 rounded-lg border-[#c5c5d4]">
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-medium">Prix limite (FCFA)</label>
                        <input type="number" wire:model="limit_price" class="w-full mt-1 rounded-lg border-[#c5c5d4]">
                        <p class="text-[11px] text-[#757683] mt-1">Utilisé uniquement si vous avez déjà un compte SGI.</p>
                    </div>
                    <div class="grid sm:grid-cols-3 gap-3">
                        <div><label class="text-sm font-medium">Nom</label><input wire:model="name" class="w-full mt-1 rounded-lg border-[#c5c5d4]">@error('name')<p class="text-xs text-red-600">{{ $message }}</p>@enderror</div>
                        <div><label class="text-sm font-medium">Email</label><input type="email" wire:model="email" class="w-full mt-1 rounded-lg border-[#c5c5d4]">@error('email')<p class="text-xs text-red-600">{{ $message }}</p>@enderror</div>
                        <div><label class="text-sm font-medium">Téléphone</label><input wire:model="phone" class="w-full mt-1 rounded-lg border-[#c5c5d4]">@error('phone')<p class="text-xs text-red-600">{{ $message }}</p>@enderror</div>
                    </div>
                    <div>
                        <label class="text-sm font-medium">Notes</label>
                        <textarea wire:model="notes" rows="3" class="w-full mt-1 rounded-lg border-[#c5c5d4]"></textarea>
                    </div>
                    <button type="submit" class="w-full py-3 rounded-xl bg-[#001a61] text-white font-bold">Continuer</button>
                </form>
            </div>

            <aside class="lg:col-span-2 space-y-4">
                <div class="bg-white border border-[#c5c5d4] rounded-xl p-5">
                    <h3 class="font-bold text-[#001a61] mb-3">Carnet (volumes du jour)</h3>
                    <ul class="space-y-2 text-sm">
                        @foreach ($book as $b)
                            <li class="flex justify-between border-b border-[#e7eeff] pb-2">
                                <button type="button" wire:click="$set('symbol','{{ $b->symbol }}')" class="font-bold text-[#001a61] hover:underline">{{ $b->symbol }}</button>
                                <span>{{ number_format($b->volume,0,',',' ') }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @if ($myIntents->isNotEmpty())
                    <div class="bg-white border border-[#c5c5d4] rounded-xl p-5">
                        <h3 class="font-bold text-[#001a61] mb-3">Mes intentions</h3>
                        <ul class="space-y-2 text-sm">
                            @foreach ($myIntents as $o)
                                <li class="flex flex-col gap-0.5 border-b border-[#e7eeff] pb-2 last:border-0">
                                    <div class="flex justify-between gap-2">
                                        <span>{{ strtoupper($o->side) }} {{ $o->stock?->symbol }} × {{ $o->quantity }}</span>
                                        <span class="text-[#757683]">{{ $o->statusLabel() }}</span>
                                    </div>
                                    @if ($o->partner)
                                        <span class="text-[11px] text-[#0a2e8c]">SGI : {{ $o->partner->nom }}@if($o->sgi_account_number) · {{ $o->sgi_account_number }}@endif</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </aside>
        </div>
    </section>

    @include('livewire.partials.sgi-order-modal')
</div>
