#!/bin/bash

# Mostrar comandos en ejecución
set -x

echo "🚀 Iniciando worker de Laravel..."

php artisan queue:work

echo "❌ El worker ha terminado"
