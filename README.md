# API Template PHP – Symfony 8.0 RC + PHP 8.4 + Architecture Hexagonale

Template ultra-moderne avec monitoring Prometheus/Grafana.

## 🎯 Stack Technique

- **Framework**: Symfony 8.0 (RC)
- **PHP**: 8.4 avec JIT et OPcache
- **Serveur Web**: Caddy 2.8
- **Architecture**: Hexagonale (Domain / Application / Infrastructure)
- **Cache**: APCu (mémoire partagée)
- **Monitoring**: Prometheus + Grafana
- **Permissions**: Zéro problème sur Linux

## 🚀 Démarrage Rapide

```bash
git clone https://github.com/suarezd/api-template-php.git
cd api-template-php
make prepare
make test
```

Le projet sera accessible sur :
- **API** : http://localhost
- **Grafana** : http://localhost:3000 (admin/admin)
- **Prometheus** : http://localhost:9090

## 📋 Commandes Make Disponibles

### Commandes Principales

```bash
make help          # Afficher toutes les commandes disponibles
make prepare       # Installation complète (build + up + install + cache)
make test          # Tester l'API (routes /hello et /add-task)
```

### Gestion des Conteneurs

```bash
make build         # Construire les images Docker
make up            # Démarrer les conteneurs
make down          # Arrêter et supprimer les conteneurs
make restart       # Redémarrer les conteneurs
make status        # Voir l'état des conteneurs
```

### Développement

```bash
make install       # Installer les dépendances Composer
make cache-clear   # Vider le cache Symfony
make sh            # Accéder au shell du conteneur PHP
make logs          # Voir les logs (PHP + Caddy)
make clean         # Nettoyage complet (down + suppression cache/vendor)
```

## 🏗️ Architecture du Projet

```
src/
├── Domain/                    # Entites metier pures
│   ├── Task.php
│   └── TaskRepository.php    # Interface
├── Application/               # Use Cases
|   ├── Command/
|   │   └── AddTaskUseCase.php
│   └── Query/
|       └── GetTaskskUseCase.php
└── Infrastructure/            # Implementations techniques
    ├── Persistence/
    │   └── InMemoryTaskRepository.php
    └── Symfony/
        ├── Controller/
        │   ├── HelloController.php
        │   ├── TaskController.php
        │   └── MetricsController.php
        ├── Kernel.php
        └── config/
```

## 🔌 Routes Disponibles

| Route | Méthode | Description |
|-------|---------|-------------|
| `/hello` | GET | Health check avec infos système |
| `/add-task` | POST | Créer une tâche |
| `/metrics` | GET | Métriques Prometheus |

### Exemples d'utilisation

```bash
# Health check
curl http://localhost/hello

# Créer une tâche
curl -X POST http://localhost/add-task \
  -H 'Content-Type: application/json' \
  -d '{"title":"Ma première tâche"}'

# Voir les métriques
curl http://localhost/metrics
```

## 📊 Monitoring

### Accès aux Dashboards

- **Grafana** : http://localhost:3000
  - Username: `admin`
  - Password: `admin`
  - Dashboard: "Symfony API Performance"

- **Prometheus** : http://localhost:9090

### Métriques Disponibles

- **OPcache** : Utilisation mémoire, hits/misses, scripts cachés
- **APCu** : Utilisation mémoire, hits/misses, entrées
- **PHP** : Utilisation mémoire courante et peak

Voir [MONITORING.md](MONITORING.md) pour plus de détails.

## ⚡ Optimisations Appliquées

- ✅ **OPcache** avec JIT PHP 8.4 activé
- ✅ **APCu** pour cache applicatif (128 MB)
- ✅ **PHP-FPM** optimisé (pool dynamique, 50 workers max)
- ✅ **Symfony Cache** optimisé (router, validator)
- ✅ **Sessions désactivées** (API sans état)
- ✅ **HTTP Cache activé**

Performances attendues :
- 3000-5000+ requêtes/seconde
- Latence : 10-30ms

## 🧪 Tests de Charge

```bash
# Apache Bench
ab -n 1000 -c 10 http://localhost/hello

# wrk (recommandé)
wrk -t4 -c100 -d30s http://localhost/hello
```

## 📚 Documentation

- [MONITORING.md](MONITORING.md) - Guide complet du monitoring
- [CHANGELOG.md](CHANGELOG.md) - Historique des modifications

## 🛠️ Configuration

### Variables d'environnement

Les variables sont définies dans `docker-compose.yml` :

```yaml
environment:
  - APP_ENV=dev
  - APP_DEBUG=1
  - APP_SECRET=changeme_in_production
```

### Fichiers de configuration

- `docker/php/Dockerfile` - Configuration PHP
- `docker/php/php-fpm.conf` - Configuration PHP-FPM
- `docker/caddy/Caddyfile` - Configuration Caddy
- `src/Infrastructure/Symfony/config/` - Configuration Symfony

## 🔒 Sécurité

- Version PHP masquée (`expose_php = Off`)
- Limites mémoire configurées (256 MB par requête)
- Timeouts configurés (30s max)
- Pas d'inscription utilisateur sur Grafana

## 📝 Commandes Utiles

### Accès au conteneur PHP

```bash
make sh
```

### Vérifier les optimisations

```bash
# OPcache
docker compose exec php php -i | grep opcache

# APCu
docker compose exec php php -i | grep apcu

# Voir les stats en temps réel
curl http://localhost/metrics | grep opcache
```

### Monitoring Docker

```bash
# Utilisation des ressources
docker stats

# Logs spécifiques
docker compose logs -f prometheus
docker compose logs -f grafana
```

## 🚀 Production

Pour déployer en production :

1. Modifier les variables d'environnement :
   ```yaml
   APP_ENV=prod
   APP_DEBUG=0
   APP_SECRET=<générer-un-secret-sécurisé>
   ```

2. Optimiser l'autoloader :
   ```bash
   composer install --no-dev --optimize-autoloader --classmap-authoritative --apcu-autoloader
   ```

3. Configurer HTTPS dans Caddy (le certificat SSL sera automatique)

4. Désactiver `opcache.validate_timestamps` pour performances maximales

## 🤝 Contribution

Les contributions sont les bienvenues ! N'hésitez pas à ouvrir une issue ou une pull request.

## 📄 License

MIT
