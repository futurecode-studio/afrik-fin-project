@php
    $eid = $event->id;
    $items = [
        ['route' => 'admin.events', 'params' => [], 'match' => 'admin.events', 'label' => 'Tous les événements', 'icon' => 'event'],
        ['route' => 'admin.event.registrations', 'params' => [$eid], 'match' => 'admin.event.registrations', 'label' => 'Inscrits', 'icon' => 'group'],
        ['route' => 'admin.event.program', 'params' => [$eid], 'match' => 'admin.event.program', 'label' => 'Programme', 'icon' => 'schedule'],
        ['route' => 'admin.event.speakers', 'params' => [$eid], 'match' => 'admin.event.speakers', 'label' => 'Intervenants', 'icon' => 'record_voice_over'],
        ['route' => 'admin.event.documents', 'params' => [$eid], 'match' => 'admin.event.documents', 'label' => 'Documents', 'icon' => 'description'],
        ['route' => 'admin.event.tickets', 'params' => [$eid], 'match' => 'admin.event.tickets', 'label' => 'Billets', 'icon' => 'confirmation_number'],
        ['route' => 'admin.event.checkin', 'params' => [$eid], 'match' => 'admin.event.checkin', 'label' => 'Émargement QR', 'icon' => 'qr_code_scanner'],
        ['route' => 'admin.event.products', 'params' => [$eid], 'match' => 'admin.event.products', 'label' => 'Boutique', 'icon' => 'storefront'],
    ];
@endphp
<nav class="flex flex-wrap gap-1 border-b border-[#c5c5d4] mb-6">
    @foreach ($items as $item)
        @php
            $active = request()->routeIs($item['match']);
            $url = empty($item['params']) ? route($item['route']) : route($item['route'], $item['params']);
        @endphp
        <a href="{{ $url }}"
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
