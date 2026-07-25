<div class="bg-[#f9f9ff] text-[#131c2a] min-h-screen">
    <section class="bg-[#001a61] text-white">
        <div class="max-w-[1280px] mx-auto px-5 lg:px-16 py-16">
            <h1 class="text-3xl md:text-4xl font-extrabold">Calendrier des événements</h1>
            <p class="mt-3 text-white/80 max-w-2xl">Conférences, webinaires et rencontres financières.</p>
        </div>
    </section>

    <section class="max-w-[1280px] mx-auto px-5 lg:px-16 py-8">
        <div class="bg-white border border-[#c5c5d4] rounded-xl p-4 flex flex-col md:flex-row gap-3">
            <input type="search" wire:model.live.debounce.300ms="search" placeholder="Rechercher un événement…"
                class="flex-1 rounded-lg border-[#c5c5d4] focus:border-[#001a61] focus:ring-[#001a61]">
            <select wire:model.live="filterCategory" class="rounded-lg border-[#c5c5d4]">
                <option value="">Toutes catégories</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>
            <select wire:model.live="filterCity" class="rounded-lg border-[#c5c5d4]">
                <option value="">Toutes villes</option>
                @foreach ($cities as $city)
                    <option value="{{ $city }}">{{ $city }}</option>
                @endforeach
            </select>
            <select wire:model.live="filterType" class="rounded-lg border-[#c5c5d4]">
                <option value="">Tous</option>
                <option value="upcoming">À venir</option>
                <option value="featured">À la une</option>
                <option value="past">Passés</option>
            </select>
        </div>
    </section>

    <section class="max-w-[1280px] mx-auto px-5 lg:px-16 pb-20">
        <div class="grid md:grid-cols-2 gap-6">
            @forelse ($events as $event)
                <a href="{{ route('event-detail', $event->slug) }}"
                    class="bg-white border border-[#c5c5d4] rounded-xl overflow-hidden hover:border-[#001a61] transition flex flex-col sm:flex-row">
                    <div class="sm:w-40 h-36 sm:h-auto bg-[#e7eeff] shrink-0">
                        @if ($event->featured_image)
                            <img src="{{ Str::startsWith($event->featured_image, 'http') ? $event->featured_image : asset('storage/'.$event->featured_image) }}"
                                alt="" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div class="p-5 flex-1">
                        @if ($event->category)
                            <span class="text-xs font-bold text-[#0a2e8c]">{{ $event->category }}</span>
                        @endif
                        <h2 class="font-bold text-lg text-[#001a61] mt-1">{{ $event->title }}</h2>
                        <p class="text-sm text-[#444652] mt-2 line-clamp-2">{{ $event->description }}</p>
                        <p class="text-xs text-[#757683] mt-3">
                            {{ optional($event->starts_at)->format('d/m/Y H:i') }}
                            @if ($event->city) · {{ $event->city }}@endif
                        </p>
                    </div>
                </a>
            @empty
                <p class="col-span-full text-center py-16 text-[#757683]">Aucun événement trouvé.</p>
            @endforelse
        </div>
    </section>
</div>
