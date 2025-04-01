# Usar una versión estable de PHP
FROM php:8.3-fpm-alpine

# Instalar dependencias necesarias
RUN apk update && apk add --no-cache \
    ca-certificates \
    postgresql-dev \
    curl \
    bash \
    gd-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libxpm-dev \
    zlib-dev \
    libzip-dev \
    linux-headers \
    nodejs \
    npm \
    && docker-php-ext-install pdo pdo_pgsql gd zip sockets \
    && rm -rf /var/cache/apk/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer directorio de trabajo
WORKDIR /var/www

# Copiar archivos del proyecto
COPY . .

# Instalar dependencias de PHP
RUN composer install --no-dev --prefer-dist --optimize-autoloader

# Permisos de almacenamiento y caché
RUN chmod -R 777 storage bootstrap/cache

# Crear enlace simbólico para almacenamiento
RUN php artisan storage:link || true

# Exponer puerto
EXPOSE 9000

# Ejecutar PHP-FPM
CMD ["php-fpm"]
