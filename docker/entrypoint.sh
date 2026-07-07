#!/bin/sh
set -e

# Write .env file from Railway environment variables
cat > /var/www/html/.env << ENVFILE
APP_NAME=FundiConnect
APP_ENV=production
APP_KEY=${APP_KEY}
APP_DEBUG=false
APP_URL=${APP_URL}
DB_CONNECTION=pgsql
DB_HOST=${DB_HOST}
DB_PORT=${DB_PORT}
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}
SESSION_DRIVER=file
CACHE_STORE=file
CACHE_PREFIX=fundiconnect
LOG_CHANNEL=stack
LOG_LEVEL=error
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
BROADCAST_CONNECTION=log
MAIL_MAILER=log
MAIL_FROM_ADDRESS=mugisha2edwin@gmail.com
MAIL_FROM_NAME=FundiConnect
ENVFILE

# Verify the .env was written correctly
echo "=== DB_HOST from env: ${DB_HOST} ==="
echo "=== DB_CONNECTION from env: ${DB_CONNECTION} ==="

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