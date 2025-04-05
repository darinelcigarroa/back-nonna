# Usa la imagen base de PHP
FROM php:8.2-cli

# Instalar dependencias del sistema necesarias para Reverb y PostgreSQL
RUN apt-get update && apt-get upgrade -y

# Instalar las dependencias del sistema una por una para aislar el error
RUN apt-get install -y \
    git \
    unzip \
    curl \
    libpq-dev \
    libzip-dev \
    pkg-config

# Verificar que libzip esté correctamente instalado
RUN apt-cache policy libzip-dev

# Instalar las extensiones PHP necesarias
RUN docker-php-ext-install pdo_pgsql zip

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
