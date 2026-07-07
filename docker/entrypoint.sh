#!/bin/sh
set -e

# Update Nginx to use Railway's assigned port
if [ -n "$PORT" ]; then
    sed -i "s|listen 80;|listen ${PORT};|" /etc/nginx/conf.d/default.conf
fi

# Laravel setup
php artisan package:discover --ansi

php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage symlink if it doesn't exist
php artisan storage:link || true

# Run database migrations
php artisan migrate --force

# Start services
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf