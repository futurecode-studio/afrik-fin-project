<div>
    <div class="mb-6 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <a href="{{ route('client.formation', $formation->slug) }}" class="text-sm font-bold text-[#001a61] underline">← Cours</a>
            <h1 class="text-3xl font-extrabold text-[#001a61] mt-2">Bibliothèque de ressources</h1>
            <p class="text-[#444652] mt-1">{{ $formation->titre }}</p>
        </div>
        <select wire:model.live="moduleFilter" class="rounded-lg border-[#c5c5d4] text-sm">
            <option value="">Tous les modules</option>
            @foreach ($formation->modules as $m)
                <option value="{{ $m->id }}">{{ $m->titre }}</option>
            @endforeach
        </select>
    </div>

    <div class="grid md:grid-cols-2 gap-4">
        @foreach ($resources as $r)
            <a href="{{ $r->url }}" target="_blank" class="bg-white border border-[#c5c5d4] rounded-xl p-4 hover:border-[#001a61] flex items-center gap-3">
                <span class="material-symbols-outlined text-[#001a61] text-3xl">
                    {{ $r->type === 'pdf' ? 'picture_as_pdf' : ($r->type === 'video' ? 'play_circle' : 'link') }}
                </span>
                <div class="min-w-0">
                    <p class="font-bold text-[#001a61] truncate">{{ $r->title }}</p>
                    <p class="text-xs text-[#757683] uppercase">{{ $r->type }}{{ $r->file_size ? ' · '.$r->file_size : '' }}</p>
                </div>
            </a>
        @endforeach

        @foreach ($lessonResources as $r)
            <a href="{{ $r['url'] }}" target="_blank" class="bg-white border border-[#c5c5d4] rounded-xl p-4 hover:border-[#001a61] flex items-center gap-3">
                <span class="material-symbols-outlined text-[#001a61] text-3xl">{{ $r['type'] === 'pdf' ? 'picture_as_pdf' : 'play_circle' }}</span>
                <div class="min-w-0">
                    <p class="font-bold text-[#001a61] truncate">{{ $r['title'] }}</p>
                    <p class="text-xs text-[#757683]">{{ $r['module'] }} · {{ strtoupper($r['type']) }}</p>
                </div>
            </a>
        @endforeach
    </div>

    @if ($resources->isEmpty() && $lessonResources->isEmpty())
        <p class="text-center text-[#757683] py-16">Aucune ressource publiée pour le moment.</p>
    @endif
</div>
