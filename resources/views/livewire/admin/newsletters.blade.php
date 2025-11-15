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
            <button type="button" wire:click="setActiveTab('campaigns')"
                class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 {{ $activeTab === 'campaigns' ? 'bg-background text-foreground shadow-sm' : '' }}">
                Campagnes
            </button>
            <button type="button" wire:click="setActiveTab('subscribers')"
                class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 {{ $activeTab === 'subscribers' ? 'bg-background text-foreground shadow-sm' : '' }}">
                Abonnés
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
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 max-w-2xl w-full mx-4">
                <h2 class="text-xl font-semibold mb-4">Nouvelle Campagne Newsletter</h2>
                <form wire:submit.prevent="createCampaign">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Titre</label>
                            <input type="text" wire:model="title" class="w-full px-3 py-2 border rounded-md" required>
                            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Sujet</label>
                            <input type="text" wire:model="subject" class="w-full px-3 py-2 border rounded-md" required>
                            @error('subject') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Contenu</label>
                            <textarea wire:model="content" rows="6" class="w-full px-3 py-2 border rounded-md" required></textarea>
                            @error('content') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="flex justify-end space-x-2 mt-6">
                        <button type="button" wire:click="$set('showCampaignModal', false)"
                            class="px-4 py-2 border rounded-md hover:bg-gray-50">
                            Annuler
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90">
                            Créer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal pour ajouter un abonné -->
    @if($showSubscriberModal)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                <h2 class="text-xl font-semibold mb-4">Ajouter un Abonné</h2>
                <form wire:submit.prevent="addSubscriber">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Email</label>
                            <input type="email" wire:model="subscriberEmail" class="w-full px-3 py-2 border rounded-md" required>
                            @error('subscriberEmail') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Nom (optionnel)</label>
                            <input type="text" wire:model="subscriberName" class="w-full px-3 py-2 border rounded-md">
                            @error('subscriberName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="flex justify-end space-x-2 mt-6">
                        <button type="button" wire:click="$set('showSubscriberModal', false)"
                            class="px-4 py-2 border rounded-md hover:bg-gray-50">
                            Annuler
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90">
                            Ajouter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</main>