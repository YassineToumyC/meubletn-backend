#!/bin/sh

echo "→ Running migrations..."
php artisan migrate --force --no-interaction

echo "→ Creating storage link..."
php artisan storage:link --force 2>/dev/null || true

echo "→ Caching config & routes..."
php artisan config:cache
php artisan route:cache

echo "→ Starting FrankenPHP..."
exec frankenphp run --config /etc/caddy/Caddyfile
