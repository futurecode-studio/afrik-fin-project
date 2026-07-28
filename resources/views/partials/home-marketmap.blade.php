{{-- MARKETMAP BRVM — treemap interactif (taille = poids, couleur = variation) --}}
@php
    $tm = $marketTreemap ?? ['nodes' => [], 'size_label' => '', 'count' => 0];
@endphp
@if(!empty($tm['nodes']))
<section class="py-16 lg:py-20 px-5 lg:px-16 bg-[#0b1630]">
    <div class="max-w-[1280px] mx-auto">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
            <div>
                <h2 class="inline-flex items-center bg-[#c1121f] text-white uppercase text-sm font-extrabold tracking-wider px-4 py-2 rounded-md">
                    Marketmap
                </h2>
                <p class="mt-3 text-white/80 text-sm max-w-xl">
                    Carte des titres BRVM — taille = {{ $tm['size_label'] }}, couleur = variation du jour.
                    {{ $tm['count'] }} valeurs. Cliquez une case pour ouvrir la fiche.
                </p>
            </div>
            <a href="{{ route('marches.carte') }}"
                class="inline-flex items-center gap-2 text-[#ffbf00] font-bold text-sm hover:underline shrink-0">
                Carte complète
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>
        </div>

        <x-market-treemap :treemap="$tm" height="min(560px, 70vh)" />
    </div>
</section>
@endif
