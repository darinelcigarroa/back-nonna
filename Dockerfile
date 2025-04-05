FROM php:8.2-cli

# Instalar extensiones necesarias de PHP y herramientas del sistema
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libzip-dev \
    zip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql zip

# Crear directorio de trabajo
WORKDIR /app

# Copiar archivos de composer primero para aprovechar el cache
COPY composer.json composer.lock ./

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Ejecutar composer install
RUN composer install --no-dev --optimize-autoloader

# Luego copiar el resto del proyecto
COPY . .

# Asegurar permisos correctos
RUN mkdir -p storage/framework/{sessions,views,cache} && chmod -R 777 storage bootstrap/cache

EXPOSE 6001

CMD ["php", "artisan", "reverb:start"]
