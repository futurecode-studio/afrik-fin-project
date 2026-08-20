{{-- Modal envoi d’ordres : compte SGI ou ouverture via ADF --}}
@php
    $modalPartners = $partners ?? collect();
    $requiredDocs = $requiredDocs ?? collect();
@endphp
@teleport('body')
<div
    x-data="{
        open: @entangle('showOrderModal').live,
        lockScroll(on) {
            const html = document.documentElement;
            const body = document.body;
            if (on) {
                const sb = window.innerWidth - html.clientWidth;
                html.style.overflow = 'hidden';
                body.style.overflow = 'hidden';
                if (sb > 0) body.style.paddingRight = sb + 'px';
            } else {
                html.style.overflow = '';
                body.style.overflow = '';
                body.style.paddingRight = '';
            }
        }
    }"
    x-show="open"
    x-cloak
    x-effect="lockScroll(open)"
    style="display:none"
    class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
    role="dialog"
    aria-modal="true"
    @keydown.escape.window="if (open) $wire.closeOrderModal()"
>
    <div class="absolute inset-0 bg-[#001a61]/60" wire:click="closeOrderModal"></div>

    <div
        class="relative z-10 w-full max-w-lg max-h-[90vh] overflow-y-auto bg-white rounded-2xl border border-[#c5c5d4] shadow-2xl"
        @click.stop
    >
        <div class="sticky top-0 z-10 flex items-center justify-between gap-3 px-5 py-4 border-b border-[#e7eeff] bg-white rounded-t-2xl">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-[#0a2e8c]">Souscription</p>
                <h2 class="text-lg font-extrabold text-[#001a61]">
                    @if ($modalScreen === 'choice')
                        Compte chez une SGI ?
                    @elseif ($modalScreen === 'has_account')
                        Votre compte SGI
                    @elseif ($modalScreen === 'create_step1')
                        Étape 1 — Ouverture de compte
                    @else
                        Étape 2 — Documents à préparer
                    @endif
                </h2>
            </div>
            <button type="button" wire:click="closeOrderModal" class="p-1.5 rounded-lg hover:bg-[#e7eeff] text-[#757683]" aria-label="Fermer">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <div class="p-5 space-y-4">
            @if ($modalScreen === 'choice')
                <p class="text-sm text-[#444652]">
                    Africaine des Finances ne détient pas de comptes-titres. Pour préparer votre souscription, indiquez si vous avez déjà un compte chez une SGI agréée.
                </p>
                <button
                    type="button"
                    wire:click="selectHasAccount"
                    class="w-full text-left rounded-xl border border-[#c5c5d4] p-4 hover:border-[#001a61] hover:bg-[#f0f3ff] transition"
                >
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-[#001a61] mt-0.5">account_balance</span>
                        <div>
                            <p class="font-bold text-[#001a61]">J’ai déjà un compte chez une SGI</p>
                            <p class="text-sm text-[#444652] mt-1">Sélectionnez votre SGI et saisissez votre numéro de compte.</p>
                        </div>
                    </div>
                </button>
                <button
                    type="button"
                    wire:click="selectCreateAccount"
                    class="w-full text-left rounded-xl border border-[#c5c5d4] p-4 hover:border-[#001a61] hover:bg-[#f0f3ff] transition"
                >
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-[#001a61] mt-0.5">person_add</span>
                        <div>
                            <p class="font-bold text-[#001a61]">Pas de compte SGI</p>
                            <p class="text-sm text-[#444652] mt-1">Demandez l’ouverture d’un compte via Africaine des Finances.</p>
                        </div>
                    </div>
                </button>

            @elseif ($modalScreen === 'has_account')
                <p class="text-sm text-[#444652]">
                    Renseignez la SGI et votre numéro de compte-titres. Votre demande sera ensuite enregistrée dans le tableau de suivi.
                </p>
                <div>
                    <label class="text-sm font-medium">SGI *</label>
                    <select wire:model="partner_id" class="w-full mt-1 rounded-lg border-[#c5c5d4]">
                        <option value="">— Choisir une SGI —</option>
                        @foreach ($modalPartners as $p)
                            <option value="{{ $p->id }}">{{ $p->nom }}</option>
                        @endforeach
                    </select>
                    @error('partner_id')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="text-sm font-medium">N° de compte chez la SGI *</label>
                    <input type="text" wire:model="sgi_account_number" placeholder="Ex. CT-2024-…" class="w-full mt-1 rounded-lg border-[#c5c5d4]">
                    @error('sgi_account_number')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="flex gap-2 pt-2">
                    <button type="button" wire:click="backToChoice" class="flex-1 py-3 rounded-xl border border-[#c5c5d4] font-bold text-[#001a61]">Retour</button>
                    <button type="button" wire:click="submitWithSgiAccount" class="flex-1 py-3 rounded-xl bg-[#001a61] text-white font-bold" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="submitWithSgiAccount">Confirmer l’envoi</span>
                        <span wire:loading wire:target="submitWithSgiAccount">Envoi…</span>
                    </button>
                </div>

            @elseif ($modalScreen === 'create_step1')
                @php
                    $missingContact = $this->missingContactFields();
                    $showName = in_array('name', $missingContact, true) || $errors->has('contact_name');
                    $showEmail = in_array('email', $missingContact, true) || $errors->has('contact_email');
                    $showPhone = in_array('phone', $missingContact, true) || $errors->has('contact_phone');
                @endphp
                <div class="rounded-xl bg-[#f0f3ff] border border-[#c5c5d4] p-4 space-y-3 text-sm text-[#444652]">
                    <p>
                    Vous n’avez pas encore de compte chez une SGI. Africaine des Finances peut vous accompagner pour ouvrir un compte ou finaliser votre dossier auprès d’un partenaire agréé.
                    </p>
                    <p>
                        En confirmant, <strong class="text-[#001a61]">des chargées de clientèle vous contacteront</strong> pour prendre en charge votre dossier. Aucun montant ni ordre n’est demandé à ce stade.
                    </p>
                    <p>
                        Préparez dès maintenant les documents listés à l’étape suivante afin d’accélérer le traitement.
                    </p>
                </div>

                @if ($showName || $showEmail || $showPhone)
                    <div class="space-y-3 rounded-xl border border-amber-200 bg-amber-50 p-4">
                        <p class="text-sm font-bold text-amber-950">Complétez les informations manquantes</p>
                        <p class="text-xs text-amber-900">Elles seront enregistrées sur votre profil pour les prochains échanges.</p>

                        @if ($showName)
                            <div>
                                <label class="text-sm font-medium text-[#001a61]">Nom complet *</label>
                                <input type="text" wire:model="contact_name" class="w-full mt-1 rounded-lg border-[#c5c5d4] bg-white" placeholder="Prénom et nom" autocomplete="name">
                                @error('contact_name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        @endif

                        @if ($showEmail)
                            <div>
                                <label class="text-sm font-medium text-[#001a61]">Email *</label>
                                <input type="email" wire:model="contact_email" class="w-full mt-1 rounded-lg border-[#c5c5d4] bg-white" placeholder="vous@exemple.com" autocomplete="email">
                                @error('contact_email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        @endif

                        @if ($showPhone)
                            <div>
                                <label class="text-sm font-medium text-[#001a61]">Téléphone *</label>
                                <input type="tel" wire:model="contact_phone" class="w-full mt-1 rounded-lg border-[#c5c5d4] bg-white" placeholder="+229 XX XX XX XX" autocomplete="tel">
                                @error('contact_phone')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        @endif
                    </div>
                @endif

                <div class="flex gap-2 pt-2">
                    <button type="button" wire:click="backToChoice" class="flex-1 py-3 rounded-xl border border-[#c5c5d4] font-bold text-[#001a61]">Retour</button>
                    <button type="button" wire:click="confirmCreateAccount" class="flex-1 py-3 rounded-xl bg-[#001a61] text-white font-bold" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="confirmCreateAccount">Confirmer</span>
                        <span wire:loading wire:target="confirmCreateAccount">Enregistrement…</span>
                    </button>
                </div>

            @else
                <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-950">
                    <p class="font-bold">Patience — votre demande est enregistrée</p>
                    <p class="mt-1">Une chargée de clientèle vous recontactera. En attendant, préparez les documents ci-dessous.</p>
                </div>
                <ul class="space-y-3">
                    @forelse ($requiredDocs as $doc)
                        <li class="flex gap-3 rounded-xl border border-[#e7eeff] p-3">
                            <span class="material-symbols-outlined text-[#001a61] shrink-0">description</span>
                            <div>
                                <p class="font-bold text-[#001a61] text-sm">{{ $doc->title }}</p>
                                @if ($doc->description)
                                    <p class="text-xs text-[#444652] mt-0.5">{{ $doc->description }}</p>
                                @endif
                            </div>
                        </li>
                    @empty
                        <li class="text-sm text-[#757683]">La liste des documents sera bientôt publiée par l’équipe.</li>
                    @endforelse
                </ul>
                <button type="button" wire:click="closeOrderModal" class="w-full py-3 rounded-xl bg-[#001a61] text-white font-bold">
                    J’ai compris
                </button>
            @endif
        </div>
    </div>
</div>
@endteleport
