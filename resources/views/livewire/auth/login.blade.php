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

            <a href="{{ route('auth.google.redirect') }}"
                class="mt-6 w-full inline-flex items-center justify-center gap-3 rounded-lg border border-[#c5c5d4] bg-white px-4 py-3 text-sm font-bold text-[#001a61] hover:bg-[#f9f9ff] transition">
                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.1c-.22-.66-.35-1.36-.35-2.1s.13-1.44.35-2.1V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l3.66-2.84z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06L5.84 9.9C6.71 7.3 9.14 5.38 12 5.38z"/>
                </svg>
                Se connecter via Google
            </a>

            <div class="my-5 flex items-center gap-3">
                <div class="h-px flex-1 bg-[#e7eeff]"></div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-[#757683]">ou</span>
                <div class="h-px flex-1 bg-[#e7eeff]"></div>
            </div>

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
