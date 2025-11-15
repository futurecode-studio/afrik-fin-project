# Table Transactions - Documentation

## Vue d'ensemble

La table `transactions` stocke toutes les transactions de paiement effectuées sur la plateforme via différents agrégateurs de paiement (KKiaPay, FedaPay, etc.).

## Structure de la table

### Champs principaux

| Champ | Type | Nullable | Description |
|-------|------|----------|-------------|
| `id` | bigint(20) unsigned | Non | ID auto-incrémenté |
| `agregateur` | varchar(255) | Non | Provider de paiement (kkiapay, fedapay, etc.) |
| `external_transaction_id` | varchar(255) | Oui | ID de la transaction chez le provider |
| `status` | varchar(255) | Non | Statut de la transaction |
| `amount` | decimal(10,2) | Non | Montant de la transaction |
| `currency` | varchar(3) | Non | Devise (XOF par défaut) |
| `description` | text | Oui | Description de la transaction |
| `mode` | varchar(255) | Oui | Mode sandbox ou live |

### Informations utilisateur

| Champ | Type | Nullable | Description |
|-------|------|----------|-------------|
| `fullname` | varchar(255) | Oui | Nom complet |
| `phone` | varchar(255) | Oui | Téléphone |
| `email` | varchar(255) | Oui | Email |
| `account` | varchar(255) | Oui | Compte |
| `person` | text | Oui | Données complètes JSON |

### Détails de paiement

| Champ | Type | Nullable | Description |
|-------|------|----------|-------------|
| `type_paiement` | varchar(255) | Oui | Type de paiement KKiaPay |
| `type` | varchar(255) | Oui | Type de transaction |
| `source` | varchar(255) | Oui | Source du paiement |
| `source_common_name` | varchar(255) | Oui | Nom commun de la source |
| `country` | varchar(255) | Oui | Pays de la transaction |
| `paymentlink` | text | Oui | Lien de paiement |

### Finances

| Champ | Type | Nullable | Description |
|-------|------|----------|-------------|
| `fees` | decimal(10,2) | Oui | Frais de la transaction |
| `isFeesBorneByMerchant` | tinyint(1) | Non | Frais supportés par le marchand |
| `net` | decimal(10,2) | Oui | Montant net après frais |
| `before_balance` | decimal(10,2) | Oui | Solde avant transaction |
| `after_balance` | decimal(10,2) | Oui | Solde après transaction |

### État et comptabilité

| Champ | Type | Nullable | Description |
|-------|------|----------|-------------|
| `state` | varchar(255) | Oui | État de la transaction |
| `reason` | text | Oui | Raison/motif |
| `is_payout` | tinyint(1) | Non | Est-ce un payout |
| `is_counted` | tinyint(1) | Non | Transaction comptabilisée |
| `wallet` | varchar(255) | Oui | Portefeuille utilisé |

### Dates

| Champ | Type | Nullable | Description |
|-------|------|----------|-------------|
| `performed_at` | timestamp | Oui | Date d'exécution KKiaPay |
| `received_at` | timestamp | Oui | Date de réception KKiaPay |
| `performedAt` | timestamp | Oui | Date d'exécution formatée |
| `created_at` | timestamp | Oui | Date de création |
| `updated_at` | timestamp | Oui | Date de mise à jour |

### Relations

| Champ | Type | Nullable | Description |
|-------|------|----------|-------------|
| `user_id` | bigint(20) unsigned | Oui | ID de l'utilisateur |
| `course_id` | bigint(20) unsigned | Oui | ID du cours/formation |
| `enrollment_id` | bigint(20) unsigned | Oui | ID de l'inscription |

### Données techniques

| Champ | Type | Nullable | Description |
|-------|------|----------|-------------|
| `callback_url` | text | Oui | URL de callback |
| `meta_data` | text | Oui | Métadonnées JSON |
| `raw_response` | text | Oui | Réponse brute du provider |
| `webhook_data` | text | Oui | Données webhook reçues |
| `isNewGeneration` | tinyint(1) | Non | Nouvelle génération KKiaPay |
| `transactionId` | varchar(255) | Oui | ID de transaction KKiaPay |

## Statuts possibles

- `pending` : En attente
- `approved` : Approuvée
- `succeeded` : Réussie
- `canceled` : Annulée
- `declined` : Refusée
- `failed` : Échouée

## Agrégateurs supportés

- `kkiapay` : KKiaPay
- `fedapay` : FedaPay

## Index

Pour optimiser les performances, les index suivants ont été créés :

- `agregateur`
- `external_transaction_id`
- `status`
- `user_id`
- `course_id`
- `enrollment_id`
- `created_at`

## Utilisation du modèle

### Créer une transaction

```php
use App\Models\Transaction;

$transaction = Transaction::create([
    'agregateur' => 'kkiapay',
    'external_transaction_id' => 'KKP_123456',
    'status' => Transaction::STATUS_PENDING,
    'amount' => 50000,
    'currency' => 'XOF',
    'description' => 'Paiement formation Laravel',
    'fullname' => 'Jean Dupont',
    'phone' => '+22997123456',
    'email' => 'jean@example.com',
    'user_id' => 1,
    'course_id' => 5,
]);
```

### Vérifier le statut

```php
if ($transaction->isSuccessful()) {
    // Transaction réussie
}

if ($transaction->isPending()) {
    // Transaction en attente
}

if ($transaction->isFailed()) {
    // Transaction échouée
}
```

### Utiliser les scopes

```php
// Toutes les transactions réussies
$successful = Transaction::successful()->get();

// Transactions en attente
$pending = Transaction::pending()->get();

// Transactions échouées
$failed = Transaction::failed()->get();

// Par agrégateur
$kkiapay = Transaction::agregateur('kkiapay')->get();

// Par statut
$approved = Transaction::status('approved')->get();
```

### Formater les montants

```php
echo $transaction->formatted_amount;  // 50 000 XOF
echo $transaction->formatted_fees;    // 1 500 XOF
echo $transaction->formatted_net;     // 48 500 XOF
```

### Calculer le net

```php
$net = $transaction->calculateNet();
```

### Relations

```php
// Utilisateur
$user = $transaction->user;

// Formation/Cours
$course = $transaction->course;
```

## Seeder

Pour insérer des données de test :

```bash
php artisan db:seed --class=TransactionSeeder
```

Cela créera 5 transactions de test avec différents statuts.

## Migration

La migration a déjà été exécutée. Pour la relancer :

```bash
php artisan migrate:fresh
# ou
php artisan migrate:rollback
php artisan migrate
```

## Exemples de requêtes

### Statistiques

```php
// Total des transactions réussies
$total = Transaction::successful()->sum('amount');

// Nombre de transactions par agrégateur
$stats = Transaction::selectRaw('agregateur, count(*) as count, sum(amount) as total')
    ->groupBy('agregateur')
    ->get();

// Transactions du jour
$today = Transaction::whereDate('created_at', today())->get();

// Transactions d'un utilisateur
$userTransactions = Transaction::where('user_id', $userId)
    ->orderBy('created_at', 'desc')
    ->get();
```

### Filtres avancés

```php
// Transactions réussies du mois
$monthly = Transaction::successful()
    ->whereMonth('created_at', now()->month)
    ->whereYear('created_at', now()->year)
    ->sum('amount');

// Transactions avec frais supportés par le marchand
$merchantFees = Transaction::where('isFeesBorneByMerchant', true)
    ->sum('fees');
```
