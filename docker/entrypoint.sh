#!/bin/sh
set -e

# Update Nginx to use Railway's assigned port
if [ -n "$PORT" ]; then
    sed -i "s|listen 80;|listen ${PORT};|" /etc/nginx/conf.d/default.conf
fi

# Laravel setup
php artisan package:discover --ansi

# Create storage symlink
php artisan storage:link || true

# Run migrations
php artisan migrate --force

# Seed trades
php artisan db:seed --force || true

# Start services
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf