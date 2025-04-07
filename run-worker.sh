#!/bin/bash

echo "📁 Listando archivos..."
ls -la

echo "📂 Directorio actual: $(pwd)"

echo "🚀 Iniciando el worker..."
php artisan queue:work --verbose --tries=3 --timeout=90
