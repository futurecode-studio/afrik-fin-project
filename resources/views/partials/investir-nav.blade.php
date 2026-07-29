{{-- Sous-navigation Investir --}}
@php
    $items = [
        ['route' => 'investir.hub', 'label' => 'Investir', 'match' => 'investir.hub'],
        ['route' => 'guide-bourse', 'label' => 'Guide bourse', 'match' => 'guide-bourse'],
        ['route' => 'investir.comment', 'label' => 'Comment investir', 'match' => 'investir.comment'],
        ['route' => 'investir.profil-test', 'label' => 'Test profil', 'match' => ['investir.profil-test', 'investir.profil']],
        ['route' => 'investir.opcvm', 'label' => 'FCP / OPCVM', 'match' => ['investir.opcvm', 'investir.fcp-detail', 'investir.vl']],
        ['route' => 'marches.carte', 'label' => 'Carte marché', 'match' => 'marches.carte'],
        ['route' => 'marches.calendrier', 'label' => 'Calendrier', 'match' => 'marches.calendrier'],
        ['route' => 'investir.partenaires', 'label' => 'Partenaires', 'match' => 'investir.partenaires'],
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
                    ])>{{ $item['label'] }}</a>
            @endforeach
        </div>
    </div>
</nav>
