@php
    $heroImage = $formation->image_url ?: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=1600&h=900&fit=crop';
    $short = plain_text($formation->description_courte);
@endphp

<main class="flex-1">
    {{-- Flash via SweetAlert2 (layouts + Livewire dispatch) --}}

    {{-- Hero plein écran --}}
    <section class="relative min-h-[min(88vh,760px)] flex flex-col justify-end overflow-hidden">
        <div class="absolute inset-0">
            <img src="{{ $heroImage }}" alt="" class="w-full h-full object-cover scale-105 adf-float">
            <div class="absolute inset-0 bg-gradient-to-t from-[#001a61] via-[#001a61]/78 to-[#001a61]/35"></div>
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,rgba(255,191,0,0.18),transparent_45%)]"></div>
        </div>

        <div class="relative z-10 w-full max-w-[1200px] mx-auto px-5 lg:px-8 pb-10 pt-28">
            <a href="{{ route('formations') }}"
                class="inline-flex items-center gap-1.5 text-sm font-semibold text-white/80 hover:text-[#ffbf00] transition mb-6">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                Catalogue des formations
            </a>

            <div class="grid lg:grid-cols-12 gap-8 lg:gap-10 items-end">
                <div class="lg:col-span-7 adf-reveal">
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="adf-chip text-white/90 border-white/25 bg-white/10">Formation</span>
                        @if ($formation->niveau)
                            <span class="adf-chip text-[#261a00] bg-[#ffbf00] border-transparent font-bold uppercase tracking-wide">{{ $formation->niveau }}</span>
                        @endif
                        @if ($formation->isFree())
                            <span class="adf-chip bg-emerald-400/90 text-[#04301a] border-transparent font-bold">Gratuit</span>
                        @endif
                    </div>

                    <p class="text-[#ffbf00] text-xs font-extrabold uppercase tracking-[0.2em] mb-3">Africaine des Finances</p>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white leading-[1.12] max-w-3xl">
                        {{ $formation->titre }}
                    </h1>
                    @if ($short)
                        <p class="mt-4 text-base lg:text-lg text-white/80 max-w-2xl leading-relaxed">{{ $short }}</p>
                    @endif

                    <div class="mt-7 flex flex-wrap gap-x-6 gap-y-3 text-sm text-white/85">
                        @if (filled($formation->duree))
                            <span class="inline-flex items-center gap-1.5"><span class="material-symbols-outlined text-[#ffbf00] text-[20px]">schedule</span>{{ $formation->duree }}</span>
                        @endif
                        <span class="inline-flex items-center gap-1.5"><span class="material-symbols-outlined text-[#ffbf00] text-[20px]">view_module</span>{{ $formation->modules->count() }} modules</span>
                        <span class="inline-flex items-center gap-1.5"><span class="material-symbols-outlined text-[#ffbf00] text-[20px]">menu_book</span>{{ $lessonsCount }} leçons</span>
                        <span class="inline-flex items-center gap-1.5"><span class="material-symbols-outlined text-[#ffbf00] text-[20px]">workspace_premium</span>Certificat</span>
                    </div>
                </div>

                {{-- Panneau CTA --}}
                <aside class="lg:col-span-5 adf-reveal" style="animation-delay: 120ms">
                    <div class="adf-glass-strong rounded-2xl p-6 lg:p-7 border border-white/40" style="box-shadow: var(--adf-shadow-lg)">
                        <p class="text-xs font-bold uppercase tracking-widest text-[#757683]">Investissement</p>
                        @if ($formation->isCatalogOnly())
                            <p class="mt-1 text-3xl font-extrabold text-[#001a61] tracking-tight">{{ $formation->priceDisplay() }}</p>
                            <p class="mt-2 text-sm text-[#757683]">Tarifs et modalités sur demande auprès de notre équipe.</p>
                        @elseif ($formation->isFree())
                            <p class="mt-1 text-4xl font-extrabold text-[#001a61]">Gratuit</p>
                        @else
                            <p class="mt-1 text-4xl font-extrabold text-[#001a61] tracking-tight">
                                {{ number_format($formation->prix, 0, ',', ' ') }}
                                <span class="text-lg font-bold text-[#757683]">FCFA</span>
                            </p>
                        @endif

                        @if ($isEnrolled)
                            <button type="button" wire:click="accessCourse" class="adf-btn-gold w-full mt-5 h-14 text-base">
                                <span class="material-symbols-outlined">play_circle</span>
                                Continuer le cours
                            </button>
                        @elseif ($formation->isCatalogOnly())
                            <a href="{{ route('contact') }}" class="adf-btn-gold w-full mt-5 h-14 text-base inline-flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined">mail</span>
                                Nous contacter
                            </a>
                            <a href="{{ route('formations') }}"
                                class="mt-3 w-full h-12 rounded-xl border-2 border-[#001a61]/20 text-[#001a61] font-bold hover:bg-[#e7eeff] transition inline-flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                                Retour au catalogue
                            </a>
                        @else
                            <button type="button" wire:click="openPaymentModal" class="adf-btn-gold w-full mt-5 h-14 text-base">
                                <span class="material-symbols-outlined">rocket_launch</span>
                                @if ($formation->isFree())
                                    Commencer gratuitement
                                @else
                                    S'inscrire maintenant
                                @endif
                            </button>
                            @unless ($formation->isFree())
                                <button type="button" wire:click="addToCart"
                                    class="mt-3 w-full h-12 rounded-xl border-2 border-[#001a61]/20 text-[#001a61] font-bold hover:bg-[#e7eeff] transition inline-flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-[20px]">add_shopping_cart</span>
                                    Ajouter au panier
                                </button>
                            @endunless
                        @endif

                        <ul class="mt-6 space-y-2.5 text-sm text-[#444652]">
                            @foreach (['Accès à vie au contenu', 'Support pédagogique 7j/7', 'Certificat vérifiable', 'Mises à jour incluses'] as $perk)
                                <li class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[#001a61] text-[18px]">check_circle</span>
                                    {{ $perk }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    {{-- Contenu --}}
    <section class="relative py-14 lg:py-20">
        <div class="max-w-[1200px] mx-auto px-5 lg:px-8 grid lg:grid-cols-12 gap-10">
            <div class="lg:col-span-8 space-y-14">
                @if ($formation->description_complete)
                    <div class="adf-reveal">
                        <h2 class="text-2xl lg:text-3xl font-extrabold text-[#001a61]">À propos de cette formation</h2>
                        <div class="mt-5 prose prose-slate max-w-none text-[#444652] leading-relaxed
                            prose-headings:text-[#001a61] prose-a:text-[#0a2e8c] prose-strong:text-[#131c2a]">
                            {!! preg_replace(
                                '/^\s*<h2[^>]*>\s*À propos de cette formation\s*<\/h2>\s*/iu',
                                '',
                                rich_html($formation->description_complete),
                                1
                            ) !!}
                        </div>
                    </div>
                @endif

                <div class="adf-reveal">
                    <div class="flex flex-wrap items-end justify-between gap-3 mb-5">
                        <div>
                            <h2 class="text-2xl lg:text-3xl font-extrabold text-[#001a61]">Programme détaillé</h2>
                            <p class="text-sm text-[#757683] mt-1">{{ $formation->modules->count() }} modules · {{ $lessonsCount }} leçons</p>
                        </div>
                    </div>

                    <div class="space-y-3" x-data="{ open: 0 }">
                        @forelse ($formation->modules as $index => $module)
                            <div class="adf-card-static overflow-hidden">
                                <button type="button" @click="open = open === {{ $index }} ? null : {{ $index }}"
                                    class="w-full flex items-center gap-4 p-4 lg:p-5 text-left hover:bg-[#f0f3ff]/70 transition">
                                    <span class="w-11 h-11 rounded-xl bg-[#001a61] text-[#ffbf00] font-extrabold flex items-center justify-center shrink-0">
                                        {{ str_pad((string) ($module->ordre ?: $index + 1), 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-extrabold text-[#001a61] truncate">{{ $module->titre }}</h3>
                                        <p class="text-xs text-[#757683] mt-0.5">{{ $module->lessons->count() }} leçons</p>
                                    </div>
                                    <span class="material-symbols-outlined text-[#757683] transition-transform"
                                        :class="open === {{ $index }} && 'rotate-180'">expand_more</span>
                                </button>
                                <div x-show="open === {{ $index }}" x-collapse x-cloak class="border-t border-[#e7eeff]">
                                    <div class="p-4 lg:px-5 lg:pb-5 bg-[#f9f9ff]/80">
                                        @if ($module->description)
                                            <p class="text-sm text-[#444652] mb-4">{{ plain_text($module->description) }}</p>
                                        @endif
                                        @if ($module->lessons->isNotEmpty())
                                            <ul class="space-y-2">
                                                @foreach ($module->lessons as $lesson)
                                                    <li class="flex items-center gap-3 text-sm text-[#131c2a] py-2 px-3 rounded-lg hover:bg-white/80">
                                                        <span class="material-symbols-outlined text-[#001a61] text-[18px]">{{ $lesson->icon() }}</span>
                                                        <span class="flex-1 font-medium">{{ $lesson->titre }}</span>
                                                        @if ($lesson->duree_estimee ?? $lesson->duree ?? null)
                                                            <span class="text-xs text-[#757683]">{{ $lesson->duree_estimee ?? $lesson->duree }}{{ isset($lesson->duree_estimee) ? ' min' : '' }}</span>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-sm text-[#757683] italic">Contenu à venir…</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="adf-card-static p-10 text-center text-[#757683]">
                                Le programme détaillé sera bientôt disponible.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <aside class="lg:col-span-4 space-y-5 lg:sticky lg:top-28 self-start">
                <div class="adf-card-static p-6">
                    <h3 class="font-extrabold text-[#001a61] text-lg">Cette formation inclut</h3>
                    <ul class="mt-4 space-y-3 text-sm text-[#444652]">
                        <li class="flex gap-2"><span class="material-symbols-outlined text-[#001a61] text-[20px]">play_circle</span>{{ $formation->modules->count() }} modules structurés</li>
                        <li class="flex gap-2"><span class="material-symbols-outlined text-[#001a61] text-[20px]">description</span>{{ $lessonsCount }} leçons & ressources</li>
                        <li class="flex gap-2"><span class="material-symbols-outlined text-[#001a61] text-[20px]">quiz</span>Quiz & évaluations</li>
                        <li class="flex gap-2"><span class="material-symbols-outlined text-[#001a61] text-[20px]">verified</span>Certificat de réussite</li>
                        <li class="flex gap-2"><span class="material-symbols-outlined text-[#001a61] text-[20px]">forum</span>Accès communauté</li>
                    </ul>
                </div>

                <div class="adf-card-static p-6">
                    <h3 class="font-extrabold text-[#001a61] text-lg">Prérequis</h3>
                    <ul class="mt-4 space-y-2.5 text-sm text-[#444652]">
                        <li class="flex gap-2"><span class="material-symbols-outlined text-[#ffbf00] text-[20px]">check</span>Aucun prérequis technique</li>
                        <li class="flex gap-2"><span class="material-symbols-outlined text-[#ffbf00] text-[20px]">check</span>Connexion internet stable</li>
                        <li class="flex gap-2"><span class="material-symbols-outlined text-[#ffbf00] text-[20px]">check</span>Envie d’apprendre la finance</li>
                    </ul>
                </div>

                @if ($studentsCount > 0)
                    <div class="rounded-2xl bg-[#001a61] text-white p-6">
                        <p class="text-3xl font-extrabold text-[#ffbf00]">{{ $studentsCount }}</p>
                        <p class="text-sm text-white/80 mt-1">apprenant{{ $studentsCount > 1 ? 's' : '' }} déjà inscrit{{ $studentsCount > 1 ? 's' : '' }}</p>
                    </div>
                @endif
            </aside>
        </div>
    </section>

    {{-- CTA bas de page --}}
    <section class="relative overflow-hidden py-16 lg:py-20">
        <div class="absolute inset-0 bg-gradient-to-br from-[#001a61] to-[#0a2e8c]"></div>
        <div class="absolute inset-0 opacity-30 bg-[radial-gradient(circle_at_20%_50%,#ffbf00,transparent_40%)]"></div>
        <div class="relative max-w-[800px] mx-auto px-5 text-center">
            <p class="text-[#ffbf00] text-xs font-extrabold uppercase tracking-[0.2em]">Passez à l’action</p>
            <h2 class="mt-3 text-3xl lg:text-4xl font-extrabold text-white">Prêt à maîtriser la finance BRVM ?</h2>
            <p class="mt-4 text-white/75 text-lg">
                @if ($studentsCount > 0)
                    Rejoignez {{ $studentsCount }} apprenant{{ $studentsCount > 1 ? 's' : '' }} déjà engagés.
                @else
                    Démarrez votre parcours certifiant dès aujourd’hui.
                @endif
            </p>
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                @if ($isEnrolled)
                    <button type="button" wire:click="accessCourse" class="adf-btn-gold h-14 px-8 text-base">
                        <span class="material-symbols-outlined">play_circle</span> Continuer le cours
                    </button>
                @else
                    @if ($formation->isCatalogOnly())
                        <a href="{{ route('contact') }}" class="adf-btn-gold h-14 px-8 text-base inline-flex items-center gap-2">
                            <span class="material-symbols-outlined">mail</span>
                            Demander le catalogue
                        </a>
                    @else
                        <button type="button" wire:click="openPaymentModal" class="adf-btn-gold h-14 px-8 text-base">
                            <span class="material-symbols-outlined">rocket_launch</span>
                            @if ($formation->isFree())
                                Commencer gratuitement
                            @else
                                S'inscrire — {{ number_format($formation->prix, 0, ',', ' ') }} FCFA
                            @endif
                        </button>
                        @unless ($formation->isFree())
                            <button type="button" wire:click="addToCart"
                                class="h-14 px-6 rounded-xl border-2 border-white/40 text-white font-bold hover:bg-white/10 transition inline-flex items-center gap-2">
                                <span class="material-symbols-outlined">add_shopping_cart</span> Panier
                            </button>
                        @endunless
                    @endif
                @endif
            </div>
        </div>
    </section>

    {{-- Barre CTA mobile sticky --}}
    @unless ($isEnrolled)
        @unless ($formation->isCatalogOnly())
        <div class="lg:hidden fixed bottom-0 inset-x-0 z-40 adf-glass-strong border-t border-[#c5c5d4]/80 px-4 py-3 safe-pb">
            <div class="flex items-center gap-3 max-w-lg mx-auto">
                <div class="min-w-0 flex-1">
                    <p class="font-extrabold text-[#001a61] truncate">{{ $formation->priceDisplay() }}</p>
                    <p class="text-[11px] text-[#757683] truncate">{{ $formation->titre }}</p>
                </div>
                <button type="button" wire:click="openPaymentModal" class="adf-btn-gold shrink-0 h-12 px-5">
                    S'inscrire
                </button>
            </div>
        </div>
        <div class="lg:hidden h-20"></div>
        @endunless
    @endunless

    {{-- Modale paiement --}}
    @if ($showPaymentModal)
        <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-[#001a61]/55 backdrop-blur-sm" wire:click="closePaymentModal"></div>
            <div class="relative w-full sm:max-w-lg bg-white rounded-t-3xl sm:rounded-2xl shadow-2xl overflow-hidden max-h-[92vh] overflow-y-auto">
                <div class="bg-[#001a61] px-6 py-5 text-white">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-[#ffbf00] text-[11px] font-bold uppercase tracking-widest">Inscription</p>
                            <h3 class="text-xl font-extrabold mt-1" id="modal-title">Finaliser votre accès</h3>
                            <p class="text-white/70 text-sm mt-1 line-clamp-2">{{ $formation->titre }}</p>
                        </div>
                        <button type="button" wire:click="closePaymentModal" class="p-1 rounded-lg hover:bg-white/10" aria-label="Fermer">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                </div>

                <div class="px-6 py-5 space-y-5">
                    <div class="flex gap-4 rounded-xl bg-[#f0f3ff] p-4">
                        <img src="{{ $heroImage }}" alt="" class="w-16 h-16 rounded-xl object-cover shrink-0">
                        <div class="min-w-0">
                            <p class="font-bold text-[#001a61] line-clamp-2 text-sm">{{ $formation->titre }}</p>
                            <p class="text-xs text-[#757683] mt-1">
                                @if (filled($formation->duree)){{ $formation->duree }} · @endif{{ $formation->modules->count() }} modules
                            </p>
                            <p class="text-lg font-extrabold text-[#001a61] mt-1">
                                {{ $formation->priceDisplay() }}
                            </p>
                        </div>
                    </div>

                    @unless ($formation->isFree())
                        <x-payment-method-picker />

                        @if ($needsPhone ?? true)
                        <div>
                            <label for="phone" class="text-sm font-bold text-[#001a61]">Téléphone Mobile Money <span class="font-normal text-[#757683]">(recommandé)</span></label>
                            <input type="tel" id="phone" wire:model="phone" placeholder="+229 XX XX XX XX"
                                class="mt-1.5 w-full rounded-xl border border-[#c5c5d4] px-4 py-3 text-sm focus:ring-2 focus:ring-[#001a61]/20 focus:border-[#001a61]">
                        </div>
                        @endif
                    @endunless

                    <button type="button"
                        wire:click="@if ($formation->isFree()) enrollFree @else initiatePayment @endif"
                        wire:loading.attr="disabled" wire:target="initiatePayment, enrollFree"
                        class="adf-btn-gold w-full h-14 text-base disabled:opacity-50">
                        <span wire:loading.remove wire:target="initiatePayment, enrollFree">
                            @if ($formation->isFree())
                                Confirmer l'inscription
                            @else
                                Payer {{ $formation->priceDisplay() }}
                                @if (! empty($methodLabel)) · {{ $methodLabel }}@endif
                            @endif
                        </span>
                        <span wire:loading wire:target="initiatePayment, enrollFree" class="inline-flex items-center gap-2">
                            <span class="material-symbols-outlined animate-spin text-[20px]">progress_activity</span>
                            Traitement…
                        </span>
                    </button>

                    <p class="flex items-center justify-center gap-1.5 text-xs text-[#757683]">
                        <span class="material-symbols-outlined text-[16px]">lock</span>
                        Paiement sécurisé · Agréé AMF-UMOA
                    </p>
                </div>
            </div>
        </div>
    @endif
</main>

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
@include('partials.payment-widget-scripts')
@endpush
