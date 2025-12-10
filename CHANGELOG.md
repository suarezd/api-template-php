# Changelog - Optimisations des Performances

## [1.0.0] - 10/12/2025

### 🚀 Optimisations Majeures Ajoutées

#### Configuration Symfony
- **framework.yaml** : Configuration optimisée avec cache du routeur, sérialisation php_array, validation en cache, sessions désactivées, HTTP cache activé
- **cache.yaml** : Migration vers APCu pour un cache ultra-rapide en mémoire partagée, ajout de pools de cache spécialisés

#### Infrastructure PHP
- **Dockerfile** : 
  - Installation d'APCu via PECL
  - Configuration OPcache optimisée avec JIT PHP 8.4 activé
  - Configuration APCu (128M de mémoire partagée)
  - Optimisations PHP générales (realpath cache, etc.)
  - Préchargement des classes Symfony (opcache.preload)

- **php-fpm.conf** : 
  - Configuration du pool dynamique optimisée (50 max children, 10 au démarrage)
  - Monitoring activé (/fpm-status, /fpm-ping)
  - Timeouts et slow log configurés
  - Recyclage des workers après 1000 requêtes

### 📊 Améliorations Attendues

| Métrique | Amélioration |
|----------|--------------|
| Temps de réponse | -50% à -70% |
| Throughput | +200% à +400% |
| Utilisation CPU | -40% à -60% |

### 📝 Documentation
- **OPTIMIZATIONS.md** : Guide complet d'optimisation avec toutes les recommandations et étapes de déploiement

### 🔧 Prochaines Étapes

1. Reconstruire les conteneurs : `docker compose build --no-cache`
2. Relancer : `docker compose up -d`
3. Vider le cache Symfony : `docker compose exec php php bin/console cache:clear`
4. Tester les performances avec les outils de benchmark (ab, wrk)

### 📚 Fichiers Modifiés
- `src/Infrastructure/Symfony/config/packages/framework.yaml`
- `src/Infrastructure/Symfony/config/packages/cache.yaml`
- `docker/php/Dockerfile`

### ✨ Fichiers Ajoutés
- `docker/php/php-fpm.conf`
- `OPTIMIZATIONS.md`
- `CHANGELOG.md`

---

Pour plus de détails, consultez [OPTIMIZATIONS.md](./OPTIMIZATIONS.md)
