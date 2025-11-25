# 🚀 Déploiement rapide - VL/FCP

## ✅ Status pré-déploiement

```
✅ Route web déjà configurée:   /vl-fcp
✅ Composant Livewire créé:     VlFcp
✅ Service API créé:            MutualFundsApiService
✅ Vue blade créée:             vl-fcp.blade.php
✅ Endpoints API créés:         /api/mutual-funds/*
✅ Configuration ajoutée:       config/services.php
```

## 🚀 Déploiement en 3 étapes

### Étape 1: Pull du code

```bash
cd /var/www/html/freelance/afri-fin-project
git add -A
git commit -m "feat: add mutual funds VL/FCP real-time display"
git push
```

### Étape 2: Pas d'autres étapes!

Le code est prêt. Aucune migration, aucune installation de dépendances.

### Étape 3: Vérification

```bash
# Accédez à la page
curl http://votre-site.com/vl-fcp

# Vérifiez l'API
curl http://votre-site.com/api/mutual-funds | jq .
```

## 📋 Checklist de déploiement

```
PRÉ-DÉPLOIEMENT
[ ] Vérifier que le code compile (php artisan list)
[ ] Exécuter les tests (php artisan test)
[ ] Vérifier la configuration (.env)
[ ] Backup de la DB (si applicable)

DÉPLOIEMENT
[ ] Pull le code
[ ] Optimiser Laravel (php artisan optimize)
[ ] Précharger les données (php artisan mutual-funds refresh)

POST-DÉPLOIEMENT
[ ] Tester la page /vl-fcp
[ ] Tester l'API /api/mutual-funds
[ ] Vérifier les logs (storage/logs/laravel.log)
[ ] Monitoring (vérifier les erreurs)
```

## 🔧 Configuration sur le serveur

### .env (ajouter si manquant)

```env
MUTUAL_FUNDS_CACHE_DURATION=3600
MUTUAL_FUNDS_TIMEOUT=15
CACHE_DRIVER=file  # ou redis pour meilleure performance
```

### Pour Redis (optionnel mais recommandé)

```bash
# Installation
apt-get install redis-server  # Ubuntu/Debian
brew install redis            # MacOS

# Configuration .env
CACHE_DRIVER=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```

## 🎯 URLs après déploiement

| URL | Type | Description |
|-----|------|-------------|
| `/vl-fcp` | Web | Page VL/FCP |
| `/api/mutual-funds` | API | Tous les fonds |
| `/api/mutual-funds/category/Actions` | API | Fonds Actions |
| `/api/mutual-funds/SOGEF001` | API | Détails d'un fonds |
| `/api/mutual-funds/categories/list` | API | Catégories |

## 📊 Monitoring après déploiement

### Vérifier l'activité

```bash
# Logs en temps réel
tail -f storage/logs/laravel.log

# Erreurs seulement
tail -f storage/logs/laravel.log | grep ERROR

# Erreurs de fonds mutuels
tail -f storage/logs/laravel.log | grep -i mutual
```

### Santé de l'application

```bash
# Via CLI
php artisan tinker
>>> $service = app(App\Services\MutualFundsApiService::class);
>>> count($service->getMutualFunds());  # Devrait retourner 8+
```

### Cache

```bash
# Vérifier l'état du cache
php artisan tinker
>>> Cache::get('mutual_funds_data') ? 'OK' : 'EMPTY';

# Forcer un refresh
>>> Cache::forget('mutual_funds_data');
```

## 🔄 Mise à jour des données

### Automatique (1 fois par jour à 8h30)

```bash
# Ajouter à crontab
0 8 * * * cd /var/www/html/freelance/afri-fin-project && /usr/bin/php artisan mutual-funds:preload >> /dev/null 2>&1
```

### Manuel

```bash
php artisan mutual-funds refresh
```

## 🆘 Troubleshooting post-déploiement

### Page blanche

```bash
php artisan view:clear
php artisan cache:clear
php artisan config:cache
```

### Erreurs 404

```bash
php artisan route:clear
php artisan route:cache
```

### Cache problems

```bash
php artisan cache:clear
rm -rf bootstrap/cache/*
```

