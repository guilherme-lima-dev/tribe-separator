#!/bin/sh
set -e

if [ -n "$PORT" ] && [ "$PORT" != "80" ]; then
    sed -i "s/listen 80/listen ${PORT}/" /etc/nginx/http.d/default.conf
    sed -i "s/listen \[::\]:80/listen [::]:${PORT}/" /etc/nginx/http.d/default.conf
fi

rm -f bootstrap/cache/config.php bootstrap/cache/routes-v7.php 2>/dev/null || true
rm -f bootstrap/cache/*.php 2>/dev/null || true

php artisan migrate --force --no-interaction || true
php artisan config:cache
php artisan route:cache
php artisan view:cache

php-fpm -D
exec nginx -g 'daemon off;'
