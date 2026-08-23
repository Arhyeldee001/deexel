#!/bin/sh
set -e

# Generate app key if not already set (safe to run every time; only sets if empty)
php artisan config:clear

# Run database migrations (safe to run on every deploy; only applies new ones)
php artisan migrate --force

# Cache config, routes, and views for performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create the storage symlink (public/storage -> storage/app/public)
php artisan storage:link || true

exec "$@"
