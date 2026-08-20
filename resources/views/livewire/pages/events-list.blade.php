@php
    $formatLabel = fn (?string $type) => match ($type) {
        'online' => 'En ligne',
        'hybrid' => 'Hybride',
        default => 'Présentiel',
    };
    $formatIcon = fn (?string $type) => match ($type) {
        'online' => 'videocam',
        'hybrid' => 'hub',
        default => 'location_on',
    };
@endphp

<div class="bg-[#f9f9ff] text-[#131c2a] min-h-screen">
    {{-- Hero --}}
    <section class="relative overflow-hidden bg-[#001a61] text-white">
        <div class="pointer-events-none absolute inset-0" aria-hidden="true">
            <div class="absolute -top-20 -right-16 w-80 h-80 rounded-full bg-[#ffbf00]/15 blur-3xl"></div>
            <div class="absolute bottom-0 left-1/4 w-96 h-96 rounded-full bg-[#0a2e8c]/50 blur-3xl"></div>
        </div>
        <div class="relative max-w-[1280px] mx-auto px-5 lg:px-16 py-14 lg:py-20">
            <p class="text-sm font-semibold tracking-widest uppercase text-[#ffbf00] mb-3">Événements</p>
            <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight">Des rencontres qui font avancer le marché financier régional</h1>
            <p class="mt-4 text-white/80 max-w-2xl text-base md:text-lg leading-relaxed">
                Découvrez nos événements, rencontres et initiatives dédiés à l'information, à la formation et au développement des acteurs du marché financier.
            </p>
            <div class="mt-8 flex flex-wrap gap-6 text-sm">
                <div>
                    <p class="text-2xl font-extrabold text-[#ffbf00] tabular-nums">{{ $stats['upcoming'] }}</p>
                    <p class="text-white/70">À venir</p>
                </div>
                <div class="w-px bg-white/20"></div>
                <div>
                    <p class="text-2xl font-extrabold text-[#ffbf00] tabular-nums">{{ $stats['featured'] }}</p>
                    <p class="text-white/70">À la une</p>
                </div>
                <div class="w-px bg-white/20"></div>
                <div>
                    <p class="text-2xl font-extrabold text-[#ffbf00] tabular-nums">{{ $stats['cities'] }}</p>
                    <p class="text-white/70">Villes</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Featured --}}
    @if ($featured && $filterType !== 'past')
        <section class="max-w-[1280px] mx-auto px-5 lg:px-16 -mt-8 relative z-10">
            <a href="{{ route('event-detail', $featured->slug) }}"
                class="group block rounded-2xl overflow-hidden border border-[#c5c5d4] bg-white shadow-lg hover:border-[#001a61] transition">
                <div class="grid lg:grid-cols-5">
                    <div class="lg:col-span-2 h-52 lg:h-auto min-h-[220px] bg-[#e7eeff] relative overflow-hidden">
                        @if ($featured->featured_image)
                            <img src="{{ Str::startsWith($featured->featured_image, 'http') ? $featured->featured_image : (Str::startsWith($featured->featured_image, 'assets/') ? asset($featured->featured_image) : asset('storage/'.$featured->featured_image)) }}"
                                alt="{{ $featured->title }}"
                                class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="material-symbols-outlined text-7xl text-[#001a61]/30">event</span>
                            </div>
                        @endif
                        <span class="absolute top-4 left-4 px-3 py-1 rounded-md bg-[#ffbf00] text-[#261a00] text-xs font-extrabold uppercase tracking-wide">
                            À la une
                        </span>
                    </div>
                    <div class="lg:col-span-3 p-6 lg:p-8 flex flex-col justify-center">
                        <div class="flex flex-wrap items-center gap-2 text-xs">
                            @if ($featured->category)
                                <span class="font-bold uppercase text-[#0a2e8c]">{{ $featured->category }}</span>
                            @endif
                            <span class="px-2 py-0.5 rounded bg-[#e7eeff] text-[#001a61] font-semibold">{{ $formatLabel($featured->event_type) }}</span>
                            @if ($featured->isRegistrationOpen())
                                <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-800 font-semibold">Inscriptions ouvertes</span>
                            @elseif (! $featured->isPast())
                                <span class="px-2 py-0.5 rounded bg-[#f0f0f4] text-[#757683] font-semibold">Inscriptions fermées</span>
                            @endif
                        </div>
                        <h2 class="mt-3 text-2xl lg:text-3xl font-extrabold text-[#001a61] group-hover:underline decoration-[#ffbf00] underline-offset-4">
                            {{ $featured->title }}
                        </h2>
                        <p class="mt-3 text-[#444652] line-clamp-2 max-w-2xl">{{ plain_text($featured->description, 200) }}</p>
                        <div class="mt-5 flex flex-wrap gap-4 text-sm text-[#757683]">
                            <span class="inline-flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-base text-[#001a61]">calendar_month</span>
                                {{ optional($featured->starts_at)->translatedFormat('l j F Y — H:i') }}
                            </span>
                            <span class="inline-flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-base text-[#001a61]">{{ $formatIcon($featured->event_type) }}</span>
                                {{ $featured->location_name ?? $featured->city ?? 'En ligne' }}
                                @if ($featured->city && $featured->location_name) · {{ $featured->city }}@endif
                            </span>
                        </div>
                        <span class="mt-6 inline-flex items-center gap-2 self-start font-bold text-[#001a61]">
                            Voir l’événement
                            <span class="material-symbols-outlined text-lg group-hover:translate-x-1 transition">arrow_forward</span>
                        </span>
                    </div>
                </div>
            </a>
        </section>
    @endif

    {{-- Filters --}}
    <section class="max-w-[1280px] mx-auto px-5 lg:px-16 py-8">
        <div class="bg-white border border-[#c5c5d4] rounded-xl p-4 space-y-3">
            <div class="flex flex-col lg:flex-row gap-3">
                <div class="relative flex-1">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#757683] text-xl">search</span>
                    <input type="search" wire:model.live.debounce.300ms="search" placeholder="Rechercher un événement…"
                        class="w-full pl-10 rounded-lg border-[#c5c5d4] focus:border-[#001a61] focus:ring-[#001a61]">
                </div>
                <select wire:model.live="filterCategory" class="rounded-lg border-[#c5c5d4] lg:w-44">
                    <option value="">Toutes catégories</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
                <select wire:model.live="filterCity" class="rounded-lg border-[#c5c5d4] lg:w-40">
                    <option value="">Toutes villes</option>
                    @foreach ($cities as $city)
                        <option value="{{ $city }}">{{ $city }}</option>
                    @endforeach
                </select>
                <select wire:model.live="filterFormat" class="rounded-lg border-[#c5c5d4] lg:w-40">
                    <option value="">Tous formats</option>
                    <option value="physical">Présentiel</option>
                    <option value="online">En ligne</option>
                    <option value="hybrid">Hybride</option>
                </select>
            </div>
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex flex-wrap gap-2">
                    @foreach (['upcoming' => 'À venir', 'featured' => 'À la une', 'past' => 'Passés', 'all' => 'Tous'] as $key => $label)
                        <button type="button" wire:click="$set('filterType', '{{ $key }}')"
                            @class([
                                'px-3 py-1.5 rounded-lg text-sm font-bold border transition',
                                'bg-[#001a61] text-white border-[#001a61]' => $filterType === $key,
                                'bg-white text-[#444652] border-[#c5c5d4] hover:border-[#001a61] hover:text-[#001a61]' => $filterType !== $key,
                            ])>
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
                @if ($search || $filterCategory || $filterCity || $filterFormat || $filterType !== 'upcoming')
                    <button type="button" wire:click="clearFilters" class="text-sm font-bold text-[#0a2e8c] hover:underline">
                        Réinitialiser
                    </button>
                @endif
            </div>
        </div>
        <p class="mt-4 text-sm text-[#757683]">
            <span class="font-bold text-[#001a61] tabular-nums">{{ $events->count() }}</span>
            événement{{ $events->count() > 1 ? 's' : '' }}
            @if ($filterType === 'upcoming') à venir
            @elseif ($filterType === 'past') passé{{ $events->count() > 1 ? 's' : '' }}
            @elseif ($filterType === 'featured') à la une
            @endif
        </p>
    </section>

    {{-- Grid --}}
    <section class="max-w-[1280px] mx-auto px-5 lg:px-16 pb-20">
        <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse ($events as $event)
                @php
                    $regLabel = $event->registrationStatusLabel();
                    $img = $event->featured_image
                        ? (Str::startsWith($event->featured_image, 'http') ? $event->featured_image : (Str::startsWith($event->featured_image, 'assets/') ? asset($event->featured_image) : asset('storage/'.$event->featured_image)))
                        : null;
                @endphp
                <article class="bg-white border border-[#c5c5d4] rounded-xl overflow-hidden flex flex-col hover:border-[#001a61] transition group">
                    <a href="{{ route('event-detail', $event->slug) }}" class="relative block h-44 bg-[#e7eeff] overflow-hidden">
                        @if ($img)
                            <img src="{{ $img }}" alt="" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-5xl text-[#001a61]/25">event</span>
                            </div>
                        @endif
                        <div class="absolute top-3 left-3 flex flex-wrap gap-1.5">
                            @if ($event->is_featured)
                                <span class="px-2 py-0.5 rounded bg-[#ffbf00] text-[#261a00] text-[10px] font-extrabold uppercase">À la une</span>
                            @endif
                            @if ($regLabel === 'Terminé')
                                <span class="px-2 py-0.5 rounded bg-black/60 text-white text-[10px] font-bold uppercase">Terminé</span>
                            @elseif ($regLabel === 'Ouvert')
                                <span class="px-2 py-0.5 rounded bg-emerald-600 text-white text-[10px] font-bold uppercase">Ouvert</span>
                            @else
                                <span class="px-2 py-0.5 rounded bg-[#757683] text-white text-[10px] font-bold uppercase">Fermé</span>
                            @endif
                        </div>
                    </a>
                    <div class="p-5 flex flex-col flex-1">
                        <div class="flex flex-wrap items-center gap-2 text-xs">
                            @if ($event->category)
                                <span class="font-bold uppercase text-[#0a2e8c]">{{ $event->category }}</span>
                            @endif
                            <span class="text-[#757683]">·</span>
                            <span class="text-[#757683]">{{ $formatLabel($event->event_type) }}</span>
                        </div>
                        <h2 class="font-bold text-lg text-[#001a61] mt-2 leading-snug">
                            <a href="{{ route('event-detail', $event->slug) }}" class="hover:underline">{{ $event->title }}</a>
                        </h2>
                        <p class="text-sm text-[#444652] mt-2 line-clamp-2 flex-1">{{ plain_text($event->description, 140) }}</p>
                        <div class="mt-4 space-y-1.5 text-xs text-[#757683]">
                            <p class="inline-flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-sm text-[#001a61]">calendar_month</span>
                                {{ optional($event->starts_at)->format('d/m/Y H:i') }}
                                @if ($event->ends_at && ! $event->starts_at?->isSameDay($event->ends_at))
                                    → {{ $event->ends_at->format('d/m/Y') }}
                                @endif
                            </p>
                            <p class="inline-flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-sm text-[#001a61]">{{ $formatIcon($event->event_type) }}</span>
                                {{ $event->city ?? $event->location_name ?? 'En ligne' }}
                            </p>
                        </div>
                        <div class="mt-4 pt-4 border-t border-[#c5c5d4] flex items-center justify-between gap-2">
                            <a href="{{ route('event-detail', $event->slug) }}"
                                class="inline-flex items-center gap-1 text-sm font-bold text-[#001a61] hover:text-[#0a2e8c]">
                                Détails
                                <span class="material-symbols-outlined text-base">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-xl border border-dashed border-[#c5c5d4] bg-white p-12 text-center">
                    <span class="material-symbols-outlined text-5xl text-[#c5c5d4]">event_busy</span>
                    <p class="mt-3 font-bold text-[#001a61]">Aucun événement trouvé</p>
                    <p class="mt-1 text-sm text-[#757683]">Modifiez vos filtres ou revenez bientôt.</p>
                    <button type="button" wire:click="clearFilters"
                        class="mt-5 inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-[#001a61] text-white text-sm font-bold">
                        Réinitialiser les filtres
                    </button>
                </div>
            @endforelse
        </div>
    </section>

    {{-- Événements réalisés --}}
    @if ($pastWithGallery->isNotEmpty())
    <section class="max-w-[1280px] mx-auto px-5 lg:px-16 pb-16">
        <div class="mb-8">
            <span class="text-[#001a61] text-sm font-medium uppercase tracking-wider">📸 Événements réalisés</span>
            <h2 class="text-2xl md:text-3xl font-extrabold text-[#001a61] mt-2">Retour en images</h2>
        </div>

        <div class="space-y-10">
            @foreach ($pastWithGallery as $pastEvent)
                @php
                    $featImg = $pastEvent->featured_image
                        ? (Str::startsWith($pastEvent->featured_image, 'http') ? $pastEvent->featured_image : asset($pastEvent->featured_image))
                        : ($pastEvent->galleries->first() ? asset($pastEvent->galleries->first()->image_path) : null);
                @endphp
                <article class="rounded-2xl border border-[#c5c5d4] bg-white overflow-hidden">
                    <div class="grid lg:grid-cols-5">
                        <div class="lg:col-span-2 h-56 lg:h-auto min-h-[240px] bg-[#e7eeff] relative overflow-hidden">
                            @if ($featImg)
                                <img src="{{ $featImg }}" alt="{{ $pastEvent->title }}" class="absolute inset-0 w-full h-full object-cover">
                            @else
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-7xl text-[#001a61]/30">photo_camera</span>
                                </div>
                            @endif
                            <span class="absolute top-4 left-4 px-3 py-1 rounded-md bg-black/60 text-white text-xs font-extrabold uppercase tracking-wide">
                                Terminé
                            </span>
                        </div>
                        <div class="lg:col-span-3 p-6 lg:p-8 flex flex-col justify-center">
                            <h3 class="text-xl lg:text-2xl font-extrabold text-[#001a61]">{{ $pastEvent->title }}</h3>
                            <div class="mt-3 flex flex-wrap gap-4 text-sm text-[#757683]">
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-base text-[#001a61]">calendar_month</span>
                                    {{ optional($pastEvent->starts_at)->translatedFormat('j F Y') }}
                                </span>
                                @if ($pastEvent->city)
                                    <span class="inline-flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-base text-[#001a61]">location_on</span>
                                        {{ $pastEvent->city }}{{ $pastEvent->country ? ', '.$pastEvent->country : '' }}
                                    </span>
                                @endif
                            </div>
                            <p class="mt-3 text-[#444652] text-sm leading-relaxed line-clamp-3">{{ plain_text($pastEvent->description, 250) }}</p>
                            <a href="{{ route('event-detail', $pastEvent->slug) }}" class="mt-4 inline-flex items-center gap-2 text-sm font-bold text-[#001a61] hover:underline">
                                Voir les photos →
                            </a>
                        </div>
                    </div>

                    @if ($pastEvent->galleries->count() > 0)
                        <div class="border-t border-[#c5c5d4] p-4 lg:p-6 bg-[#f9f9ff]">
                            <div class="grid grid-cols-3 md:grid-cols-6 gap-2 md:gap-3">
                                @foreach ($pastEvent->galleries->take(6) as $photo)
                                    <div class="aspect-[4/3] rounded-lg overflow-hidden bg-[#e7eeff]">
                                        <img src="{{ asset($photo->image_path) }}" alt="{{ $photo->caption ?? '' }}" class="w-full h-full object-cover hover:scale-105 transition duration-300">
                                    </div>
                                @endforeach
                            </div>
                            @if ($pastEvent->galleries->count() > 6)
                                <div class="mt-3 text-center">
                                    <a href="{{ route('event-detail', $pastEvent->slug) }}" class="text-sm font-bold text-[#001a61] hover:underline">
                                        Voir la galerie complète →
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif
                </article>
            @endforeach
        </div>
    </section>
    @endif

    {{-- CTA --}}
    <section class="border-t border-[#c5c5d4] bg-white">
        <div class="max-w-[1280px] mx-auto px-5 lg:px-16 py-12 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="text-xl font-extrabold text-[#001a61]">Organisez ou sponsorisez un événement</h2>
                <p class="mt-1 text-sm text-[#444652] max-w-xl">Contactez Africaine des Finances pour un partenariat, un atelier ou une conférence sur les marchés UEMOA.</p>
            </div>
            <a href="{{ route('contact') }}"
                class="inline-flex items-center gap-2 shrink-0 px-5 py-3 rounded-xl bg-[#ffbf00] text-[#261a00] font-extrabold hover:brightness-105 transition">
                Nous contacter
                <span class="material-symbols-outlined text-lg">mail</span>
            </a>
        </div>
    </section>
</div>
