FROM php:8.4-fpm-alpine

# Actualizar los repositorios de apk y agregar las dependencias necesarias.
RUN apk update && apk add --no-cache ca-certificates postgresql-dev curl bash libpng-dev libjpeg-turbo-dev libwebp-dev libxpm-dev zlib-dev libgd-dev

# Instalar las extensiones de PHP necesarias.
RUN docker-php-ext-install pdo pdo_pgsql gd

# Instalar Composer y configurar el archivo de certificados CA
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer config --global cafile /etc/ssl/certs/ca-certificates.crt

# Establecer el directorio de trabajo en /var/www
WORKDIR /var/www

# Copiar el código de tu aplicación al contenedor
COPY . .

# Ejecutar Composer para instalar las dependencias de producción con opción verbose para ver más detalles
RUN composer install --prefer-dist --no-interaction --optimize-autoloader --no-dev -vvv

# Exponer el puerto 8000 para el servidor de Laravel
EXPOSE 8000

# Ejecutar el servidor de desarrollo de Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]