#!/bin/bash

echo "🚀 Iniciando reverb..."
php artisan reverb:start --host=0.0.0.0 --port=9000 --debug
echo "🚀 Termino reverb..."
