@php
    $items = $partners ?? collect();
    $height = $height ?? 'h-14';
    $linkToList = $linkToList ?? false;
@endphp
<div class="flex flex-wrap justify-center items-center gap-x-10 gap-y-8">
    @forelse ($items as $partner)
        @php
            $src = $partner->logo_url ?? null;
            $href = $partner->getKey()
                ? route('partenaires.show', $partner->id)
                : ($partner->website ?: route('partenaires'));
        @endphp
        @if ($src)
            <a href="{{ $linkToList ? route('partenaires') : $href }}"
                @if (! $linkToList && $partner->website) target="_blank" rel="noopener noreferrer" @endif
                class="flex items-center justify-center {{ $height }} px-2"
                title="{{ $partner->nom }}">
                <img src="{{ $src }}" alt="{{ $partner->nom }}" class="max-h-full max-w-[140px] object-contain">
            </a>
        @else
            <div class="font-extrabold text-xl tracking-tighter text-[#757683]">{{ $partner->nom }}</div>
        @endif
    @empty
        @foreach (\App\Models\Partner::catalogCollection() as $partner)
            <a href="{{ route('partenaires') }}" class="flex items-center justify-center {{ $height }} px-2" title="{{ $partner->nom }}">
                <img src="{{ $partner->logo_url }}" alt="{{ $partner->nom }}" class="max-h-full max-w-[140px] object-contain">
            </a>
        @endforeach
    @endforelse
</div>
