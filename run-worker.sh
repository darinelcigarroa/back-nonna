#!/bin/bash

# Mostrar comandos en ejecución
set -x

echo "🚀 Iniciando worker de Laravel..."

php artisan queue:work --sleep=3 --tries=3

echo "❌ El worker ha terminado"
