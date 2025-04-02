# Usar la imagen de PHP con FPM
FROM php:8.2-fpm

# Instalar dependencias del sistema y PHP
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev \
    git unzip curl libzip-dev libicu-dev \
    nodejs npm nginx && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd pdo pdo_mysql zip intl

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copiar archivos de la aplicación
COPY . /var/www/html

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Instalar dependencias de PHP y Node
RUN composer install
RUN npm install --production
RUN php artisan optimize
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache
RUN php artisan migrate --force
RUN php artisan db:seed --force

# Ejecutar migraciones, seeders y configuración de almacenamiento
RUN chmod -R 777 storage && \
    php artisan storage:link

# Permisos de almacenamiento y caché
RUN chmod -R 777 storage bootstrap/cache

# Crear enlace simbólico para almacenamiento
RUN php artisan storage:link || true

# Instalar la configuración de Nginx
COPY nginx.conf /etc/nginx/sites-available/default

# Exponer puerto 80 para Nginx
EXPOSE 80

# Iniciar Nginx y PHP-FPM
CMD service nginx start && php-fpm
