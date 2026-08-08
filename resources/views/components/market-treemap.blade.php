{{-- Treemap BRVM partagé (accueil + heatmap) — JS : window.adfMarketMap --}}
@props([
    'treemap' => ['nodes' => [], 'size_label' => '', 'count' => 0],
    'height' => 'min(560px, 70vh)',
    'showLegend' => true,
    'tone' => 'dark', // dark = section accueil, light = page heatmap
])

@php
    $tm = $treemap;
    $nodes = $tm['nodes'] ?? [];
    $isLight = $tone === 'light';
@endphp

@if(!empty($nodes))
<div
    @class([
        'relative w-full rounded-xl overflow-hidden',
        'border border-[#c5c5d4] bg-[#0b1630]' => $isLight,
        'border border-white/15 bg-[#040b18]' => ! $isLight,
    ])
    style="height: {{ $height }};"
    wire:ignore.self
    x-data="adfMarketMap(@js($tm))"
    @resize.window.debounce.150ms="layout()"
    @mousemove="moveTip($event)"
    @mouseleave="tip = null"
>
    <div class="absolute inset-0" x-ref="map">
        <template x-for="cell in cells" :key="cell.symbol">
            <a
                :href="cell.url"
                class="absolute overflow-hidden border border-black/40 hover:border-white/80 hover:z-10 transition-colors duration-150"
                :style="`left:${cell.x}px;top:${cell.y}px;width:${cell.w}px;height:${cell.h}px;background:${cell.color}`"
                @mouseenter="tip = cell"
                @focus="tip = cell"
            >
                <div
                    class="p-1 h-full flex flex-col justify-center items-center text-center pointer-events-none"
                    x-show="cell.w >= 48 && cell.h >= 32"
                >
                    <div class="rounded px-1.5 py-0.5 bg-black/55 backdrop-blur-[2px] shadow-sm">
                        <span
                            class="block font-extrabold leading-tight text-white drop-shadow"
                            :class="cell.w >= 100 ? 'text-sm' : 'text-[11px]'"
                            x-text="cell.symbol"
                        ></span>
                        <span
                            class="block text-[10px] font-bold tabular-nums text-white"
                            x-show="cell.h >= 48"
                            x-text="(cell.variation >= 0 ? '+' : '') + cell.variation.toFixed(2) + '%'"
                        ></span>
                    </div>
                </div>
            </a>
        </template>
    </div>

    <div
        x-show="tip"
        x-cloak
        x-transition.opacity.duration.150ms
        class="pointer-events-none absolute z-30 min-w-[210px] max-w-[280px] rounded-lg bg-white border border-[#c5c5d4] shadow-2xl p-3 text-[#131c2a]"
        :style="`left:${tipX}px;top:${tipY}px;transform:translate(12px,12px)`"
    >
        <template x-if="tip">
            <div>
                <p class="font-extrabold text-sm text-[#001a61]" x-text="tip.name"></p>
                <p class="text-xs text-[#757683] mt-0.5" x-text="tip.symbol + (tip.sector ? ' · ' + tip.sector : '')"></p>
                <p class="text-xs text-[#444652] mt-2" x-text="sizeLine(tip)"></p>
                <p class="text-xs font-bold mt-1"
                    :class="tip.variation >= 0 ? 'text-emerald-700' : 'text-red-700'"
                    x-text="'Variation: ' + (tip.variation >= 0 ? '+' : '') + Number(tip.variation).toFixed(2) + '%'"></p>
                <p class="text-xs text-[#444652] mt-1 tabular-nums" x-text="'Cours: ' + Number(tip.price).toLocaleString('fr-FR') + ' FCFA'"></p>
            </div>
        </template>
    </div>
</div>

@if($showLegend)
<div class="mt-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <p @class(['text-xs font-medium', 'text-[#444652]' => $isLight, 'text-white/85' => ! $isLight])>
        {{ $tm['count'] ?? count($nodes) }} titres · taille = {{ $tm['size_label'] ?? 'poids' }} · couleur = variation
    </p>
    <div class="flex flex-col items-end gap-1">
        <div @class([
                'h-3 w-48 sm:w-56 rounded-full overflow-hidden',
                'border border-[#c5c5d4]' => $isLight,
                'border border-white/20' => ! $isLight,
            ])
            style="background: linear-gradient(90deg, #14532d 0%, #16a34a 35%, #6b7280 50%, #dc2626 65%, #7f1d1d 100%);"></div>
        <div @class([
            'flex justify-between w-48 sm:w-56 text-[11px] font-bold',
            'text-[#001a61]' => $isLight,
            'text-white' => ! $isLight,
        ])>
            <span>Positif</span>
            <span>0</span>
            <span>Négatif</span>
        </div>
    </div>
