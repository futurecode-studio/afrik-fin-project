.PHONY: deploy deploy-code deploy-composer deploy-assets deploy-migrate deploy-optimize deploy-app

CORE_DIR=/home/u213525791/domains/africainedesfinances.com/africairedesfinances_core
DOCROOT_DIR=/home/u213525791/domains/africainedesfinances.com/public_html
NODE_BIN=/opt/alt/alt-nodejs20/root/usr/bin

# ==============================================================================
# DEPLOY PROD (AFRICAINE DES FINANCES)
# Serveur SSH : africaine (Hostinger)
# App Laravel : africairedesfinances_core
# Docroot     : public_html (vendor/bootstrap via ../africairedesfinances_core)
#
# IMPORTANT — assets Vite :
# - public/build n'est PAS versionné (manifest + hashes générés au deploy)
# - public_html/build est un symlink vers africairedesfinances_core/public/build
# - Toujours lancer deploy-assets après un changement CSS/JS (ou make deploy)
# ==============================================================================

deploy: deploy-code deploy-composer deploy-assets deploy-migrate deploy-optimize

# Code + vues Blade uniquement (pas de rebuild Vite)
deploy-app: deploy-code deploy-composer deploy-migrate deploy-optimize

deploy-code:
	ssh africaine "set -e; \
		echo '=== DEPLOY CODE ==='; \
		cd $(CORE_DIR); \
		git remote set-url origin https://github.com/futurecode-studio/afrik-fin-project.git; \
		git fetch origin main; \
		git checkout main 2>/dev/null || git checkout -b main origin/main; \
		git reset --hard origin/main; \
		cp public/.htaccess $(DOCROOT_DIR)/.htaccess; \
		cp public/adf-navigation-fix-sw.js $(DOCROOT_DIR)/adf-navigation-fix-sw.js; \
		chmod +x scripts/ensure-vite-build.sh 2>/dev/null || true; \
		if [ -f scripts/ensure-vite-build.sh ] && [ -f public/build/manifest.json ]; then \
			./scripts/ensure-vite-build.sh '$(CORE_DIR)' '$(DOCROOT_DIR)'; \
		fi; \
		echo '=== DONE CODE ==='"

deploy-composer:
	ssh africaine "set -e; \
		echo '=== DEPLOY COMPOSER ==='; \
		cd $(CORE_DIR); \
		composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist; \
		echo '=== DONE COMPOSER ==='"

deploy-assets:
	ssh africaine "set -e; \
		echo '=== DEPLOY ASSETS ==='; \
		export PATH=$(NODE_BIN):\$$PATH; \
		export GOMAXPROCS=2; \
		cd $(CORE_DIR); \
		rm -rf public/build; \
		npm ci; \
		npm run build; \
		chmod +x scripts/ensure-vite-build.sh; \
		./scripts/ensure-vite-build.sh '$(CORE_DIR)' '$(DOCROOT_DIR)'; \
		mkdir -p $(DOCROOT_DIR)/assets; \
		rsync -a \
			$(CORE_DIR)/public/assets/ \
			$(DOCROOT_DIR)/assets/; \
		echo '=== DONE ASSETS ==='"

deploy-migrate:
	ssh africaine "set -e; \
		echo '=== DEPLOY MIGRATE ==='; \
		cd $(CORE_DIR); \
		php artisan migrate --force; \
		php artisan market:sync-brvm --caps-only || true; \
		echo '=== DONE MIGRATE ==='"

deploy-optimize:
	ssh africaine "set -e; \
		echo '=== DEPLOY OPTIMIZE ==='; \
		cd $(CORE_DIR); \
		if [ ! -f public/build/manifest.json ]; then \
			echo 'ERROR: public/build/manifest.json missing. Run: make deploy-assets'; \
			exit 1; \
		fi; \
		chmod +x scripts/ensure-vite-build.sh; \
		./scripts/ensure-vite-build.sh '$(CORE_DIR)' '$(DOCROOT_DIR)'; \
		php artisan optimize:clear; \
		php artisan config:cache; \
		php artisan route:cache; \
		php artisan view:cache; \
		chmod -R 775 storage bootstrap/cache; \
		echo '=== DONE OPTIMIZE ==='"
