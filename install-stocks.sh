#!/bin/bash

# Script d'installation rapide des données boursières
# Usage: ./install-stocks.sh

echo "📊 Installation des données boursières..."

# Vérifier si on est dans le bon répertoire
if [ ! -f "artisan" ]; then
    echo "❌ Erreur: Ce script doit être exécuté depuis la racine du projet Laravel"
    exit 1
fi

# Exécuter le seeder
echo "Insertion des données de stocks..."
php artisan db:seed --class=StockSeeder --force

# Vérifier le résultat
STOCK_COUNT=$(php artisan tinker --execute="echo \App\Models\Stock::count();")

if [ "$STOCK_COUNT" -gt 0 ]; then
    echo "✅ $STOCK_COUNT actions insérées avec succès !"
    
    # Vider le cache
    echo "Nettoyage du cache..."
    php artisan cache:clear
    
    echo ""
    echo "✅ Installation terminée !"
    echo ""
    echo "📋 Actions disponibles:"
    php artisan tinker --execute="\App\Models\Stock::select('symbol', 'company_name')->get()->each(fn(\$s) => print('  - ' . \$s->symbol . ': ' . \$s->company_name . PHP_EOL));"
    echo ""
    echo "🌐 Visitez https://votre-domaine.com/bourse pour voir les données"
else
    echo "⚠️ Aucune donnée insérée. Vérifiez les logs."
    tail -20 storage/logs/laravel.log
fi
