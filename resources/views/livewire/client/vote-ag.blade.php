<div>
    @if (! $meeting)
        <div class="bg-white border border-[#c5c5d4] rounded-xl p-8 text-center text-[#757683]">Aucune assemblée générale disponible pour le moment.</div>
    @else
        <div class="rounded-2xl bg-[#001a61] text-white p-6 md:p-8 mb-8">
            <div class="flex flex-wrap items-center gap-2 text-xs font-bold text-[#ffbf00] mb-3">
                <span class="material-symbols-outlined text-base">verified</span> SÉCURISÉ : SESSION ACTIVE
            </div>
            <h1 class="text-3xl font-extrabold">Proxy Voting Portal</h1>
            <p class="mt-2 text-white/80">{{ $meeting->title }} — {{ $meeting->company_name }}</p>
            <div class="mt-6 grid sm:grid-cols-3 gap-4">
                <div>
                    <p class="text-xs uppercase text-white/60">Valeur liée</p>
                    <p class="text-xl font-bold">{{ number_format($value, 0, ',', ' ') }} XOF</p>
                </div>
                <div>
                    <p class="text-xs uppercase text-white/60">Actions détenues</p>
                    <p class="text-xl font-bold">{{ number_format($shares, 0, ',', ' ') }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase text-white/60">Clôture</p>
                    <p class="text-xl font-bold">{{ optional($meeting->closes_at)->format('d M Y, H:i') ?? '—' }}</p>
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-4">
                <h2 class="font-bold text-[#001a61] text-xl">Résolutions à voter ({{ $meeting->resolutions->count() }})</h2>
                @foreach ($meeting->resolutions as $res)
                    <div class="bg-white border border-[#c5c5d4] rounded-xl p-5">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <span class="text-xs font-bold uppercase text-[#757683]">Résolution {{ $res->number }} · {{ $res->kind }}</span>
                                <h3 class="font-bold text-[#001a61] mt-1">{{ $res->title }}</h3>
                            </div>
                        </div>
                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach (['pour' => 'Pour', 'contre' => 'Contre', 'abstention' => 'Abstention'] as $val => $label)
                                <button type="button" wire:click="setChoice({{ $res->id }}, '{{ $val }}')"
                                    @class([
                                        'px-4 py-2 rounded-lg text-sm font-bold border transition',
                                        'bg-[#001a61] text-white border-[#001a61]' => ($choices[$res->id] ?? '') === $val,
                                        'border-[#c5c5d4] text-[#444652] hover:bg-[#e7eeff]' => ($choices[$res->id] ?? '') !== $val,
                                    ])>{{ $label }}</button>
                            @endforeach
                        </div>
                    </div>
                @endforeach
                <button type="button" wire:click="submit" class="w-full sm:w-auto px-8 py-3 rounded-xl bg-[#ffbf00] text-[#261a00] font-extrabold">
                    Soumettre mes votes
                </button>
            </div>
            <aside class="space-y-4">
                <div class="bg-white border border-[#c5c5d4] rounded-xl p-5">
                    <h3 class="font-bold text-[#001a61] mb-2">Détails de l'AG</h3>
                    <p class="text-sm text-[#444652]"><span class="font-medium">Lieu :</span> {{ $meeting->location ?? '—' }}</p>
                    <p class="text-sm text-[#444652] mt-1"><span class="font-medium">Quorum :</span> {{ $meeting->quorum_percent ?? '—' }}%</p>
                    @if ($meeting->report_url)
                        <a href="{{ $meeting->report_url }}" target="_blank" class="inline-flex items-center gap-1 mt-3 text-sm font-bold text-[#001a61]">
                            <span class="material-symbols-outlined text-base">description</span> Rapport annuel
                        </a>
                    @endif
                </div>
                <div class="bg-[#e7eeff] rounded-xl p-5 text-sm text-[#001a61]">
                    <span class="material-symbols-outlined">verified_user</span>
                    Vos choix sont enregistrés de façon sécurisée. Africaine des Finances facilite le vote par procuration.
                </div>
            </aside>
        </div>
    @endif
</div>
