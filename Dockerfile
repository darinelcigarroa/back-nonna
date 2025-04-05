# Usa la imagen base de PHP
FROM php:8.2-cli

# Instalar dependencias del sistema necesarias para Reverb y PostgreSQL
RUN apt-get update && apt-get upgrade -y

# Instalar las dependencias del sistema una por una para aislar el error
FROM php:8.2-fpm
 
 # Instalar dependencias del sistema y PHP
 RUN apt-get update && apt-get install -y \
     libpng-dev libjpeg-dev libfreetype6-dev \
     git unzip curl libzip-dev libicu-dev \
     nodejs npm && \
     docker-php-ext-configure gd --with-freetype --with-jpeg && \
     docker-php-ext-install gd pdo pdo_pgsql zip intl
 

# Limpiar el caché de apt para reducir el tamaño de la imagen
RUN rm -rf /var/lib/apt/lists/*

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copiar archivos de la aplicación
COPY . /var/www/html

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Instalar dependencias de PHP (sin dev) y Composer
RUN composer install --no-dev --optimize-autoloader

# Exponer el puerto necesario para Reverb (usualmente 6001)
EXPOSE 6001

# Comando por defecto para levantar Laravel Reverb
CMD ["php", "artisan", "reverb:start"]
