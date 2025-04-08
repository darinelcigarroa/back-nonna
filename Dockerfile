FROM php:8.3-cli

# Instala dependencias necesarias
RUN apt-get update && apt-get upgrade -y && \
    apt-get install -y \
    nginx \
    git \
    unzip \
    curl \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    pkg-config \
    supervisor && \
    echo "APT install complete" && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd pdo_pgsql zip && \
    rm -rf /var/lib/apt/lists/*

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copia tu app Laravel (o solo lo necesario para Reverb)
WORKDIR /app
COPY . /app

# Ejecuta composer install después de copiar tu app
RUN composer install --no-dev --optimize-autoloader

# Copia configuración de nginx
COPY ./nginx.conf /etc/nginx/nginx.conf

# Crear los directorios necesarios para los logs
RUN mkdir -p /var/log/reverb /var/log/nginx && \
    chown -R www-data:www-data /var/log/reverb /var/log/nginx

# Copia el archivo de configuración de supervisord
COPY ./supervisord.conf /etc/supervisord.conf

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
