# API Endpoints - Valeurs Liquidatives

## Vue d'ensemble

Les endpoints API permettent d'accéder aux données des valeurs liquidatives (VL/FCP) en format JSON pour intégration avec d'autres systèmes.

## Endpoints disponibles

### 1. Récupérer tous les fonds

```http
GET /api/mutual-funds
```

**Réponse:**
```json
{
    "success": true,
    "data": [
        {
            "id": "SOGEF001",
            "name": "Sogéfidev Actions",
            "company": "SOGÉ GESTION",
            "nav_value": "8 542,50 FCFA",
            "nav_numeric": 8542.50,
            "variation": "+125,30 (+1,49%)",
            "variation_percentage": 1.49,
            "currency": "FCFA",
            "date": "2025-11-25",
            "category": "Actions"
        },
        ...
    ],
    "timestamp": "2025-11-25T14:30:00.000000Z"
}
```

### 2. Récupérer les catégories disponibles

```http
GET /api/mutual-funds/categories/list
```

**Réponse:**
```json
{
    "success": true,
    "categories": ["Actions", "Obligations", "Mixte", "Monétaire"],
    "timestamp": "2025-11-25T14:30:00.000000Z"
}
```

### 3. Récupérer les fonds par catégorie

```http
GET /api/mutual-funds/category/{category}
```

**Exemple:**
```http
GET /api/mutual-funds/category/Actions
```

**Réponse:**
```json
{
    "success": true,
    "category": "Actions",
    "data": [
        {
            "id": "SOGEF001",
            "name": "Sogéfidev Actions",
            "company": "SOGÉ GESTION",
            "nav_value": "8 542,50 FCFA",
            "nav_numeric": 8542.50,
            "variation": "+125,30 (+1,49%)",
            "variation_percentage": 1.49,
            "currency": "FCFA",
            "date": "2025-11-25",
            "category": "Actions"
        },
        ...
    ],
    "timestamp": "2025-11-25T14:30:00.000000Z"
}
```

### 4. Récupérer un fonds spécifique

```http
GET /api/mutual-funds/{id}
```

**Exemple:**
```http
GET /api/mutual-funds/SOGEF001
```

**Réponse (succès):**
```json
{
    "success": true,
    "data": {
        "id": "SOGEF001",
        "name": "Sogéfidev Actions",
        "company": "SOGÉ GESTION",
        "nav_value": "8 542,50 FCFA",
        "nav_numeric": 8542.50,
        "variation": "+125,30 (+1,49%)",
        "variation_percentage": 1.49,
        "currency": "FCFA",
        "date": "2025-11-25",
        "category": "Actions"
    },
    "timestamp": "2025-11-25T14:30:00.000000Z"
}
```

**Réponse (non trouvé):**
```json
{
    "success": false,
    "message": "Fonds non trouvé"
}
```

## Codes de statut HTTP

| Statut | Description |
|--------|-------------|
| 200 | Succès |
| 404 | Ressource non trouvée |
| 500 | Erreur serveur |

## Exemples d'utilisation

### JavaScript / Fetch API

```javascript
// Récupérer tous les fonds
fetch('/api/mutual-funds')
    .then(response => response.json())
    .then(data => {
        console.log('Fonds:', data.data);
    })
    .catch(error => console.error('Erreur:', error));

// Récupérer les fonds Actions
fetch('/api/mutual-funds/category/Actions')
    .then(response => response.json())
    .then(data => {
        console.log('Fonds Actions:', data.data);
    });

// Récupérer un fonds spécifique
fetch('/api/mutual-funds/SOGEF001')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Fonds:', data.data);
        } else {
            console.log('Fonds non trouvé');
        }
    });
```

### cURL

```bash
# Récupérer tous les fonds
curl https://votre-site.com/api/mutual-funds

# Récupérer une catégorie
curl https://votre-site.com/api/mutual-funds/category/Actions

# Récupérer un fonds
curl https://votre-site.com/api/mutual-funds/SOGEF001
```

### Python

```python
import requests

# Récupérer tous les fonds
response = requests.get('https://votre-site.com/api/mutual-funds')
funds = response.json()['data']

# Filtrer par catégorie
response = requests.get('https://votre-site.com/api/mutual-funds/category/Actions')
action_funds = response.json()['data']

# Récupérer un fonds
response = requests.get('https://votre-site.com/api/mutual-funds/SOGEF001')
fund = response.json()['data']
```

### Laravel / Guzzle

```php
use Illuminate\Support\Facades\Http;

// Récupérer tous les fonds
$response = Http::get('https://votre-site.com/api/mutual-funds');
$funds = $response->json()['data'];

// Récupérer une catégorie
$response = Http::get('https://votre-site.com/api/mutual-funds/category/Actions');
$actionFunds = $response->json()['data'];

// Récupérer un fonds
$response = Http::get('https://votre-site.com/api/mutual-funds/SOGEF001');
$fund = $response->json()['data'];
```

## Paramètres de requête

Actuellement, les endpoints ne supportent pas de paramètres de requête additionnels, mais voici des idées pour les futures améliorations:

```http
GET /api/mutual-funds?sort=name&order=asc
GET /api/mutual-funds?search=Sogé
GET /api/mutual-funds?currency=FCFA
GET /api/mutual-funds?minNav=5000&maxNav=10000
```

## Limites et restrictions

- **Authentification**: Aucune authentification requise actuellement
- **Rate limiting**: À déterminer selon la charge
- **CORS**: À configurer selon les besoins
- **Cache**: Les données sont mises en cache pendant 1 heure (configurable)

## Optimisations

Pour l'utilisation en production:

1. **Filtrer côté client** plutôt que de faire plusieurs requêtes API
2. **Mettre en cache** les réponses dans le navigateur:
   ```javascript
   const cacheKey = 'mutual_funds_cache';
   if (localStorage[cacheKey]) {
       const cached = JSON.parse(localStorage[cacheKey]);
       if (Date.now() - cached.timestamp < 3600000) { // 1 heure
           return cached.data;
       }
   }
   ```

3. **Utiliser GraphQL** pour des requêtes plus efficaces (future amélioration)

## Documentation API Swagger

Pour ajouter une documentation Swagger, installez:
```bash
composer require darkaonline/l5-swagger
php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
```

Puis annotez vos contrôleurs avec les commentaires OpenAPI.

## Support et débogage

Pour déboguer les requêtes API:

```bash
# Afficher les requêtes
php artisan tinker
>>> $service = app(App\Services\MutualFundsApiService::class);
>>> $service->getMutualFunds();

# Vérifier le cache
>>> Cache::get('mutual_funds_data');

# Effacer le cache
>>> Cache::forget('mutual_funds_data');
```

## Réponses d'erreur

Les erreurs sont retournées en JSON avec un code de statut HTTP approprié:

```json
{
    "success": false,
    "message": "Description de l'erreur"
}
```

### Exemples d'erreurs courantes

**404 - Fonds non trouvé:**
```json
{
    "success": false,
    "message": "Fonds non trouvé"
}
```

**500 - Erreur serveur:**
```json
{
    "success": false,
    "message": "Une erreur est survenue"
}
```
