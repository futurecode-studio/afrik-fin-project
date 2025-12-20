<main class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h2 class="text-3xl font-bold mb-2">Bienvenue sur votre Tableau de Bord</h2>
        <p class="text-muted-foreground">Gérez l'ensemble de votre site depuis cette interface
            d'administration</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"><a href="{{ route('admin.articles') }}">
            <div
                class="rounded-lg border bg-card text-card-foreground shadow-sm h-full hover:shadow-lg transition-shadow cursor-pointer group">
                <div class="flex flex-col space-y-1.5 p-6">
                    <div class="flex items-center gap-3">
                        <div
                            class="p-2 bg-primary/10 rounded-lg group-hover:bg-primary/20 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-file-text h-6 w-6 text-primary">
                                <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                                <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                                <path d="M10 9H8"></path>
                                <path d="M16 13H8"></path>
                                <path d="M16 17H8"></path>
                            </svg></div>
                        <h3 class="font-semibold tracking-tight text-lg">Articles &amp; Actualités</h3>
                    </div>
                    <p class="text-sm text-muted-foreground">Gérer les articles et actualités</p>
                </div>
            </div>
        </a><a href="{{ route('admin.formations') }}">
            <div
                class="rounded-lg border bg-card text-card-foreground shadow-sm h-full hover:shadow-lg transition-shadow cursor-pointer group">
                <div class="flex flex-col space-y-1.5 p-6">
                    <div class="flex items-center gap-3">
                        <div
                            class="p-2 bg-primary/10 rounded-lg group-hover:bg-primary/20 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-graduation-cap h-6 w-6 text-primary">
                                <path
                                    d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z">
                                </path>
                                <path d="M22 10v6"></path>
                                <path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"></path>
                            </svg></div>
                        <h3 class="font-semibold tracking-tight text-lg">Formations</h3>
                    </div>
                    <p class="text-sm text-muted-foreground">Gérer les formations e-learning</p>
                </div>
            </div>
        </a><a href="{{ route('admin.users') }}">
            <div
                class="rounded-lg border bg-card text-card-foreground shadow-sm h-full hover:shadow-lg transition-shadow cursor-pointer group">
                <div class="flex flex-col space-y-1.5 p-6">
                    <div class="flex items-center gap-3">
                        <div
                            class="p-2 bg-primary/10 rounded-lg group-hover:bg-primary/20 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-users h-6 w-6 text-primary">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg></div>
                        <h3 class="font-semibold tracking-tight text-lg">Utilisateurs</h3>
                    </div>
                    <p class="text-sm text-muted-foreground">Gérer les utilisateurs et rôles</p>
                </div>
            </div>
        </a>
        <!-- <a href="{{ route('admin.stock-data') }}">
            <div
                class="rounded-lg border bg-card text-card-foreground shadow-sm h-full hover:shadow-lg transition-shadow cursor-pointer group">
                <div class="flex flex-col space-y-1.5 p-6">
                    <div class="flex items-center gap-3">
                        <div
                            class="p-2 bg-primary/10 rounded-lg group-hover:bg-primary/20 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-trending-up h-6 w-6 text-primary">
                                <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                                <polyline points="16 7 22 7 22 13"></polyline>
                            </svg></div>
                        <h3 class="font-semibold tracking-tight text-lg">Données Boursières</h3>
                    </div>
                    <p class="text-sm text-muted-foreground">Configuration API BRVM</p>
                </div>
            </div>
        </a> -->
        <a href="{{ route('admin.transactions') }}">
            <div
                class="rounded-lg border bg-card text-card-foreground shadow-sm h-full hover:shadow-lg transition-shadow cursor-pointer group">
                <div class="flex flex-col space-y-1.5 p-6">
                    <div class="flex items-center gap-3">
                        <div
                            class="p-2 bg-primary/10 rounded-lg group-hover:bg-primary/20 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-credit-card h-6 w-6 text-primary">
                                <rect width="20" height="14" x="2" y="5" rx="2"></rect>
                                <line x1="2" x2="22" y1="10" y2="10"></line>
                            </svg></div>
                        <h3 class="font-semibold tracking-tight text-lg">Transactions</h3>
                    </div>
                    <p class="text-sm text-muted-foreground">Suivre les paiements</p>
                </div>
            </div>
        </a>
        <!-- <a href="{{ route('admin.appointments') }}">
            <div
                class="rounded-lg border bg-card text-card-foreground shadow-sm h-full hover:shadow-lg transition-shadow cursor-pointer group">
                <div class="flex flex-col space-y-1.5 p-6">
                    <div class="flex items-center gap-3">
                        <div
                            class="p-2 bg-primary/10 rounded-lg group-hover:bg-primary/20 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-calendar h-6 w-6 text-primary">
                                <path d="M8 2v4"></path>
                                <path d="M16 2v4"></path>
                                <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                                <path d="M3 10h18"></path>
                            </svg></div>
                        <h3 class="font-semibold tracking-tight text-lg">Rendez-vous</h3>
                    </div>
                    <p class="text-sm text-muted-foreground">Gérer les rendez-vous</p>
                </div>
            </div>
        </a> -->
        <a href="{{ route('admin.newsletters') }}">
            <div
                class="rounded-lg border bg-card text-card-foreground shadow-sm h-full hover:shadow-lg transition-shadow cursor-pointer group">
                <div class="flex flex-col space-y-1.5 p-6">
                    <div class="flex items-center gap-3">
                        <div
                            class="p-2 bg-primary/10 rounded-lg group-hover:bg-primary/20 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-mail h-6 w-6 text-primary">
                                <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                            </svg></div>
                        <h3 class="font-semibold tracking-tight text-lg">Newsletters</h3>
                    </div>
                    <p class="text-sm text-muted-foreground">Créer et envoyer newsletters</p>
                </div>
            </div>
        </a><a href="{{ route('admin.contacts') }}">
            <div
                class="rounded-lg border bg-card text-card-foreground shadow-sm h-full hover:shadow-lg transition-shadow cursor-pointer group">
                <div class="flex flex-col space-y-1.5 p-6">
                    <div class="flex items-center gap-3">
                        <div
                            class="p-2 bg-primary/10 rounded-lg group-hover:bg-primary/20 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-message-square h-6 w-6 text-primary">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                            </svg></div>
                        <h3 class="font-semibold tracking-tight text-lg">Messages Contact</h3>
                    </div>
                    <p class="text-sm text-muted-foreground">Gérer les messages de contact</p>
                </div>
            </div>
        </a><a href="{{ route('admin.statistics') }}">
            <div
                class="rounded-lg border bg-card text-card-foreground shadow-sm h-full hover:shadow-lg transition-shadow cursor-pointer group">
                <div class="flex flex-col space-y-1.5 p-6">
                    <div class="flex items-center gap-3">
                        <div
                            class="p-2 bg-primary/10 rounded-lg group-hover:bg-primary/20 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-chart-column h-6 w-6 text-primary">
                                <path d="M3 3v16a2 2 0 0 0 2 2h16"></path>
                                <path d="M18 17V9"></path>
                                <path d="M13 17V5"></path>
                                <path d="M8 17v-3"></path>
                            </svg></div>
                        <h3 class="font-semibold tracking-tight text-lg">Statistiques</h3>
                    </div>
                    <p class="text-sm text-muted-foreground">Statistiques avancées</p>
                </div>
            </div>
        </a>
        <!-- <a href="{{ route('admin.api-config') }}">
            <div
                class="rounded-lg border bg-card text-card-foreground shadow-sm h-full hover:shadow-lg transition-shadow cursor-pointer group">
                <div class="flex flex-col space-y-1.5 p-6">
                    <div class="flex items-center gap-3">
                        <div
                            class="p-2 bg-primary/10 rounded-lg group-hover:bg-primary/20 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-settings h-6 w-6 text-primary">
                                <path
                                    d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z">
                                </path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg></div>
                        <h3 class="font-semibold tracking-tight text-lg">Configuration API</h3>
                    </div>
                    <p class="text-sm text-muted-foreground">Gérer les APIs externes</p>
                </div>
            </div>
        </a> -->
    </div>
    <!-- <div class="rounded-lg border bg-card text-card-foreground shadow-sm mt-8">
        <div class="flex flex-col space-y-1.5 p-6">
            <h3 class="text-2xl font-semibold leading-none tracking-tight">Accès rapide</h3>
        </div>
        <div class="p-6 pt-0 flex flex-wrap gap-2"><a
                class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 border-2 border-primary bg-background text-primary hover:bg-primary hover:text-primary-foreground transition-smooth h-11 px-6 py-3"
                href="{{ route('home') }}">Retour au site</a><a
                class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 border-2 border-primary bg-background text-primary hover:bg-primary hover:text-primary-foreground transition-smooth h-11 px-6 py-3"
                href="{{ route('admin.articles') }}">Nouvel article</a><a
                class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 border-2 border-primary bg-background text-primary hover:bg-primary hover:text-primary-foreground transition-smooth h-11 px-6 py-3"
                href="{{ route('admin.formations') }}">Nouvelle formation</a></div>
    </div> -->
</main>