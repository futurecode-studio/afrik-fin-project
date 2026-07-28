<div>
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4 mb-8">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-[#0a2e8c]">Marchés &amp; partenaires</p>
            <h1 class="text-3xl font-extrabold text-[#001a61]">SGI / SGO</h1>
            <p class="text-[#444652] mt-2 max-w-2xl">
                Gérez les partenaires agréés et activez les fonctionnalités qui leur sont liées.
                Tant qu’un service est désactivé, le site affiche « Bientôt disponible ».
            </p>
        </div>
        <a href="{{ route('admin.partners') }}"
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#001a61] text-white font-bold hover:bg-[#0a2e8c]">
            <span class="material-symbols-outlined text-base">handshake</span>
            Gérer les partenaires
        </a>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white border border-[#c5c5d4] rounded-xl p-4">
            <p class="text-xs uppercase text-[#757683]">SGI</p>
            <p class="text-3xl font-extrabold text-[#001a61] tabular-nums">{{ $sgiCount }}</p>
        </div>
        <div class="bg-white border border-[#c5c5d4] rounded-xl p-4">
            <p class="text-xs uppercase text-[#757683]">SGO</p>
            <p class="text-3xl font-extrabold text-[#001a61] tabular-nums">{{ $sgoCount }}</p>
        </div>
        <div class="bg-white border border-[#c5c5d4] rounded-xl p-4">
            <p class="text-xs uppercase text-[#757683]">Actifs publiés</p>
            <p class="text-3xl font-extrabold text-[#001a61] tabular-nums">{{ $activePartners }}</p>
        </div>
        <div class="bg-white border border-[#c5c5d4] rounded-xl p-4">
            <p class="text-xs uppercase text-[#757683]">Intentions d’ordres</p>
            <p class="text-3xl font-extrabold text-[#001a61] tabular-nums">{{ $orderIntents }}</p>
            <p class="text-[11px] text-[#757683] mt-1">{{ $carnetCount }} carnet · {{ $programmeCount }} client · {{ $pendingOrders }} en attente</p>
            <a href="{{ route('admin.order-intents') }}" class="text-xs font-bold text-[#0a2e8c] hover:underline">Gérer les intentions</a>
        </div>
    </div>

    <div class="bg-white border border-[#c5c5d4] rounded-xl overflow-hidden">
        <div class="px-5 py-4 border-b border-[#e7eeff]">
            <h2 class="font-bold text-[#001a61] text-lg">Fonctionnalités liées SGI / SGO</h2>
            <p class="text-sm text-[#757683] mt-1">Activez uniquement quand le branchement partenaire est prêt.</p>
        </div>
        <div class="divide-y divide-[#e7eeff]">
            @foreach ($flags as $flag)
                <div class="px-5 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <p class="font-bold text-[#001a61]">{{ $flag->label }}</p>
                            <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded
                                {{ $flag->group === 'sgo' ? 'bg-blue-50 text-blue-800' : 'bg-violet-50 text-violet-800' }}">
                                {{ strtoupper($flag->group) }}
                            </span>
                            <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded
                                {{ $flag->enabled ? 'bg-emerald-50 text-emerald-800' : 'bg-amber-50 text-amber-800' }}">
                                {{ $flag->enabled ? 'Actif' : 'Bientôt' }}
                            </span>
                        </div>
                        <p class="text-sm text-[#444652] mt-1">{{ $flag->description }}</p>
                        <p class="text-[11px] text-[#757683] mt-1 font-mono">{{ $flag->key }}</p>
                    </div>
                    <button type="button" wire:click="toggleFlag({{ $flag->id }})"
                        @class([
                            'shrink-0 px-4 py-2 rounded-lg text-sm font-bold border transition',
                            'bg-[#001a61] text-white border-[#001a61]' => $flag->enabled,
                            'bg-white text-[#001a61] border-[#c5c5d4] hover:bg-[#e7eeff]' => ! $flag->enabled,
                        ])>
                        {{ $flag->enabled ? 'Désactiver' : 'Activer' }}
                    </button>
                </div>
            @endforeach
        </div>
    </div>
</div>
