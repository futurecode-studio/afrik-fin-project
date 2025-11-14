<main class="container mx-auto px-4 py-8">
    <div dir="ltr" data-orientation="horizontal">
        <div role="tablist" aria-orientation="horizontal"
            class="inline-flex h-10 items-center justify-center rounded-md bg-muted p-1 text-muted-foreground"
            tabindex="0" data-orientation="horizontal" style="outline: none;"><button type="button"
                role="tab" aria-selected="true" aria-controls="radix-:r7:-content-campaigns"
                data-state="active" id="radix-:r7:-trigger-campaigns"
                class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50"
                tabindex="-1" data-orientation="horizontal"
                data-radix-collection-item="">Campagnes</button><button type="button" role="tab"
                aria-selected="false" aria-controls="radix-:r7:-content-subscribers" data-state="inactive"
                id="radix-:r7:-trigger-subscribers"
                class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50"
                tabindex="-1" data-orientation="horizontal" data-radix-collection-item="">Abonnés</button>
        </div>
        <div data-state="active" data-orientation="horizontal" role="tabpanel"
            aria-labelledby="radix-:r7:-trigger-campaigns" id="radix-:r7:-content-campaigns" tabindex="0"
            class="ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 mt-6"
            style="">
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6">
                    <h3 class="text-2xl font-semibold leading-none tracking-tight">Campagnes Newsletter</h3>
                    <p class="text-sm text-muted-foreground">0 campagne(s) créée(s)</p>
                </div>
                <div class="p-6 pt-0">
                    <div class="relative w-full overflow-auto">
                        <table class="w-full caption-bottom text-sm">
                            <thead class="[&amp;_tr]:border-b">
                                <tr
                                    class="border-b transition-colors data-[state=selected]:bg-muted hover:bg-muted/50">
                                    <th
                                        class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                                        Titre</th>
                                    <th
                                        class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                                        Sujet</th>
                                    <th
                                        class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                                        Destinataires</th>
                                    <th
                                        class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                                        Statut</th>
                                    <th
                                        class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&amp;:has([role=checkbox])]:pr-0">
                                        Date</th>
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
        </div>
        <div data-state="inactive" data-orientation="horizontal" role="tabpanel"
            aria-labelledby="radix-:r7:-trigger-subscribers" hidden="" id="radix-:r7:-content-subscribers"
            tabindex="0"
            class="ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 mt-6">
        </div>
    </div>
</main>