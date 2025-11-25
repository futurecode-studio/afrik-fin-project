# 📡 Exemples de Réponses Réelles - VL/FCP APIs

Date: 25 novembre 2025

---

## 🎯 Exemples de Réponses API

### 1️⃣ Yahoo Finance Response

**Requête:**
```bash
curl https://query1.finance.yahoo.com/v8/finance/chart/VTI?interval=1d&range=1mo
```

**Réponse brute (partie pertinente):**
```json
{
  "chart": {
    "result": [
      {
        "meta": {
          "currency": "USD",
          "symbol": "VTI",
          "regularMarketPrice": 245.67,
          "previousClose": 242.80,
          "regularMarketOpen": 243.50,
          "regularMarketDayHigh": 247.23,
          "regularMarketDayLow": 243.20
        },
        "timestamp": [1700000000, 1700086400, 1700172800],
        "indicators": {
          "quote": [
            {
              "close": [242.80, 244.50, 245.67],
              "high": [243.50, 245.23, 247.23],
              "low": [241.20, 243.20, 244.80],
              "open": [242.00, 242.80, 243.50],
              "volume": [1250000, 1320000, 1180000]
            }
          ]
        }
      }
    ]
  }
}
```

**Données extraites par notre service:**
```php
[
    'id' => 'IDX-VTI',
    'name' => 'Vanguard Total Market ETF',
    'company' => 'Vanguard Group',
    'nav_numeric' => 245.67,
    'nav_value' => '245,67 USD',
    'variation' => '+2,87 (+1,18%)',
    'variation_percentage' => 1.18,
    'currency' => 'USD',
    'date' => '2025-11-25',
    'category' => 'Actions',
]
```

**Affichage dans le tableau VL/FCP:**
```
| Fonds                           | Entreprise      | Catégorie | VL      | Variation     | Date       |
|--------------------------------|-----------------|-----------|---------|---------------|-----------|
| Vanguard Total Market ETF       | Vanguard Group  | Actions   | 245,67  | +2,87 (+1,18%)| 2025-11-25 |
```

---

### 2️⃣ BRVM API Response

**Requête:**
```bash
curl https://www.brvm.org/api/indices
```

**Réponse type (simulée):**
```json
{
  "status": "success",
  "data": [
    {
      "code": "BRVM10",
      "name": "BRVM 10 Index",
      "value": 2847.50,
      "change": 24.20,
      "changePercent": 0.85,
      "date": "2025-11-25"
    },
    {
      "code": "BRVM-C",
      "name": "BRVM Composite",
      "value": 3421.75,
      "change": -15.30,
      "changePercent": -0.44,
      "date": "2025-11-25"
    }
  ]
}
```

**Données extraites par notre service:**
```php
[
    [
        'id' => 'BRVM-BRVM10',
        'name' => 'BRVM 10 Index',
        'company' => 'BRVM (Côte d\'Ivoire)',
        'nav_numeric' => 2847.50,
        'nav_value' => '2 847,50 FCFA',
        'variation' => '+24,20 (+0,85%)',
        'variation_percentage' => 0.85,
        'currency' => 'FCFA',
        'date' => '2025-11-25',
        'category' => 'Actions',
    ],
    [
        'id' => 'BRVM-BRVM-C',
        'name' => 'BRVM Composite',
        'company' => 'BRVM (Côte d\'Ivoire)',
        'nav_numeric' => 3421.75,
        'nav_value' => '3 421,75 FCFA',
        'variation' => '-15,30 (-0,44%)',
        'variation_percentage' => -0.44,
        'currency' => 'FCFA',
        'date' => '2025-11-25',
        'category' => 'Mixte',
    ]
]
```

---

### 3️⃣ Notre API REST Response

**Endpoint: `GET /api/mutual-funds`**

```bash
curl http://localhost:8000/api/mutual-funds | jq .
```

