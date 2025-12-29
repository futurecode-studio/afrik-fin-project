# Guide de configuration - Valeurs Liquidatives

## Installation et configuration initiale

### 1. Installation des dépendances

Les seules dépendances nécessaires sont déjà incluses dans Laravel:
- Livewire (pour les composants réactifs)
- Laravel HTTP (pour les requêtes API)
- Laravel Cache (pour la mise en cache)

Aucune installation supplémentaire n'est requise.

### 2. Configuration de l'environnement

Ajoutez les variables suivantes à votre fichier `.env`:

```env
# Durée du cache des fonds mutuels en secondes (par défaut 3600 = 1 heure)
MUTUAL_FUNDS_CACHE_DURATION=3600

# Timeout pour les requêtes API en secondes (par défaut 15)
MUTUAL_FUNDS_TIMEOUT=15

# Type de cache (par défaut: file)
CACHE_DRIVER=file

# Pour la production avec Redis:
# CACHE_DRIVER=redis
# REDIS_HOST=127.0.0.1
# REDIS_PASSWORD=null
# REDIS_PORT=6379
```

### 3. Vérification de la configuration

Pour vérifier que tout fonctionne:

```bash
php artisan tinker
>>> $service = app(App\Services\MutualFundsApiService::class);
>>> $funds = $service->getMutualFunds();
>>> count($funds); // Devrait afficher le nombre de fonds
```

## Migration depuis une version existante

Si vous aviez déjà une page VL/FCP:

1. **Sauvegarde** de l'ancienne vue:
   ```bash
   cp resources/views/livewire/pages/vl-fcp.blade.php resources/views/livewire/pages/vl-fcp.blade.php.backup
   ```

2. **Remplacement** du composant et de la vue

3. **Test** de la nouvelle version

4. **Suppression** des anciennes données VL/FCP de la base de données (optionnel)

## Performance en production

### Optimisations recommandées

#### 1. Utiliser Redis pour le cache

```bash
# Installation
composer require predis/predis

# Configuration .env
CACHE_DRIVER=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```

#### 2. Augmenter la durée du cache

```env
# Pour une mise à jour quotidienne (24 heures)
MUTUAL_FUNDS_CACHE_DURATION=86400
```

#### 3. Implémenter une tâche CRON pour précharger les données

Créer un commande:

```bash
php artisan make:command PreloadMutualFunds
```

Fichier `app/Console/Commands/PreloadMutualFunds.php`:

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MutualFundsApiService;

class PreloadMutualFunds extends Command
{
    protected $signature = 'mutual-funds:preload';
    protected $description = 'Précharger les données des fonds mutuels';

