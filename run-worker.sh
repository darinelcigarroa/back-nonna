#!/bin/bash

echo "🚀 Iniciando el worker..."
php artisan queue:work
echo "🚀 Termino el worker..."