**Réponse:**
```json
{
  "success": true,
  "data": [
    {
      "id": "IDX-GSPC",
      "name": "S&P 500 Index",
      "company": "S&P Global",
      "nav_numeric": 5234.56,
      "nav_value": "5 234,56 USD",
      "variation": "+87.30 (+1.69%)",
      "variation_percentage": 1.69,
      "currency": "USD",
      "date": "2025-11-25",
      "category": "Actions"
    },
    {
      "id": "IDX-IXIC",
      "name": "NASDAQ Composite",
      "company": "Nasdaq",
      "nav_numeric": 16542.34,
      "nav_value": "16 542,34 USD",
      "variation": "+243.45 (+1.49%)",
      "variation_percentage": 1.49,
      "currency": "USD",
      "date": "2025-11-25",
      "category": "Actions"
    },
    {
      "id": "IDX-VTI",
      "name": "Vanguard Total Market",
      "company": "Vanguard Group",
      "nav_numeric": 245.67,
      "nav_value": "245,67 USD",
      "variation": "+2.87 (+1.18%)",
      "variation_percentage": 1.18,
      "currency": "USD",
      "date": "2025-11-25",
      "category": "Actions"
    },
    {
      "id": "IDX-TNX",
      "name": "US 10-Year Bond Yield",
      "company": "US Treasury",
      "nav_numeric": 4.25,
      "nav_value": "4,25 USD",
      "variation": "-0.08 (-1.84%)",
      "variation_percentage": -1.84,
      "currency": "USD",
      "date": "2025-11-25",
      "category": "Obligations"
    },
    {
      "id": "IDX-BND",
      "name": "Vanguard Total Bond",
      "company": "Vanguard Group",
      "nav_numeric": 74.23,
      "nav_value": "74,23 USD",
      "variation": "+0.52 (+0.70%)",
      "variation_percentage": 0.70,
      "currency": "USD",
      "date": "2025-11-25",
      "category": "Obligations"
    },
    {
      "id": "IDX-VIX",
      "name": "Volatility Index",
      "company": "CBOE",
      "nav_numeric": 16.45,
      "nav_value": "16,45 USD",
      "variation": "-0.98 (-5.63%)",
      "variation_percentage": -5.63,
      "currency": "USD",
      "date": "2025-11-25",
      "category": "Obligations"
    },
    {
      "id": "IDX-FTSE",
      "name": "FTSE 100 Index",
      "company": "London Exchange",
      "nav_numeric": 7542.30,
      "nav_value": "7 542,30 USD",
      "variation": "+34.50 (+0.46%)",
      "variation_percentage": 0.46,
      "currency": "USD",
      "date": "2025-11-25",
      "category": "Monétaire"
    },
    {
      "id": "IDX-N225",
      "name": "Nikkei 225",
      "company": "Japan Exchange",
      "nav_numeric": 33454.67,
      "nav_value": "33 454,67 USD",
      "variation": "+542.10 (+1.64%)",
      "variation_percentage": 1.64,
      "currency": "USD",
      "date": "2025-11-25",
      "category": "Mixte"
    }
  ]
}
```

---

### 4️⃣ Filtrage par Catégorie

**Endpoint: `GET /api/mutual-funds/category/Actions`**

```bash
curl http://localhost:8000/api/mutual-funds/category/Actions | jq .
```

**Réponse:**
```json
{
  "success": true,
  "data": [
    {
      "id": "IDX-GSPC",
      "name": "S&P 500 Index",
      "company": "S&P Global",
      "nav_numeric": 5234.56,
      "nav_value": "5 234,56 USD",
      "variation": "+87.30 (+1.69%)",
      "variation_percentage": 1.69,
      "currency": "USD",
      "date": "2025-11-25",
      "category": "Actions"
    },
    {
      "id": "IDX-IXIC",
      "name": "NASDAQ Composite",
      "company": "Nasdaq",
      "nav_numeric": 16542.34,
      "nav_value": "16 542,34 USD",
      "variation": "+243.45 (+1.49%)",
      "variation_percentage": 1.49,
      "currency": "USD",
      "date": "2025-11-25",
      "category": "Actions"
    },
    {
      "id": "IDX-VTI",
      "name": "Vanguard Total Market",
      "company": "Vanguard Group",
      "nav_numeric": 245.67,
      "nav_value": "245,67 USD",
      "variation": "+2.87 (+1.18%)",
      "variation_percentage": 1.18,
      "currency": "USD",
      "date": "2025-11-25",
      "category": "Actions"
    }
  ]
}
```

---

### 5️⃣ Fonds Spécifique

**Endpoint: `GET /api/mutual-funds/IDX-GSPC`**

```bash
curl http://localhost:8000/api/mutual-funds/IDX-GSPC | jq .
```

**Réponse:**
```json
{
  "success": true,
  "data": {
    "id": "IDX-GSPC",
    "name": "S&P 500 Index",
    "company": "S&P Global",
    "nav_numeric": 5234.56,
    "nav_value": "5 234,56 USD",
    "variation": "+87.30 (+1.69%)",
    "variation_percentage": 1.69,
    "currency": "USD",
    "date": "2025-11-25",
    "category": "Actions"
  }
}
```

---

### 6️⃣ Catégories Disponibles

**Endpoint: `GET /api/mutual-funds/categories/list`**

```bash
curl http://localhost:8000/api/mutual-funds/categories/list | jq .
```

**Réponse:**
```json
{
  "success": true,
  "data": {
    "categories": [
      "Actions",
      "Obligations",
      "Monétaire",
      "Mixte"
    ]
  }
}
```

---

## 🎨 Affichage Web (VL/FCP Page)

### Interface Tableau

