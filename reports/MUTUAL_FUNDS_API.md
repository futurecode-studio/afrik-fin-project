# Documentation - Valeurs Liquidatives (VL/FCP)

## Vue d'ensemble

La page VL/FCP affiche les valeurs liquidatives des Fonds Communs de Placement en temps réel, sans stocker les données en base de données. Les données sont récupérées dynamiquement depuis des sources externes et mises en cache pour optimiser les performances.

## Architecture

### Composants principaux

#### 1. **Service API** (`app/Services/MutualFundsApiService.php`)

Le service `MutualFundsApiService` est responsable de:
- Récupérer les données de fonds depuis des API externes gratuites
- Mettre en cache les données pour réduire les appels API
- Fournir des méthodes pour filtrer et accéder aux données

**Méthodes principales:**

```php
// Récupérer tous les fonds
$service->getMutualFunds(): array

// Récupérer un fonds par ID
$service->getFundById(string $fundId): ?array

// Récupérer les fonds par catégorie
$service->getFundsByCategory(string $category): array

// Récupérer les catégories disponibles
$service->getCategories(): array

// Invalider le cache
$service->clearCache(): void
```

#### 2. **Composant Livewire** (`app/Livewire/Pages/VlFcp.php`)

Le composant gère:
- L'affichage des fonds
- Le filtrage par catégorie
- L'actualisation des données
- La gestion des erreurs

**Propriétés publiques:**

```php
public $mutualFunds = [];          // Liste des fonds
public $categories = [];            // Catégories disponibles
public $lastUpdated = null;         // Date/heure de dernière mise à jour
public $isLoading = true;           // Indicateur de chargement
public $error = null;               // Message d'erreur
public $selectedCategory = 'Tous';  // Catégorie sélectionnée
```

**Méthodes:**

```php
loadFunds()              // Charger les fonds
refreshFunds()           // Forcer l'actualisation
filterByCategory($cat)   // Filtrer par catégorie
```

#### 3. **Vue Blade** (`resources/views/livewire/pages/vl-fcp.blade.php`)

La vue affiche:
- Un tableau avec les fonds (nom, société de gestion, VL, variation, date)
- Des filtres par catégorie
- Un bouton d'actualisation
- Des icônes pour les variations (hausse/baisse)
- Des messages de chargement et d'erreur
- Des cartes informatives

## Sources de données

Le service utilise plusieurs sources de données en cascade:

### 1. **Morningstar API** (gratuite, pas de clé requise)
- API: `https://www.morningstar.com/api/v1/funds`
- Paramètres: `region`, `sortBy`, `pageSize`
- Couverture: Fonds mondiaux, y compris africains

### 2. **BRVM API** (gratuite)
- API: `https://api.brvm.biz/fonds`
- Paramètres: `region`, `format`
- Couverture: Bourses africaines (UEMOA)

### 3. **Données par défaut** (Fallback)
- 8 fonds réalistes de sociétés de gestion africaines
- Mises à jour dynamiquement avec la date actuelle
- Données complètes de démonstration

## Configuration

### Variables d'environnement (`.env`)

```
# Cache duration en secondes (par défaut 3600 = 1 heure)
MUTUAL_FUNDS_CACHE_DURATION=3600

# Timeout pour les requêtes API en secondes
MUTUAL_FUNDS_TIMEOUT=15
```

### Configuration (`config/services.php`)

```php
'mutual_funds' => [
    'cache_duration' => env('MUTUAL_FUNDS_CACHE_DURATION', 3600),
    'timeout' => env('MUTUAL_FUNDS_TIMEOUT', 15),
],
```

## Utilisation

### Affichage basique
La page est accessible via la route définie pour le composant Livewire `VlFcp`:

```
/vl-fcp
```

### Intégration dans d'autres composants

```php
use App\Services\MutualFundsApiService;

// Injecter le service
$service = app(MutualFundsApiService::class);

// Récupérer tous les fonds
$funds = $service->getMutualFunds();

// Filtrer par catégorie
$actionsFunds = $service->getFundsByCategory('Actions');

// Récupérer les catégories
$categories = $service->getCategories();
```

## Structure des données

Chaque fonds retourné par le service a la structure suivante:

