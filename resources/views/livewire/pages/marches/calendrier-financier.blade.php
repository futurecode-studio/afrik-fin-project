<div class="bg-[#f9f9ff] text-[#131c2a] min-h-screen">
    @include('partials.investir-nav')
    @include('partials.markets-nav')

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pt-10 pb-6">
        <h1 class="text-3xl md:text-4xl font-extrabold text-[#001a61]">Calendrier financier</h1>
        <p class="mt-2 text-[#444652]">Adjudications, émissions et échéances obligataires + événements publiés.</p>
    </section>

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pb-6 flex items-center justify-between gap-4">
        <button type="button" wire:click="prev" class="px-4 py-2 rounded border border-[#c5c5d4] bg-white font-bold text-[#001a61] hover:bg-[#e7eeff]">←</button>
        <h2 class="text-xl font-bold text-[#001a61] capitalize">{{ $label }}</h2>
        <button type="button" wire:click="next" class="px-4 py-2 rounded border border-[#c5c5d4] bg-white font-bold text-[#001a61] hover:bg-[#e7eeff]">→</button>
    </section>

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pb-8">
        <div class="grid grid-cols-7 gap-1 text-center text-xs font-bold text-[#757683] mb-2">
            @foreach (['Lun','Mar','Mer','Jeu','Ven','Sam','Dim'] as $d)
                <div class="py-2">{{ $d }}</div>
            @endforeach
        </div>
        <div class="grid grid-cols-7 gap-1">
            @for ($i = 1; $i < $startWeekday; $i++)
                <div class="min-h-[5.5rem] bg-transparent"></div>
            @endfor
            @for ($day = 1; $day <= $daysInMonth; $day++)
                @php $dayEvents = $byDay->get($day, collect()); @endphp
                <div @class([
                    'min-h-[5.5rem] bg-white border border-[#c5c5d4] rounded-lg p-1.5 text-left',
                    'ring-2 ring-[#ffbf00]' => $year === (int) now()->year && $month === (int) now()->month && $day === (int) now()->day,
                ])>
                    <p class="text-xs font-bold text-[#001a61] px-1">{{ $day }}</p>
                    <div class="mt-1 space-y-0.5">
                        @foreach ($dayEvents->take(3) as $ev)
                            <a href="{{ $ev['url'] ?? '#' }}"
                                @class([
                                    'block text-[10px] leading-tight px-1 py-0.5 rounded truncate',
                                    'bg-[#e7eeff] text-[#001a61]' => $ev['type'] === 'adjudication',
                                    'bg-[#fff3cc] text-[#7a5c00]' => $ev['type'] === 'emission',
                                    'bg-[#fce7e7] text-[#991b1b]' => $ev['type'] === 'echeance',
                                    'bg-[#e8f5e9] text-[#166534]' => $ev['type'] === 'evenement',
                                ])
                                title="{{ $ev['label'] }} — {{ $ev['title'] }}">
                                {{ $ev['label'] }}
                            </a>
                        @endforeach
                        @if ($dayEvents->count() > 3)
                            <p class="text-[10px] text-[#757683] px-1">+{{ $dayEvents->count() - 3 }}</p>
                        @endif
                    </div>
                </div>
            @endfor
        </div>
    </section>

    <section class="px-5 lg:px-16 max-w-[1280px] mx-auto pb-20">
        <h2 class="text-lg font-bold text-[#001a61] mb-4">Événements du mois ({{ count($events) }})</h2>
        <div class="space-y-3">
            @forelse ($events as $ev)
                <a href="{{ $ev['url'] ?? '#' }}" class="block bg-white border border-[#c5c5d4] rounded-xl p-4 hover:border-[#001a61] transition">
                    <div class="flex flex-wrap gap-2 items-center text-xs">
                        <span class="font-bold text-[#001a61]">{{ \Carbon\Carbon::parse($ev['date'])->format('d/m/Y') }}</span>
                        <span class="px-2 py-0.5 rounded bg-[#e7eeff] text-[#001a61] font-semibold">{{ $ev['label'] }}</span>
                    </div>
                    <p class="font-semibold mt-2 text-[#131c2a]">{{ $ev['title'] }}</p>
                    <p class="text-sm text-[#757683]">{{ $ev['subtitle'] }}</p>
                </a>
            @empty
                <p class="text-[#757683] py-8 text-center">Aucun événement pour ce mois.</p>
            @endforelse
        </div>
    </section>
</div>
