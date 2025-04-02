# Usar la imagen de PHP con FPM (solo para la instalación de dependencias)
FROM php:8.2-cli

# Instalar dependencias del sistema y PHP
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev \
    git unzip curl libzip-dev libicu-dev \
    nodejs npm && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd pdo pdo_mysql zip intl

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copiar archivos de la aplicación
COPY . /var/www/html

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Instalar dependencias de PHP y Node
RUN composer install --no-dev
RUN npm install --production

# Ejecutar migraciones, seeders y configuración de almacenamiento
RUN php artisan optimize
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache
RUN php artisan migrate --force
# RUN php artisan db:seed --force
# RUN php artisan migrate --force --seed

# Exponer el puerto 8000 para interactuar con la app si es necesario (pero Railway usará Nginx)
EXPOSE 8000
