# Module de Gestion des Données Boursières

## 📊 Description
Module complet de gestion des données boursières de la BRVM (Bourse Régionale des Valeurs Mobilières) avec interface CRUD complète.

## ✨ Fonctionnalités Implémentées

### 1. **Gestion des Titres Boursiers**
- ✅ Ajout de nouveaux titres avec tous les détails
- ✅ Modification des titres existants
- ✅ Suppression avec confirmation
- ✅ Activation/désactivation des titres
- ✅ Recherche en temps réel par symbole ou nom d'entreprise
- ✅ Pagination automatique

### 2. **Champs de Données**
- **Symbole** : Code boursier unique (ex: SNTS, BOAB)
- **Nom de l'entreprise** : Nom complet
- **Prix actuel** : Prix courant en FCFA
- **Prix précédent** : Prix de référence pour calculer la variation
- **Variation** : Calculée automatiquement en pourcentage
- **Volume** : Nombre de transactions
- **Capitalisation boursière** : En millions de FCFA
- **Secteur d'activité** : Télécommunications, Banque, Agriculture, etc.
- **Plus haut/Plus bas** : Prix extrêmes de la journée
- **Statut** : Actif ou Inactif

### 3. **Interface Utilisateur**
- ✅ Tableau responsive avec toutes les colonnes
- ✅ Indicateurs visuels (flèches haut/bas pour variations)
- ✅ Badges de couleur pour le statut
- ✅ Modales élégantes pour ajout/modification
- ✅ Modale de confirmation de suppression
- ✅ Messages flash de succès/erreur
- ✅ Boutons d'action (Modifier/Supprimer)

### 4. **Calculs Automatiques**
- ✅ Calcul automatique de la variation en %
- ✅ Détection automatique des variations positives/négatives
- ✅ Formatage automatique des prix et capitalisations
- ✅ Mise à jour du timestamp lors des modifications

## 🗄️ Base de Données

### Table: `stocks`
```sql
- id (primary key)
- symbol (unique) - Symbole boursier
- company_name - Nom de l'entreprise
- current_price - Prix actuel
- previous_price - Prix précédent
- variation_percent - Variation en %
- volume - Volume de transactions
- market_cap - Capitalisation boursière
- sector - Secteur d'activité
- high_price - Plus haut du jour
- low_price - Plus bas du jour
- is_active - Statut (actif/inactif)
- last_updated - Dernière mise à jour
- created_at / updated_at
```

## 📁 Structure des Fichiers

```
app/
├── Models/
│   └── Stock.php                    # Modèle avec méthodes de calcul
├── Livewire/
│   └── Admin/
│       └── StockData.php            # Composant Livewire complet

database/
├── migrations/
│   └── 2025_11_15_134504_create_stocks_table.php
└── seeders/
    └── StockSeeder.php              # 8 titres BRVM de test

resources/
└── views/
    └── livewire/
        └── admin/
            └── stock-data.blade.php  # Interface complète
```

## 🚀 Utilisation

### Accès au Module
1. Connexion à l'admin : `/admin/dashboard`
2. Cliquer sur la carte "Données Boursières"
3. URL directe : `/admin/stock-data`

### Ajouter un Titre
1. Cliquer sur "Ajouter un titre"
2. Remplir le formulaire (seuls symbole, nom et prix actuel sont obligatoires)
3. Le prix précédent permet de calculer automatiquement la variation
4. Cliquer sur "Ajouter"

### Modifier un Titre
1. Cliquer sur l'icône crayon dans la colonne "Actions"
2. Modifier les champs souhaités
3. Le symbole ne peut plus être modifié après création
4. Cliquer sur "Modifier"

### Supprimer un Titre
1. Cliquer sur l'icône poubelle
2. Confirmer la suppression dans la modale

### Activer/Désactiver
- Cliquer directement sur le badge de statut (Actif/Inactif)

### Rechercher
- Taper dans la barre de recherche
- La recherche se fait en temps réel sur symbole et nom d'entreprise

## 📊 Données de Test

Le seeder inclut 8 titres réels de la BRVM :
1. SNTS - Sonatel (Télécommunications)
2. BOAB - BOA Bénin (Banque)
3. ORAC - Orange CI (Télécommunications)
4. ETIT - Ecobank TG (Banque)
5. SIVC - SIVOM (Distribution)
6. SPHC - SAPH Côte d'Ivoire (Agriculture)
7. SGBC - Société Générale (Banque)
8. PALC - Palm CI (Agriculture)

Pour charger les données de test :
```bash
php artisan db:seed --class=StockSeeder
```

## 🎨 Affichage Public

Les données peuvent être affichées sur la page publique `/bourse` en utilisant le modèle Stock :

```php
$stocks = Stock::where('is_active', true)
    ->orderBy('symbol')
    ->get();
```

## 🔄 Intégration Future

Le module est prêt pour :
- ✅ Intégration d'une API BRVM en temps réel
- ✅ Mise à jour automatique des prix
- ✅ Historique des cours
- ✅ Graphiques d'évolution
- ✅ Alertes de prix

## 📝 Notes Techniques

- **Validation** : Tous les champs ont des règles de validation
- **Sécurité** : Protection CSRF, validation côté serveur
- **Performance** : Pagination automatique (10 titres/page)
- **Responsive** : Interface adaptée mobile/tablette/desktop
- **Accessibilité** : Labels, aria-labels, focus states

## 🎯 Route

```php
Route::get('/stock-data', \App\Livewire\Admin\StockData::class)
    ->name('stock-data');
```

---

**Module développé et testé** ✅
