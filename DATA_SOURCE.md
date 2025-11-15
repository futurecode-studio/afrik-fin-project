# 📊 Source des Données - Page Bourse

## Flux de données mis à jour (après correction)

### 🔄 Nouveau comportement

Le système affiche maintenant **directement les données de l'API Marketstack** lorsqu'elle est configurée.

### Source des données selon la configuration

```
┌─────────────────────────────────────────────────────┐
│  AVEC API CONFIGURÉE                                │
│  ✅ Données affichées = API Marketstack             │
│     - Données en temps réel                         │
│     - Cache de 5 minutes                            │
│     - Backup automatique en base de données         │
└─────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────┐
│  SANS API ou EN CAS D'ERREUR                        │
│  📦 Données affichées = Base de données locale      │
│     - Données du seeder                             │
│     - Ou dernière synchronisation API               │
└─────────────────────────────────────────────────────┘
```

### Processus détaillé

1. **Vérification de la configuration API**
   ```php
   if ($apiConfigured) {
       // Tenter d'utiliser l'API
   } else {
       // Utiliser la base de données
   }
   ```

2. **Si API configurée** :
   - Appel à `http://api.marketstack.com/v1/eod/latest`
   - Si succès : **Affichage direct des données API**
   - Si erreur : Fallback vers la base de données
   - En parallèle : Synchronisation des données en base (backup)

3. **Affichage** :
   - Prix, volumes, variations → **Temps réel depuis l'API**
   - Noms des entreprises, secteurs → **Enrichis depuis la base**

### Indicateurs visuels

#### Mode API activé (vert)
```
✅ Mode API activé : Les données affichées proviennent 
   directement de l'API Marketstack en temps réel (cache: 5 minutes).
```

#### Mode hors ligne (jaune)
```
⚠️ Mode hors ligne : L'API Marketstack n'est pas configurée. 
   Les données affichées proviennent de la base de données locale.
```

### Vérifier la source sur le serveur

```bash
# 1. Vérifier si l'API est configurée
php artisan tinker
>>> config('services.marketstack.api_key')

# 2. Tester la connexion API
>>> $service = app(\App\Services\MarketstackApiService::class);
>>> $service->testConnection();

# 3. Vérifier le cache
>>> \Cache::has('marketstack_stocks')
```

### Avantages de cette approche

✅ **Données en temps réel** : Affichage direct depuis l'API
✅ **Fallback robuste** : Base de données en cas d'erreur
✅ **Performance** : Cache de 5 minutes pour éviter les appels excessifs
✅ **Enrichissement** : Noms et secteurs depuis la base locale
✅ **Backup automatique** : Chaque appel API synchronise la base

### Configuration requise

Dans le fichier `.env` du serveur :
```env
MARKETSTACK_API_URL=http://api.marketstack.com/v1
MARKETSTACK_API_KEY=127351fb6814d6d0a4f33441c1e081
MARKETSTACK_CACHE_DURATION=300
```

### Données affichées

| Champ | Source |
|-------|--------|
| **Prix actuel** | API Marketstack (temps réel) |
| **Prix d'ouverture** | API Marketstack (temps réel) |
| **Volume** | API Marketstack (temps réel) |
| **Plus haut/bas** | API Marketstack (temps réel) |
| **Variation %** | Calculé depuis les prix API |
| **Nom entreprise** | Base de données locale |
| **Secteur** | Base de données locale |
| **Capitalisation** | Base de données locale |

### Cache et actualisation

- **Cache automatique** : 5 minutes (300 secondes)
- **Actualisation manuelle** : Bouton "Actualiser" vide le cache
- **Actualisation automatique** : Après expiration du cache

### En cas de problème

Si les données ne s'affichent pas :
1. Vérifier la clé API dans `.env`
2. Exécuter le seeder : `php artisan db:seed --class=StockSeeder`
3. Vider le cache : `php artisan cache:clear`
4. Vérifier les logs : `tail -f storage/logs/laravel.log`
