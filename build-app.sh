#!/bin/bash
set -e
echo "Building assets using npm"
npm run build

echo "Clearing cache"
php artisan optimize:clear

echo "Caching config, events, routes, views"
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache
