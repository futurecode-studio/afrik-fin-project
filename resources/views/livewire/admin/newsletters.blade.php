<main class="container mx-auto px-4 py-8">
    @if (session()->has('message'))
        <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800 border border-green-200">
            {{ session('message') }}
        </div>
    @endif

    <div dir="ltr" data-orientation="horizontal">
        <!-- Onglets -->
        <div role="tablist" aria-orientation="horizontal"
            class="inline-flex h-10 items-center justify-center rounded-md bg-muted p-1 text-muted-foreground"
            tabindex="0" data-orientation="horizontal" style="outline: none;">
            <button type="button" wire:click="setActiveTab('subscribers')"
                class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 {{ $activeTab === 'subscribers' ? 'bg-background text-foreground shadow-sm' : '' }}">
                Abonnés
            </button>
            <button type="button" wire:click="setActiveTab('campaigns')"
                class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 {{ $activeTab === 'campaigns' ? 'bg-background text-foreground shadow-sm' : '' }}">
                Campagnes
            </button>
        </div>

        <!-- Onglet Campagnes -->
        <div class="mt-6 {{ $activeTab === 'campaigns' ? '' : 'hidden' }}">
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex justify-between items-center p-6">
                    <div>
                        <h3 class="text-2xl font-semibold leading-none tracking-tight">Campagnes Newsletter</h3>
                        <p class="text-sm text-muted-foreground mt-1">{{ $campaigns->total() }} campagne(s) créée(s)</p>
                    </div>
                    <button wire:click="$set('showCampaignModal', true)"
                        class="inline-flex items-center justify-center rounded-md text-sm font-medium bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                        Nouvelle Campagne
                    </button>
                </div>
                <div class="p-6 pt-0">
                    <div class="relative w-full overflow-auto">
                        <table class="w-full caption-bottom text-sm">
                            <thead class="[&_tr]:border-b">
                                <tr class="border-b transition-colors hover:bg-muted/50">
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Titre</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Sujet</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Destinataires</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Statut</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Date</th>
                                    <th class="h-12 px-4 align-middle font-medium text-muted-foreground text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="[&_tr:last-child]:border-0">
                                @forelse($campaigns as $campaign)
                                    <tr class="border-b transition-colors hover:bg-muted/50">
                                        <td class="p-4 align-middle">{{ $campaign->title }}</td>
                                        <td class="p-4 align-middle">{{ $campaign->subject }}</td>
                                        <td class="p-4 align-middle">{{ $campaign->recipients_count }}</td>
                                        <td class="p-4 align-middle">
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold 
                                                {{ $campaign->status === 'sent' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $campaign->status === 'draft' ? 'bg-gray-100 text-gray-800' : '' }}
                                                {{ $campaign->status === 'scheduled' ? 'bg-blue-100 text-blue-800' : '' }}">
                                                {{ ucfirst($campaign->status) }}
                                            </span>
                                        </td>
                                        <td class="p-4 align-middle">{{ $campaign->created_at->format('d/m/Y') }}</td>
                                        <td class="p-4 align-middle text-right">
                                            <button wire:click="deleteCampaign({{ $campaign->id }})" 
                                                wire:confirm="Êtes-vous sûr de vouloir supprimer cette campagne ?"
                                                class="inline-flex items-center justify-center rounded-md text-sm font-medium text-destructive hover:bg-destructive/10 h-8 px-3">
                                                Supprimer
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-4 text-center text-muted-foreground">Aucune campagne créée</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $campaigns->links() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Onglet Abonnés -->
        <div class="mt-6 {{ $activeTab === 'subscribers' ? '' : 'hidden' }}">
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex justify-between items-center p-6">
                    <div>
                        <h3 class="text-2xl font-semibold leading-none tracking-tight">Abonnés Newsletter</h3>
                        <p class="text-sm text-muted-foreground mt-1">{{ $subscribers->total() }} abonné(s)</p>
                    </div>
                    <button wire:click="$set('showSubscriberModal', true)"
                        class="inline-flex items-center justify-center rounded-md text-sm font-medium bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                        Ajouter un Abonné
                    </button>
                </div>
                <div class="p-6 pt-0">
                    <div class="relative w-full overflow-auto">
                        <table class="w-full caption-bottom text-sm">
                            <thead class="[&_tr]:border-b">
                                <tr class="border-b transition-colors hover:bg-muted/50">
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Email</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Nom</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Statut</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Date d'inscription</th>
                                    <th class="h-12 px-4 align-middle font-medium text-muted-foreground text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="[&_tr:last-child]:border-0">
                                @forelse($subscribers as $subscriber)
                                    <tr class="border-b transition-colors hover:bg-muted/50">
                                        <td class="p-4 align-middle">{{ $subscriber->email }}</td>
                                        <td class="p-4 align-middle">{{ $subscriber->name ?? 'N/A' }}</td>
                                        <td class="p-4 align-middle">
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $subscriber->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $subscriber->is_active ? 'Actif' : 'Inactif' }}
                                            </span>
                                        </td>
                                        <td class="p-4 align-middle">{{ $subscriber->subscribed_at->format('d/m/Y') }}</td>
                                        <td class="p-4 align-middle text-right space-x-2">
                                            <button wire:click="toggleSubscriberStatus({{ $subscriber->id }})"
                                                class="inline-flex items-center justify-center rounded-md text-sm font-medium hover:bg-secondary h-8 px-3">
                                                {{ $subscriber->is_active ? 'Désactiver' : 'Activer' }}
                                            </button>
                                            <button wire:click="deleteSubscriber({{ $subscriber->id }})"
                                                wire:confirm="Êtes-vous sûr de vouloir supprimer cet abonné ?"
                                                class="inline-flex items-center justify-center rounded-md text-sm font-medium text-destructive hover:bg-destructive/10 h-8 px-3">
                                                Supprimer
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-4 text-center text-muted-foreground">Aucun abonné</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $subscribers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour créer une campagne -->
    @if($showCampaignModal)
        <div class="fixed inset-0 z-[100] bg-black bg-opacity-80 flex items-center justify-center p-4" wire:click.self="$set('showCampaignModal', false)">
            <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto relative">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold">Nouvelle Campagne Newsletter</h2>
                        <button wire:click="$set('showCampaignModal', false)" class="text-gray-500 hover:text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18"/>
                                <path d="m6 6 12 12"/>
                            </svg>
                        </button>
                    </div>

                    <form wire:submit.prevent="createCampaign">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Titre <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="title" 
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm">
                                @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Sujet <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="subject" 
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm">
                                @error('subject') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Contenu <span class="text-red-500">*</span></label>
                                <textarea wire:model="content" rows="6" 
                                    class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"></textarea>
                                @error('content') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 mt-6">
                            <button type="button" wire:click="$set('showCampaignModal', false)"
                                class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-11 px-6 py-3">
                                Annuler
                            </button>
                            <button type="submit"
                                class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary-light shadow-elegant hover:shadow-glow transition-smooth h-11 px-6 py-3">
                                Créer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal pour ajouter un abonné -->
    @if($showSubscriberModal)
        <div class="fixed inset-0 z-[100] bg-black bg-opacity-80 flex items-center justify-center p-4" wire:click.self="$set('showSubscriberModal', false)">
            <div class="bg-white rounded-lg shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto relative">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold">Ajouter un Abonné</h2>
                        <button wire:click="$set('showSubscriberModal', false)" class="text-gray-500 hover:text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18"/>
                                <path d="m6 6 12 12"/>
                            </svg>
                        </button>
                    </div>

                    <form wire:submit.prevent="addSubscriber">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Email <span class="text-red-500">*</span></label>
                                <input type="email" wire:model="subscriberEmail" 
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm">
                                @error('subscriberEmail') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Nom (optionnel)</label>
                                <input type="text" wire:model="subscriberName" 
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-base ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 md:text-sm">
                                @error('subscriberName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 mt-6">
                            <button type="button" wire:click="$set('showSubscriberModal', false)"
                                class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-11 px-6 py-3">
                                Annuler
                            </button>
                            <button type="submit"
                                class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary-light shadow-elegant hover:shadow-glow transition-smooth h-11 px-6 py-3">
                                Ajouter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</main>