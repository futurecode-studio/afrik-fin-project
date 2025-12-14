<div>
    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Formations en cours -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Formations en cours</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $activeEnrollments->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Formations terminées -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Formations terminées</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $completedEnrollments->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Certificats obtenus -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Certificats obtenus</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $completedEnrollments->whereNotNull('certificate_number')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-yellow-600">
                        <circle cx="12" cy="8" r="6"></circle>
                        <path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Progression moyenne -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Progression moyenne</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalProgress }}%</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-purple-600">
                        <line x1="12" y1="20" x2="12" y2="10"></line>
                        <line x1="18" y1="20" x2="18" y2="4"></line>
                        <line x1="6" y1="20" x2="6" y2="16"></line>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Formations en cours -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-8">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">Mes formations en cours</h2>
        </div>
        <div class="p-6">
            @if($activeEnrollments->count() > 0)
                <div class="space-y-4">
                    @foreach($activeEnrollments as $enrollment)
                        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <img src="{{ $enrollment->formation->image_url ?: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=80&h=80&fit=crop' }}" 
                                 alt="{{ $enrollment->formation->titre }}" 
                                 class="w-16 h-16 rounded-lg object-cover">
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 truncate">{{ $enrollment->formation->titre }}</h3>
                                <p class="text-sm text-gray-500">Inscrit le {{ $enrollment->enrolled_at?->format('d/m/Y') ?? $enrollment->created_at->format('d/m/Y') }}</p>
                                <div class="mt-2 flex items-center gap-2">
                                    <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full bg-primary rounded-full transition-all" style="width: {{ $enrollment->progress ?? 0 }}%"></div>
                                    </div>
                                    <span class="text-sm font-medium text-gray-600">{{ $enrollment->progress ?? 0 }}%</span>
                                </div>
                            </div>
                            <a href="{{ route('client.formation', $enrollment->formation->slug) }}" 
                               class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors text-sm font-medium">
                                Continuer
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto text-gray-300 mb-4">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune formation en cours</h3>
                    <p class="text-gray-500 mb-4">Commencez votre parcours d'apprentissage dès maintenant</p>
                    <a href="{{ route('formations') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        Explorer les formations
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Formations terminées avec certificats -->
    @if($completedEnrollments->count() > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">Formations terminées</h2>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @foreach($completedEnrollments as $enrollment)
                    <div class="flex items-center gap-4 p-4 bg-green-50 rounded-lg">
                        <img src="{{ $enrollment->formation->image_url ?: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=80&h=80&fit=crop' }}" 
                             alt="{{ $enrollment->formation->titre }}" 
                             class="w-16 h-16 rounded-lg object-cover">
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-900 truncate">{{ $enrollment->formation->titre }}</h3>
                            <p class="text-sm text-gray-500">Terminé le {{ $enrollment->completed_at?->format('d/m/Y') }}</p>
                            @if($enrollment->certificate_number)
                                <p class="text-sm text-green-600 font-medium mt-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline mr-1">
                                        <circle cx="12" cy="8" r="6"></circle>
                                        <path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"></path>
                                    </svg>
                                    Certificat N° {{ $enrollment->certificate_number }}
                                </p>
                            @endif
                        </div>
                        @if($enrollment->certificate_number)
                            <a href="{{ route('certificate.download', $enrollment) }}" 
                               class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="7 10 12 15 17 10"></polyline>
                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                </svg>
                                Télécharger
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
