# Usa la imagen oficial de Laravel
FROM composer:latest AS build

# Establece el directorio de trabajo
WORKDIR /app

# Copia composer files y descarga dependencias
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# Copia el resto del proyecto
COPY . .

# Imagen final
FROM php:8.2-cli

# Instala extensiones necesarias (ajusta según lo que uses)
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-install zip pdo pdo_mysql

# Instala Composer
COPY --from=build /usr/bin/composer /usr/bin/composer

# Copia código del proyecto
COPY --from=build /app /app
WORKDIR /app

# Crea cache y directorios necesarios
RUN mkdir -p storage/framework/{sessions,views,cache} && chmod -R 777 storage bootstrap/cache

# Expone el puerto (no obligatorio para Reverb, pero útil)
EXPOSE 6001

# Comando principal: aquí corre Reverb
CMD ["php", "artisan", "reverb:start"]
