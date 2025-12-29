# 📊 Intégration API BRVM en Temps Réel

## 🎯 Vue d'ensemble

Ce module permet d'afficher les données boursières de la BRVM en temps réel depuis une API externe, avec un système de fallback sur la base de données locale en cas d'indisponibilité de l'API.

## ✨ Fonctionnalités

### 1. **Récupération Données en Temps Réel**
- ✅ Connexion à une API BRVM externe
- ✅ Cache automatique (5 minutes par défaut)
- ✅ Fallback sur la base de données locale
- ✅ Synchronisation automatique BDD ↔ API
- ✅ Gestion complète des erreurs

### 2. **Affichage Dynamique**
- ✅ Indices BRVM (BRVM 10, Composite, Prestige)
- ✅ Liste complète des titres boursiers
- ✅ Variations en temps réel (+ et -)
- ✅ Volume, capitalisation, secteur
- ✅ Bouton de rafraîchissement manuel

### 3. **Service API Dédié**
- ✅ Service `BRVMApiService` pour gérer toutes les requêtes
- ✅ Gestion automatique des tokens d'authentification
- ✅ Adaptation flexible aux différents formats d'API
- ✅ Logging des erreurs
- ✅ Test de connexion intégré

## 📁 Structure des Fichiers

```
app/
├── Livewire/
│   └── Pages/
│       └── Bourse.php              # Composant page bourse
├── Services/
│   └── BRVMApiService.php          # Service API BRVM
└── Models/
    └── Stock.php                    # Modèle données boursières

config/
└── services.php                     # Configuration API

resources/
└── views/
    └── livewire/
        └── pages/
            └── bourse.blade.php     # Vue avec données dynamiques
```

## 🔧 Configuration

### 1. Variables d'Environnement (.env)

Ajoutez ces lignes à votre fichier `.env` :

```env
# Configuration API BRVM
BRVM_API_URL=https://api.brvm.org/v1
BRVM_API_KEY=votre_clé_api_ici
BRVM_CACHE_DURATION=300
```

**Paramètres :**
- `BRVM_API_URL` : URL de base de l'API BRVM
- `BRVM_API_KEY` : Clé d'authentification (Bearer token)
- `BRVM_CACHE_DURATION` : Durée du cache en secondes (300 = 5 minutes)

### 2. Endpoints API Attendus

Le service attend ces endpoints standards :

```
GET /stocks         → Liste de tous les titres
GET /stocks/{symbol} → Détails d'un titre spécifique
GET /indices        → Indices BRVM (BRVM 10, Composite, Prestige)
GET /health         → Santé de l'API (optionnel)
```

### 3. Format de Réponse API

#### Pour `/stocks` :

```json
{
  "data": [
    {
      "symbol": "SNTS",
      "company_name": "Sonatel",
      "current_price": 16500,
      "previous_price": 16100,
      "variation_percent": 2.48,
      "volume": 1245,
      "market_cap": 2500000,
      "sector": "Télécommunications",
      "high_price": 16800,
      "low_price": 16200
    }
  ]
}
```

