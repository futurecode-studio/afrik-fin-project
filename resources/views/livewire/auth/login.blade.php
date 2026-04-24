<main class="flex-1 pt-20">
    <section class="bg-gradient-hero text-primary-foreground py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Bienvenue,
                </h1>
                <p class="text-lg text-primary-foreground/90">Connectez-vous pour accéder à votre espace personnel et gérer vos finances</p>
            </div>
        </div>
    </section>
    <section class="py-16">
        <div class="container mx-auto px-4 flex items-center justify-center">
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm w-full max-w-md">
        <div class="flex flex-col space-y-1.5 p-6">
            <h3 class="text-2xl font-semibold leading-none tracking-tight">Connexion</h3>
            <p class="text-sm text-muted-foreground">Connectez-vous à votre compte</p>
        </div>
        <div class="p-6 pt-0">
            @if($errorMessage)
                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-800 rounded-md">
                    {{ $errorMessage }}
                </div>
            @endif

            <form wire:submit.prevent="login" class="space-y-4">
                <div class="space-y-2">
                    <label class="text-sm font-medium" for="email">Email</label>
                    <input wire:model="email" type="email"
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base"
                        id="email" placeholder="email@exemple.com" required autofocus>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium" for="password">Mot de passe</label>
                    <input wire:model="password" type="password" 
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base"
                        id="password" placeholder="••••••••" required>
                </div>

                <div class="flex items-center">
                    <input wire:model="remember" id="remember" type="checkbox" 
                        class="rounded border-gray-300 text-primary">
                    <label for="remember" class="ms-2 text-sm text-muted-foreground">
                        Se souvenir de moi
                    </label>
                </div>

                <div class="flex items-center justify-between mt-4">
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium bg-primary text-primary-foreground hover:bg-primary-light h-11 px-6 py-3">
                        Se connecter
                    </button>
                </div>

                <div class="mt-6 pt-6 border-t border-border text-center">
                    <p class="text-sm text-muted-foreground">
                        Pas encore de compte ?
                        <a href="{{ route('inscription') }}" class="text-primary font-medium hover:underline">
                            Créer un compte
                        </a>
                    </p>
                </div>
            </form>

        </div>
    </div>
        </div>
    </section>
</main>