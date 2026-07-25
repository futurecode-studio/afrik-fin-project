<div class="bg-[#f9f9ff] text-[#131c2a] min-h-screen">
    <section class="bg-[#001a61] text-white">
        <div class="max-w-[1280px] mx-auto px-5 lg:px-16 py-16 lg:py-24">
            <p class="text-sm font-semibold tracking-widest uppercase text-[#ffbf00] mb-3">Solutions</p>
            <h1 class="text-3xl md:text-5xl font-extrabold max-w-3xl leading-tight">Nos solutions &amp; services financiers</h1>
            <p class="mt-4 text-white/80 max-w-2xl text-lg">Formation, marchés et conseil — un accompagnement structuré pour investir en UEMOA.</p>
        </div>
    </section>

    <section class="max-w-[1280px] mx-auto px-5 lg:px-16 py-14">
        @if ($services->isEmpty())
            <div class="bg-white border border-dashed border-[#c5c5d4] rounded-xl p-12 text-center">
                <p class="text-[#444652]">Aucun service publié pour le moment.</p>
            </div>
        @else
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($services as $service)
                    <a href="{{ route('service-detail', $service->slug) }}"
                        class="group bg-white border border-[#c5c5d4] rounded-xl p-6 hover:border-[#001a61] hover:shadow-sm transition flex flex-col">
                        <span class="material-symbols-outlined text-[#001a61] text-4xl">{{ $service->icon ?: 'handshake' }}</span>
                        <h2 class="text-xl font-bold text-[#001a61] mt-4">{{ $service->title }}</h2>
                        @if ($service->subtitle)
                            <p class="text-sm font-medium text-[#0a2e8c] mt-1">{{ $service->subtitle }}</p>
                        @endif
                        <p class="text-[#444652] mt-3 text-sm flex-1">{{ $service->excerpt }}</p>
                        @if ($service->price_label)
                            <p class="mt-4 font-bold text-[#001a61]">{{ $service->price_label }}</p>
                        @endif
                        <span class="mt-4 inline-flex items-center gap-1 text-sm font-bold text-[#001a61] group-hover:gap-2 transition-all">
                            {{ $service->cta_label ?: 'En savoir plus' }}
                            <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </span>
                    </a>
                @endforeach
            </div>
        @endif
    </section>
</div>
