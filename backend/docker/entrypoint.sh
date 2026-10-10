#!/bin/sh
set -eu

if [ -z "${APP_KEY:-}" ] || [ -z "${JWT_SECRET:-}" ]; then
  echo "Set APP_KEY and JWT_SECRET in backend/.env (php artisan key:generate && php artisan jwt:secret)." >&2
  exit 1
fi

mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache

i=0
until php -r 'try { new PDO("mysql:host=".getenv("DB_HOST").";port=".(getenv("DB_PORT") ?: "3306"), getenv("DB_USERNAME"), getenv("DB_PASSWORD")); } catch (Throwable $e) { exit(1); }'; do
  i=$((i + 1))
  if [ "$i" -ge 30 ]; then
    echo "Database is not ready." >&2
    exit 1
  fi
  sleep 2
done

php artisan package:discover --ansi
php artisan migrate --force
chown -R www-data:www-data storage bootstrap/cache
exec "$@"