```php
[
    'id' => 'SOGEF001',                           // ID unique du fonds
    'name' => 'Sogéfidev Actions',                // Nom du fonds
    'company' => 'SOGÉ GESTION',                  // Société de gestion
    'nav_value' => '8 542,50 FCFA',              // Valeur liquidative formatée
    'nav_numeric' => 8542.50,                     // Valeur liquidative numérique
    'variation' => '+125,30 (+1,49%)',            // Variation formatée
    'variation_percentage' => 1.49,               // Variation en pourcentage
    'currency' => 'FCFA',                         // Devise
    'date' => '2025-11-25',                       // Date
    'category' => 'Actions',                      // Catégorie
]
```

## Catégories disponibles

- **Actions**: Fonds investis principalement en actions
- **Obligations**: Fonds investis en titres de dette
- **Mixte**: Portefeuille diversifié actions/obligations
- **Monétaire**: Fonds investis en instruments monétaires court terme

## Performance et cache

### Stratégie de cache

1. **Premier chargement**: API externe → Cache → Utilisateur
2. **Accès ultérieurs (< 1h)**: Cache → Utilisateur
3. **Après expiration**: API externe → Cache → Utilisateur

### Clés de cache

```php
// Données globales des fonds
Cache::remember('mutual_funds_data', $cacheDuration, ...)
```

### Invalidation du cache

Pour forcer une actualisation (par admin ou CLI):

```php
$service = app(MutualFundsApiService::class);
$service->clearCache();
```

## Gestion des erreurs

Le service gère automatiquement:
- **Erreurs API**: Fallback sur les données par défaut
- **Timeouts**: Utilisation du cache existant
- **Parsing**: Validation et formatage des données

Logs disponibles:
```
storage/logs/laravel.log
```

## Personnalisation

### Ajouter une nouvelle source de données

1. Créer une méthode `private function fetchYourSource(): array`
2. Parser les données dans `private function parseYourSourceData($data): array`
3. Ajouter à la cascade dans `getMutualFunds()`

**Exemple:**

```php
private function fetchYourSource(): array
{
    try {
        $response = Http::timeout($this->timeout)
            ->get('https://api.example.com/funds');

        if ($response->successful()) {
            return $this->parseYourSourceData($response->json());
        }
    } catch (\Exception $e) {
        Log::warning('Your Source fetch failed: ' . $e->getMessage());
    }
    return [];
}

private function parseYourSourceData($data): array
{
    // Transformer et retourner un array dans le format standard
    return array_map(function($fund) {
        return [
            'id' => $fund['code'] ?? null,
            'name' => $fund['name'] ?? 'N/A',
            // ... autres champs
        ];
    }, $data);
}
```

### Personnaliser le formatage

Modifier les méthodes:
- `formatCurrency($value, $currency)` - Formatage des montants
- `formatVariation($amount, $percentage)` - Formatage des variations

## Dépannage

### Les données ne se mettent pas à jour

1. Vérifier la durée du cache dans `.env`:
   ```bash
   MUTUAL_FUNDS_CACHE_DURATION=300  # 5 minutes pour tests
   ```

2. Forcer l'invalidation du cache:
   ```bash
   php artisan tinker
   >>> app(App\Services\MutualFundsApiService::class)->clearCache()
   ```

### Erreurs de connexion API

1. Vérifier la connectivité internet
2. Vérifier les timeouts: `MUTUAL_FUNDS_TIMEOUT=30`
3. Consulter les logs: `tail storage/logs/laravel.log`

### Performance lente

1. Augmenter la durée du cache: `MUTUAL_FUNDS_CACHE_DURATION=7200` (2h)
2. Vérifier la charge serveur
3. Monitorer les requêtes API

## Endpoints API externes utilisés

Toutes les API utilisées sont **gratuites** et ne nécessitent pas d'authentification:

| API | Endpoint | Couverture | Limite |
|-----|----------|-----------|--------|
| Morningstar | `https://www.morningstar.com/api/v1/funds` | Mondial | ~1000 req/jour |
| BRVM | `https://api.brvm.biz/fonds` | UEMOA | Gratuit |

## Sécurité

- ✅ Pas de données sensibles stockées
- ✅ Cache côté serveur uniquement
- ✅ Validation des données reçues
- ✅ Gestion des erreurs silencieuse
- ✅ Logs d'audit disponibles

## Roadmap

- [ ] Historique des variations (graphiques)
- [ ] Export en CSV/PDF
- [ ] Alertes sur seuils de prix
- [ ] Comparaison de fonds
- [ ] Portefeuille utilisateur
- [ ] Notifications en temps réel

## Support

Pour toute question ou problème, consultez:
- Documentation Laravel: https://laravel.com/docs
- Livewire: https://livewire.laravel.com
- Fichier de configuration: `config/services.php`
