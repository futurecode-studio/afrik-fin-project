<main class="flex-1 pt-20">
    <section class="bg-gradient-hero text-primary-foreground py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Bienvenue,
                    <!-- <span class="text-secondary">Africaine des Finances</span> -->
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
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form wire:submit="login" class="space-y-4">
                <!-- Email Address -->
                <div class="space-y-2">
                    <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="email">Email</label>
                    <input wire:model="form.email" type="email"
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                        id="email" name="email" placeholder="email@exemple.com" required autofocus autocomplete="username">
                    <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="space-y-2">
                    <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                        for="password">Mot de passe</label>
                    <input wire:model="form.password" type="password" 
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                        id="password" name="password" placeholder="••••••••" required autocomplete="current-password">
                    <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input wire:model="form.remember" id="remember" type="checkbox" 
                        class="rounded border-gray-300 text-primary shadow-sm focus:ring-primary" 
                        name="remember">
                    <label for="remember" class="ms-2 text-sm text-muted-foreground">
                        Se souvenir de moi
                    </label>
                </div>

                <div class="flex items-center justify-between mt-4">
                    @if (Route::has('password.request'))
                        <a class="text-sm text-primary hover:underline" 
                           href="{{ route('password.request') }}" 
                           wire:navigate>
                            Mot de passe oublié?
                        </a>
                    @endif

                    <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 bg-primary text-primary-foreground hover:bg-primary-light shadow-elegant hover:shadow-glow transition-smooth h-11 px-6 py-3"
                        type="submit">
                        Se connecter
                    </button>
                </div>
            </form>

        </div>
    </div>
        </div>
    </section>
</main>