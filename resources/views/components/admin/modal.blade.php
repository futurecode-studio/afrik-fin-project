{{-- Modal admin instantané : Alpine + @entangle (pas d'attente réseau pour ouvrir/fermer) --}}
@props([
    'name' => 'showModal',
    'max' => 'sm:max-w-3xl',
])

<div
    x-data="{ open: @entangle($name).live }"
    x-show="open"
    x-cloak
    role="dialog"
    aria-modal="true"
    {{ $attributes->class('fixed inset-0 z-[100] flex items-end sm:items-center justify-center p-0 sm:p-4') }}
    style="display: none;"
    @keydown.escape.window="if (open) open = false"
>
    <div class="absolute inset-0 bg-[#131c2a]/55" @click="open = false"></div>
    <div
        @click.stop
        @class([
            'relative adf-modal-panel bg-white w-full shadow-2xl border border-[#c5c5d4] max-h-[94vh] flex flex-col overflow-hidden rounded-t-2xl sm:rounded-2xl',
            $max,
        ])
    >
        {{ $slot }}
    </div>
</div>
