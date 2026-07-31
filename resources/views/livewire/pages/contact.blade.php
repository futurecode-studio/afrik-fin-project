<main class="flex-1 pt-20">
    <section class="relative bg-gradient-hero text-primary-foreground py-20 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1423666639041-f56000c27a9a?q=80&w=2074&auto=format&fit=crop" 
                 alt="Contact" 
                 class="w-full h-full object-cover opacity-20">
            <div class="absolute inset-0 bg-gradient-to-r from-primary/90 via-primary/80 to-secondary/90"></div>
        </div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-3xl">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Contactez-<span
                        class="text-secondary">nous</span></h1>
                <p class="text-lg text-primary-foreground/90">Notre équipe est à votre écoute pour répondre
                    à toutes vos questions</p>
            </div>
        </div>
    </section>
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <div>
                    <div
                        class="rounded-lg border bg-card text-card-foreground shadow-sm p-8 border-border shadow-card">
                        <h2 class="text-2xl font-bold mb-6">Envoyez-nous un message</h2>

                        <form wire:submit.prevent="submit" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2">Prénom <span class="text-red-500">*</span></label>
                                    <input wire:model="first_name" type="text"
                                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                                        placeholder="Votre prénom">
                                    @error('first_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Nom <span class="text-red-500">*</span></label>
                                    <input wire:model="last_name" type="text"
                                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                                        placeholder="Votre nom">
                                    @error('last_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Email <span class="text-red-500">*</span></label>
                                <input wire:model="email" type="email"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                                    placeholder="votre.email@exemple.com">
                                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Téléphone <span class="text-red-500">*</span></label>
                                <input wire:model="phone" type="tel"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                                    placeholder="+229 XX XX XX XX">
                                @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Sujet <span class="text-red-500">*</span></label>
                                <input wire:model="subject" type="text"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium file:text-foreground placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                                    placeholder="Objet de votre message">
                                @error('subject') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Message <span class="text-red-500">*</span></label>
                                <textarea wire:model="message"
                                    class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 min-h-32"
                                    placeholder="Décrivez votre demande en détail..."></textarea>
                                @error('message') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <button type="submit" wire:loading.attr="disabled"
                                class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 bg-primary text-primary-foreground hover:bg-primary-light shadow-elegant hover:shadow-glow transition-smooth h-14 rounded-lg px-10 text-base w-full">
                                <span wire:loading.remove>Envoyer le message</span>
                                <span wire:loading>Envoi en cours...</span>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="space-y-6">
                    <div
                        class="rounded-lg border bg-card text-card-foreground shadow-sm p-6 border-border hover:border-primary/30 hover:shadow-elegant transition-smooth">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-mail w-6 h-6 text-primary">
                                    <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                                    <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                                </svg></div>
                            <div>
                                <h3 class="font-semibold text-lg mb-1">Email</h3>
                                <p class="text-muted-foreground mb-2">Envoyez-nous un email, nous répondons
                                    sous 24h</p><a href="mailto:contact@africainedesfinances.com"
                                    class="text-primary font-medium hover:underline">contact@africainedesfinances.com</a>
                            </div>
                        </div>
                    </div>
                    <div
                        class="rounded-lg border bg-card text-card-foreground shadow-sm p-6 border-border hover:border-primary/30 hover:shadow-elegant transition-smooth">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-12 h-12 bg-accent/10 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-phone w-6 h-6 text-accent">
                                    <path
                                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                    </path>
                                </svg></div>
                            <div>
                                <h3 class="font-semibold text-lg mb-1">Téléphone</h3>
                                <p class="text-muted-foreground mb-2">Appelez-nous du lundi au vendredi</p>
                                <a href="tel:+2290144218209"
                                    class="text-primary font-medium hover:underline block">+229 01 44 21 82 09</a>
                                <a href="tel:+2290166555121"
                                    class="text-primary font-medium hover:underline block">+229 01 66 55 51 21</a>
                                <a href="tel:+2290148718851"
                                    class="text-primary font-medium hover:underline block">+229 01 48 71 88 51</a>
                            </div>
                        </div>
                    </div>
                    <div
                        class="rounded-lg border bg-card text-card-foreground shadow-sm p-6 border-border hover:border-primary/30 hover:shadow-elegant transition-smooth">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-12 h-12 bg-secondary/10 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-map-pin w-6 h-6 text-secondary">
                                    <path
                                        d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0">
                                    </path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg></div>
                            <div>
                                <h3 class="font-semibold text-lg mb-1">Adresse</h3>
                                <p class="text-muted-foreground mb-2">Visitez notre bureau</p>
                                <p class="text-foreground">Cot Agla c/3881<br>Agla, 4ème maison à étage après la pharmacie Agla en quittant le stade de l'amitié<br>Cotonou, Bénin</p>
                            </div>
                        </div>
                    </div>
                    <div
                        class="rounded-lg border bg-card text-card-foreground shadow-sm p-6 border-border hover:border-primary/30 hover:shadow-elegant transition-smooth">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-12 h-12 bg-destructive/10 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-clock w-6 h-6 text-destructive">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg></div>
                            <div>
                                <h3 class="font-semibold text-lg mb-1">Horaires d'ouverture</h3>
                                <p class="text-muted-foreground mb-2">Du lundi au vendredi</p>
                                <p class="text-foreground">8h00 - 18h00<br>Samedi: 9h00 - 13h00</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="py-12 bg-muted/30">
        <div class="container mx-auto px-4">
            <div
                class="rounded-lg border bg-card text-card-foreground shadow-sm overflow-hidden border-border">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31720.498358941124!2d2.3588901758194023!3d6.3859622322039105!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1023560981120879%3A0xa8c510dd35428f36!2sStade%20de%20l&#39;Amiti%C3%A9%20G%C3%A9n%C3%A9ral%20Mathieu%20Kerekou!5e0!3m2!1sfr!2sbj!4v1763119601143!5m2!1sfr!2sbj" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </section>
</main>