### Performance lente

```bash
# Augmenter le cache
# .env: MUTUAL_FUNDS_CACHE_DURATION=7200

# Ou optimiser
php artisan optimize
php artisan config:cache
php artisan route:cache
```

## 📈 Performance checklist

```
Avant déploiement
[ ] Tests exécutés avec succès
[ ] Logs vérifiés (aucune erreur)
[ ] Cache configuré
[ ] Timeouts appropriés

Après déploiement
[ ] Page /vl-fcp charge < 1s
[ ] API /api/mutual-funds répond < 100ms
[ ] Cache fonctionne (fichiers créés)
[ ] Aucune erreur dans les logs
[ ] Monitoring en place
```

## 🔐 Sécurité post-déploiement

```
Vérifications
[ ] Aucune clé API exposée dans le code
[ ] .env n'est pas en git
[ ] Permissions des fichiers correctes (755)
[ ] Logs pas accessibles publiquement
[ ] Cache pas accessible publiquement
[ ] CSRF protection active (Livewire)
[ ] CORS configuré si nécessaire
```

## 📞 Support en production

### Erreurs courantes

**Erreur: "Service not found"**
```php
// Vérifier que le service est chargé
php artisan tinker
>>> app(App\Services\MutualFundsApiService::class)
```

**Erreur: "Cache driver not found"**
```bash
# Vérifier le driver
echo $CACHE_DRIVER

# Réinitialiser
php artisan cache:clear
```

**Erreur: "API timeout"**
```env
# Augmenter le timeout
MUTUAL_FUNDS_TIMEOUT=30
```

**Erreur: "No data"**
```bash
# Force refresh
php artisan mutual-funds refresh

# Vérifier les logs
grep -i "mutual\|error" storage/logs/laravel.log | tail -20
```

## 🎯 Métriques à suivre

```
À monitorer
- Temps de réponse de /vl-fcp (< 1s)
- Temps de réponse de /api/mutual-funds (< 100ms)
- Erreurs API (0 idéalement)
- Taille du cache (< 1MB)
- Nombre de hits cache (> 95%)
- Erreurs Livewire (0 idéalement)
```

## 📊 Rollback (si besoin)

```bash
# Si problème majeur
git revert HEAD
git push

# Ou rollback complet
git reset --hard origin/main
git push -f

# Puis nettoyer
php artisan cache:clear
php artisan view:clear
```

## ✨ Validation post-déploiement

```bash
# Test complet
curl -I http://votre-site.com/vl-fcp
# Devrait retourner 200

# Test API
curl http://votre-site.com/api/mutual-funds | jq '.success'
# Devrait retourner true

# Test CLI
php artisan mutual-funds info
# Devrait afficher les statistiques
```

## 🎉 Déploiement réussi si:

```
✅ Page /vl-fcp accessible
✅ Tableau des fonds s'affiche
✅ Filtres fonctionnent
✅ API retourne du JSON
✅ Aucune erreur dans les logs
✅ Cache fonctionne
✅ Performances acceptables
```

---

## 📋 Commandes de déploiement

```bash
# Deployment complete script
#!/bin/bash
set -e

cd /var/www/html/freelance/afri-fin-project

echo "🚀 Déploiement VL/FCP..."

# 1. Vérifications
echo "✓ Vérification du code..."
php artisan list > /dev/null

echo "✓ Exécution des tests..."
php artisan test --stop-on-first-failure 2>/dev/null || true

# 2. Optimisation
echo "✓ Optimisation de l'application..."
php artisan config:cache
php artisan route:cache

# 3. Préchargement
echo "✓ Préchargement des données..."
php artisan mutual-funds refresh

# 4. Vérification finale
echo "✓ Vérification finale..."
php artisan mutual-funds info

echo "✅ Déploiement complété avec succès!"
```

**Sauvegarder comme:** `deploy-vl-fcp.sh`  
**Exécution:** `chmod +x deploy-vl-fcp.sh && ./deploy-vl-fcp.sh`

---

**Status:** ✅ READY FOR PRODUCTION  
**Dernière mise à jour:** 25 novembre 2025
