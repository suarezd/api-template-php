# 📊 Monitoring avec Prometheus et Grafana

## Services de Monitoring

Votre API Symfony est maintenant équipée d'une stack de monitoring complète :

- **Prometheus** : Collecte et stockage des métriques
- **Grafana** : Visualisation et dashboards
- **Endpoint /metrics** : Exposition des métriques Symfony

## 🚀 Accès aux Services

### Grafana
- **URL** : http://localhost:3000
- **Username** : admin
- **Password** : admin
- **Dashboard** : "Symfony API Performance" (pré-configuré)

### Prometheus
- **URL** : http://localhost:9090
- **Targets** : http://localhost:9090/targets
- **Queries** : http://localhost:9090/graph

### Endpoint Metrics
- **URL** : http://localhost/metrics
- **Format** : Prometheus text format

## 📈 Métriques Disponibles

### OPcache
- `opcache_memory_usage{type="used"}` : Mémoire OPcache utilisée (bytes)
- `opcache_memory_usage{type="free"}` : Mémoire OPcache disponible (bytes)
- `opcache_hits_total` : Nombre total de hits OPcache
- `opcache_misses_total` : Nombre total de misses OPcache
- `opcache_scripts_cached` : Nombre de scripts en cache

### APCu
- `apcu_memory_usage{type="used"}` : Mémoire APCu utilisée (bytes)
- `apcu_memory_usage{type="free"}` : Mémoire APCu disponible (bytes)
- `apcu_hits_total` : Nombre total de hits APCu
- `apcu_misses_total` : Nombre total de misses APCu
- `apcu_entries` : Nombre d'entrées en cache

### PHP
- `php_memory_usage` : Utilisation mémoire PHP courante (bytes)
- `php_memory_peak_usage` : Pic d'utilisation mémoire PHP (bytes)

## 📊 Dashboard Grafana

Le dashboard "Symfony API Performance" inclut :

1. **OPcache Memory Usage** : Graphique de l'utilisation mémoire OPcache
2. **OPcache Hit Rate** : Taux de succès du cache (%)
3. **OPcache Scripts Cached** : Nombre de scripts en cache
4. **APCu Memory Usage** : Graphique de l'utilisation mémoire APCu
5. **APCu Hit Rate** : Taux de succès du cache (%)
6. **APCu Entries** : Nombre d'entrées en cache
7. **PHP Memory Usage** : Utilisation mémoire PHP

## 🔍 Requêtes Prometheus Utiles

### Taux de hit OPcache (%)
```promql
rate(opcache_hits_total[1m]) / (rate(opcache_hits_total[1m]) + rate(opcache_misses_total[1m])) * 100
```

### Taux de hit APCu (%)
```promql
rate(apcu_hits_total[1m]) / (rate(apcu_hits_total[1m]) + rate(apcu_misses_total[1m])) * 100
```

### Utilisation mémoire OPcache (%)
```promql
opcache_memory_usage{type="used"} / (opcache_memory_usage{type="used"} + opcache_memory_usage{type="free"}) * 100
```

## ⚙️ Configuration

### Prometheus
Fichier : [docker/prometheus/prometheus.yml](docker/prometheus/prometheus.yml)

Le scraping est configuré pour interroger l'endpoint `/metrics` toutes les 10 secondes.

### Grafana
- Datasource : Configurée automatiquement au démarrage
- Dashboard : Provisionné depuis [docker/grafana/provisioning/dashboards/symfony-dashboard.json](docker/grafana/provisioning/dashboards/symfony-dashboard.json)

## 🛠️ Commandes Utiles

### Démarrer les services de monitoring
```bash
docker compose up -d prometheus grafana
```

### Arrêter les services de monitoring
```bash
docker compose stop prometheus grafana
```

### Voir les logs
```bash
docker compose logs -f prometheus
docker compose logs -f grafana
```

### Tester l'endpoint metrics
```bash
curl http://localhost/metrics
```

### Vérifier les targets Prometheus
```bash
curl http://localhost:9090/api/v1/targets | jq
```

## 📝 Ajouter de Nouvelles Métriques

Pour ajouter de nouvelles métriques, modifiez le fichier [MetricsController.php](src/Infrastructure/Symfony/Controller/MetricsController.php).

Exemple :
```php
$metrics[] = sprintf('# HELP my_metric Description de ma métrique');
$metrics[] = sprintf('# TYPE my_metric gauge');
$metrics[] = sprintf('my_metric %d', $value);
```

## 🎯 Alertes Recommandées

Vous pouvez configurer des alertes Prometheus pour :
- Taux de hit OPcache < 95%
- Taux de hit APCu < 95%
- Utilisation mémoire OPcache > 90%
- Utilisation mémoire PHP > 200MB

## 🔍 Benchmarking et Tests de Charge

### Apache Bench
```bash
ab -n 1000 -c 10 http://localhost/hello
ab -n 10000 -c 100 -k http://localhost/api/hello
```

### wrk (recommandé)
```bash
sudo apt install wrk
wrk -t4 -c100 -d30s http://localhost/api/hello
```

Résultats attendus avec les optimisations :
- Requests/sec: 3000-5000+
- Latency: 10-30ms

## 🎨 Optimisations Recommandées

### Redis pour cache distribué (Production)
Pour une architecture multi-serveurs :

```yaml
services:
  redis:
    image: redis:7-alpine
    ports:
      - "6379:6379"
    command: redis-server --appendonly yes --maxmemory 512mb --maxmemory-policy allkeys-lru
```

### Compression Brotli/Zstd dans Caddy
```
encode zstd gzip
```

### Optimiser l'autoloader Composer (Production)
```bash
composer install --no-dev --optimize-autoloader --classmap-authoritative --apcu-autoloader
```

## 📚 Ressources

- [Documentation Prometheus](https://prometheus.io/docs/)
- [Documentation Grafana](https://grafana.com/docs/)
- [Prometheus Query Language (PromQL)](https://prometheus.io/docs/prometheus/latest/querying/basics/)
- [Symfony Performance Best Practices](https://symfony.com/doc/current/performance.html)
