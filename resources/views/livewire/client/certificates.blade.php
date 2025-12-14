<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Mes Certificats</h1>
        <p class="text-gray-500">Téléchargez vos certificats de réussite</p>
    </div>

    @if($certificates->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($certificates as $enrollment)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-primary to-primary/80 p-6 text-white">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="8" r="6"></circle>
                                    <path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg">Certificat de Réussite</h3>
                                <p class="text-white/80 text-sm">N° {{ $enrollment->certificate_number }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <h4 class="font-semibold text-gray-900 mb-2">{{ $enrollment->formation->titre }}</h4>
                        <div class="space-y-2 text-sm text-gray-500 mb-4">
                            <p class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                Délivré le {{ $enrollment->certificate_issued_at->format('d/m/Y') }}
                            </p>
                            <p class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg>
                                Formation terminée le {{ $enrollment->completed_at->format('d/m/Y') }}
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('certificate.download', $enrollment) }}" 
                               class="flex-1 px-4 py-2 bg-primary text-white text-center rounded-lg hover:bg-primary/90 transition-colors text-sm font-medium flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="7 10 12 15 17 10"></polyline>
                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                </svg>
                                Télécharger PDF
                            </a>
                            <a href="{{ route('certificate.view', $enrollment) }}" 
                               target="_blank"
                               class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                Voir
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto text-gray-300 mb-4">
                <circle cx="12" cy="8" r="6"></circle>
                <path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucun certificat</h3>
            <p class="text-gray-500 mb-6">Terminez une formation pour obtenir votre certificat</p>
            <a href="{{ route('client.formations') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors font-medium">
                Voir mes formations
            </a>
        </div>
    @endif
</div>
