<x-client.coming-soon
    title="Bientôt disponible"
    feature="Vote en Assemblée Générale (Proxy Voting)"
    icon="how_to_vote"
    description="Le vote par procuration sur les AG des sociétés cotées BRVM sera bientôt proposé, en lien avec votre compte-titres et une SGI agréée. Aucune intention de vote n’est enregistrée pour le moment."
>
    <div class="space-y-6">
        <div class="rounded-2xl bg-[#001a61] text-white p-6 md:p-8">
            <p class="text-xs font-bold text-[#ffbf00]">PROXY VOTING</p>
            <h1 class="text-3xl font-extrabold mt-2">Vote en Assemblée Générale</h1>
            <p class="mt-2 text-white/75">Exercez vos droits d’actionnaire sur les résolutions des sociétés cotées.</p>
            <div class="mt-6 grid sm:grid-cols-3 gap-4">
                <div><p class="text-xs text-white/50 uppercase">Valeur liée</p><p class="text-xl font-bold">— XOF</p></div>
                <div><p class="text-xs text-white/50 uppercase">Actions</p><p class="text-xl font-bold">—</p></div>
                <div><p class="text-xs text-white/50 uppercase">Clôture</p><p class="text-xl font-bold">—</p></div>
            </div>
        </div>
        <div class="grid gap-4">
            @foreach (['Approbation des comptes', 'Affectation du résultat', 'Renouvellement du mandat'] as $i => $title)
                <div class="bg-white border border-[#c5c5d4] rounded-xl p-5">
                    <p class="text-xs font-bold uppercase text-[#757683]">Résolution {{ $i + 1 }}</p>
                    <h3 class="font-bold text-[#001a61] mt-1">{{ $title }}</h3>
                    <div class="mt-3 flex gap-2">
                        <span class="px-4 py-2 rounded-lg border text-sm font-bold text-[#c5c5d4]">Pour</span>
                        <span class="px-4 py-2 rounded-lg border text-sm font-bold text-[#c5c5d4]">Contre</span>
                        <span class="px-4 py-2 rounded-lg border text-sm font-bold text-[#c5c5d4]">Abstention</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-client.coming-soon>
