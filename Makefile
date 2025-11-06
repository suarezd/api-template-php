.PHONY: build up down prepare sh logs test_api cache_clear 

# Ajoute la commande pour ajouter le répertoire comme "safe" pour Git
install:
	@echo "🛠️ Installation des dépendances..."
	@docker compose exec -T php bash -c "git config --global --add safe.directory /var/www/html && cd src/Infrastructure/Symfony && composer install"

build:
	@echo "🛠️ Construction des images..."
	@docker compose build --no-cache

up:
	@echo "🚀 Démarrage des conteneurs..."
	@docker compose up -d

down:
	@echo "🛑 Arrêt et nettoyage complet..."
	@docker compose down -v

cache_clear:
	@echo "🧹 Clear cache Symfony (force reload routes)..."
	@docker compose exec -T php bash -c "\
	    cd src/Infrastructure/Symfony && \
	    composer dump-autoload --optimize --classmap-authoritative && \
	    rm -rf var/cache/* && \
	    php bin/console cache:clear --no-interaction && \
	    php bin/console cache:warmup --no-interaction"

:
	@echo "🔧 Permissions safe pour Ubuntu 24.04 (pas de chown)"
	@docker compose exec -T php bash -c "\
	    cd src\/Infrastructure\/Symfony \&\& \
	    chmod -R 777 var \&\& \
	    find . -type f \! -path \"./var/*\" -exec chmod 664 {} \; \&\& \
	    find . -type d \! -path \"./var/*\" -exec chmod 775 {} \;"

prepare: build up install cache_clear 
	@echo ""
	@echo "🎉 PROJET 100% PRÊT – git clone + make prepare = tout fonctionne"
	@echo "Test : curl -i http://localhost/api/hello"
	@echo ""

sh:
	@docker compose exec php bash

logs:
	@docker compose logs -f caddy php

test_api:
	@echo "🧪 Test API..."
	@sleep 3
	@curl -f -s http://localhost/api/hello > /dev/null && \
	    echo "✅ 200 OK – Tout fonctionne !" || \
	    (echo "❌ Échec – voir make logs" && exit 1)

debug:
	@echo "🛠️ Test de l'autoload et du namespace Kernel..."
	@docker compose exec -T php bash -c "\
	    cd src/Infrastructure/Symfony && \
	    php -r 'var_dump(class_exists(\"App\\Infrastructure\\Symfony\\Kernel\"));'"

debug_autoload:
	@echo "🛠️ Vérification des fichiers autoloadés..."
	@docker compose exec -T php bash -c "\
	    cd src/Infrastructure/Symfony && \
	    php -r 'var_dump(get_included_files());'"
