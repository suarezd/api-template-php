.PHONY: help build up down install prepare test clean logs sh cache-clear restart status

YELLOW=\033[0;33m
GREEN=\033[0;32m
RED=\033[0;31m
NC=\033[0m

help: ## Afficher cette aide
	@echo "$(GREEN)Commandes disponibles:$(NC)"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "  $(YELLOW)%-15s$(NC) %s\n", $$1, $$2}'

build: ## Construire les images Docker
	@echo "$(GREEN)Construction des images Docker...$(NC)"
	@USER_ID=$$(id -u) GROUP_ID=$$(id -g) docker compose build --no-cache

up: ## Demarrer les conteneurs
	@echo "$(GREEN)Demarrage des conteneurs...$(NC)"
	@USER_ID=$$(id -u) GROUP_ID=$$(id -g) docker compose up -d

down: ## Arreter et supprimer les conteneurs
	@echo "$(RED)Arret des conteneurs...$(NC)"
	@docker compose down -v

install: ## Installer les dependances Composer
	@echo "$(GREEN)Installation des dependances...$(NC)"
	@docker compose exec -T php bash -c "\
		cd src/Infrastructure/Symfony && \
		composer install --no-interaction --optimize-autoloader"

cache-clear: ## Vider le cache Symfony
	@echo "$(GREEN)Nettoyage du cache...$(NC)"
	@docker compose exec -T php bash -c "\
		rm -rf var/cache/* var/log/* && \
		cd src/Infrastructure/Symfony && \
		composer dump-autoload --optimize && \
		php bin/console cache:clear --no-interaction"

prepare: down build up install cache-clear ## Installation complete
	@echo ""
	@echo "$(GREEN)PROJET PRET !$(NC)"
	@echo ""
	@echo "$(YELLOW)Test de la route hello:$(NC)"
	@echo "  curl http://localhost/hello"
	@echo ""
	@echo "$(YELLOW)Test d'ajout de tache:$(NC)"
	@echo "  curl -X POST http://localhost/add-task \\"
	@echo "       -H 'Content-Type: application/json' \\"
	@echo "       -d '{\"title\":\"Ma premiere tache\"}'"
	@echo ""

clean: down ## Nettoyage complet (conteneurs + cache + vendor)
	@echo "$(RED)Nettoyage complet...$(NC)"
	@rm -rf src/Infrastructure/Symfony/var/cache/* src/Infrastructure/Symfony/var/log/*
	@rm -rf src/Infrastructure/Symfony/vendor

sh: ## Acceder au shell du conteneur PHP
	@docker compose exec php bash

logs: ## Voir les logs (PHP + Caddy)
	@docker compose logs -f php caddy

test: ## Tester l'API
	@echo "$(YELLOW)Test de l'API...$(NC)"
	@echo ""
	@echo "$(GREEN)Test route /hello:$(NC)"
	@curl -s http://localhost/hello | jq . || echo "$(RED)Echec$(NC)"
	@echo ""
	@echo "$(GREEN)Test route /add-task:$(NC)"
	@curl -s -X POST http://localhost/add-task \
		-H 'Content-Type: application/json' \
		-d '{"title":"Test task"}' | jq . || echo "$(RED)Echec$(NC)"

restart: ## Redemarrer les conteneurs
	@echo "$(YELLOW)Redemarrage...$(NC)"
	@docker compose restart

status: ## Voir l'etat des conteneurs
	@docker compose ps
