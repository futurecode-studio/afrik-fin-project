<main class="bg-[#f9f9ff] pt-20">
    <section class="max-w-[1100px] mx-auto px-5 lg:px-8 py-12 lg:py-16">
        <div class="grid lg:grid-cols-5 gap-8 items-start">
            <div class="lg:col-span-3">
                <p class="text-sm font-semibold tracking-widest uppercase text-[#0a2e8c]">Souscription accompagnée</p>
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#001a61] mt-3 leading-tight">Être accompagné par nos équipes</h1>
                <p class="mt-4 text-[#444652] text-lg">
                    Laissez vos coordonnées. Une chargée de clientèle vous contacte pour vous orienter vers une SGI ou SGO et préparer le dossier.
                </p>

                <div class="mt-8 grid sm:grid-cols-2 gap-4">
                    <a href="{{ route('client.ordres') }}" class="rounded-xl border border-[#c5c5d4] bg-white p-5 hover:border-[#001a61] transition">
                        <span class="material-symbols-outlined text-[#001a61]">contract_edit</span>
                        <h2 class="mt-3 font-extrabold text-[#001a61]">Souscription directe</h2>
                        <p class="mt-1 text-sm text-[#757683]">Vous avez déjà une SGI : renseignez l’intention et le compte-titres.</p>
                    </a>
                    <div class="rounded-xl border border-[#001a61] bg-[#001a61] p-5 text-white">
                        <span class="material-symbols-outlined">support_agent</span>
                        <h2 class="mt-3 font-extrabold">Souscription accompagnée</h2>
                        <p class="mt-1 text-sm text-white/75">Vous voulez être guidé : l’équipe prend le relais.</p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 bg-white border border-[#c5c5d4] rounded-xl p-6">
                @if ($submitted)
                    <div class="text-center py-8">
                        <span class="material-symbols-outlined text-5xl text-emerald-600">check_circle</span>
                        <h2 class="mt-4 text-xl font-extrabold text-[#001a61]">Demande enregistrée</h2>
                        <p class="mt-2 text-sm text-[#444652]">Nos équipes vous contacteront pour finaliser votre dossier.</p>
                        <a href="{{ route('home') }}" class="mt-5 inline-flex items-center justify-center rounded-xl bg-[#001a61] px-5 py-3 text-white font-bold">Retour à l’accueil</a>
                    </div>
                @else
                    <h2 class="text-xl font-extrabold text-[#001a61]">Vos informations</h2>
                    @if ($selectedPartner)
                        <p class="mt-1 text-sm text-[#757683]">Partenaire souhaité : <strong class="text-[#001a61]">{{ $selectedPartner->nom }}</strong></p>
                    @endif

                    <form wire:submit.prevent="submit" class="mt-5 space-y-4">
                        <div>
                            <label class="text-xs font-semibold text-[#757683]">Nom complet *</label>
                            <input wire:model="name" type="text" class="mt-1 w-full rounded-lg border-[#c5c5d4] focus:border-[#001a61] focus:ring-[#001a61]">
                            @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-[#757683]">Email *</label>
                            <input wire:model="email" type="email" class="mt-1 w-full rounded-lg border-[#c5c5d4] focus:border-[#001a61] focus:ring-[#001a61]">
                            @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-[#757683]">Téléphone *</label>
                            <input wire:model="phone" type="tel" class="mt-1 w-full rounded-lg border-[#c5c5d4] focus:border-[#001a61] focus:ring-[#001a61]">
                            @error('phone') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-[#757683]">SGI / SGO souhaitée</label>
                            <select wire:model="partner" class="mt-1 w-full rounded-lg border-[#c5c5d4] focus:border-[#001a61] focus:ring-[#001a61]">
                                <option value="">Je veux être orienté</option>
                                @foreach ($partners as $p)
                                    <option value="{{ $p->id }}">{{ $p->type }} — {{ $p->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-[#757683]">Message</label>
                            <textarea wire:model="message" rows="4" class="mt-1 w-full rounded-lg border-[#c5c5d4] focus:border-[#001a61] focus:ring-[#001a61]" placeholder="Produit visé, disponibilité, question particulière…"></textarea>
                            @error('message') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <button type="submit" class="w-full rounded-xl bg-[#001a61] py-3 font-bold text-white hover:bg-[#0a2e8c]" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="submit">Être accompagné</span>
                            <span wire:loading wire:target="submit">Enregistrement…</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <section class="mt-10 bg-white border border-[#c5c5d4] rounded-xl p-6">
            <h2 class="text-xl font-extrabold text-[#001a61]">Documents à préparer</h2>
            <div class="mt-4 grid md:grid-cols-2 lg:grid-cols-4 gap-3">
                @forelse ($requiredDocs as $doc)
                    <div class="rounded-xl border border-[#e7eeff] p-4">
                        <span class="material-symbols-outlined text-[#001a61]">description</span>
                        <p class="mt-2 font-bold text-sm text-[#001a61]">{{ $doc->title }}</p>
                        @if ($doc->description)
                            <p class="mt-1 text-xs text-[#757683]">{{ $doc->description }}</p>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-[#757683]">La liste des documents sera confirmée par l’équipe.</p>
                @endforelse
            </div>
        </section>
    </section>
</main>
