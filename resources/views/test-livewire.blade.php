<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Livewire</title>
    @livewireStyles
</head>
<body>
    <div class="container">
        <h1>Test du composant Livewire</h1>
        <p>Ceci est un test pour vérifier que Livewire fonctionne correctement.</p>
        
        <livewire:test-component />
    </div>

    @livewireScripts
</body>
</html>
