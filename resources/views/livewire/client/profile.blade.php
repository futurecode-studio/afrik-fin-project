<div>
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-[#001a61]">Paramètres du compte</h1>
        <p class="text-[#444652] mt-2">Gérez vos informations personnelles et votre sécurité.</p>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        <section class="bg-white border border-[#c5c5d4] rounded-xl p-6">
            <h2 class="font-bold text-[#001a61] text-lg mb-4">Profil</h2>
            @if (session('profile_success'))
                <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg px-3 py-2">{{ session('profile_success') }}</div>
            @endif
            <form wire:submit.prevent="updateProfile" class="space-y-4">
                <div>
                    <label class="text-xs font-semibold text-[#757683]">Nom complet</label>
                    <input wire:model="name" type="text" class="mt-1 w-full rounded-lg border-[#c5c5d4] focus:border-[#001a61] focus:ring-[#001a61]">
                    @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-xs font-semibold text-[#757683]">Email</label>
                    <input wire:model="email" type="email" class="mt-1 w-full rounded-lg border-[#c5c5d4] focus:border-[#001a61] focus:ring-[#001a61]">
                    @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-xs font-semibold text-[#757683]">Téléphone</label>
                    <input wire:model="phone" type="text" class="mt-1 w-full rounded-lg border-[#c5c5d4] focus:border-[#001a61] focus:ring-[#001a61]">
                </div>
                <div>
                    <label class="text-xs font-semibold text-[#757683]">Adresse</label>
                    <input wire:model="address" type="text" class="mt-1 w-full rounded-lg border-[#c5c5d4] focus:border-[#001a61] focus:ring-[#001a61]">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-semibold text-[#757683]">Ville</label>
                        <input wire:model="city" type="text" class="mt-1 w-full rounded-lg border-[#c5c5d4] focus:border-[#001a61] focus:ring-[#001a61]">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-[#757683]">Pays</label>
                        <input wire:model="country" type="text" class="mt-1 w-full rounded-lg border-[#c5c5d4] focus:border-[#001a61] focus:ring-[#001a61]">
                    </div>
                </div>
                <button type="submit" class="bg-[#001a61] text-white font-bold px-5 py-2.5 rounded hover:bg-[#0a2e8c]">Enregistrer</button>
            </form>
        </section>

        <section class="bg-white border border-[#c5c5d4] rounded-xl p-6">
            <h2 class="font-bold text-[#001a61] text-lg mb-4">Mot de passe</h2>
            @if (session('password_success'))
                <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg px-3 py-2">{{ session('password_success') }}</div>
            @endif
            <form wire:submit.prevent="updatePassword" class="space-y-4">
                <div>
                    <label class="text-xs font-semibold text-[#757683]">Mot de passe actuel</label>
                    <input wire:model="current_password" type="password" class="mt-1 w-full rounded-lg border-[#c5c5d4] focus:border-[#001a61] focus:ring-[#001a61]">
                    @error('current_password') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-xs font-semibold text-[#757683]">Nouveau mot de passe</label>
                    <input wire:model="password" type="password" class="mt-1 w-full rounded-lg border-[#c5c5d4] focus:border-[#001a61] focus:ring-[#001a61]">
                    @error('password') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-xs font-semibold text-[#757683]">Confirmation</label>
                    <input wire:model="password_confirmation" type="password" class="mt-1 w-full rounded-lg border-[#c5c5d4] focus:border-[#001a61] focus:ring-[#001a61]">
                </div>
                <button type="submit" class="bg-[#001a61] text-white font-bold px-5 py-2.5 rounded hover:bg-[#0a2e8c]">Mettre à jour</button>
            </form>

            <div class="mt-8 pt-6 border-t border-[#c5c5d4]">
                <h3 class="font-bold text-[#001a61] mb-2">Préférences</h3>
                <a href="{{ route('client.interests') }}" class="inline-flex items-center gap-2 text-sm font-bold text-[#001a61] hover:underline">
                    <span class="material-symbols-outlined text-base">interests</span>
                    Modifier mes intérêts
                </a>
            </div>
        </section>
    </div>
</div>
