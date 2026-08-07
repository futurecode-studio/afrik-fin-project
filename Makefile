.PHONY: deploy deploy-code deploy-composer deploy-assets deploy-migrate deploy-optimize

# ==============================================================================
# DEPLOY PROD (AFRICAINE DES FINANCES)
# Serveur SSH : africaine (Hostinger)
# App Laravel : africairedesfinances_core
# Docroot     : public_html (vendor/bootstrap via ../africairedesfinances_core)
# - Aucun for
# - Aucune variable
# - Cibles explicites (lisible par tout le monde)
# ==============================================================================

deploy: deploy-code deploy-composer deploy-assets deploy-migrate deploy-optimize

deploy-code:
	ssh africaine "set -e; \
		echo '=== DEPLOY CODE ==='; \
		cd /home/u213525791/domains/africainedesfinances.com/africairedesfinances_core; \
		git remote set-url origin https://github.com/futurecode-studio/afrik-fin-project.git; \
		git fetch origin main; \
		git checkout main 2>/dev/null || git checkout -b main origin/main; \
		git reset --hard origin/main; \
		echo '=== DONE CODE ==='"

deploy-composer:
	ssh africaine "set -e; \
		echo '=== DEPLOY COMPOSER ==='; \
		cd /home/u213525791/domains/africainedesfinances.com/africairedesfinances_core; \
		composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist; \
		echo '=== DONE COMPOSER ==='"

deploy-assets:
	ssh africaine "set -e; \
		echo '=== DEPLOY ASSETS ==='; \
		export PATH=/opt/alt/alt-nodejs20/root/usr/bin:\$$PATH; \
		export GOMAXPROCS=2; \
		cd /home/u213525791/domains/africainedesfinances.com/africairedesfinances_core; \
		npm ci; \
		npm run build; \
		mkdir -p /home/u213525791/domains/africainedesfinances.com/public_html/build; \
		mkdir -p /home/u213525791/domains/africainedesfinances.com/public_html/assets; \
		rsync -a --delete \
			/home/u213525791/domains/africainedesfinances.com/africairedesfinances_core/public/build/ \
			/home/u213525791/domains/africainedesfinances.com/public_html/build/; \
		rsync -a \
			/home/u213525791/domains/africainedesfinances.com/africairedesfinances_core/public/assets/ \
			/home/u213525791/domains/africainedesfinances.com/public_html/assets/; \
		echo '=== DONE ASSETS ==='"

deploy-migrate:
	ssh africaine "set -e; \
		echo '=== DEPLOY MIGRATE ==='; \
		cd /home/u213525791/domains/africainedesfinances.com/africairedesfinances_core; \
		php artisan migrate --force; \
		php artisan db:seed --class=StockSeeder --force || true; \
		echo '=== DONE MIGRATE ==='"

deploy-optimize:
	ssh africaine "set -e; \
		echo '=== DEPLOY OPTIMIZE ==='; \
		cd /home/u213525791/domains/africainedesfinances.com/africairedesfinances_core; \
		php artisan optimize:clear; \
		php artisan config:cache; \
		php artisan route:cache; \
		php artisan view:cache; \
		chmod -R 775 storage bootstrap/cache; \
		echo '=== DONE OPTIMIZE ==='"
