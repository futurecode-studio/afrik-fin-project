#!/bin/bash

# Script de déploiement automatique pour Africaine des Finances
# Usage: ./deploy.sh

echo "🚀 Début du déploiement..."

# Couleurs pour les messages
GREEN='\033[0;32m'
BLUE='\033[0;34m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Fonction pour afficher les messages
print_message() {
    echo -e "${BLUE}[$(date +'%H:%M:%S')]${NC} $1"
}

print_success() {
    echo -e "${GREEN}✓${NC} $1"
}

print_error() {
    echo -e "${RED}✗${NC} $1"
}

# 1. Git pull
print_message "Récupération des dernières modifications..."
if git pull origin master; then
    print_success "Code source mis à jour"
else
    print_error "Échec du git pull"
    exit 1
fi

# 2. Composer install
print_message "Installation des dépendances PHP..."
if composer install --optimize-autoloader --no-dev; then
    print_success "Dépendances PHP installées"
else
    print_error "Échec de l'installation des dépendances PHP"
    exit 1
fi

# 3. NPM install et build
print_message "Installation des dépendances Node.js..."
if npm install; then
    print_success "Dépendances Node.js installées"
else
    print_error "Échec de l'installation des dépendances Node.js"
    exit 1
fi

print_message "Build des assets..."
if npm run build; then
    print_success "Assets compilés"
else
    print_error "Échec de la compilation des assets"
    exit 1
fi

# 4. Migrations
print_message "Exécution des migrations..."
if php artisan migrate --force; then
    print_success "Migrations exécutées"
else
    print_error "Échec des migrations"
    exit 1
fi

# 5. Seeders (uniquement Stock)
print_message "Exécution du seeder des stocks..."
if php artisan db:seed --class=StockSeeder --force; then
    print_success "Données de stock insérées"
else
    print_error "Échec du seeder des stocks"
    # Ne pas arrêter le déploiement si le seeder échoue (données peut-être déjà présentes)
fi

# 6. Clear cache
print_message "Nettoyage du cache..."
php artisan optimize:clear
print_success "Cache nettoyé"

# 7. Cache config
print_message "Mise en cache des configurations..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
print_success "Configurations mises en cache"

# 8. Storage link (si pas déjà fait)
print_message "Création du lien symbolique storage..."
php artisan storage:link 2>/dev/null || print_success "Lien storage déjà existant"

# 9. Permissions
print_message "Configuration des permissions..."
chmod -R 775 storage bootstrap/cache
print_success "Permissions configurées"

# Afficher le statut final
echo ""
echo "=========================================="
echo -e "${GREEN}✓ Déploiement terminé avec succès !${NC}"
echo "=========================================="
echo ""
echo "📊 Vérifications recommandées :"
echo "  1. Visitez https://votre-domaine.com/bourse"
echo "  2. Vérifiez que le tableau affiche les données"
echo "  3. Vérifiez que le graphique s'affiche"
echo ""
echo "🔍 En cas de problème :"
echo "  - Logs : tail -f storage/logs/laravel.log"
echo "  - Vérifier les stocks : php artisan tinker -> Stock::count()"
echo ""
