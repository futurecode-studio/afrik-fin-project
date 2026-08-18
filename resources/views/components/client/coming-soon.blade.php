@props([
    'title' => 'Bientôt disponible',
    'feature' => 'Cette fonctionnalité',
    'description' => 'Nous finalisons le branchement avec nos partenaires SGI agréés et le cadre réglementaire AMF-UMOA. Merci de votre patience.',
    'icon' => 'schedule',
])

{{-- Overlay plein contenu : fonctionnalité pas encore branchée --}}
<div {{ $attributes->class('relative min-h-[70vh]') }}>
    <div class="pointer-events-none select-none opacity-[0.18] blur-[1px]" aria-hidden="true">
        {{ $slot }}
    </div>

    <div class="absolute inset-0 z-20 flex items-center justify-center p-4 sm:p-8">
        <div class="w-full max-w-lg rounded-2xl border border-[#c5c5d4] bg-white shadow-2xl p-6 sm:p-8 text-center">
            <div class="mx-auto w-14 h-14 rounded-2xl bg-[#e7eeff] flex items-center justify-center">
                <span class="material-symbols-outlined text-3xl text-[#001a61]">{{ $icon }}</span>
            </div>
            <p class="mt-4 text-xs font-extrabold uppercase tracking-widest text-[#ffbf00] bg-[#001a61] inline-block px-3 py-1 rounded-md">
                Bientôt
            </p>
            <h2 class="mt-4 text-2xl font-extrabold text-[#001a61]">{{ $title }}</h2>
            <p class="mt-2 text-sm font-semibold text-[#0a2e8c]">{{ $feature }}</p>
            <p class="mt-3 text-sm text-[#444652] leading-relaxed">{{ $description }}</p>
            {{ $actions ?? '' }}
            <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('client.my-events') }}"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-[#001a61] text-white text-sm font-bold hover:bg-[#0a2e8c] transition">
                    <span class="material-symbols-outlined text-base">dashboard</span>
                    Tableau de bord
                </a>
                <a href="{{ route('partenaires') }}"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl border border-[#c5c5d4] text-[#001a61] text-sm font-bold hover:bg-[#e7eeff] transition">
                    <span class="material-symbols-outlined text-base">handshake</span>
                    Partenaires SGI
                </a>
            </div>
            <p class="mt-5 text-xs text-[#757683]">
                Africaine des Finances (AMF-UMOA AA/2022-03) — éducation &amp; mise en relation, pas d’exécution d’ordres.
            </p>
        </div>
    </div>
</div>
