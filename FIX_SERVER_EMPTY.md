# 🚨 SOLUTION RAPIDE - Tableau vide sur le serveur

## Problème observé
- ✅ Graphique visible
- ❌ Tableau affiche "Aucune donnée boursière disponible"
- ❌ Indices BRVM affichent "0.00"

## Cause
La table `stocks` est vide dans la base de données du serveur.

## Solution IMMÉDIATE (2 commandes)

### Sur le serveur, dans le dossier du projet :

```bash
# 1. Insérer les données
php artisan db:seed --class=StockSeeder --force

# 2. Vider le cache
php artisan cache:clear
```

**OU utilisez le script automatique :**

```bash
./install-stocks.sh
```

## Vérification

Après avoir exécuté ces commandes :

```bash
# Compter les stocks
php artisan tinker --execute="echo \App\Models\Stock::count();"
# Devrait afficher: 8
```

## Ce qui sera inséré

Le seeder va créer 8 actions BRVM :

| Symbole | Entreprise | Prix | Secteur |
|---------|-----------|------|---------|
| SNTS | Sonatel | 16,500 | Télécommunications |
| BOAB | BOA Bénin | 5,200 | Banque |
| ORAC | Orange CI | 9,800 | Télécommunications |
| ETIT | Ecobank TG | 25 | Banque |
| SIVC | SIVOM | 3,800 | Industrie |
| SPHC | SAPH Côte d'Ivoire | 4,100 | Agriculture |
| SGBC | Société Générale | 12,300 | Banque |
| PALC | Palm CI | 6,500 | Agriculture |

## Après l'insertion

1. **Rafraîchir la page** `/bourse`
2. Le tableau devrait maintenant afficher les 8 actions
3. Les indices BRVM afficheront des valeurs calculées
4. Le graphique continuera de fonctionner normalement

## Si le problème persiste

### Option 1 : Vérifier la migration
```bash
php artisan migrate:status
# La migration "create_stocks_table" doit être "Ran"
```

### Option 2 : Recréer la table (ATTENTION: efface les données)
```bash
php artisan migrate:fresh --seed
# ⚠️ Ceci efface TOUTES les données et recrée toutes les tables
```

### Option 3 : Vérifier les logs
```bash
tail -50 storage/logs/laravel.log
```

## Amélioration apportée

✅ **Maintenant, même si la base est vide**, l'API Marketstack utilisera des symboles par défaut :
- SNTS, BOAB, ORAC, ETIT, SIVC, SPHC, SGBC, PALC

✅ **Les noms d'entreprises seront affichés** même sans la base de données

## Commandes utiles

```bash
# Voir toutes les actions en base
php artisan tinker
>>> \App\Models\Stock::all(['symbol', 'company_name', 'current_price'])

# Supprimer toutes les actions
>>> \App\Models\Stock::truncate()

# Réinsérer les actions
>>> exit
php artisan db:seed --class=StockSeeder --force

# Vider le cache Marketstack
php artisan cache:forget marketstack_stocks
php artisan cache:forget marketstack_indices
```

## Prévention

Pour éviter ce problème lors des futurs déploiements, ajoutez dans votre workflow :

```bash
php artisan migrate --force
php artisan db:seed --class=StockSeeder --force
php artisan cache:clear
```

Ou utilisez simplement :
```bash
./deploy.sh
```

## Contact

Si le problème persiste après avoir suivi ces étapes, vérifiez :
1. Les permissions : `ls -la storage/`
2. La connexion DB : `php artisan tinker --execute="DB::connection()->getPdo();"`
3. Les logs : `tail -100 storage/logs/laravel.log`
