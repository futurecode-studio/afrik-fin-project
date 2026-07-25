<div>
    {{-- Indicateur de chargement Livewire --}}
    <div wire:loading class="fixed top-0 left-0 right-0 bg-blue-500 text-white text-center py-2 z-50">
        Chargement en cours...
    </div>

    <main class="flex-1 pt-20">
        <section class="bg-gradient-hero text-primary-foreground py-20">
            <div class="container mx-auto px-4">
                <div class="max-w-3xl">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">Mot de passe oublié</h1>
                    <p class="text-lg text-primary-foreground/90">Entrez votre adresse email et nous vous enverrons un lien pour réinitialiser votre mot de passe</p>
                </div>
            </div>
        </section>

        <section class="py-16">
            <div class="container mx-auto px-4 flex items-center justify-center">
                <div class="rounded-lg border bg-card text-card-foreground shadow-sm w-full max-w-md">
                    <div class="flex flex-col space-y-1.5 p-6">
                        <h3 class="text-2xl font-semibold leading-none tracking-tight">Récupération du mot de passe</h3>
                        <p class="text-sm text-muted-foreground">Nous vous enverrons un lien de réinitialisation par email</p>
                    </div>
                    <div class="p-6 pt-0">
                        {{-- Message de succès --}}

                        {{-- Session Status --}}
                        @if (session()->has('status'))
                            <div class="mb-4 rounded-lg bg-blue-50 p-4 text-blue-800 border border-blue-200">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form wire:submit="sendPasswordResetLink" class="space-y-4">
                            {{-- Email Address --}}
                            <div class="space-y-2">
                                <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70" for="email">
                                    Email
                                </label>
                                <input wire:model="email" type="email"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                                    id="email" name="email" placeholder="email@exemple.com" required autofocus>
                                @error('email')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="flex items-center justify-between mt-4">
                                <a class="text-sm text-primary hover:underline" 
                                   href="{{ route('connexion') }}" 
                                   wire:navigate>
                                    Retour à la connexion
                                </a>

                                <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 bg-primary text-primary-foreground hover:bg-primary-light shadow-elegant hover:shadow-glow transition-smooth h-11 px-6 py-3"
                                    type="submit">
                                    Envoyer le lien
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>
