{{-- Sous-navigation Marchés --}}
@php
    $items = [
        ['route' => 'marches.palmares', 'label' => 'Palmarès', 'match' => 'marches.palmares'],
        ['route' => 'marches.comparateur', 'label' => 'Comparateur', 'match' => 'marches.comparateur'],
        ['route' => 'marches.indices', 'label' => 'Indices', 'match' => 'marches.indices'],
        ['route' => 'marches.obligations', 'label' => 'Obligations', 'match' => ['marches.obligations', 'marches.obligation']],
        ['route' => 'marches.screener', 'label' => 'Screener', 'match' => 'marches.screener'],
        ['route' => 'marches.secteurs', 'label' => 'Secteurs', 'match' => 'marches.secteurs'],
        ['route' => 'marches.introductions', 'label' => 'IPO', 'match' => 'marches.introductions'],
        ['route' => 'marches.carnet', 'label' => 'Carnet', 'match' => 'marches.carnet'],
        ['route' => 'marches.bibliotheque', 'label' => 'Bibliothèque', 'match' => 'marches.bibliotheque'],
        ['route' => 'marches.comparateur-multi', 'label' => 'Multi-actifs', 'match' => 'marches.comparateur-multi'],
        ['route' => 'marches.produits-structures', 'label' => 'Structurés', 'match' => ['marches.produits-structures', 'marches.certificat']],
        ['route' => 'marches.analyse-pro', 'label' => 'Graphique Pro', 'match' => 'marches.analyse-pro*'],
    ];
@endphp
<nav class="border-b border-[#c5c5d4] bg-white/80 backdrop-blur sticky top-[7.5rem] z-30">
    <div class="max-w-[1280px] mx-auto px-5 lg:px-16 overflow-x-auto">
        <div class="flex items-center gap-1 min-w-max py-2">
            @foreach ($items as $item)
                @php
                    $active = is_array($item['match'])
                        ? request()->routeIs(...$item['match'])
                        : request()->routeIs($item['match']);
                @endphp
                <a href="{{ route($item['route']) }}"
                    @class([
                        'px-3 py-2 text-sm font-medium rounded transition-colors whitespace-nowrap',
                        'bg-[#001a61] text-white' => $active,
                        'text-[#444652] hover:bg-[#e7eeff] hover:text-[#001a61]' => !$active,
                    ])>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </div>
    </div>
</nav>
