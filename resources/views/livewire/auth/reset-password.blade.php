<main class="flex-1 pt-20">
    <section class="bg-gradient-hero text-primary-foreground py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Réinitialisation du mot de passe</h1>
                <p class="text-lg text-primary-foreground/90">Définissez un nouveau mot de passe pour sécuriser votre compte</p>
            </div>
        </div>
    </section>
    <section class="py-16">
        <div class="container mx-auto px-4 flex items-center justify-center">
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm w-full max-w-md">
                <div class="flex flex-col space-y-1.5 p-6">
                    <h3 class="text-2xl font-semibold leading-none tracking-tight">Nouveau mot de passe</h3>
                    <p class="text-sm text-muted-foreground">Entrez votre nouveau mot de passe ci-dessous</p>
                </div>
                <div class="p-6 pt-0">
                    <!-- Session Status -->

                    @if (session('status'))
                        <div class="mb-4 p-4 text-sm text-blue-700 bg-blue-50 border border-blue-200 rounded-md">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="resetPassword" class="space-y-4">
                        <input type="hidden" name="token" value="{{ $token }}">

                        <!-- Email Address -->
                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="email">Email</label>
                            <input wire:model.defer="email" type="email"
                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                                id="email" name="email" placeholder="email@exemple.com" required autocomplete="username">
                            @error('email')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                for="password">Nouveau mot de passe</label>
                            <input wire:model.defer="password" type="password" 
                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                                id="password" name="password" placeholder="••••••••" required autocomplete="new-password">
                            @error('password')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                for="password_confirmation">Confirmer le mot de passe</label>
                            <input wire:model.defer="password_confirmation" type="password" 
                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                                id="password_confirmation" name="password_confirmation" placeholder="••••••••" required autocomplete="new-password">
                        </div>

                        <div class="flex items-center justify-between mt-4">
                            <a class="text-sm text-primary hover:underline" 
                               href="{{ route('login') }}" 
                               wire:navigate>
                                Retour à la connexion
                            </a>

                            <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 bg-primary text-primary-foreground hover:bg-primary-light shadow-elegant hover:shadow-glow transition-smooth h-11 px-6 py-3"
                                type="submit">
                                Réinitialiser le mot de passe
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>
