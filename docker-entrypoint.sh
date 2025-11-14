#!/usr/bin/env bash
set -e

echo "🔄 Lancement des migrations Doctrine..."
php bin/console doctrine:migrations:migrate --no-interaction --env=prod

echo "✅ Migrations OK, démarrage du serveur PHP..."
php -S 0.0.0.0:10000 -t public
