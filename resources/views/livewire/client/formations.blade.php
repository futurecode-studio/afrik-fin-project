<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Mes Formations</h1>
        <p class="text-gray-500">Gérez et suivez vos formations</p>
    </div>

    @if($enrollments->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($enrollments as $enrollment)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="relative">
                        <img src="{{ $enrollment->formation->image_url ?: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=400&h=200&fit=crop' }}" 
                             alt="{{ $enrollment->formation->titre }}" 
                             class="w-full h-40 object-cover">
                        <div class="absolute top-3 right-3">
                            @if($enrollment->status === 'completed')
                                <span class="px-3 py-1 bg-green-500 text-white text-xs font-semibold rounded-full">Terminé</span>
                            @elseif($enrollment->status === 'active')
                                <span class="px-3 py-1 bg-blue-500 text-white text-xs font-semibold rounded-full">En cours</span>
                            @else
                                <span class="px-3 py-1 bg-yellow-500 text-white text-xs font-semibold rounded-full">En attente</span>
                            @endif
                        </div>
                    </div>
                    <div class="p-5">
                        <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $enrollment->formation->titre }}</h3>
                        <div class="flex items-center gap-4 text-sm text-gray-500 mb-4">
                            <span class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                {{ $enrollment->formation->duree }}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                </svg>
                                {{ $enrollment->formation->modules->count() }} modules
                            </span>
                        </div>

                        <!-- Barre de progression -->
                        <div class="mb-4">
                            <div class="flex items-center justify-between text-sm mb-1">
                                <span class="text-gray-500">Progression</span>
                                <span class="font-medium text-gray-900">{{ $enrollment->progress ?? 0 }}%</span>
                            </div>
                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-primary rounded-full transition-all" style="width: {{ $enrollment->progress ?? 0 }}%"></div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            @if($enrollment->status === 'active')
                                <a href="{{ route('client.formation', $enrollment->formation->slug) }}" 
                                   class="flex-1 px-4 py-2 bg-primary text-white text-center rounded-lg hover:bg-primary/90 transition-colors text-sm font-medium">
                                    Continuer
                                </a>
                            @elseif($enrollment->status === 'completed')
                                @if($enrollment->certificate_number)
                                    <a href="{{ route('certificate.download', $enrollment) }}" 
                                       class="flex-1 px-4 py-2 bg-green-600 text-white text-center rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                                        Télécharger certificat
                                    </a>
                                @endif
                                <a href="{{ route('client.formation', $enrollment->formation->slug) }}" 
                                   class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium">
                                    Revoir
                                </a>
                            @else
                                <span class="flex-1 px-4 py-2 bg-gray-100 text-gray-500 text-center rounded-lg text-sm">
                                    En attente de paiement
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto text-gray-300 mb-4">
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucune formation</h3>
            <p class="text-gray-500 mb-6">Vous n'êtes inscrit à aucune formation pour le moment</p>
            <a href="{{ route('formations') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                Explorer les formations
            </a>
        </div>
    @endif
</div>
