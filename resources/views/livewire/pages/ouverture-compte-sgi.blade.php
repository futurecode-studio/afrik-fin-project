<div class="bg-[#f9f9ff] text-[#131c2a] min-h-screen">
    <section class="relative bg-[#001a61] text-white overflow-hidden">
        <div class="absolute inset-0 opacity-35 pointer-events-none"
            style="background: radial-gradient(700px 340px at 90% 0%, rgba(255,191,0,.4), transparent 55%);"></div>
        <div class="relative max-w-[1100px] mx-auto px-5 lg:px-8 pt-14 pb-12 lg:pt-18 lg:pb-14">
            <p class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-[#ffbf00]">Compte-titres · SGI</p>
            <h1 class="mt-3 text-3xl md:text-4xl font-extrabold tracking-tight max-w-2xl leading-tight">
                Demander l’ouverture d’un compte chez une SGI
            </h1>
            <p class="mt-4 text-white/80 text-lg max-w-2xl leading-relaxed">
                Africaine des Finances (agrément AMF-UMOA AA/2022-03) vous accompagne pour ouvrir un compte-titres
                auprès d’une Société de Gestion et d’Intermédiation agréée. Remplissez le formulaire :
                une chargée de clientèle vous recontactera.
            </p>
        </div>
    </section>

    <section class="max-w-[1100px] mx-auto px-5 lg:px-8 py-12 lg:py-16">
        <div class="grid lg:grid-cols-5 gap-8">
            <div class="lg:col-span-3">
                @if ($submitted)
                    <div class="bg-white border border-[#c5c5d4] rounded-2xl p-8 space-y-5">
                        <div class="w-14 h-14 rounded-full bg-emerald-50 text-emerald-700 flex items-center justify-center">
                            <span class="material-symbols-outlined text-3xl">check_circle</span>
                        </div>
                        <h2 class="text-2xl font-extrabold text-[#001a61]">Demande bien reçue</h2>
                        <p class="text-[#444652] leading-relaxed">
                            Merci. Votre demande d’ouverture de compte SGI est enregistrée.
                            Une chargée de clientèle Africaine des Finances vous contactera pour la suite du dossier.
                        </p>
                        <p class="text-sm text-[#444652]">
                            En attendant, préparez les documents listés à droite afin d’accélérer le traitement.
                        </p>
                        <div class="flex flex-wrap gap-3 pt-2">
                            <a href="{{ route('guide-bourse') }}" class="inline-flex px-5 py-3 rounded-xl bg-[#001a61] text-white font-bold hover:bg-[#0a2e8c] transition">
                                Lire le guide bourse
                            </a>
                            <a href="{{ route('contact') }}" class="inline-flex px-5 py-3 rounded-xl border border-[#c5c5d4] text-[#001a61] font-bold hover:bg-[#e7eeff] transition">
                                Nous contacter
                            </a>
                        </div>
                    </div>
                @else
                    <form wire:submit.prevent="submit" class="bg-white border border-[#c5c5d4] rounded-2xl p-6 md:p-8 space-y-5">
                        <div>
                            <h2 class="text-xl font-extrabold text-[#001a61]">Vos coordonnées</h2>
                            <p class="mt-1 text-sm text-[#444652]">Aucun montant n’est demandé à ce stade. Nous vous rappelons pour ouvrir le dossier.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1.5">Nom complet *</label>
                            <input type="text" wire:model="name" class="w-full rounded-xl border-[#c5c5d4] bg-[#f9f9ff]" placeholder="Prénom et nom">
                            @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1.5">Email *</label>
                                <input type="email" wire:model="email" class="w-full rounded-xl border-[#c5c5d4] bg-[#f9f9ff]" placeholder="vous@exemple.com">
                                @error('email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1.5">Téléphone *</label>
                                <input type="tel" wire:model="phone" class="w-full rounded-xl border-[#c5c5d4] bg-[#f9f9ff]" placeholder="+229 XX XX XX XX">
                                @error('phone')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1.5">Message <span class="text-[#757683] font-normal">(optionnel)</span></label>
                            <textarea wire:model="message" rows="4" class="w-full rounded-xl border-[#c5c5d4] bg-[#f9f9ff]"
                                placeholder="Précisez votre situation, votre pays, ou une question…"></textarea>
                            @error('message')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="rounded-xl bg-[#f0f3ff] border border-[#c5c5d4] px-4 py-3 text-sm text-[#444652]">
                            En envoyant ce formulaire, vous acceptez d’être contacté(e) par Africaine des Finances
                            pour l’ouverture d’un compte-titres via une SGI partenaire agréée.
                        </div>

                        <button type="submit"
                            class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-[#001a61] text-white font-bold hover:bg-[#0a2e8c] transition"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="submit">Envoyer ma demande</span>
                            <span wire:loading wire:target="submit">Envoi…</span>
                        </button>
                    </form>
                @endif
            </div>

            <aside class="lg:col-span-2 space-y-5">
                <div class="bg-white border border-[#c5c5d4] rounded-2xl p-5">
                    <h3 class="font-bold text-[#001a61]">Comment ça se passe ?</h3>
                    <ol class="mt-4 space-y-3 text-sm text-[#444652]">
                        <li class="flex gap-3">
                            <span class="shrink-0 w-7 h-7 rounded-full bg-[#001a61] text-white text-xs font-bold flex items-center justify-center">1</span>
                            <span>Vous laissez vos coordonnées sur cette page.</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="shrink-0 w-7 h-7 rounded-full bg-[#001a61] text-white text-xs font-bold flex items-center justify-center">2</span>
                            <span>Une chargée de clientèle vous contacte et vous oriente vers une SGI adaptée.</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="shrink-0 w-7 h-7 rounded-full bg-[#001a61] text-white text-xs font-bold flex items-center justify-center">3</span>
                            <span>Vous fournissez les documents et signez la convention avec la SGI.</span>
                        </li>
                    </ol>
                </div>

                <div class="bg-white border border-[#c5c5d4] rounded-2xl p-5">
                    <h3 class="font-bold text-[#001a61]">Documents à préparer</h3>
                    <p class="mt-1 text-xs text-[#757683]">Liste gérée par l’équipe ADF</p>
                    <ul class="mt-4 space-y-3">
                        @forelse ($documents as $doc)
                            <li class="flex gap-3">
                                <span class="material-symbols-outlined text-[#001a61] text-[20px] shrink-0">description</span>
                                <div>
                                    <p class="text-sm font-semibold text-[#001a61]">{{ $doc->title }}</p>
                                    @if ($doc->description)
                                        <p class="text-xs text-[#444652] mt-0.5">{{ $doc->description }}</p>
                                    @endif
                                </div>
                            </li>
                        @empty
                            <li class="text-sm text-[#757683]">La liste sera bientôt publiée.</li>
                        @endforelse
                    </ul>
                </div>

                <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 text-sm text-amber-950">
                    <p class="font-bold">Bon à savoir</p>
                    <p class="mt-1 leading-relaxed">
                        Africaine des Finances n’exécute pas les ordres de bourse. Les comptes-titres sont tenus
                        par des SGI agréées ; nous assurons l’orientation et l’accompagnement.
                    </p>
                </div>
            </aside>
        </div>
    </section>
</div>
