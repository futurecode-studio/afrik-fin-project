<div class="p-6 lg:p-8 space-y-6">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-[#001a61]">Analyse de l'engagement</h1>
            <p class="text-sm text-[#444652] mt-1">Conversion pédagogique et points de friction.</p>
        </div>
        <select wire:model.live="formationId" class="admin-input rounded-lg text-sm">
            <option value="">Toutes les formations</option>
            @foreach ($formations as $f)
                <option value="{{ $f->id }}">{{ $f->titre }}</option>
            @endforeach
        </select>
    </div>

    <div class="grid sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="admin-card p-5"><p class="text-xs uppercase text-[#757683]">Taux de complétion</p><p class="text-3xl font-extrabold text-[#001a61] mt-1">{{ $completionRate }}%</p></div>
        <div class="admin-card p-5"><p class="text-xs uppercase text-[#757683]">Progression moyenne</p><p class="text-3xl font-extrabold text-[#001a61] mt-1">{{ $avgProgress }}%</p></div>
        <div class="admin-card p-5"><p class="text-xs uppercase text-[#757683]">Inscriptions (30j)</p><p class="text-3xl font-extrabold text-[#001a61] mt-1">{{ $recent }}</p></div>
        <div class="admin-card p-5"><p class="text-xs uppercase text-[#757683]">Actifs / Total</p><p class="text-3xl font-extrabold text-[#001a61] mt-1">{{ $active }} / {{ $total }}</p></div>
    </div>

    <div class="admin-card p-5">
        <h2 class="font-bold text-[#001a61] mb-1">Point de friction majeur</h2>
        <p class="text-sm text-[#444652]">Segment le plus peuplé hors complétion : <strong>{{ $friction }}%</strong></p>
        <div class="mt-4 grid grid-cols-5 gap-2">
            @foreach ($buckets as $label => $count)
                <div class="rounded-lg bg-[#f0f3ff] p-3 text-center">
                    <p class="text-[11px] font-bold text-[#757683]">{{ $label }}%</p>
                    <p class="text-xl font-extrabold text-[#001a61]">{{ $count }}</p>
                </div>
            @endforeach
        </div>
    </div>
</div>
