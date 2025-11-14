<main class="container mx-auto px-4 py-8">
    <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
        <div class="flex flex-col space-y-1.5 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-semibold leading-none tracking-tight">Liste des Utilisateurs
                    </h3>
                    <p class="text-sm text-muted-foreground">0 utilisateur(s) enregistré(s)</p>
                </div><button
                    class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 bg-primary text-primary-foreground hover:bg-primary-light shadow-elegant hover:shadow-glow transition-smooth h-11 px-6 py-3"
                    type="button" aria-haspopup="dialog" aria-expanded="false" aria-controls="radix-:r3:"
                    data-state="closed"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-user-plus h-4 w-4 mr-2">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <line x1="19" x2="19" y1="8" y2="14"></line>
                        <line x1="22" x2="16" y1="11" y2="11"></line>
                    </svg>Ajouter un utilisateur</button>
            </div>
        </div>
        <div class="p-6 pt-0">
            <div class="relative w-full overflow-auto">
                <table class="w-full caption-bottom text-sm">
                    <thead class="[&amp;_tr]:border-b">
                        <tr
                            class="border-b transition-colors data-[state=selected]:bg-muted hover:bg-muted/50">
                            <th
                                class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                                Nom</th>
                            <th
                                class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                                Email</th>
                            <th
                                class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                                Date d'inscription</th>
                            <th
                                class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                                Rôle</th>
                            <th
                                class="h-12 px-4 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 text-right">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="[&amp;_tr:last-child]:border-0"></tbody>
                </table>
            </div>
        </div>
    </div>
</main>