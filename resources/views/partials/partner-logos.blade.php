@php
    $items = $partners ?? collect();
    if ($items->isEmpty()) {
        $items = \App\Models\Partner::catalogCollection();
    }

    $height = $height ?? 'h-14';
    $linkToList = $linkToList ?? false;
    $compact = $height === 'h-10';
    $duration = max(28, min(70, $items->count() * 4));
@endphp

<div
    class="partner-marquee {{ $compact ? 'partner-marquee--compact' : '' }}"
    data-partner-marquee
    style="--partner-marquee-duration: {{ $duration }}s;"
    aria-label="Logos des partenaires"
>
    <div class="partner-marquee__edge partner-marquee__edge--left" aria-hidden="true"></div>
    <div class="partner-marquee__edge partner-marquee__edge--right" aria-hidden="true"></div>

    <div class="partner-marquee__viewport" data-partner-marquee-viewport>
        <div class="partner-marquee__track" data-partner-marquee-track>
            @foreach ([1, 2] as $copy)
                @foreach ($items as $partner)
                    @php
                        $src = $partner->logo_url ?? null;
                        $href = $partner->getKey()
                            ? route('partenaires.show', $partner->id)
                            : ($partner->website ?: route('partenaires'));
                    @endphp
                    @if ($src)
                        <a href="{{ $linkToList ? route('partenaires') : $href }}"
                            @if (! $linkToList && $partner->website) target="_blank" rel="noopener noreferrer" @endif
                            class="partner-marquee__item {{ $height }}"
                            data-partner-marquee-item
                            aria-label="{{ $partner->nom }}">
                            <span class="partner-marquee__logo">
                                <img src="{{ $src }}" alt="" class="partner-marquee__img" loading="lazy" decoding="async">
                            </span>
                            <span class="partner-marquee__name">{{ $partner->nom }}</span>
                        </a>
                    @else
                        <div class="partner-marquee__item partner-marquee__item--text {{ $height }}" data-partner-marquee-item>
                            <span class="partner-marquee__name partner-marquee__name--solo">{{ $partner->nom }}</span>
                        </div>
                    @endif
                @endforeach
            @endforeach
        </div>
    </div>

    <p class="partner-marquee__hint" aria-hidden="true">
        <span class="hidden md:inline">Survolez un logo pour le détail</span>
        <span class="md:hidden">Touchez un logo pour mettre en pause</span>
    </p>
</div>

@once
    @push('scripts')
        <script>
            (() => {
                const marquees = document.querySelectorAll('[data-partner-marquee]');
                if (!marquees.length) return;

                marquees.forEach((root) => {
                    let resumeTimer = null;

                    const pause = () => {
                        root.classList.add('is-paused');
                        clearTimeout(resumeTimer);
                    };

                    const resume = (delay = 0) => {
                        clearTimeout(resumeTimer);
                        resumeTimer = setTimeout(() => {
                            root.classList.remove('is-paused');
                            root.querySelectorAll('[data-partner-marquee-item].is-active')
                                .forEach((el) => el.classList.remove('is-active'));
                        }, delay);
                    };

                    root.querySelectorAll('[data-partner-marquee-item]').forEach((item) => {
                        item.addEventListener('mouseenter', pause);
                        item.addEventListener('mouseleave', () => resume(120));

                        item.addEventListener('touchstart', () => {
                            pause();
                            root.querySelectorAll('[data-partner-marquee-item].is-active')
                                .forEach((el) => el.classList.remove('is-active'));
                            item.classList.add('is-active');
                            resume(4000);
                        }, { passive: true });
                    });

                    root.addEventListener('mouseleave', () => resume(0));

                    document.addEventListener('touchstart', (event) => {
                        if (!root.contains(event.target)) {
                            resume(0);
                        }
                    }, { passive: true });
                });
            })();
        </script>
    @endpush
@endonce
