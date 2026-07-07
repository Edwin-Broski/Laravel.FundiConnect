#!/bin/sh
set -e

# Create .env from Railway environment variables if it doesn't exist
if [ ! -f /var/www/html/.env ]; then
    cat > /var/www/html/.env << EOF
APP_NAME="${APP_NAME}"
APP_ENV="${APP_ENV}"
APP_KEY="${APP_KEY}"
APP_DEBUG="${APP_DEBUG}"
APP_URL="${APP_URL}"
DB_CONNECTION="${DB_CONNECTION}"
DB_HOST="${DB_HOST}"
DB_PORT="${DB_PORT}"
DB_DATABASE="${DB_DATABASE}"
DB_USERNAME="${DB_USERNAME}"
DB_PASSWORD="${DB_PASSWORD}"
SESSION_DRIVER="file"
CACHE_STORE="file"
LOG_CHANNEL="stack"
LOG_LEVEL="error"
FILESYSTEM_DISK="local"
EOF
fi

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