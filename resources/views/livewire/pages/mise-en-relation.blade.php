<div class="bg-[#f9f9ff] min-h-[70vh]">
    <section class="max-w-[1100px] mx-auto px-5 lg:px-8 py-12 lg:py-16">
        <div class="grid lg:grid-cols-2 gap-10 items-start">
            <div>
                <p class="text-xs font-semibold tracking-widest uppercase text-[#0a2e8c]">Partenariat agréé</p>
                <h1 class="text-4xl font-extrabold text-[#001a61] mt-2 leading-tight">Accédez à l’excellence financière.</h1>
                <p class="text-[#444652] mt-4">Nous vous mettons en relation avec une SGI / SGO régulée AMF-UMOA pour exécuter vos investissements.</p>
                <ul class="mt-8 space-y-3 text-sm text-[#131c2a]">
                    <li class="flex gap-2"><span class="material-symbols-outlined text-[#001a61]">verified_user</span> Protection des données</li>
                    <li class="flex gap-2"><span class="material-symbols-outlined text-[#001a61]">handshake</span> Accompagnement premium</li>
                    <li class="flex gap-2"><span class="material-symbols-outlined text-[#001a61]">analytics</span> Orientation selon votre profil</li>
                </ul>
                @if ($selected)
                    <div class="mt-8 p-4 rounded-xl border border-[#c5c5d4] bg-white flex items-center gap-4">
                        @if ($selected->logo_url)
                            <img src="{{ $selected->logo_url }}" alt="{{ $selected->nom }}" class="h-12 w-12 object-contain">
                        @endif
                        <div>
                            <p class="text-xs uppercase tracking-wider text-[#757683]">Partenaire sélectionné</p>
                            <p class="font-bold text-[#001a61] mt-1">{{ $selected->nom }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="bg-white border border-[#c5c5d4] rounded-xl p-6 lg:p-8">

                <form wire:submit.prevent="submit" class="space-y-4">
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Prénom</label>
                            <input wire:model="first_name" type="text" class="w-full rounded-lg border border-[#c5c5d4] px-3 py-2.5 bg-[#f9f9ff] focus:border-[#001a61] outline-none">
                            @error('first_name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Nom</label>
                            <input wire:model="last_name" type="text" class="w-full rounded-lg border border-[#c5c5d4] px-3 py-2.5 bg-[#f9f9ff] focus:border-[#001a61] outline-none">
                            @error('last_name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Email professionnel</label>
                        <input wire:model="email" type="email" class="w-full rounded-lg border border-[#c5c5d4] px-3 py-2.5 bg-[#f9f9ff] focus:border-[#001a61] outline-none">
                        @error('email') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Téléphone</label>
                        <input wire:model="phone" type="text" class="w-full rounded-lg border border-[#c5c5d4] px-3 py-2.5 bg-[#f9f9ff] focus:border-[#001a61] outline-none">
                        @error('phone') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Profil investisseur</label>
                        <select wire:model="investor_profile" class="w-full rounded-lg border border-[#c5c5d4] px-3 py-2.5 bg-[#f9f9ff] focus:border-[#001a61] outline-none">
                            <option value="">Sélectionnez votre profil</option>
                            <option value="retail">Particulier (Retail)</option>
                            <option value="hnwi">Particulier à haute contribution (HNWI)</option>
                            <option value="institutionnel">Investisseur institutionnel</option>
                            <option value="entreprise">Entreprise / PME</option>
                        </select>
                        @error('investor_profile') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Budget indicatif (FCFA)</label>
                        <input wire:model="budget" type="text" placeholder="Ex. 5 000 000" class="w-full rounded-lg border border-[#c5c5d4] px-3 py-2.5 bg-[#f9f9ff] focus:border-[#001a61] outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Service recherché</label>
                        <select wire:model="service" class="w-full rounded-lg border border-[#c5c5d4] px-3 py-2.5 bg-[#f9f9ff] focus:border-[#001a61] outline-none">
                            <option value="marche_boursier">Marché boursier</option>
                            <option value="gestion_mandat">Gestion sous mandat</option>
                            <option value="placements_libres">Placements libres</option>
                            <option value="opcvm">OPCVM / FCP</option>
                            <option value="obligations">Obligations</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Partenaire (optionnel)</label>
                        <select wire:model="partner_id" class="w-full rounded-lg border border-[#c5c5d4] px-3 py-2.5 bg-[#f9f9ff] focus:border-[#001a61] outline-none">
                            <option value="">Sans préférence</option>
                            @foreach ($partners as $p)
                                <option value="{{ $p->id }}">{{ $p->nom }} ({{ $p->type }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Votre message / besoins</label>
                        <textarea wire:model="message" rows="4" class="w-full rounded-lg border border-[#c5c5d4] px-3 py-2.5 bg-[#f9f9ff] focus:border-[#001a61] outline-none"></textarea>
                    </div>
                    <button type="submit" class="w-full py-3 rounded-xl bg-[#001a61] text-white font-bold hover:bg-[#051c5b]">Envoyer la demande</button>
                </form>
            </div>
        </div>
    </section>
</div>
