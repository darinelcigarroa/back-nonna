FROM php:8.4-fpm-alpine

# Aceptar argumentos de nombre de usuario y UID
ARG USER
ARG UID

# Instalar dependencias del sistema necesarias para Laravel y las extensiones de PHP
RUN apk add --no-cache \
    libxml2-dev \
    libpng-dev \
    libzip-dev \
    libxslt-dev \
    curl \
    postgresql-dev \
    bash \
    zlib-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libxpm-dev \
    && apk update

# Instalar las extensiones de PHP requeridas por Laravel
RUN docker-php-ext-install soap pdo pdo_pgsql exif pcntl bcmath gd intl zip xsl sockets

# Limpiar las herramientas de desarrollo después de la instalación
RUN apk del bash autoconf gcc g++ make

# Obtener la última versión de Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Limpiar la caché de apk
RUN rm -rf /var/cache/apk/*

# Configurar el directorio de trabajo
WORKDIR /var/www

# Agregar usuario y grupo con el UID proporcionado
RUN addgroup -g $UID -S $USER && \
    adduser -u $UID -S $USER -G $USER -s /bin/sh && \
    adduser $USER www-data

# Establecer permisos adecuados
RUN chown -R $USER:$USER /var/www

# Cambiar al usuario proporcionado para evitar correr el contenedor como root
USER $USER

# Copiar el código del proyecto Laravel al contenedor
COPY . .

# Instalar las dependencias de Composer de Laravel en modo producción
RUN composer install --prefer-dist --no-interaction --optimize-autoloader --no-dev

# Exponer el puerto 8000 para el servidor de Laravel
EXPOSE 8000

# Ejecutar el servidor de desarrollo de Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
