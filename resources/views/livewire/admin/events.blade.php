<div x-data="{ open: @entangle('showModal').live, del: @entangle('showDeleteModal').live }">
    <div class="admin-page space-y-6">
<div class="flex flex-wrap justify-between items-end gap-4 mb-2">
        <div>
            <nav class="flex items-center gap-2 text-[#757683] mb-2 text-xs font-semibold tracking-wider uppercase">
                <span>Administration</span>
                <span class="material-symbols-outlined text-sm">chevron_right</span>
                <span class="text-[#001a61]">ÉVÉNEMENTS</span>
            </nav>
            <h2 class="text-3xl font-extrabold text-[#001a61] tracking-tight">Gestion des Événements</h2>
        </div>
    </div>

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Événements</h1>
            <button @click="open = true; $wire.openModal()" class="inline-flex items-center gap-2 px-4 py-2 admin-btn-primary rounded-lg hover:bg-primary-light transition-colors text-sm font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                Nouvel événement
            </button>
        </div>

        <div class="mb-4 flex items-center gap-4">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Rechercher un événement..." class="w-full max-w-md px-4 py-2 border border-[#c5c5d4] rounded-lg bg-[#f9f9ff] text-foreground focus:ring-2 focus:ring-primary">
        </div>

        <div class="admin-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="admin-table w-full text-sm">
                    <thead class="bg-[#eef3ff]">
                        <tr>
                            <th class="text-left p-4 font-semibold">Événement</th>
                            <th class="text-left p-4 font-semibold">Date</th>
                            <th class="text-left p-4 font-semibold">Lieu</th>
                            <th class="text-left p-4 font-semibold">Statut</th>
                            <th class="text-left p-4 font-semibold">Inscrits</th>
                            <th class="text-right p-4 font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($events as $event)
                        <tr class="border-t border-[#c5c5d4] hover:bg-[#f0f3ff] transition-colors">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    @if($event->featured_image)
                                        <img src="{{ asset('storage/'.$event->featured_image) }}" alt="" class="w-10 h-10 rounded-lg object-cover">
                                    @else
                                        <div class="w-10 h-10 rounded-lg bg-[#eef3ff] flex items-center justify-center text-[#757683] text-xs">Aucune</div>
                                    @endif
                                    <div>
                                        <p class="font-semibold">{{ $event->title }}</p>
                                        <div class="flex flex-wrap items-center gap-1 mt-0.5">
                                            @if ($event->is_jeudi_opportunite)
                                                <span class="text-[10px] font-extrabold uppercase tracking-wide px-1.5 py-0.5 rounded bg-[#ffbf00] text-[#261a00]">Jeudi d’opportunité</span>
                                            @endif
                                            @if ($event->category)
                                                <p class="text-xs text-[#757683]">{{ $event->category }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 whitespace-nowrap">
                                {{ $event->starts_at?->format('d/m/Y H:i') }}
                            </td>
                            <td class="p-4">{{ $event->city ?? $event->location_name ?? '-' }}</td>
                            <td class="p-4">
                                <div class="flex flex-col gap-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $event->statusColorClasses() }}">
                                        {{ $event->statusLabel() }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $event->pricingBadgeClasses() }}">
                                        {{ $event->pricingLabel() }}
                                    </span>
                                </div>
                            </td>
                            <td class="p-4">
                                <span class="font-semibold">{{ $event->registrations_count }}</span>
                                @if($event->capacity > 0)
                                    <span class="text-[#757683] text-xs"> / {{ $event->capacity }}</span>
                                @endif
                            </td>
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-2 flex-wrap">
                                    <a href="{{ route('admin.event.registrations', $event) }}"
                                        class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1.5 rounded-lg border border-[#c5c5d4] hover:bg-[#e7eeff] text-[#001a61]" wire:navigate.hover>
                                        <span class="material-symbols-outlined text-[16px]">group</span> Inscrits
                                    </a>
                                    <a href="{{ route('admin.event.program', $event) }}"
                                        class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1.5 rounded-lg border border-[#c5c5d4] hover:bg-[#e7eeff] text-[#001a61]" wire:navigate.hover>
                                        <span class="material-symbols-outlined text-[16px]">schedule</span> Programme
                                    </a>
                                    <a href="{{ route('admin.event.speakers', $event) }}"
                                        class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1.5 rounded-lg border border-[#c5c5d4] hover:bg-[#e7eeff] text-[#001a61]" wire:navigate.hover>
                                        <span class="material-symbols-outlined text-[16px]">record_voice_over</span> Intervenants
                                    </a>
                                    <a href="{{ route('admin.event.documents', $event) }}"
                                        class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1.5 rounded-lg border border-[#c5c5d4] hover:bg-[#e7eeff] text-[#001a61]" wire:navigate.hover>
                                        <span class="material-symbols-outlined text-[16px]">description</span> Docs
                                    </a>
                                    @if($event->is_paid)
                                    <a href="{{ route('admin.event.tickets', $event) }}"
                                        class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1.5 rounded-lg border border-[#c5c5d4] hover:bg-[#e7eeff] text-[#001a61]" wire:navigate.hover>
                                        <span class="material-symbols-outlined text-[16px]">confirmation_number</span> Billets
                                    </a>
                                    @endif
                                    @if(in_array($event->status, ['published', 'ongoing']))
                                    <button type="button"
                                        @click="navigator.clipboard.writeText(@js($event->publicUrl())).then(() => window.adfToast && window.adfToast('success', 'Lien public copié'))"
                                        class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1.5 rounded-lg border border-[#ffbf00] bg-[#fff8e1] hover:bg-[#ffefb0] text-[#001a61]"
                                        title="{{ $event->publicUrl() }}">
                                        <span class="material-symbols-outlined text-[16px]">link</span> Lien public
                                    </button>
                                    <a href="{{ $event->publicUrl() }}" target="_blank" rel="noopener"
                                        class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1.5 rounded-lg border border-[#c5c5d4] hover:bg-[#e7eeff] text-[#001a61]">
                                        <span class="material-symbols-outlined text-[16px]">open_in_new</span> Voir
                                    </a>
                                    @endif
                                    <a href="{{ route('admin.event.checkin', $event) }}"
                                        class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1.5 rounded-lg bg-[#001a61] text-white hover:bg-[#0a2e8c]" wire:navigate.hover>
                                        <span class="material-symbols-outlined text-[16px]">qr_code_scanner</span> Émargement QR
                                    </a>
                                    <a href="{{ route('admin.event.products', $event) }}"
                                        class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1.5 rounded-lg border border-[#c5c5d4] hover:bg-[#e7eeff] text-[#001a61]" wire:navigate.hover>
                                        <span class="material-symbols-outlined text-[16px]">storefront</span> Boutique
                                    </a>
                                    <button wire:click="edit({{ $event->id }})" class="text-sm text-primary hover:underline">Modifier</button>
                                    <button wire:click="duplicate({{ $event->id }})" class="text-sm text-[#757683] hover:text-foreground">Dupliquer</button>
                                    @if($event->trashed())
                                        <button wire:click="restore({{ $event->id }})" class="text-sm text-emerald-600 hover:underline">Restaurer</button>
                                    @else
                                        <button wire:click="confirmDelete({{ $event->id }})" class="text-sm text-red-600 hover:underline">Supprimer</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="p-8 text-center text-[#757683]">Aucun événement trouvé.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">{{ $events->links() }}</div>
    </div>

    <!-- Modal Event -->
    <div x-show="open" x-cloak style="display:none" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-data="{ open: true }">
        <div class="fixed inset-0 bg-black/60" @click="open = false"></div>
        <div class="relative adf-modal-panel bg-white rounded-lg shadow-xl w-full max-w-3xl max-h-[90vh] overflow-y-auto border border-[#c5c5d4]">
            <div class="p-6 border-b border-[#c5c5d4] flex items-center justify-between">
                <h2 class="text-xl font-bold">{{ $editMode ? 'Modifier' : 'Créer' }} un événement</h2>
                <button @click="open = false" class="text-[#757683] hover:text-foreground"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1">Titre *</label>
                        <input type="text" wire:model="title" class="w-full px-3 py-2 border border-[#c5c5d4] rounded-lg bg-[#f9f9ff] focus:ring-2 focus:ring-primary">
                        @error('title')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Slug</label>
                        <input type="text" wire:model="slug" class="w-full px-3 py-2 border border-[#c5c5d4] rounded-lg bg-[#f9f9ff] focus:ring-2 focus:ring-primary">
                        @error('slug')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Catégorie</label>
                        <input type="text" wire:model="category" class="w-full px-3 py-2 border border-[#c5c5d4] rounded-lg bg-[#f9f9ff] focus:ring-2 focus:ring-primary">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1">Description courte</label>
                        <textarea wire:model="description" rows="3" class="w-full px-3 py-2 border border-[#c5c5d4] rounded-lg bg-[#f9f9ff] focus:ring-2 focus:ring-primary"></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <x-admin.rich-editor
                            wire:key="event-content-{{ $eventId ?? 'new' }}-{{ $editMode ? 'e' : 'c' }}"
                            model="content"
                            :value="$content"
                            label="Description détaillée"
                            placeholder="Décrivez l’événement : programme, intervenants, infos pratiques…"
                            hint="Gras, titres, listes, liens — sans code HTML."
                            min-height="220px"
                        />
                    </div>
                    <div class="md:col-span-2 rounded-xl border border-dashed border-[#c5c5d4] bg-white p-3 text-xs text-[#757683]">
                        <p class="font-bold text-[#001a61] mb-1">Bloc « Informations » (page publique)</p>
                        <p>Date, horaires, lieu, adresse, ville, pays et capacité ci-dessus alimentent automatiquement la carte Informations à droite sur la page événement.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Type</label>
                        <select wire:model.live="event_type" class="w-full px-3 py-2 border border-[#c5c5d4] rounded-lg bg-[#f9f9ff] focus:ring-2 focus:ring-primary">
                            <option value="physical">Présentiel</option>
                            <option value="online">En ligne</option>
                            <option value="hybrid">Hybride</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Capacité (0 = illimité)</label>
                        <input type="number" wire:model="capacity" min="0" class="w-full px-3 py-2 border border-[#c5c5d4] rounded-lg bg-[#f9f9ff] focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Date de début *</label>
                        <input type="datetime-local" wire:model="starts_at" class="w-full px-3 py-2 border border-[#c5c5d4] rounded-lg bg-[#f9f9ff] focus:ring-2 focus:ring-primary">
                        @error('starts_at')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Date de fin</label>
                        <input type="datetime-local" wire:model="ends_at" class="w-full px-3 py-2 border border-[#c5c5d4] rounded-lg bg-[#f9f9ff] focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Ouverture inscriptions</label>
                        <input type="datetime-local" wire:model="registration_opens_at" class="w-full px-3 py-2 border border-[#c5c5d4] rounded-lg bg-[#f9f9ff] focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Fermeture inscriptions</label>
                        <input type="datetime-local" wire:model="registration_closes_at" class="w-full px-3 py-2 border border-[#c5c5d4] rounded-lg bg-[#f9f9ff] focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Lieu</label>
                        <input type="text" wire:model="location_name" placeholder="{{ in_array($event_type, ['online','hybrid']) ? 'Ex. Zoom / Live' : 'Nom du lieu' }}" class="w-full px-3 py-2 border border-[#c5c5d4] rounded-lg bg-[#f9f9ff] focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Adresse</label>
                        <input type="text" wire:model="location_address" class="w-full px-3 py-2 border border-[#c5c5d4] rounded-lg bg-[#f9f9ff] focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Ville</label>
                        <input type="text" wire:model="city" class="w-full px-3 py-2 border border-[#c5c5d4] rounded-lg bg-[#f9f9ff] focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Pays</label>
                        <input type="text" wire:model="country" class="w-full px-3 py-2 border border-[#c5c5d4] rounded-lg bg-[#f9f9ff] focus:ring-2 focus:ring-primary">
                    </div>

                    @if (in_array($event_type, ['online', 'hybrid'], true))
                    <div class="md:col-span-2 rounded-xl border border-[#001a61]/20 bg-[#e7eeff] p-4 space-y-3">
                        <div>
                            <p class="text-sm font-extrabold text-[#001a61]">Accès visioconférence</p>
                            <p class="text-xs text-[#444652] mt-0.5">Ces infos sont envoyées par e-mail après inscription (et affichées sur le ticket). Elles ne sont pas publiques avant inscription.</p>
                        </div>
                        <div class="grid md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium mb-1">Plateforme</label>
                                <select wire:model="online_platform" class="w-full px-3 py-2 border border-[#c5c5d4] rounded-lg bg-white focus:ring-2 focus:ring-primary">
                                    <option value="zoom">Zoom</option>
                                    <option value="teams">Microsoft Teams</option>
                                    <option value="meet">Google Meet</option>
                                    <option value="other">Autre</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Lien de connexion *</label>
                                <input type="url" wire:model="online_meeting_url" placeholder="https://zoom.us/j/…" class="w-full px-3 py-2 border border-[#c5c5d4] rounded-lg bg-white focus:ring-2 focus:ring-primary">
                                @error('online_meeting_url')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">ID de réunion</label>
                                <input type="text" wire:model="online_meeting_id" placeholder="Ex. 123 456 7890" class="w-full px-3 py-2 border border-[#c5c5d4] rounded-lg bg-white focus:ring-2 focus:ring-primary">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Code secret / mot de passe</label>
                                <input type="text" wire:model="online_meeting_passcode" placeholder="Ex. 8aB3cD" class="w-full px-3 py-2 border border-[#c5c5d4] rounded-lg bg-white focus:ring-2 focus:ring-primary">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium mb-1">Instructions d’accès</label>
                                <textarea wire:model="online_access_instructions" rows="2" placeholder="Ex. Connectez-vous 10 min avant. Micro coupé à l’arrivée." class="w-full px-3 py-2 border border-[#c5c5d4] rounded-lg bg-white focus:ring-2 focus:ring-primary"></textarea>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="md:col-span-2 rounded-xl border border-[#c5c5d4] bg-[#f7f9ff] p-4 space-y-3">
                        <div>
                            <label class="block text-sm font-bold text-[#001a61] mb-1">Image de couverture *</label>
                            <p class="text-xs text-[#757683] mb-2">Grande image affichée sous le titre sur la page publique de l’événement.</p>
                            <input type="file" wire:model="featured_image" accept="image/*" class="w-full text-sm">
                            @error('featured_image')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                            @if($featured_image)
                                <img src="{{ $featured_image->temporaryUrl() }}" class="mt-3 w-full max-h-48 rounded-xl object-cover border border-[#c5c5d4]">
                            @elseif($featured_image_url)
                                <img src="{{ asset('storage/'.$featured_image_url) }}" class="mt-3 w-full max-h-48 rounded-xl object-cover border border-[#c5c5d4]">
                            @endif
                        </div>
                    </div>

                    <!-- Galerie photos -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1">Galerie photos</label>
                        <input type="file" wire:model="galleryImages" multiple accept="image/*" class="w-full text-sm">
                        @error('galleryImages.*')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror

                        <!-- Nouvelles images sélectionnées -->
                        @if($galleryImages)
                            <div class="flex flex-wrap gap-2 mt-2">
                                @foreach($galleryImages as $img)
                                    <div class="w-20 h-20 rounded-lg overflow-hidden border border-[#c5c5d4]">
                                        <img src="{{ $img->temporaryUrl() }}" class="w-full h-full object-cover">
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Images existantes -->
                        @if($existingGallery)
                            <div class="flex flex-wrap gap-2 mt-3">
                                @foreach($existingGallery as $g)
                                    <div class="relative w-20 h-20 rounded-lg overflow-hidden border border-[#c5c5d4] group">
                                        <img src="{{ asset('storage/'.$g['image_path']) }}" class="w-full h-full object-cover">
                                        <button wire:click="removeGalleryImage({{ $g['id'] }})" class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity text-white text-xs">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Titre SEO</label>
                        <input type="text" wire:model="seo_title" class="w-full px-3 py-2 border border-[#c5c5d4] rounded-lg bg-[#f9f9ff] focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Description SEO</label>
                        <input type="text" wire:model="seo_description" class="w-full px-3 py-2 border border-[#c5c5d4] rounded-lg bg-[#f9f9ff] focus:ring-2 focus:ring-primary">
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="checkbox" wire:model="is_featured" id="is_featured" class="rounded border-[#c5c5d4]">
                        <label for="is_featured" class="text-sm font-medium">Événement mis en avant</label>
                    </div>
                    <div class="md:col-span-2 rounded-xl border border-[#ffbf00]/50 bg-[#fff8e1] p-4">
                        <label class="inline-flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" wire:model.live="is_jeudi_opportunite" id="is_jeudi_opportunite" class="mt-1 rounded border-[#c5c5d4]">
                            <span>
                                <span class="block text-sm font-extrabold text-[#001a61]">Jeudi d’opportunité</span>
                                <span class="block text-xs text-[#444652] mt-0.5">
                                    Webinaire hebdomadaire gratuit. Une fois publié, un popup s’ouvre automatiquement sur le site public — pas besoin d’envoyer le lien à l’équipe.
                                </span>
                            </span>
                        </label>
                    </div>
                    @unless($is_jeudi_opportunite)
                    <div class="md:col-span-2 rounded-xl border border-[#c5c5d4] bg-[#f7f9ff] p-4">
                        <label class="inline-flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" wire:model.live="is_paid" id="is_paid" class="mt-1 rounded border-[#c5c5d4]">
                            <span>
                                <span class="block text-sm font-bold text-[#001a61]">Types de billets (gratuits et/ou payants)</span>
                                <span class="block text-xs text-[#757683] mt-0.5">
                                    Activez pour proposer plusieurs billets sur le même événement (ex. Standard gratuit + VIP payant).
                                    Désactivé = inscription libre sans choix de billet.
                                </span>
                            </span>
                        </label>
                        @if($editMode && $is_paid && $eventId)
                            <a href="{{ route('admin.event.tickets', $eventId) }}" class="inline-flex items-center gap-1 mt-3 text-xs font-bold text-[#001a61] underline" wire:navigate.hover>
                                Configurer les billets (gratuits / payants) →
                            </a>
                        @endif
                    </div>
                    @endunless
                    <div>
                        <label class="block text-sm font-medium mb-1">Statut</label>
                        <select wire:model="status" class="w-full px-3 py-2 border border-[#c5c5d4] rounded-lg bg-[#f9f9ff] focus:ring-2 focus:ring-primary">
                            <option value="draft">Brouillon</option>
                            <option value="published">Publié</option>
                            <option value="ongoing">En cours</option>
                            <option value="completed">Terminé</option>
                            <option value="cancelled">Annulé</option>
                            <option value="archived">Archivé</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="p-6 border-t border-[#c5c5d4] flex justify-end gap-3">
                <button @click="open = false" class="px-4 py-2 border border-[#c5c5d4] rounded-lg text-foreground hover:bg-[#eef3ff] transition-colors">Annuler</button>
                <button wire:click="save" class="px-4 py-2 admin-btn-primary rounded-lg hover:bg-primary-light transition-colors font-medium">Enregistrer</button>
            </div>
        </div>
    </div>
    <div x-show="del" x-cloak style="display:none" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/60" @click="del = false"></div>
        <div class="relative adf-modal-panel bg-white rounded-lg shadow-xl w-full max-w-md border border-[#c5c5d4] p-6">
            <h3 class="text-lg font-bold mb-2">Confirmer la suppression</h3>
            <p class="text-sm text-[#757683] mb-6">Cet événement sera mis à la corbeille. Les inscriptions et commandes associées restent en base.</p>
            <div class="flex justify-end gap-3">
                <button @click="del = false" class="px-4 py-2 border border-[#c5c5d4] rounded-lg text-foreground hover:bg-[#eef3ff]">Annuler</button>
                <button wire:click="delete" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Supprimer</button>
            </div>
        </div>
    </div>
    
</div>
