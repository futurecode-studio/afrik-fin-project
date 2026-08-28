<div class="bg-[#f9f9ff] text-[#131c2a] min-h-screen" x-data="{ lightbox: null }">
    <section class="bg-[#001a61] text-white">
        <div class="max-w-[1280px] mx-auto px-5 lg:px-16 py-12 lg:py-16">
            <a href="{{ route('formation-detail', $formation->slug) }}"
                class="inline-flex items-center gap-1.5 text-sm font-semibold text-white/80 hover:text-[#ffbf00] transition mb-6">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                Retour à la formation
            </a>
            <p class="text-sm font-semibold tracking-widest uppercase text-[#ffbf00] mb-3">Catalogue Fidis Invest</p>
            <h1 class="text-3xl md:text-4xl font-extrabold max-w-4xl leading-tight">{{ $formation->titre }}</h1>
            <p class="mt-4 text-white/80 max-w-2xl">
                Parcourez les programmes certifiants et formations professionnelles proposés par notre partenaire Fidis Invest.
            </p>
        </div>
    </section>

    <section class="max-w-[1280px] mx-auto px-5 lg:px-16 py-10 lg:py-14">
        @if ($items->isEmpty())
            <div class="rounded-2xl border border-dashed border-[#c5c5d4] bg-white p-12 text-center">
                <span class="material-symbols-outlined text-5xl text-[#c5c5d4]">collections</span>
                <p class="mt-4 font-bold text-[#001a61]">Catalogue en cours de mise à jour</p>
                <p class="mt-2 text-sm text-[#757683]">Contactez-nous pour recevoir les programmes disponibles.</p>
                <a href="{{ route('contact') }}" class="inline-block mt-6 bg-[#001a61] text-white font-bold px-6 py-3 rounded-xl hover:bg-[#0a2e8c]">
                    Nous contacter
                </a>
            </div>
        @else
            <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-5 lg:gap-6">
                @foreach ($items as $item)
                    <button type="button"
                        @click="lightbox = @js(['src' => $item->image_url, 'title' => $item->title])"
                        class="group text-left bg-white border border-[#c5c5d4] rounded-2xl overflow-hidden hover:border-[#001a61] hover:shadow-lg transition-all focus:outline-none focus:ring-2 focus:ring-[#001a61]/30">
                        <div class="aspect-[3/4] bg-[#e7eeff] overflow-hidden relative">
                            <img src="{{ $item->image_url }}" alt="{{ $item->title ?? 'Programme Fidis' }}"
                                class="w-full h-full object-cover object-top group-hover:scale-[1.02] transition-transform duration-300"
                                loading="lazy">
                            <span class="absolute inset-0 bg-[#001a61]/0 group-hover:bg-[#001a61]/10 transition-colors"></span>
                            <span class="absolute bottom-3 right-3 inline-flex items-center gap-1 rounded-full bg-white/95 px-2.5 py-1 text-[11px] font-bold text-[#001a61] opacity-0 group-hover:opacity-100 transition-opacity">
                                <span class="material-symbols-outlined text-sm">zoom_in</span> Agrandir
                            </span>
                        </div>
                        @if ($item->title)
                            <div class="p-4 border-t border-[#e7eeff]">
                                <p class="font-bold text-[#001a61] text-sm leading-snug">{{ $item->title }}</p>
                            </div>
                        @endif
                    </button>
                @endforeach
            </div>
        @endif
    </section>

    <section class="border-t border-[#c5c5d4] bg-white">
        <div class="max-w-[1280px] mx-auto px-5 lg:px-16 py-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <p class="font-extrabold text-[#001a61] text-lg">Intéressé par un programme ?</p>
                <p class="text-sm text-[#757683] mt-1">Notre équipe vous oriente vers la session adaptée à votre profil.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('contact') }}" class="adf-btn-gold inline-flex items-center gap-2 px-6 py-3 text-sm font-bold">
                    <span class="material-symbols-outlined text-[18px]">mail</span>
                    Demander des informations
                </a>
                <a href="{{ route('formations') }}" class="inline-flex items-center gap-2 border border-[#001a61] text-[#001a61] font-bold px-6 py-3 rounded-xl hover:bg-[#e7eeff] text-sm">
                    Toutes les formations
                </a>
            </div>
        </div>
    </section>

    <div x-show="lightbox" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none">
        <div class="absolute inset-0 bg-[#001a61]/80 backdrop-blur-sm" @click="lightbox = null"></div>
        <div class="relative max-w-4xl w-full max-h-[92vh] flex flex-col" @click.outside="lightbox = null">
            <button type="button" @click="lightbox = null"
                class="absolute -top-2 right-0 md:-right-2 z-10 w-10 h-10 rounded-full bg-white text-[#001a61] shadow-lg flex items-center justify-center hover:bg-[#ffbf00]"
                aria-label="Fermer">
                <span class="material-symbols-outlined">close</span>
            </button>
            <div class="bg-white rounded-2xl overflow-hidden shadow-2xl">
                <img :src="lightbox?.src" :alt="lightbox?.title || 'Catalogue'" class="w-full max-h-[80vh] object-contain bg-[#f0f3ff]">
                <p x-show="lightbox?.title" x-text="lightbox?.title" class="px-5 py-4 font-bold text-[#001a61] border-t border-[#e7eeff]"></p>
            </div>
        </div>
    </div>
</div>
