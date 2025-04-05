FROM php:8.2-cli

# Instalar dependencias del sistema y extensiones de PHP
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libzip-dev \
    zip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql zip gd

# Directorio de trabajo
WORKDIR /app

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar composer y correr instalación
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# Copiar el resto del proyecto
COPY . .

# Permisos necesarios
RUN mkdir -p storage/framework/{sessions,views,cache} && chmod -R 777 storage bootstrap/cache

EXPOSE 6001

CMD ["php", "artisan", "reverb:start"]
