<div>
    <a href="{{ route('client.certificates') }}" class="text-sm font-bold text-[#001a61] hover:underline">← Mes certificats</a>

    <div class="mt-4 flex flex-col lg:flex-row lg:items-end justify-between gap-4 mb-8">
        <div>
            <p class="text-xs font-bold uppercase tracking-widest text-[#757683]">Certificat de réussite</p>
            <h1 class="text-3xl font-extrabold text-[#001a61] mt-1">Validation officielle</h1>
            <p class="text-[#444652] mt-2">N° {{ $enrollment->certificate_number }}</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('certificate.download', $enrollment) }}" class="inline-flex items-center gap-2 bg-[#001a61] text-white font-bold px-5 py-3 rounded-xl">
                <span class="material-symbols-outlined">download</span> Télécharger PDF
            </a>
            <a href="{{ route('certificate.view', $enrollment) }}" target="_blank" class="inline-flex items-center gap-2 border border-[#c5c5d4] font-bold px-5 py-3 rounded-xl text-[#001a61]">Voir</a>
        </div>
    </div>

    <div class="bg-white border-2 border-[#001a61]/20 rounded-2xl p-8 md:p-12 text-center shadow-sm relative overflow-hidden">
        <div class="absolute inset-0 opacity-[0.04] bg-[radial-gradient(circle_at_20%_20%,#001a61,transparent_40%),radial-gradient(circle_at_80%_80%,#ffbf00,transparent_35%)]"></div>
        <div class="relative">
            <span class="material-symbols-outlined text-5xl text-[#ffbf00]">workspace_premium</span>
            <p class="mt-4 text-sm font-bold uppercase tracking-widest text-[#0a2e8c]">Africaine des Finances</p>
            <h2 class="text-2xl md:text-3xl font-extrabold text-[#001a61] mt-2">Certificat Officiel d'Expertise</h2>
            <p class="text-[#444652] mt-6">Ce document certifie que</p>
            <p class="text-xl md:text-2xl font-extrabold text-[#001a61] mt-2">{{ Auth::user()->name }}</p>
            <p class="text-[#444652] mt-4">a complété avec succès le programme</p>
            <p class="text-lg font-bold text-[#001a61] mt-2">« {{ $enrollment->formation->titre }} »</p>
            <p class="text-sm text-[#757683] mt-8">
                Délivré le {{ optional($enrollment->certificate_issued_at)->format('d F Y') }}
                · Agréé AMF-UMOA AA/2022-03
            </p>
            @if ($enrollment->formation->user)
                <p class="text-xs text-[#757683] mt-2">Sous la direction pédagogique de {{ $enrollment->formation->user->name }}</p>
            @endif
            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-6">
                <div class="w-40 h-40 rounded-xl border border-[#c5c5d4] bg-white p-2 flex items-center justify-center">
                    <img src="data:image/svg+xml;base64,{{ base64_encode(\QrCode::format('svg')->size(160)->generate(route('certificate.verify.show', $enrollment->certificate_number))) }}"
                        alt="QR vérification certificat" class="w-full h-full">
                </div>
                <p class="text-xs text-[#757683] max-w-xs text-left">Scannez pour vérifier l’authenticité du certificat n° {{ $enrollment->certificate_number }}.</p>
            </div>
        </div>
    </div>
</div>