    public function handle(MutualFundsApiService $service)
    {
        $this->info('Préchargement des données des fonds mutuels...');
        
        try {
            $service->clearCache();
            $funds = $service->getMutualFunds();
            
            $this->info("✓ {$count} fonds chargés avec succès");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('✗ Erreur: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
```

Ajouter à `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    // Précharger les fonds chaque jour à 8h30
    $schedule->command('mutual-funds:preload')->dailyAt('08:30');
}
```

#### 4. Monitoring et logs

```bash
# Vérifier les logs
tail -f storage/logs/laravel.log

# Filtrer les erreurs de fonds mutuels
tail -f storage/logs/laravel.log | grep -i "mutual"
```

## Intégration avec des services externes

### Intégration avec Morningstar

Si vous avez une clé API Morningstar Premium:

```php
// Dans MutualFundsApiService
private function fetchFromMorningstar(): array
{
    try {
        $response = Http::timeout($this->timeout)
            ->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . config('services.morningstar.api_key'),
            ])
            ->get('https://api.morningstar.com/v1/funds', [
                'region' => 'FR',
            ]);

        if ($response->successful()) {
            return $this->parseMorningstarData($response->json());
        }
    } catch (\Exception $e) {
        Log::warning('Morningstar API: ' . $e->getMessage());
    }

    return [];
}
```

Configuration `.env`:
```env
MORNINGSTAR_API_KEY=votre_clé_api
```

### Intégration avec Yahoo Finance

Pour ajouter Yahoo Finance comme source:

```php
private function fetchFromYahooFinance(): array
{
    try {
        // Utiliser: https://rapidapi.com/principalapis/api/yahoo-finance15
        $response = Http::timeout($this->timeout)
            ->withHeaders([
                'X-RapidAPI-Key' => config('services.yahoo_finance.api_key'),
                'X-RapidAPI-Host' => 'yahoo-finance15.p.rapidapi.com'
            ])
            ->get('https://yahoo-finance15.p.rapidapi.com/api/v1/markets/funds');

        if ($response->successful()) {
            return $this->parseYahooFinanceData($response->json());
        }
    } catch (\Exception $e) {
        Log::warning('Yahoo Finance API: ' . $e->getMessage());
    }

    return [];
}
```

## Dépannage courant

### Problème: "Les données ne se mettent pas à jour"

**Solution 1: Vérifier le cache**
```bash
php artisan tinker
>>> Cache::forget('mutual_funds_data')
```

**Solution 2: Réduire la durée du cache**
```env
MUTUAL_FUNDS_CACHE_DURATION=300  # 5 minutes pour tests
```

**Solution 3: Vérifier les drivers de cache**
```bash
# Si le cache file ne fonctionne pas
rm -rf bootstrap/cache/*
chmod 775 bootstrap/cache
```

### Problème: "Erreur de connexion API"

**Vérifier la connectivité:**
```bash
# Tester la connexion
curl https://www.morningstar.com/api/v1/funds

# Vérifier les logs
tail storage/logs/laravel.log | grep -A 5 "mutual"
```

**Augmenter le timeout:**
```env
MUTUAL_FUNDS_TIMEOUT=30  # Au lieu de 15
```

### Problème: "Performance lente"

**Solutions:**

1. **Augmenter le cache:**
   ```env
   MUTUAL_FUNDS_CACHE_DURATION=7200  # 2 heures
   ```

2. **Vérifier la charge:**
   ```bash
   php artisan queue:listen  # Si utilisation de queues
   ```

3. **Optimiser la base de données (si applicable):**
   ```bash
   php artisan optimize
   php artisan config:cache
   php artisan route:cache
   ```

### Problème: "Erreur 'Service not found'"

**Vérifier l'enregistrement du service:**

```bash
php artisan tinker
>>> app(App\Services\MutualFundsApiService::class)  # Devrait fonctionner
```

Si erreur, ajouter à `app/Providers/AppServiceProvider.php`:

```php
public function register()
{
    $this->app->singleton(MutualFundsApiService::class, function ($app) {
        return new MutualFundsApiService();
    });
}
```

## Tests

### Exécuter les tests unitaires

```bash
php artisan test tests/Feature/MutualFundsApiServiceTest.php

# Avec détails
php artisan test tests/Feature/MutualFundsApiServiceTest.php --verbose

# Test spécifique
php artisan test tests/Feature/MutualFundsApiServiceTest.php --filter test_get_mutual_funds_returns_array
```

### Ajouter vos propres tests

```php
// tests/Feature/VlFcpComponentTest.php
use Livewire\Livewire;
use App\Livewire\Pages\VlFcp;

public function test_component_renders_successfully()
{
    Livewire::test(VlFcp::class)
        ->assertStatus(200)
        ->assertSeeText('Cotations en Temps Réel');
}

public function test_filter_by_category()
{
    Livewire::test(VlFcp::class)
        ->call('filterByCategory', 'Actions')
        ->assertSet('selectedCategory', 'Actions');
}
```

## Déploiement

### Checklist de déploiement

- [ ] Variables `.env` configurées
- [ ] Cache driver approprié (Redis pour production)
- [ ] Permissions de fichiers correctes
- [ ] Logs configurés et testés
- [ ] CRON jobs pour préchargement (optionnel)
- [ ] Monitoring des erreurs API
- [ ] Sauvegarde des logs

### Déploiement sur un serveur production

```bash
# 1. Cloner le repo
git clone ...

# 2. Installer les dépendances
composer install --no-dev

# 3. Configurer .env
cp .env.example .env
# Éditer .env avec les vrais paramètres

# 4. Générer la clé d'app
php artisan key:generate

# 5. Exécuter les migrations (si nécessaire)
php artisan migrate --force

# 6. Optimiser pour production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Précharger les données
php artisan mutual-funds:preload

# 8. Configurer les permissions
chmod 755 bootstrap/cache
chmod 755 storage

# 9. Redémarrer les services
systemctl restart php-fpm
systemctl restart nginx  # ou apache2
```

## Monitoring en production

### Health Check

Créer un endpoint de vérification:

```php
Route::get('/health/mutual-funds', function (MutualFundsApiService $service) {
    try {
        $funds = $service->getMutualFunds();
        return response()->json([
            'status' => 'ok',
            'funds_count' => count($funds),
            'timestamp' => now(),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
        ], 500);
    }
});
```

### Alertes

Configurer les alertes pour:
- Erreurs API fréquentes
- Cache expiré
- Timeouts réseau

## Support supplémentaire

Pour toute question:
- 📧 Email: support@votre-site.com
- 📚 Documentation: `/MUTUAL_FUNDS_API.md`
- 🔗 Repository: https://github.com/...
- 💬 Discord/Slack: ...

## Changelog

### Version 1.0.0
- ✓ Page VL/FCP avec données en temps réel
- ✓ Filtrage par catégorie
- ✓ API endpoints JSON
- ✓ Cache optimisé
- ✓ Tests unitaires
- ✓ Documentation complète
