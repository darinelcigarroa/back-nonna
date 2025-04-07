#!/bin/bash

echo "📁 Listando archivos..."
ls -la

echo "🔑 APP_KEY: $APP_KEY"

echo "📂 Directorio actual: $(pwd)"

echo "🚀 Iniciando el worker..."
php artisan queue:work --verbose --tries=3 --timeout=90