</div>
@endif
@endif

@once
@push('scripts')
<script>
window.adfMarketMap = function (payload) {
    return {
        nodes: payload.nodes || [],
        sizeLabel: payload.size_label || '',
        cells: [],
        tip: null,
        tipX: 0,
        tipY: 0,

        init() {
            this.$nextTick(() => this.layout());
        },

        layout() {
            const el = this.$refs.map;
            if (!el || !this.nodes.length) return;
            const width = el.clientWidth || 800;
            const height = el.clientHeight || 500;
            const items = this.nodes.map(n => ({ ...n, value: Math.max(Number(n.size) || 1, 1) }));
            const rects = this.squarify(items, 0, 0, width, height);
            this.cells = rects.map(r => ({
                ...r.item,
                x: r.x,
                y: r.y,
                w: Math.max(r.w, 1),
                h: Math.max(r.h, 1),
                color: this.colorFor(r.item.variation),
            }));
        },

        // Couleurs saturées + sombres pour garder le texte blanc lisible dans les cases
        colorFor(v) {
            const x = Math.max(-8, Math.min(8, Number(v) || 0));
            if (Math.abs(x) < 0.05) return '#3f4b5c';
            const t = Math.abs(x) / 8;
            if (x > 0) {
                const r = Math.round(4 + t * 8);
                const g = Math.round(80 + t * 60);
                const b = Math.round(30 + t * 20);
                return `rgb(${r}, ${g}, ${b})`;
            }
            const r = Math.round(140 + t * 70);
            const g = Math.round(18 + t * 10);
            const b = Math.round(18 + t * 8);
            return `rgb(${r}, ${g}, ${b})`;
        },

        sizeLine(tip) {
            if (!tip) return '';
            const cap = Number(tip.market_cap) || 0;
            if (cap > 0) {
                const mrd = cap >= 1_000_000_000 ? (cap / 1_000_000_000) : (cap / 1000);
                return 'Capitalisation: ' + mrd.toLocaleString('fr-FR', { maximumFractionDigits: 2 }) + ' Mrd FCFA';
            }
            return 'Volume: ' + Number(tip.volume || 0).toLocaleString('fr-FR') + ' titres';
        },

        moveTip(e) {
            const box = this.$refs.map?.getBoundingClientRect();
            if (!box) return;
            let x = e.clientX - box.left;
            let y = e.clientY - box.top;
            x = Math.min(x, box.width - 220);
            y = Math.min(y, box.height - 120);
            this.tipX = Math.max(0, x);
            this.tipY = Math.max(0, y);
        },

        squarify(items, x, y, w, h) {
            const total = items.reduce((s, i) => s + i.value, 0);
            if (!items.length || total <= 0 || w < 1 || h < 1) return [];
            if (items.length === 1) {
                return [{ item: items[0], x, y, w, h }];
            }

            const sorted = [...items].sort((a, b) => b.value - a.value);
            let acc = 0;
            let splitAt = 1;
            for (let i = 0; i < sorted.length; i++) {
                acc += sorted[i].value;
                if (acc >= total / 2) {
                    splitAt = Math.max(1, i + 1);
                    break;
                }
            }
            if (splitAt >= sorted.length) splitAt = sorted.length - 1;

            const left = sorted.slice(0, splitAt);
            const right = sorted.slice(splitAt);
            const leftSum = left.reduce((s, i) => s + i.value, 0);
            const ratio = leftSum / total;

            if (w >= h) {
                const lw = w * ratio;
                return [
                    ...this.squarify(left, x, y, lw, h),
                    ...this.squarify(right, x + lw, y, w - lw, h),
                ];
            }

            const lh = h * ratio;
            return [
                ...this.squarify(left, x, y, w, lh),
                ...this.squarify(right, x, y + lh, w, h - lh),
            ];
        }
    };
};

window.homeMarketMap = window.adfMarketMap;
</script>
@endpush
@endonce
