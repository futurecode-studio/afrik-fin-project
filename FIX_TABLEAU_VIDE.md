# 🔧 SOLUTION : Tableau vide sur le serveur

## Problème
Après déploiement sur le serveur :
- ✅ Le graphique "Évolution de l'indice BRVM Composite" s'affiche
- ❌ Le tableau "Principales Valeurs" affiche "Aucune donnée boursière disponible"
- ✅ En local, tout fonctionne normalement

## Cause
La table `stocks` est vide sur le serveur car le seeder n'a pas été exécuté.

## Solution Rapide (Sur le serveur)

### Option 1 : Via SSH
```bash
cd /chemin/vers/votre/projet
php artisan db:seed --class=StockSeeder --force
php artisan cache:clear
```

### Option 2 : Script de déploiement automatique
```bash
cd /chemin/vers/votre/projet
./deploy.sh
```

### Option 3 : Commande unique
```bash
php artisan db:seed --class=StockSeeder --force && php artisan cache:clear
```

## Vérification

Après avoir exécuté le seeder, vérifiez que les données sont présentes :

```bash
php artisan tinker
```

Puis dans tinker :
```php
\App\Models\Stock::count()  // Devrait retourner 8
\App\Models\Stock::all()    // Affiche tous les stocks
exit
```

## Données insérées

Le seeder insère 8 actions BRVM :
1. **SNTS** - Sonatel (Télécommunications)
2. **BOAB** - BOA Bénin (Banque)
3. **ORAC** - Orange CI (Télécommunications)
4. **ETIT** - Ecobank TG (Banque)
5. **SIVC** - SIVOM (Industrie)
6. **SPHC** - SAPH Côte d'Ivoire (Agriculture)
7. **SGBC** - Société Générale (Banque)
8. **PALC** - Palm CI (Agriculture)

## Pourquoi le graphique fonctionne mais pas le tableau ?

Le graphique génère des données simulées basées sur les stocks existants, mais peut aussi fonctionner avec une valeur par défaut si la table est vide. Le tableau, lui, nécessite impérativement des données réelles dans la base.

## Prévention pour les futurs déploiements

1. Ajoutez `StockSeeder` dans `DatabaseSeeder.php` ✅ (Déjà fait)
2. Utilisez le script `deploy.sh` qui exécute automatiquement le seeder
3. Ou ajoutez cette ligne dans votre processus de déploiement :
   ```bash
   php artisan db:seed --class=StockSeeder --force
   ```

## API Marketstack (Optionnel)

Pour des données en temps réel, configurez l'API Marketstack dans `.env` :
```env
MARKETSTACK_API_URL=http://api.marketstack.com/v1
MARKETSTACK_API_KEY=127351fb6814d6d0a4f33441c1e081
MARKETSTACK_CACHE_DURATION=300
```

**Note :** Même sans l'API, les données locales de la base suffisent pour afficher le tableau.

## Commandes de diagnostic

Si le problème persiste :

```bash
# Vérifier les migrations
php artisan migrate:status

# Vérifier la table stocks
php artisan tinker
>>> \Schema::hasTable('stocks')  // Devrait retourner true
>>> \App\Models\Stock::count()   // Nombre de stocks

# Vérifier les logs
tail -50 storage/logs/laravel.log

# Vider tous les caches
php artisan optimize:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Support

Si le problème persiste après avoir exécuté le seeder :
1. Vérifiez les logs : `storage/logs/laravel.log`
2. Vérifiez les permissions : `ls -la storage/`
3. Vérifiez la connexion à la base de données dans `.env`
