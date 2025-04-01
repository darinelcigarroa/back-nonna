FROM php:8.4-fpm-alpine

# Actualizar los repositorios de apk y agregar las dependencias necesarias.
RUN apk update && apk add --no-cache ca-certificates postgresql-dev curl bash gd-dev libpng-dev libjpeg-turbo-dev libwebp-dev libxpm-dev zlib-dev libzip-dev linux-headers

# Instalar las extensiones de PHP necesarias.
RUN docker-php-ext-install pdo pdo_pgsql gd zip sockets

# Instalar Composer y configurar el archivo de certificados CA
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer config --global cafile /etc/ssl/certs/ca-certificates.crt

# Instalar Node.js y npm
RUN apk add --no-cache nodejs npm

# Establecer el directorio de trabajo en /var/www
WORKDIR /var/www

# Copiar el código de tu aplicación al contenedor
COPY . .

# Verificar la estructura de directorios después de copiar los archivos
RUN ls -la /var/www

# Ejecutar Composer para instalar las dependencias de producción
RUN composer install --no-dev

# Instalar las dependencias de Node.js y construir la aplicación Quasar
RUN npm ci
RUN npm run build

# Ejecutar las migraciones de base de datos
RUN php artisan migrate:fresh --seed --force

# Cambiar los permisos de las carpetas de almacenamiento
RUN chmod -R 777 storage

# Enlazar la carpeta de almacenamiento
RUN php artisan storage:link

# Exponer el puerto 8000 para el servidor de Laravel
EXPOSE 8000

CMD ["php-fpm"]
