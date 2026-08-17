<div class="bg-[#f9f9ff] text-[#131c2a] min-h-screen">
    @include('partials.investir-nav')

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pt-12 pb-6">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <p class="text-sm font-semibold tracking-widest uppercase text-[#0a2e8c] mb-3">FCP du Bénin</p>
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#001a61]">FCP et OPCVM</h1>
                <p class="mt-2 text-[#444652]">Valeurs liquidatives du bulletin officiel BRVM du 23 juillet 2026 (p. 19). Mise à jour depuis l’admin.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('investir.vl') }}" class="text-sm font-bold text-[#001a61] border border-[#001a61] px-4 py-2 rounded hover:bg-[#e7eeff]">Tableau des VL</a>
                <a href="{{ route('mise-en-relation') }}" class="text-sm font-bold bg-[#001a61] text-white px-4 py-2 rounded hover:bg-[#0a2e8c]">Demander un accompagnement</a>
            </div>
        </div>
    </section>

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pb-6">
        <div class="bg-white border border-[#c5c5d4] rounded-xl p-4 flex flex-col md:flex-row gap-3">
            <input type="search" wire:model.live.debounce.300ms="search" placeholder="Nom, SGO…"
                class="flex-1 rounded-lg border-[#c5c5d4] focus:border-[#001a61] focus:ring-[#001a61]">
            <select wire:model.live="category" class="rounded-lg border-[#c5c5d4] focus:border-[#001a61] focus:ring-[#001a61]">
                <option value="">Toutes catégories</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>
        </div>
    </section>

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pb-20 space-y-10">
        @if ($loading)
            <p class="text-center py-16 text-[#757683]">Chargement des FCP…</p>
        @elseif ($error)
            <div class="bg-white border border-red-200 rounded-xl p-8 text-center">
                <p class="text-red-600">{{ $error }}</p>
                <button type="button" wire:click="refresh" class="mt-4 font-bold text-[#001a61] underline">Réessayer</button>
            </div>
        @else
            @php
                $grouped = collect($list)->groupBy(fn ($f) => $f['company'] ?? 'Autres');
            @endphp
            @forelse ($grouped as $company => $items)
                <div>
                    <h2 class="text-lg font-extrabold text-[#001a61] mb-4">{{ $company }}</h2>
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($items as $f)
                            <a href="{{ route('investir.fcp-detail', $f['id']) }}"
                                class="bg-white border border-[#c5c5d4] rounded-xl p-5 hover:border-[#001a61] hover:shadow-sm transition block">
                                <div class="flex justify-between items-start gap-2">
                                    <span class="text-xs font-semibold px-2 py-1 rounded bg-[#e7eeff] text-[#001a61]">{{ $f['category'] }}</span>
                                    <span @class([
                                        'text-sm font-bold',
                                        'text-[#757683]' => ($f['variation_percentage'] ?? null) === null,
                                        'text-green-600' => ($f['variation_percentage'] ?? null) !== null && $f['variation_percentage'] >= 0,
                                        'text-red-600' => ($f['variation_percentage'] ?? null) !== null && $f['variation_percentage'] < 0,
                                    ])>
                                        {{ $f['variation'] ?? 'ND' }}
                                    </span>
                                </div>
                                <h3 class="font-bold text-[#001a61] mt-3 line-clamp-2">{{ $f['name'] }}</h3>
                                <p class="text-xl font-extrabold text-[#131c2a] mt-4">{{ $f['nav_value'] }}</p>
                                <p class="text-xs text-[#757683] mt-1">
                                    Origine {{ $f['origin_nav_value'] ?? '—' }}
                                    · VL au {{ \Carbon\Carbon::parse($f['date'])->format('d/m/Y') }}
                                </p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @empty
                <p class="text-center py-16 text-[#757683]">Aucun fonds trouvé.</p>
            @endforelse
        @endif

        <p class="text-xs text-[#757683] max-w-3xl">
            Source : bulletin officiel BRVM du 23 juillet 2026, page 19. ND = Non Disponible. Les performances passées ne préjugent pas des performances futures. La souscription se fait auprès d’une SGO agréée AMF-UMOA, avec un dossier physique.
        </p>
    </section>
</div>
