<main class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="flex flex-col space-y-1.5 p-6 pb-3">
                <h3 class="tracking-tight text-sm font-medium">En attente</h3>
            </div>
            <div class="p-6 pt-0">
                <p class="text-2xl font-bold">0</p>
            </div>
        </div>
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="flex flex-col space-y-1.5 p-6 pb-3">
                <h3 class="tracking-tight text-sm font-medium">Confirmés</h3>
            </div>
            <div class="p-6 pt-0">
                <p class="text-2xl font-bold">0</p>
            </div>
        </div>
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="flex flex-col space-y-1.5 p-6 pb-3">
                <h3 class="tracking-tight text-sm font-medium">Terminés</h3>
            </div>
            <div class="p-6 pt-0">
                <p class="text-2xl font-bold">0</p>
            </div>
        </div>
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="flex flex-col space-y-1.5 p-6 pb-3">
                <h3 class="tracking-tight text-sm font-medium">Annulés</h3>
            </div>
            <div class="p-6 pt-0">
                <p class="text-2xl font-bold">0</p>
            </div>
        </div>
    </div>
    <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
        <div class="flex flex-col space-y-1.5 p-6">
            <h3 class="text-2xl font-semibold leading-none tracking-tight">Liste des Rendez-vous</h3>
            <p class="text-sm text-muted-foreground">0 rendez-vous enregistré(s)</p>
        </div>
        <div class="p-6 pt-0">
            <div class="relative w-full overflow-auto">
                <table class="w-full caption-bottom text-sm">
                    <thead class="[&amp;_tr]:border-b">
                        <tr
                            class="border-b transition-colors data-[state=selected]:bg-muted hover:bg-muted/50">
                            <th
                                class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                                User ID</th>
                            <th
                                class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                                Titre</th>
                            <th
                                class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                                Date &amp; Heure</th>
                            <th
                                class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                                Durée</th>
                            <th
                                class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                                Statut</th>
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