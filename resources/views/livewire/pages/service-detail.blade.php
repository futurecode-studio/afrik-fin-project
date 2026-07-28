<div class="bg-[#f9f9ff] text-[#131c2a] min-h-screen">
    <section class="max-w-[1280px] mx-auto px-5 lg:px-16 pt-10">
        <a href="{{ route('services') }}" class="text-sm font-bold text-[#001a61] hover:underline">← Tous les services</a>
    </section>

    <section class="max-w-[1280px] mx-auto px-5 lg:px-16 py-10 grid lg:grid-cols-5 gap-10">
        <div class="lg:col-span-3">
            <p class="text-sm font-semibold tracking-widest uppercase text-[#0a2e8c]">Service</p>
            <h1 class="text-3xl md:text-4xl font-extrabold text-[#001a61] mt-2">{{ $service->title }}</h1>
            @if ($service->subtitle)
                <p class="text-lg text-[#444652] mt-3">{{ $service->subtitle }}</p>
            @endif
            @if ($service->excerpt)
                <p class="mt-6 text-[#444652] leading-relaxed">{{ plain_text($service->excerpt) }}</p>
            @endif
            @if ($service->content)
                <div class="mt-6 prose prose-slate max-w-none text-[#444652]">{!! rich_html($service->content) !!}</div>
            @endif

            @if (!empty($service->features))
                <h2 class="text-xl font-bold text-[#001a61] mt-10 mb-4">Ce que vous obtenez</h2>
                <ul class="space-y-3">
                    @foreach ($service->features as $feature)
                        <li class="flex gap-3 items-start">
                            <span class="material-symbols-outlined text-[#0a7a3e] text-xl">check_circle</span>
                            <span>{{ $feature }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <aside class="lg:col-span-2">
            <div class="bg-white border border-[#c5c5d4] rounded-xl p-6 sticky top-32">
                <span class="material-symbols-outlined text-[#001a61] text-4xl">{{ $service->icon ?: 'handshake' }}</span>
                @if ($service->price_label)
                    <p class="text-sm text-[#757683] mt-4">Tarif</p>
                    <p class="text-2xl font-extrabold text-[#001a61]">{{ $service->price_label }}</p>
                @endif
                @if ($service->duration_label)
                    <p class="text-sm text-[#444652] mt-2">{{ $service->duration_label }}</p>
                @endif
                <a href="{{ $service->cta_url ?: route('contact') }}"
                    class="mt-6 block text-center bg-[#001a61] text-white font-bold px-5 py-3 rounded hover:bg-[#0a2e8c] transition">
                    {{ $service->cta_label ?: 'Nous contacter' }}
                </a>
            </div>
        </aside>
    </section>

    @if ($related->isNotEmpty())
        <section class="max-w-[1280px] mx-auto px-5 lg:px-16 pb-20">
            <h2 class="text-xl font-bold text-[#001a61] mb-4">Autres services</h2>
            <div class="grid sm:grid-cols-3 gap-4">
                @foreach ($related as $item)
                    <a href="{{ route('service-detail', $item->slug) }}" class="bg-white border border-[#c5c5d4] rounded-xl p-4 hover:border-[#001a61] block">
                        <p class="font-bold text-[#001a61]">{{ $item->title }}</p>
                        <p class="text-sm text-[#757683] mt-1 line-clamp-2">{{ plain_text($item->excerpt, 120) }}</p>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
</div>
