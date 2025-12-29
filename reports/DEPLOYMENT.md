# Guide de Déploiement

## Commandes à exécuter sur le serveur après un `git pull`

### 1. Installer/Mettre à jour les dépendances
```bash
composer install --optimize-autoloader --no-dev
npm install && npm run build
```

### 2. Configuration de l'environnement
Assurez-vous que le fichier `.env` contient les variables Marketstack :
```env
MARKETSTACK_API_URL=http://api.marketstack.com/v1
MARKETSTACK_API_KEY=127351fb6814d6d0a4f33441c1e081
MARKETSTACK_CACHE_DURATION=300
```

### 3. Migrations et Seeders
```bash
# Exécuter les migrations
php artisan migrate --force

# Option 1 : Exécuter tous les seeders (si première installation)
php artisan db:seed --force

# Option 2 : Exécuter uniquement le StockSeeder (si base déjà existante)
php artisan db:seed --class=StockSeeder --force
```

### 4. Optimiser l'application
```bash
# Vider tous les caches
php artisan optimize:clear

# Mettre en cache les configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Créer le lien symbolique pour le storage (si pas déjà fait)
php artisan storage:link
```

### 5. Permissions (si nécessaire)
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## Résolution du problème "Tableau vide sur le serveur"

Si le tableau "Principales Valeurs" est vide après le déploiement :

### Solution 1 : Exécuter le seeder des stocks
```bash
php artisan db:seed --class=StockSeeder --force
```

### Solution 2 : Vérifier que la table existe
```bash
php artisan tinker
>>> \App\Models\Stock::count()
```

Si la réponse est 0, exécutez :
```bash
php artisan db:seed --class=StockSeeder --force
```

### Solution 3 : Tester l'API Marketstack
Dans tinker :
```bash
php artisan tinker
>>> $service = app(\App\Services\MarketstackApiService::class);
>>> $service->testConnection();
```

## Vérification Post-Déploiement

### 1. Vérifier les logs
```bash
tail -f storage/logs/laravel.log
```

### 2. Tester la page Bourse
Visitez : `https://votre-domaine.com/bourse`

### 3. Vérifier les données en base
```bash
php artisan tinker
>>> \App\Models\Stock::count()  // Devrait retourner 8
>>> \App\Models\Stock::first()  // Devrait afficher SNTS (Sonatel)
```

## Commandes Rapides de Déploiement

Script complet pour un déploiement rapide :
```bash
#!/bin/bash
git pull origin master
composer install --optimize-autoloader --no-dev
npm install && npm run build
php artisan migrate --force
php artisan db:seed --class=StockSeeder --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
chmod -R 775 storage bootstrap/cache
```

## Notes Importantes

1. **API Marketstack** : Les données du tableau proviennent soit de l'API Marketstack (si configurée) soit de la base de données locale
2. **Graphique** : Le graphique utilise toujours les données de la base de données locale
3. **Cache** : Les données sont mises en cache pendant 5 minutes (300 secondes)
4. **Fallback** : Si l'API ne répond pas, le système utilise automatiquement les données de la base

## Dépannage

### Le tableau reste vide après le seeder
1. Vérifiez les logs : `tail -f storage/logs/laravel.log`
2. Videz le cache : `php artisan cache:clear`
3. Vérifiez les permissions : `ls -la storage/`

### L'API Marketstack ne fonctionne pas
1. Vérifiez la clé API dans `.env`
2. Testez la connexion avec `php artisan tinker` puis `app(\App\Services\MarketstackApiService::class)->testConnection()`
3. Les données locales servent de fallback automatique

### Le graphique ne s'affiche pas
1. Vérifiez que Chart.js est bien chargé (inspectez le réseau dans les DevTools)
2. Vérifiez les erreurs JavaScript dans la console
3. Assurez-vous que `@stack('scripts')` est présent dans `layouts/site.blade.php`
