# Quick Start - Valeurs Liquidatives (VL/FCP)

## En 5 minutes ⚡

### Étape 1: Configuration basique

Aucune configuration requise! Les fichiers sont déjà en place:
- ✓ Service API: `app/Services/MutualFundsApiService.php`
- ✓ Composant Livewire: `app/Livewire/Pages/VlFcp.php`
- ✓ Vue: `resources/views/livewire/pages/vl-fcp.blade.php`
- ✓ Routes API: `routes/api.php`

### Étape 2: Vérifier l'installation

```bash
# Vérifier que le service fonctionne
php artisan tinker
>>> $service = app(App\Services\MutualFundsApiService::class);
>>> $funds = $service->getMutualFunds();
>>> count($funds);  # Devrait afficher un nombre > 0
```

### Étape 3: Accéder à la page

Accédez à la page via votre route Livewire:
```
http://votresite.com/vl-fcp
```

### Étape 4: Tester les API

```bash
# Récupérer tous les fonds
curl http://votresite.com/api/mutual-funds

# Récupérer les catégories
curl http://votresite.com/api/mutual-funds/categories/list

# Récupérer une catégorie
curl http://votresite.com/api/mutual-funds/category/Actions

# Récupérer un fonds spécifique
curl http://votresite.com/api/mutual-funds/SOGEF001
```

## Fonctionnalités principales

### ✅ Ce qui fonctionne d'emblée

- 📊 Affichage des fonds en temps réel
- 🎯 Filtrage par catégorie
- 🔄 Actualisation manuelle
- 💾 Cache automatique (1 heure)
- 📱 Interface responsive
- ⏰ Horodatage des mises à jour
- 🎨 Indicateurs de variation (haut/bas)
- 🔗 API JSON accessible

### 🔧 Futures améliorations

- [ ] Graphiques de performance
- [ ] Export CSV/PDF
- [ ] Historique des variations
- [ ] Alertes de prix
- [ ] Portefeuille utilisateur
- [ ] Comparaison de fonds
- [ ] Dashboard admin

## Personnalisations rapides

### Changer la durée du cache

```env
# .env
MUTUAL_FUNDS_CACHE_DURATION=1800  # 30 minutes au lieu de 1 heure
```

### Ajouter plus de fonds

Modifier `getDefaultMutualFunds()` dans `MutualFundsApiService.php`:

```php
[
    'id' => 'NEW001',
    'name' => 'Mon Nouveau Fonds',
    'company' => 'Ma Société de Gestion',
    'nav_value' => $this->formatCurrency(10000),
    'nav_numeric' => 10000,
    'variation' => $this->formatVariation(100, 1.01),
    'variation_percentage' => 1.01,
    'currency' => 'FCFA',
    'date' => now()->format('Y-m-d'),
    'category' => 'Actions',
],
```

### Changer la devise

Les données utilisent FCFA par défaut. Pour changer:

```php
// Dans MutualFundsApiService
'currency' => 'EUR',  // ou USD, GBP, etc.
'nav_value' => $this->formatCurrency(10000, 'EUR'),
```

### Ajouter une nouvelle catégorie

```php
// Dans getDefaultMutualFunds()
'category' => 'Immobilier',  // ou 'Crypto', 'Commodités', etc.
```

## Intégration avec vos systèmes

### Dans une vue Blade

```blade
<livewire:pages.vl-fcp />
```

### Dans un composant Livewire

```php
use App\Services\MutualFundsApiService;

public function mount(MutualFundsApiService $service)
{
    $this->funds = $service->getMutualFunds();
    $this->categories = $service->getCategories();
}
```

### Dans une API externe

```javascript
// Récupérer les données via JavaScript
fetch('/api/mutual-funds')
    .then(res => res.json())
    .then(data => console.log(data.data));
```

## Dépannage rapide

### "Pas de données affichées"

```bash
# 1. Vérifier le cache
php artisan tinker
>>> Cache::flush()

# 2. Vérifier l'application
php artisan optimize
```

### "Erreur de timing"

```env
# Augmenter le timeout
MUTUAL_FUNDS_TIMEOUT=30
```

### "Page très lente"

```env
# Augmenter le cache
MUTUAL_FUNDS_CACHE_DURATION=7200
```

## Architecture en un coup d'œil

```
┌─────────────────────────────────────┐
│        Interface Utilisateur         │
│   (resources/views/vl-fcp.blade.php)│
└────────────────┬────────────────────┘
                 │
                 ↓
┌─────────────────────────────────────┐
│      Composant Livewire (VlFcp)     │
│   (app/Livewire/Pages/VlFcp.php)    │
└────────────────┬────────────────────┘
                 │
                 ↓
┌─────────────────────────────────────┐
│    Service API (MutualFundsService) │
│  (app/Services/MutualFundsApiService)│
└────────────────┬────────────────────┘
                 │
        ┌────────┴────────┐
        ↓                 ↓
   ┌─────────┐      ┌──────────┐
   │  Cache  │      │  APIs    │
   │ (1 heure)│      │ Externes │
   └─────────┘      └──────────┘
```

## Points clés à retenir

1. **Pas de base de données**: Les données ne sont pas stockées, toujours dynamiques
2. **Cache automatique**: 1 heure par défaut, configurable
3. **API gratuite**: Utilise Morningstar + fallback local
4. **Aucune clé API requise**: Fonctionne out-of-the-box
5. **Responsive**: Fonctionne sur desktop et mobile
6. **Testable**: Tests unitaires inclus

## Documentation complète

- 📖 `MUTUAL_FUNDS_API.md` - Documentation détaillée du service
- 🔗 `API_MUTUAL_FUNDS_ENDPOINTS.md` - Documentation des endpoints API
- ⚙️ `SETUP_MUTUAL_FUNDS.md` - Guide de configuration production

## Besoin d'aide ?

```bash
# Voir toutes les options
php artisan list mutual-funds

# Consulter les logs
tail storage/logs/laravel.log

# Exécuter les tests
php artisan test
```

## Prochaines étapes

1. ✅ Vérifier que la page fonctionne
2. ✅ Tester les filtres par catégorie
3. ✅ Tester les endpoints API
4. ✅ Personnaliser les fonds/catégories selon vos besoins
5. ✅ Configurer le cache pour la production
6. ✅ Ajouter des données depuis vos propres sources

Bon développement! 🚀
