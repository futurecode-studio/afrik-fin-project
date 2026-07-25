<div class="bg-[#f9f9ff] min-h-[70vh]">
    <section class="max-w-[1100px] mx-auto px-5 lg:px-8 py-12 lg:py-16">
        <div class="grid lg:grid-cols-3 gap-8">
            <aside class="lg:col-span-1 space-y-4">
                <div class="bg-white border border-[#c5c5d4] rounded-xl p-5">
                    <p class="text-xs uppercase tracking-wider text-[#757683]">Support client</p>
                    <h2 class="font-bold text-[#001a61] mt-1">Historique des tickets</h2>
                    @auth
                        <ul class="mt-4 space-y-3">
                            @forelse ($recent as $t)
                                <li class="p-3 rounded-lg bg-[#f0f3ff] border border-[#c5c5d4]">
                                    <p class="text-sm font-semibold text-[#001a61] line-clamp-1">{{ $t->subject }}</p>
                                    <p class="text-xs text-[#757683] mt-1">{{ $t->created_at->format('d/m/Y H:i') }} · {{ $t->status }}</p>
                                </li>
                            @empty
                                <p class="text-sm text-[#757683] mt-3">Aucun ticket pour le moment.</p>
                            @endforelse
                        </ul>
                    @else
                        <p class="text-sm text-[#444652] mt-3">Connectez-vous pour ouvrir un ticket et suivre l’historique.</p>
                        <a href="{{ route('connexion') }}" class="inline-flex mt-4 text-sm font-bold text-[#001a61] underline">Se connecter</a>
                    @endauth
                </div>
                <a href="{{ route('faq') }}" class="block text-sm font-semibold text-[#0a2e8c] hover:underline">Consulter la FAQ →</a>
            </aside>

            <div class="lg:col-span-2 bg-white border border-[#c5c5d4] rounded-xl p-6 lg:p-8">
                <h1 class="text-3xl font-extrabold text-[#001a61]">Besoin d’assistance ?</h1>
                <p class="text-[#444652] mt-2">Créez un ticket — notre équipe support vous répond.</p>

                <form wire:submit.prevent="submit" class="mt-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Sujet du ticket</label>
                        <input wire:model="subject" type="text" class="w-full rounded-lg border border-[#c5c5d4] px-3 py-2.5 bg-[#f9f9ff] outline-none focus:border-[#001a61]">
                        @error('subject') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Catégorie</label>
                            <select wire:model="category" class="w-full rounded-lg border border-[#c5c5d4] px-3 py-2.5 bg-[#f9f9ff] outline-none focus:border-[#001a61]">
                                <option value="">Sélectionner une catégorie</option>
                                <option value="technique">Technique</option>
                                <option value="facturation">Facturation</option>
                                <option value="formation">Formation</option>
                                <option value="securite">Sécurité</option>
                                <option value="autre">Autre</option>
                            </select>
                            @error('category') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Priorité</label>
                            <select wire:model="priority" class="w-full rounded-lg border border-[#c5c5d4] px-3 py-2.5 bg-[#f9f9ff] outline-none focus:border-[#001a61]">
                                <option value="basse">Basse</option>
                                <option value="normale">Normale</option>
                                <option value="haute">Haute</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Description détaillée</label>
                        <textarea wire:model="description" rows="6" class="w-full rounded-lg border border-[#c5c5d4] px-3 py-2.5 bg-[#f9f9ff] outline-none focus:border-[#001a61]"></textarea>
                        @error('description') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Pièce jointe (facultatif)</label>
                        <input wire:model="attachment" type="file" class="w-full text-sm">
                        @error('attachment') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-[#001a61] text-white font-bold">
                        <span class="material-symbols-outlined text-base">send</span>
                        Envoyer le ticket
                    </button>
                </form>
            </div>
        </div>
    </section>
</div>
