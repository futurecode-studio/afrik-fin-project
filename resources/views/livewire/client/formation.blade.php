<div>
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Sidebar - Liste des modules -->
        <div class="lg:w-80 flex-shrink-0">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 sticky top-24">
                <div class="p-4 border-b border-gray-100">
                    <h2 class="font-semibold text-gray-900">{{ $formation->titre }}</h2>
                    <div class="mt-2 flex items-center gap-2">
                        <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-primary rounded-full transition-all" style="width: {{ $enrollment->progress ?? 0 }}%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-600">{{ $enrollment->progress ?? 0 }}%</span>
                    </div>
                </div>
                
                <div class="max-h-[60vh] overflow-y-auto">
                    @foreach($formation->modules as $module)
                        <div class="border-b border-gray-100 last:border-b-0">
                            <div class="p-4 bg-gray-50">
                                <h3 class="font-medium text-gray-900 text-sm">{{ $module->ordre }}. {{ $module->titre }}</h3>
                            </div>
                            <div class="divide-y divide-gray-100">
                                @foreach($module->lessons as $lesson)
                                    <button wire:click="selectLesson({{ $module->id }}, {{ $lesson->id }})"
                                            class="w-full p-3 text-left hover:bg-gray-50 transition-colors flex items-center gap-3 {{ $currentLesson && $currentLesson->id === $lesson->id ? 'bg-primary/5 border-l-2 border-primary' : '' }}">
                                        <div class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0 {{ $currentLesson && $currentLesson->id === $lesson->id ? 'bg-primary text-white' : 'bg-gray-200 text-gray-500' }}">
                                            @if($lesson->type === 'video')
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                                </svg>
                                            @elseif($lesson->type === 'quiz')
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                    <polyline points="14 2 14 8 20 8"></polyline>
                                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                                    <polyline points="10 9 9 9 8 9"></polyline>
                                                </svg>
                                            @endif
                                        </div>
                                        <span class="text-sm text-gray-700 truncate">{{ $lesson->titre }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="flex-1">
            @if($currentLesson)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <!-- Header de la leçon -->
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                            <span>Module {{ $currentModule->ordre }}</span>
                            <span>•</span>
                            <span>{{ ucfirst($currentLesson->type) }}</span>
                            @if($currentLesson->duree)
                                <span>•</span>
                                <span>{{ $currentLesson->duree }} min</span>
                            @endif
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $currentLesson->titre }}</h1>
                    </div>

                    <!-- Contenu de la leçon -->
                    <div class="p-6">
                        @if($currentLesson->type === 'video' && $currentLesson->video_url)
                            <div class="aspect-video bg-black rounded-lg overflow-hidden mb-6">
                                @if(str_contains($currentLesson->video_url, 'youtube') || str_contains($currentLesson->video_url, 'youtu.be'))
                                    @php
                                        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $currentLesson->video_url, $matches);
                                        $videoId = $matches[1] ?? '';
                                    @endphp
                                    <iframe src="https://www.youtube.com/embed/{{ $videoId }}" 
                                            class="w-full h-full" 
                                            frameborder="0" 
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                            allowfullscreen></iframe>
                                @elseif(str_contains($currentLesson->video_url, 'vimeo'))
                                    @php
                                        preg_match('/vimeo\.com\/(\d+)/', $currentLesson->video_url, $matches);
                                        $videoId = $matches[1] ?? '';
                                    @endphp
                                    <iframe src="https://player.vimeo.com/video/{{ $videoId }}" 
                                            class="w-full h-full" 
                                            frameborder="0" 
                                            allow="autoplay; fullscreen; picture-in-picture" 
                                            allowfullscreen></iframe>
                                @else
                                    <video controls class="w-full h-full">
                                        <source src="{{ $currentLesson->video_url }}" type="video/mp4">
                                        Votre navigateur ne supporte pas la lecture de vidéos.
                                    </video>
                                @endif
                            </div>
                        @endif

                        @if($currentLesson->contenu)
                            <div class="prose prose-lg max-w-none">
                                {!! $currentLesson->contenu !!}
                            </div>
                        @endif

                        @if($currentLesson->type === 'quiz')
                            <div class="mt-6 p-6 bg-blue-50 rounded-lg text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto text-blue-500 mb-4">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Quiz disponible</h3>
                                <p class="text-gray-600 mb-4">Testez vos connaissances sur ce module</p>
                                <button class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    Commencer le quiz
                                </button>
                            </div>
                        @endif
                    </div>

                    <!-- Footer avec navigation -->
                    <div class="p-6 border-t border-gray-100 flex items-center justify-between">
                        <button wire:click="completeLesson" 
                                class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Marquer comme terminé
                        </button>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto text-gray-300 mb-4">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucun contenu disponible</h3>
                    <p class="text-gray-500">Cette formation n'a pas encore de contenu.</p>
                </div>
            @endif
        </div>
    </div>
</div>
