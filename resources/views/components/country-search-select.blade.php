@props([
    'countries' => [],
    'placeholder' => 'Tous les pays',
])

<div
    {{ $attributes->class(['relative w-full']) }}
    x-data="{
        open: false,
        search: '',
        countries: @js($countries),
        placeholder: @js($placeholder),
        selected: @entangle($attributes->wire('model')),
        filtered() {
            const q = this.search.trim().toLowerCase();
            let list = this.countries;
            if (q) {
                list = list.filter(c => c.name.toLowerCase().includes(q));
            }
            return list.slice(0, 80);
        },
        label() {
            return this.selected || this.placeholder;
        },
        pick(name) {
            this.selected = name;
            this.open = false;
            this.search = '';
        },
        clearSelection() {
            this.selected = '';
            this.open = false;
            this.search = '';
        }
    }"
    @click.outside="open = false"
>
    <div class="w-full flex items-center gap-1 rounded-lg border border-[#c5c5d4] bg-white px-2 py-1 min-h-[42px] hover:border-[#001a61] transition">
        <button
            type="button"
            @click="open = !open"
            class="flex-1 flex items-center justify-between gap-2 px-1 py-1 text-left text-sm min-w-0"
        >
            <span class="truncate" x-text="label()" :class="!selected && 'text-[#757683]'"></span>
            <span class="material-symbols-outlined text-[#757683] text-lg shrink-0" x-show="!selected">expand_more</span>
        </button>
        <button
            type="button"
            x-show="selected"
            x-cloak
            @click.stop="clearSelection()"
            class="shrink-0 p-1 text-[#757683] hover:text-[#001a61]"
            aria-label="Effacer"
        >
            <span class="material-symbols-outlined text-lg">close</span>
        </button>
    </div>

    <div
        x-show="open"
        x-cloak
        class="absolute z-50 mt-1 w-full min-w-[16rem] rounded-lg border border-[#c5c5d4] bg-white shadow-lg overflow-hidden"
    >
        <div class="p-2 border-b border-[#c5c5d4]">
            <div class="relative">
                <span class="material-symbols-outlined absolute left-2 top-1/2 -translate-y-1/2 text-[#757683] text-lg">search</span>
                <input
                    type="search"
                    x-model="search"
                    @click.stop
                    placeholder="Rechercher un pays…"
                    class="w-full rounded-md border-[#c5c5d4] text-sm pl-9 pr-2 py-2 focus:border-[#001a61] focus:ring-[#001a61]"
                >
            </div>
        </div>
        <ul class="max-h-60 overflow-y-auto py-1">
            <li>
                <button
                    type="button"
                    @click="clearSelection()"
                    class="w-full text-left px-3 py-2 text-sm hover:bg-[#e7eeff] text-[#757683]"
                >
                    {{ $placeholder }}
                </button>
            </li>
            <template x-for="country in filtered()" :key="country.code">
                <li>
                    <button
                        type="button"
                        @click="pick(country.name)"
                        class="w-full text-left px-3 py-2 text-sm hover:bg-[#e7eeff]"
                        :class="selected === country.name && 'bg-[#e7eeff] font-semibold text-[#001a61]'"
                        x-text="country.name"
                    ></button>
                </li>
            </template>
            <li x-show="filtered().length === 0" class="px-3 py-4 text-sm text-center text-[#757683]">
                Aucun pays trouvé
            </li>
        </ul>
    </div>
</div>
