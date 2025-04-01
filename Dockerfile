# Usar la imagen de PHP con soporte para Laravel
FROM php:8.1-fpm

# Instalar dependencias del sistema y PHP
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev git unzip && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd pdo pdo_mysql

# Copiar archivos de la aplicación
COPY . /var/www/html

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Instalar dependencias de PHP y Node
RUN composer install --no-dev && \
    npm ci && \
    npm run build

# Ejecutar migraciones, seeders y configuración de almacenamiento
RUN php artisan migrate:fresh --seed --force && \
    chmod -R 777 storage && \
    php artisan storage:link

# Permisos de almacenamiento y caché
RUN chmod -R 777 storage bootstrap/cache

# Crear enlace simbólico para almacenamiento
RUN php artisan storage:link || true

# Exponer puerto
EXPOSE 9000

# Ejecutar PHP-FPM
CMD ["php-fpm"]
