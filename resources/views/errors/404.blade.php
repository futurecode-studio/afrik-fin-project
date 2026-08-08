<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page introuvable (404) — Africaine des Finances</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">
    <style>
        body { font-family: Figtree, sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    </style>
</head>
<body class="bg-[#f9f9ff] text-[#131c2a] min-h-screen flex flex-col">
    <header class="h-20 border-b border-[#c5c5d4] bg-white flex items-center justify-between px-5 lg:px-16">
        <a href="{{ url('/') }}" class="font-bold text-xl text-[#001a61]">Africaine des Finances</a>
        <div class="flex items-center gap-4 text-sm">
            <a href="{{ route('connexion') }}" class="text-[#444652] hover:text-[#001a61] font-medium hidden sm:inline">Espace Client</a>
            <a href="{{ route('contact') }}" class="bg-[#001a61] text-white px-5 py-2 rounded-lg font-bold">Contact</a>
        </div>
    </header>

    <main class="flex-1 flex items-center justify-center px-5 py-16 relative overflow-hidden">
        <div class="absolute inset-0 pointer-events-none opacity-20">
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[700px] bg-[#0a2e8c] rounded-full blur-[120px]"></div>
        </div>
        <div class="relative z-10 max-w-4xl w-full grid md:grid-cols-2 gap-10 items-center">
            <div class="order-2 md:order-1">
                <div class="relative aspect-square max-w-md mx-auto border border-[#c5c5d4] rounded-xl bg-white/80 backdrop-blur flex flex-col items-center justify-center p-8">
                    <div class="text-[100px] font-extrabold text-[#001a61]/20 leading-none select-none">404</div>
                    <div class="mt-5 flex items-center justify-center text-[#001a61]/35">
                        <span class="material-symbols-outlined text-[96px]">query_stats</span>
                    </div>
                    <p class="mt-6 text-xs font-semibold tracking-wider uppercase text-[#757683] flex items-center gap-2">
                        <span class="material-symbols-outlined text-[#ffbf00]">trending_down</span>
                        Valeur non trouvée
                    </p>
                </div>
            </div>
            <div class="order-1 md:order-2 space-y-5">
                <h1 class="text-5xl font-extrabold text-[#001a61] tracking-tight">Oups !</h1>
                <p class="text-xl font-bold text-[#444652]">Cette page a quitté le marché.</p>
                <p class="text-[#757683]">Le lien est peut-être obsolète, ou la ressource n’existe plus. Revenez à l’accueil ou explorez nos outils.</p>
                <div class="flex flex-wrap gap-3 pt-2">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-[#001a61] text-white font-bold">Retour à l’accueil</a>
                    <a href="{{ route('marches.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl border border-[#001a61] text-[#001a61] font-bold">Marchés</a>
                    <a href="{{ route('faq') }}" class="inline-flex items-center gap-2 px-4 py-3 text-[#0a2e8c] font-semibold">FAQ</a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
