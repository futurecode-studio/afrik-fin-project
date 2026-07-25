<div class="bg-[#f9f9ff] min-h-[70vh]">
    <section class="max-w-[1100px] mx-auto px-5 lg:px-8 py-12 lg:py-20 grid lg:grid-cols-2 gap-12 items-center">
        <div>
            <p class="text-sm font-semibold tracking-widest uppercase text-[#0a2e8c]">Espace client</p>
            <h1 class="text-3xl md:text-4xl font-extrabold text-[#001a61] mt-3 leading-tight">Africaine des Finances</h1>
            <p class="mt-4 text-[#444652] text-lg">Connectez-vous pour retrouver formations, événements et votre liste de suivi marché.</p>
        </div>

        <div class="bg-white border border-[#c5c5d4] rounded-2xl p-6 md:p-8 shadow-sm">
            <h2 class="text-xl font-bold text-[#001a61]">Connexion</h2>
            <p class="text-sm text-[#757683] mt-1">Accédez à votre portail</p>

            @if ($errors->any())
                <div class="mt-4 p-3 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm" role="alert">
                    {{ $errors->first() }}
                </div>
            @endif

            <form wire:submit="login" class="mt-6 space-y-4">
                <div>
                    <label class="text-xs font-semibold text-[#757683]" for="email">Email</label>
                    <input wire:model="email" type="email" id="email" required autofocus autocomplete="username"
                        class="mt-1 w-full rounded-lg border-[#c5c5d4] focus:border-[#001a61] focus:ring-[#001a61]"
                        placeholder="email@exemple.com">
                    @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-xs font-semibold text-[#757683]" for="password">Mot de passe</label>
                    <input wire:model="password" type="password" id="password" required autocomplete="current-password"
                        class="mt-1 w-full rounded-lg border-[#c5c5d4] focus:border-[#001a61] focus:ring-[#001a61]"
                        placeholder="••••••••">
                    @error('password') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-center justify-between text-sm">
                    <label class="inline-flex items-center gap-2 text-[#444652]">
                        <input wire:model="remember" type="checkbox" class="rounded border-[#c5c5d4] text-[#001a61] focus:ring-[#001a61]">
                        Se souvenir de moi
                    </label>
                    <a href="{{ route('password.request') }}" class="font-medium text-[#001a61] hover:underline" wire:navigate>Mot de passe oublié ?</a>
                </div>
                <button type="submit"
                    class="w-full bg-[#001a61] text-white font-bold py-3 rounded-lg hover:bg-[#0a2e8c] disabled:opacity-60 inline-flex items-center justify-center gap-2"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="login">Se connecter</span>
                    <span wire:loading wire:target="login" class="inline-flex items-center gap-2">
                        <span class="material-symbols-outlined animate-spin text-base">progress_activity</span>
                        Connexion…
                    </span>
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-[#757683]">
                Pas encore de compte ?
                <a href="{{ route('inscription') }}" class="font-bold text-[#001a61] hover:underline" wire:navigate>Créer un compte</a>
            </p>
        </div>
    </section>
</div>
