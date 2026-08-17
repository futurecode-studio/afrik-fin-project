<div class="bg-[#f9f9ff] min-h-[70vh]">
    <section class="max-w-[560px] mx-auto px-5 lg:px-8 py-12 lg:py-20">
        <div class="bg-white border border-[#c5c5d4] rounded-2xl p-6 md:p-8 shadow-sm">
            <h1 class="text-2xl md:text-3xl font-extrabold text-[#001a61]">Inscription</h1>
            <p class="mt-2 text-sm text-[#757683]">Créez votre compte pour accéder à votre espace.</p>

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
                    Inscription
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-[#757683]">
                Déjà inscrit ?
                <a href="{{ route('connexion') }}" class="font-bold text-[#001a61] hover:underline">Se connecter</a>
            </p>
        </div>
    </section>
</div>
