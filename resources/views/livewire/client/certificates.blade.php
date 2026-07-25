<div>
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#001a61]">Mes certificats</h1>
            <p class="text-[#444652] mt-2">Téléchargez et partagez vos attestations de réussite.</p>
        </div>
        <a href="{{ route('client.learning-history') }}" class="text-sm font-bold text-[#001a61] underline">Historique</a>
    </div>

    @if ($certificates->count() > 0)
        <div class="grid md:grid-cols-2 gap-5">
            @foreach ($certificates as $enrollment)
                <article class="bg-white border border-[#c5c5d4] rounded-2xl overflow-hidden flex flex-col">
                    <div class="bg-[#001a61] p-6 text-white">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[#ffbf00] text-4xl">workspace_premium</span>
                            <div>
                                <h3 class="font-extrabold text-lg">Certificat de réussite</h3>
                                <p class="text-white/70 text-sm">N° {{ $enrollment->certificate_number }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-5 flex flex-col flex-1">
                        <h4 class="font-bold text-[#001a61]">{{ $enrollment->formation->titre }}</h4>
                        <p class="text-sm text-[#757683] mt-2">
                            Délivré le {{ optional($enrollment->certificate_issued_at)->format('d/m/Y') }}
                        </p>
                        <div class="mt-auto pt-5 flex gap-2">
                            <a href="{{ route('client.certificate.show', $enrollment->id) }}"
                                class="flex-1 text-center py-2.5 rounded-xl bg-[#001a61] text-white text-sm font-bold">Voir</a>
                            <a href="{{ route('certificate.download', $enrollment) }}"
                                class="px-4 py-2.5 rounded-xl border border-[#c5c5d4] text-sm font-bold text-[#001a61]">PDF</a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @else
        <div class="bg-white border border-dashed border-[#c5c5d4] rounded-2xl p-12 text-center">
            <span class="material-symbols-outlined text-5xl text-[#c5c5d4]">workspace_premium</span>
            <h3 class="text-xl font-bold text-[#001a61] mt-4">Aucun certificat</h3>
            <p class="text-[#757683] mt-2">Terminez une formation et réussissez l’examen final pour l’obtenir.</p>
            <a href="{{ route('client.formations') }}" class="inline-block mt-4 font-bold text-[#001a61] underline">Mes formations</a>
        </div>
    @endif
</div>
