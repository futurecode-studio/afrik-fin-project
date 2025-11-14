<main class="container mx-auto px-4 py-8">
    <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
        <div class="flex flex-col space-y-1.5 p-6">
            <h3 class="text-2xl font-semibold leading-none tracking-tight">Liste des Titres</h3>
            <p class="text-sm text-muted-foreground">0 titre(s) suivi(s)</p>
        </div>
        <div class="p-6 pt-0">
            <div class="relative w-full overflow-auto">
                <table class="w-full caption-bottom text-sm">
                    <thead class="[&amp;_tr]:border-b">
                        <tr
                            class="border-b transition-colors data-[state=selected]:bg-muted hover:bg-muted/50">
                            <th
                                class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                                Symbole</th>
                            <th
                                class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                                Entreprise</th>
                            <th
                                class="h-12 px-4 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 text-right">
                                Prix</th>
                            <th
                                class="h-12 px-4 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 text-right">
                                Variation</th>
                            <th
                                class="h-12 px-4 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 text-right">
                                Volume</th>
                            <th
                                class="h-12 px-4 align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0 text-right">
                                Cap. (M)</th>
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