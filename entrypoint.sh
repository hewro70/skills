#!/usr/bin/env bash
set -e
php artisan config:clear || true
php artisan cache:clear || true
php artisan key:generate --force || true
php artisan migrate --force || true
[ "$RUN_SEED" = "true" ] && php artisan db:seed --force || true
php artisan config:cache || true
exec php artisan serve --host 0.0.0.0 --port "${PORT}"
