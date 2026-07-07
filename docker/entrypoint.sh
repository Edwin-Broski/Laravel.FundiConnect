#!/bin/sh
set -e

# Update Nginx port
if [ -n "$PORT" ]; then
    sed -i "s|listen 80;|listen ${PORT};|" /etc/nginx/conf.d/default.conf
fi

# Clear all caches first
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Discover packages
php artisan package:discover --ansi

# Storage symlink
php artisan storage:link || true

# Run migrations
php artisan migrate --force

# Seed trades if empty
php artisan db:seed --force || true

# Cache for performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start supervisor
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf