@php
    $jeudi = \App\Models\Event::nextJeudiPopup();
@endphp
@if ($jeudi && ! request()->routeIs(['event-detail', 'connexion', 'inscription', 'password.request', 'password.reset']))
    @php
        $jeudiImg = $jeudi->featured_image
            ? (str_starts_with($jeudi->featured_image, 'http') ? $jeudi->featured_image : asset('storage/'.$jeudi->featured_image))
            : asset('assets/images/hero-04-equipe.jpg');
        $jeudiRegisterUrl = route('event-detail', $jeudi->slug);
    @endphp
    <div
        x-data="{
            open: false,
            id: {{ $jeudi->id }},
            key() { return 'adf_jeudi_popup_' + this.id; },
            init() {
                try {
                    if (sessionStorage.getItem(this.key())) return;
                } catch (e) {}
                setTimeout(() => { this.open = true }, 900);
            },
            dismiss() {
                this.open = false;
                try { sessionStorage.setItem(this.key(), '1'); } catch (e) {}
            }
        }"
        x-show="open"
        x-cloak
        x-effect="document.body.classList.toggle('overflow-hidden', open)"
        class="fixed inset-0 z-[80] flex items-center justify-center p-4"
        role="dialog"
        aria-modal="true"
        aria-labelledby="jeudi-popup-title"
        @keydown.escape.window="dismiss()"
    >
        <div class="absolute inset-0 bg-[#001a61]/75 backdrop-blur-sm" @click="dismiss()"></div>

        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-6 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            class="relative z-10 w-full max-w-xl overflow-hidden rounded-3xl bg-white shadow-2xl ring-2 ring-[#ffbf00]"
        >
            <div class="relative h-44 bg-[#001a61]">
                <img src="{{ $jeudiImg }}" alt="" class="absolute inset-0 w-full h-full object-cover opacity-70">
                <div class="absolute inset-0 bg-gradient-to-t from-[#001a61] via-[#001a61]/40 to-transparent"></div>
                <button type="button" @click="dismiss()" class="absolute top-3 right-3 w-9 h-9 rounded-full bg-black/35 text-white hover:bg-black/50 flex items-center justify-center" aria-label="Fermer">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
                <div class="absolute bottom-4 left-5 right-5">
                    <p class="inline-flex items-center gap-1 text-[10px] font-extrabold uppercase tracking-[0.18em] bg-[#ffbf00] text-[#261a00] px-2.5 py-1 rounded-full">
                        Hebdo · Gratuit
                    </p>
                    <p class="mt-2 text-white/85 text-sm font-bold">Jeudi d’opportunité</p>
                </div>
            </div>

            <div class="p-6 pt-5">
                <h2 id="jeudi-popup-title" class="text-2xl font-extrabold text-[#001a61] leading-tight">
                    {{ $jeudi->title }}
                </h2>
                <p class="mt-2 text-sm text-[#444652] flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#001a61] text-[18px]">event</span>
                    {{ optional($jeudi->starts_at)->translatedFormat('l d F Y — H\hi') }}
                </p>
                @if ($jeudi->description)
                    <p class="mt-3 text-sm text-[#444652] leading-relaxed line-clamp-3">{{ plain_text($jeudi->description, 180) }}</p>
                @endif
                <div class="mt-6 flex flex-col sm:flex-row gap-3">
                    <a href="{{ $jeudiRegisterUrl }}"
                        class="flex-1 inline-flex items-center justify-center gap-2 bg-[#ffbf00] text-[#261a00] font-extrabold px-5 py-3 rounded-xl hover:brightness-95 transition">
                        Je m’inscris
                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </a>
                    <button type="button" @click="dismiss()"
                        class="sm:w-auto inline-flex items-center justify-center px-5 py-3 rounded-xl border border-[#c5c5d4] text-sm font-bold text-[#001a61] hover:bg-[#f0f3ff]">
                        Plus tard
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
