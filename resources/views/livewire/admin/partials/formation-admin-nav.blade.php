@php
    $fid = $formation->id;
    $items = [
        ['route' => 'admin.formations.show', 'params' => [$fid], 'match' => 'admin.formations.show', 'label' => 'Vue d’ensemble', 'icon' => 'dashboard'],
        ['route' => 'admin.formations.catalog', 'params' => [$fid], 'match' => 'admin.formations.catalog', 'label' => 'Catalogue', 'icon' => 'collections'],
        ['route' => 'admin.formations.modules', 'params' => [$fid], 'match' => 'admin.formations.modules', 'label' => 'Modules', 'icon' => 'view_module'],
        ['route' => 'admin.formations.learners', 'params' => [$fid], 'match' => ['admin.formations.learners', 'admin.formations.learners.show'], 'label' => 'Apprenants', 'icon' => 'groups'],
    ];
@endphp
<nav class="flex flex-wrap gap-1 border-b border-[#c5c5d4] mb-6">
    @foreach ($items as $item)
        @php
            $active = is_array($item['match'])
                ? request()->routeIs(...$item['match'])
                : request()->routeIs($item['match']);
        @endphp
        <a href="{{ route($item['route'], $item['params']) }}"
            @class([
                'inline-flex items-center gap-1.5 px-4 py-2.5 text-sm font-semibold border-b-2 -mb-px transition',
                'border-[#ffbf00] text-[#001a61]' => $active,
                'border-transparent text-[#757683] hover:text-[#001a61]' => ! $active,
            ])>
            <span class="material-symbols-outlined text-[18px]">{{ $item['icon'] }}</span>
            {{ $item['label'] }}
        </a>
    @endforeach
</nav>