**Champs alternatifs supportés** (le service s'adapte automatiquement) :
- `name` ou `companyName` → `company_name`
- `price` ou `lastPrice` → `current_price`
- `previous_close` ou `previousClose` → `previous_price`
- `change_percent` ou `changePercent` → `variation_percent`
- `ticker` → `symbol`
- `high` ou `dayHigh` → `high_price`
- `low` ou `dayLow` → `low_price`
- `tradedVolume` → `volume`
- `marketCap` → `market_cap`
- `industry` → `sector`

#### Pour `/indices` :

```json
{
  "data": [
    {
      "name": "BRVM 10",
      "value": 160.45,
      "variation": 1.2,
      "positive": true
    },
    {
      "name": "BRVM Composite",
      "value": 215.78,
      "variation": 0.8,
      "positive": true
    }
  ]
}
```

## 🚀 Utilisation

### Mode avec API Configurée

1. **Configurer les variables d'environnement** (voir ci-dessus)
2. **Accéder à la page** : `/bourse`
3. **Les données se chargent automatiquement** depuis l'API
4. **Synchronisation auto** : Les données sont sauvegardées en base
5. **Cache actif** : 5 minutes par défaut
6. **Rafraîchir manuellement** : Bouton "Actualiser"

### Mode Sans API (Fallback)

Si l'API n'est pas configurée ou indisponible :
- ✅ Les données de la base de données locale sont affichées
- ✅ Un message informe l'utilisateur du mode hors ligne
- ✅ Les données peuvent être gérées depuis l'admin (`/admin/stock-data`)
- ✅ Aucune erreur n'est affichée

## 🔌 Utilisation du Service

### Dans un Composant Livewire

```php
use App\Services\BRVMApiService;

class MaPage extends Component
{
    protected $brvmService;

    public function boot(BRVMApiService $brvmService)
    {
        $this->brvmService = $brvmService;
    }

    public function mount()
    {
        // Récupérer tous les titres
        $stocks = $this->brvmService->getStocks();

        // Récupérer les indices
        $indices = $this->brvmService->getIndices();

        // Récupérer un titre spécifique
        $stock = $this->brvmService->getStock('SNTS');

        // Vérifier si l'API est configurée
        if ($this->brvmService->isConfigured()) {
            // API active
        }

        // Rafraîchir le cache
        $this->brvmService->refreshData();
    }
}
```

### Depuis un Contrôleur

```php
use App\Services\BRVMApiService;

class BourseController extends Controller
{
    public function index(BRVMApiService $brvmService)
    {
        $stocks = $brvmService->getStocks();
        $indices = $brvmService->getIndices();

        return view('bourse', compact('stocks', 'indices'));
    }
}
```

### Tester la Connexion API

```php
use App\Services\BRVMApiService;

$service = app(BRVMApiService::class);
$result = $service->testConnection();

if ($result['success']) {
    echo "✅ Connexion réussie !";
} else {
    echo "❌ Erreur : " . $result['message'];
}
```

## ⚙️ Personnalisation

### Modifier la Durée du Cache

Dans `config/services.php` :

```php
'brvm' => [
    'cache_duration' => 600, // 10 minutes
],
```

Ou dans `.env` :
```env
BRVM_CACHE_DURATION=600
```

### Adapter à une Autre API

Le service `BRVMApiService` peut être facilement adapté pour d'autres APIs boursières :

1. Modifier les endpoints dans les méthodes du service
2. Ajuster le mapping des champs dans `syncStocksWithDatabase()`
3. Adapter le format de réponse attendu

### Ajouter des Logs Personnalisés

Les logs sont automatiquement enregistrés dans `storage/logs/laravel.log` :

```php
Log::error('BRVM API Exception: ' . $e->getMessage());
Log::warning('BRVM API responded with error', ['status' => $response->status()]);
```

## 🎨 Affichage Frontend

### Page Bourse Publique

URL: `/bourse`

**Sections :**
1. **En-tête** : Titre et description
2. **Indices BRVM** : 3 cartes avec les indices principaux
3. **Tableau des titres** : Liste complète avec variations
4. **Bouton Actualiser** : Rafraîchit les données manuellement

### Indicateurs Visuels

- ✅ Flèche verte ↑ : Variation positive
- ❌ Flèche rouge ↓ : Variation négative
- 🔄 Icône rotation : Actualisation en cours
- ℹ️ Message jaune : Mode hors ligne (pas d'API)

## 📊 Gestion Admin

Pour gérer manuellement les données sans API :

1. Accéder à `/admin/stock-data`
2. Ajouter/Modifier/Supprimer des titres
3. Ces données seront affichées sur `/bourse`

## 🔒 Sécurité

- ✅ Clé API stockée dans `.env` (pas committée)
- ✅ Bearer token pour l'authentification
- ✅ Timeout de 10 secondes sur les requêtes
- ✅ Validation des données reçues
- ✅ Protection CSRF sur les actions

## 🐛 Dépannage

### Problème : "API non configurée"

**Solution** : Ajouter `BRVM_API_URL` et `BRVM_API_KEY` dans `.env`

### Problème : Données pas à jour

**Solution** : 
1. Cliquer sur "Actualiser"
2. Vider le cache : `php artisan cache:clear`
3. Vérifier les logs : `storage/logs/laravel.log`

### Problème : Erreur de connexion API

**Solution** :
1. Vérifier l'URL de l'API
2. Vérifier la clé API
3. Tester manuellement avec `curl` :

```bash
curl -H "Authorization: Bearer VOTRE_CLE" https://api.brvm.org/v1/stocks
```

### Problème : Format de réponse différent

**Solution** : Adapter le mapping dans `BRVMApiService::syncStocksWithDatabase()`

## 📈 Performance

- **Cache automatique** : 5 minutes par défaut
- **Timeout requêtes** : 10 secondes
- **Fallback rapide** : Base de données locale
- **Pas de blocage** : Erreurs API gérées silencieusement

## 🔄 Mises à Jour Automatiques (Optionnel)

Pour actualiser automatiquement les données :

### Avec Laravel Task Scheduling

```php
// app/Console/Kernel.php

protected function schedule(Schedule $schedule)
{
    $schedule->call(function () {
        app(\App\Services\BRVMApiService::class)->refreshData();
    })->everyFiveMinutes();
}
```

Puis dans cron :
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### Avec une Commande Artisan

```php
// app/Console/Commands/RefreshBRVMData.php

php artisan make:command RefreshBRVMData
```

```php
public function handle(BRVMApiService $brvmService)
{
    $brvmService->refreshData();
    $this->info('Données BRVM actualisées !');
}
```

Exécuter : `php artisan brvm:refresh`

## 📚 APIs BRVM Disponibles

### APIs Officielles Connues

1. **BRVM API** (hypothétique - vérifier documentation officielle)
2. **African Markets API**
3. **APIs de courtiers partenaires**

### Alternative : Web Scraping

Si aucune API n'est disponible, vous pouvez :
1. Scraper le site officiel BRVM
2. Parser les données avec Laravel HTTP
3. Respecter les CGU du site

## ✅ Checklist d'Intégration

- [ ] Obtenir une clé API BRVM
- [ ] Ajouter variables dans `.env`
- [ ] Tester la connexion avec `testConnection()`
- [ ] Vérifier le format de réponse
- [ ] Adapter le mapping si nécessaire
- [ ] Tester l'affichage sur `/bourse`
- [ ] Configurer le cache
- [ ] Mettre en place les logs
- [ ] Tester le fallback (désactiver l'API)
- [ ] Documenter pour l'équipe

---

**Module développé et testé** ✅  
**Prêt pour production avec ou sans API** 🚀
