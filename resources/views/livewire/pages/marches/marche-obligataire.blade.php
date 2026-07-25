<div class="bg-[#f9f9ff] text-[#131c2a] min-h-screen">
    @include('partials.markets-nav')

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pt-10 pb-6">
        <h1 class="text-3xl md:text-4xl font-extrabold text-[#001a61]">Marché Obligataire</h1>
        <p class="text-[#444652] mt-2">Obligations d'États UEMOA synchronisées (UMOA-Titres) — {{ $bonds->count() }} actives</p>
    </section>

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pb-6">
        <div class="bg-white border border-[#c5c5d4] rounded-xl p-4 grid md:grid-cols-3 gap-3">
            <input type="search" wire:model.live.debounce.300ms="search" placeholder="Nom, émetteur, ISIN…"
                class="rounded-lg border-[#c5c5d4] focus:border-[#001a61] focus:ring-[#001a61]">
            <select wire:model.live="country" class="rounded-lg border-[#c5c5d4] focus:border-[#001a61] focus:ring-[#001a61]">
                <option value="">Tous les pays</option>
                @foreach ($countries as $c)
                    <option value="{{ $c }}">{{ $c }}</option>
                @endforeach
            </select>
            <select wire:model.live="type" class="rounded-lg border-[#c5c5d4] focus:border-[#001a61] focus:ring-[#001a61]">
                <option value="">Tous les types</option>
                @foreach ($types as $t)
                    <option value="{{ $t }}">{{ $t }}</option>
                @endforeach
            </select>
        </div>
    </section>

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pb-20">
        <div class="overflow-x-auto bg-white border border-[#c5c5d4] rounded-xl">
            <table class="w-full text-sm min-w-[900px]">
                <thead class="bg-[#e7eeff] text-[#001a61]">
                    <tr>
                        <th class="text-left px-3 py-3">Nom</th>
                        <th class="text-left px-3 py-3">Émetteur</th>
                        <th class="text-right px-3 py-3">Taux</th>
                        <th class="text-right px-3 py-3">Durée</th>
                        <th class="text-right px-3 py-3">Prix</th>
                        <th class="text-left px-3 py-3">Émission</th>
                        <th class="text-left px-3 py-3">Échéance</th>
                        <th class="px-3 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bonds as $b)
                        <tr class="border-t border-[#c5c5d4] hover:bg-[#f0f3ff]">
                            <td class="px-3 py-3 font-semibold text-[#001a61]">{{ $b->name }}</td>
                            <td class="px-3 py-3">{{ $b->issuer }}<div class="text-xs text-[#757683]">{{ $b->country }}</div></td>
                            <td class="px-3 py-3 text-right font-bold">{{ number_format((float)$b->interest_rate, 2) }}%</td>
                            <td class="px-3 py-3 text-right">{{ $b->maturity_years }} an(s)</td>
                            <td class="px-3 py-3 text-right">{{ number_format((float)$b->current_price, 0, ',', ' ') }}</td>
                            <td class="px-3 py-3">{{ optional($b->issue_date)->format('d/m/Y') }}</td>
                            <td class="px-3 py-3">{{ optional($b->maturity_date)->format('d/m/Y') }}</td>
                            <td class="px-3 py-3 text-right">
                                <a href="{{ route('marches.obligation', $b->id) }}" class="font-bold text-[#001a61] hover:underline">Détail</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-4 py-10 text-center text-[#757683]">Aucune obligation trouvée</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
