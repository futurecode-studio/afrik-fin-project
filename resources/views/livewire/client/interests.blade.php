<div class="max-w-3xl mx-auto">
    <div class="mb-8 text-center">
        <p class="text-sm font-semibold tracking-widest uppercase text-[#0a2e8c]">Personnalisation</p>
        <h1 class="text-3xl md:text-4xl font-extrabold text-[#001a61] mt-2">Personnalisez votre expérience</h1>
        <p class="text-[#444652] mt-3">Sélectionnez les thèmes qui vous intéressent — nous adapterons vos raccourcis.</p>
    </div>

    @foreach ($grouped as $group => $items)
        <section class="mb-8">
            <h2 class="text-sm font-bold uppercase tracking-wide text-[#757683] mb-3">{{ $group }}</h2>
            <div class="grid sm:grid-cols-2 gap-3">
                @foreach ($items as $meta)
                    @php
                        $key = $meta['key'];
                        $on = in_array($key, $selected, true);
                    @endphp
                    <button type="button" wire:click="toggle('{{ $key }}')"
                        @class([
                            'text-left p-4 rounded-xl border transition flex items-center gap-3',
                            'border-[#001a61] bg-[#e7eeff]' => $on,
                            'border-[#c5c5d4] bg-white hover:border-[#001a61]' => ! $on,
                        ])>
                        <span class="material-symbols-outlined text-[#001a61]">{{ $meta['icon'] }}</span>
                        <span class="font-semibold text-[#001a61] flex-1">{{ $meta['label'] }}</span>
                        @if ($on)
                            <span class="material-symbols-outlined text-green-600">check_circle</span>
                        @endif
                    </button>
                @endforeach
            </div>
        </section>
    @endforeach

    <div class="flex flex-wrap justify-center gap-3 pt-4">
        <button type="button" wire:click="skip" class="px-5 py-3 rounded border border-[#c5c5d4] font-bold text-[#444652] hover:bg-white">Passer</button>
        <button type="button" wire:click="save" class="px-6 py-3 rounded bg-[#001a61] text-white font-bold hover:bg-[#0a2e8c]">
            Enregistrer ({{ count($selected) }})
        </button>
    </div>
</div>