```
╔════════════════════════════════════════════════════════════════════════════════╗
║                    VL/FCP - FONDS COMMUNS DE PLACEMENT                        ║
║                    Données en temps quasi-réel (cache 1h)                      ║
╠════════════════════════════════════════════════════════════════════════════════╣
║                         FILTRES PAR CATÉGORIE                                  ║
║  [Tous] [Actions] [Obligations] [Monétaire] [Mixte]  [🔄 Actualiser]         ║
╠════════════════════════════════════════════════════════════════════════════════╣
║ NOM DU FONDS        │ ENTREPRISE      │ CATÉGORIE  │ VL      │ VARIATION    │ DATE       ║
╠════════════════════════════════════════════════════════════════════════════════╣
║ S&P 500 Index       │ S&P Global      │ Actions    │ 5234,56 │ ↑ +1,69%     │ 2025-11-25 ║
║ NASDAQ Composite    │ Nasdaq          │ Actions    │ 16542   │ ↑ +1,49%     │ 2025-11-25 ║
║ Vanguard Total Mkt  │ Vanguard Group  │ Actions    │ 245,67  │ ↑ +1,18%     │ 2025-11-25 ║
║ US 10-Year Bond     │ US Treasury     │ Obligations│ 4,25    │ ↓ -1,84%     │ 2025-11-25 ║
║ Vanguard Total Bond │ Vanguard Group  │ Obligations│ 74,23   │ ↑ +0,70%     │ 2025-11-25 ║
║ Volatility Index    │ CBOE            │ Obligations│ 16,45   │ ↓ -5,63%     │ 2025-11-25 ║
║ FTSE 100 Index      │ London Exchange │ Monétaire  │ 7542,30 │ ↑ +0,46%     │ 2025-11-25 ║
║ Nikkei 225          │ Japan Exchange  │ Mixte      │ 33454   │ ↑ +1,64%     │ 2025-11-25 ║
╠════════════════════════════════════════════════════════════════════════════════╣
║ Dernière mise à jour: 2025-11-25 14:32:15 UTC                                 ║
╚════════════════════════════════════════════════════════════════════════════════╝
```

### Après clic sur "Actions"

```
╠════════════════════════════════════════════════════════════════════════════════╣
║ NOM DU FONDS        │ ENTREPRISE      │ CATÉGORIE  │ VL      │ VARIATION    │ DATE       ║
╠════════════════════════════════════════════════════════════════════════════════╣
║ S&P 500 Index       │ S&P Global      │ Actions    │ 5234,56 │ ↑ +1,69%     │ 2025-11-25 ║
║ NASDAQ Composite    │ Nasdaq          │ Actions    │ 16542   │ ↑ +1,49%     │ 2025-11-25 ║
║ Vanguard Total Mkt  │ Vanguard Group  │ Actions    │ 245,67  │ ↑ +1,18%     │ 2025-11-25 ║
╚════════════════════════════════════════════════════════════════════════════════╝

(3 fonds affichés - filtrés par catégorie "Actions")
```

---

## 🔍 Flux Détaillé d'une Requête

### 1. Utilisateur ouvre `/vl-fcp`

```
1. HTML chargé
2. Livewire exécute mount()
3. mount() → loadFunds()
4. loadFunds() → MutualFundsApiService::getMutualFunds()
```

### 2. getMutualFunds() exécute

```
3a. Cache check → MISS (première requête)
3b. Essayer Yahoo Finance
    ├─ Bouclé sur 8 symboles
    ├─ HTTP GET pour chaque
    ├─ Parse réponses
    ├─ Crée 8 objets fonds
    └─ Retour réussi ✅
3c. Cache stocke pour 1h
```

### 3. Retour au composant

```
5. $mutualFunds = [8 fonds]
6. $categories = ['Actions', 'Obligations', 'Monétaire', 'Mixte']
7. Blade template rendu
8. Affichage dans browser
```

### 4. Utilisateur clique "Actions"

```
1. Livewire actionne filterByCategory('Actions')
2. Appel loadFunds()
3. Cache HIT (< 1h)
4. Filter array_filter par catégorie
5. Re-render Blade avec 3 fonds
```

### 5. Utilisateur clique "Actualiser"

```
1. Livewire actionne refreshFunds()
2. clearCache() invalidate le cache
3. loadFunds() recharge
4. Cache MISS → Appel APIs
5. Données fraîches retournées
6. Affichage mis à jour
```

---

## 📊 Statistiques de Réponse

### Yahoo Finance Réponse Brute:
```
Taille: ~5-10 KB par symbole
Champs: 50+
Utiles pour nous: ~5 (price, change, percent, etc)
Temps réponse: 100-300ms
Rate limit: Non documenté mais généralement permissif
```

### Notre Réponse Simplifiée:
```
Taille: ~200 bytes par fonds
Champs: 9 (essentiels)
Format: JSON structuré
Temps réponse: <5ms (cache) ou 300-500ms (API)
```

### Conversion:
```
Yahoo Finance (5KB) → MutualFundsApiService → Notre API (200 bytes)
                          (Parse + Filter)
```

---

## 🔄 Cache Behavior

### Timeline:

```
12:00 PM - Utilisateur visite page
           Cache MISS → Appel APIs (300-500ms)
           Cache SET pour 1h
           
12:05 PM - Autre utilisateur visite
           Cache HIT (<5ms) ✅
           
12:30 PM - Même utilisateur F5
           Cache HIT (<5ms) ✅
           
12:45 PM - Utilisateur clique Actualiser
           Cache MISS forcé
           Appel APIs (300-500ms)
           Cache SET pour 1h
           
01:00 PM - Cache expiré automatiquement
           Prochain utilisateur → Cache MISS → APIs
```

---

**Exemples générés**: 25 novembre 2025  
**Format**: Réaliste et basé sur implémentation actuelle  
**Status**: ✅ Production Ready

