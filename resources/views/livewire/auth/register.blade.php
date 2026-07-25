<div class="bg-[#f9f9ff] min-h-[70vh]">
    <section class="max-w-[1100px] mx-auto px-5 lg:px-8 py-12 lg:py-20 grid lg:grid-cols-2 gap-12 items-center">
        <div>
            <p class="text-sm font-semibold tracking-widest uppercase text-[#0a2e8c]">Rejoindre</p>
            <h1 class="text-3xl md:text-4xl font-extrabold text-[#001a61] mt-3 leading-tight">Créez votre compte</h1>
            <p class="mt-4 text-[#444652] text-lg">Inscription gratuite pour suivre les marchés, formations et événements.</p>
            <ul class="mt-6 space-y-2 text-sm text-[#444652]">
                <li class="flex gap-2"><span class="material-symbols-outlined text-[#001a61] text-base">check_circle</span> Accès formations et certificats</li>
                <li class="flex gap-2"><span class="material-symbols-outlined text-[#001a61] text-base">check_circle</span> Liste de suivi BRVM personnalisée</li>
                <li class="flex gap-2"><span class="material-symbols-outlined text-[#001a61] text-base">check_circle</span> Billets d’événements</li>
            </ul>
        </div>

        <div class="bg-white border border-[#c5c5d4] rounded-2xl p-6 md:p-8 shadow-sm">
            <h2 class="text-xl font-bold text-[#001a61]">Inscription</h2>
            <form wire:submit.prevent="register" class="mt-6 space-y-4">
                <div>
                    <label class="text-xs font-semibold text-[#757683]">Nom complet</label>
                    <input wire:model="name" type="text" required class="mt-1 w-full rounded-lg border-[#c5c5d4] focus:border-[#001a61] focus:ring-[#001a61]">
                    @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-xs font-semibold text-[#757683]">Email</label>
                    <input wire:model="email" type="email" required class="mt-1 w-full rounded-lg border-[#c5c5d4] focus:border-[#001a61] focus:ring-[#001a61]">
                    @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-xs font-semibold text-[#757683]">Mot de passe</label>
                    <input wire:model="password" type="password" required class="mt-1 w-full rounded-lg border-[#c5c5d4] focus:border-[#001a61] focus:ring-[#001a61]">
                    @error('password') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-xs font-semibold text-[#757683]">Confirmation</label>
                    <input wire:model="password_confirmation" type="password" required class="mt-1 w-full rounded-lg border-[#c5c5d4] focus:border-[#001a61] focus:ring-[#001a61]">
                </div>
                <button type="submit" class="w-full bg-[#001a61] text-white font-bold py-3 rounded-lg hover:bg-[#0a2e8c]">
                    Créer mon compte
                </button>
            </form>
            <p class="mt-6 text-center text-sm text-[#757683]">
                Déjà inscrit ?
                <a href="{{ route('connexion') }}" class="font-bold text-[#001a61] hover:underline">Se connecter</a>
            </p>
        </div>
    </section>
</div>
