# API Template PHP – Symfony 8.0 RC + PHP 8.4 + Caddy + Architecture propre

Template ultra-moderne prêt en 30 secondes, fonctionnel dès le premier `git clone`, même sur Ubuntu 24.04.

- Symfony 8.0 (RC)
- PHP 8.4
- Caddy 2.8 (ultra léger)
- Namespace personnalisé conservé : `App\Infrastructure\Symfony\Kernel`
- Architecture hexagonale prête (Domain / Application / Infrastructure)
- Zéro problème de permissions sur Linux

## 🚀 Démarrage ultra-rapide (clone frais)

```bash
git clone https://github.com/suarezd/api-template-php.git
cd api-template-php

git config --global --add safe.directory "$(pwd)"

make prepare

make test_api