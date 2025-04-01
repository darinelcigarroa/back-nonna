FROM php:8.2-fpm

# Instala dependencias necesarias
RUN apt-get update && apt-get install -y \
    nginx \
    libpng-dev libjpeg-dev libfreetype6-dev \
    git unzip curl libzip-dev libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip intl

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copiar archivos de la aplicación
COPY . /var/www/html

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Verificar la ruta actual del proyecto
RUN pwd && ls -la

# Instalar dependencias
RUN composer install --no-dev --optimize-autoloader

# Configurar permisos
RUN chmod -R 775 storage bootstrap/cache
RUN chown -R www-data:www-data /var/www/html

# Copiar configuración de Nginx
COPY nginx.conf /etc/nginx/nginx.conf

# Exponer puerto 80 para Nginx
EXPOSE 80

# Comando para iniciar PHP-FPM y Nginx
CMD ["nginx", "-g", "daemon off;"]  # Inicia Nginx en primer plano
