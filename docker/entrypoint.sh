#!/bin/sh
set -e

if [ -n "$PORT" ]; then
  sed -i "s|listen 80;|listen ${PORT};|" /etc/nginx/conf.d/default.conf
fi

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link || true
php artisan migrate --force || true

/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
