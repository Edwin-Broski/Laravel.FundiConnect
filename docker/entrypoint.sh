#!/bin/sh
set -e

echo "=== DB_HOST: ${DB_HOST} ==="
echo "=== DB_CONNECTION: ${DB_CONNECTION} ==="
echo "=== DB_DATABASE: ${DB_DATABASE} ==="

# Remove any existing .env so Laravel reads from system environment
rm -f /var/www/html/.env

# Update Nginx port
if [ -n "$PORT" ]; then
    sed -i "s|listen 80;|listen ${PORT};|" /etc/nginx/conf.d/default.conf
fi

# Laravel setup
php artisan package:discover --ansi

# Storage symlink
php artisan storage:link || true

# Migrations
php artisan migrate --force

# Seed
php artisan db:seed --force || true

# Start
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf