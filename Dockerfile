FROM php:8.2-cli

# Dependencias necesarias para Laravel + PostgreSQL + pcntl
RUN apt-get update && apt-get install -y \
    git unzip curl libpq-dev libzip-dev libmagic-dev \
    && docker-php-ext-install pdo_pgsql zip pcntl \
    && rm -rf /var/lib/apt/lists/*

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copiar código de la app
COPY . /var/www/html
WORKDIR /var/www/html

# Instalar dependencias sin dev
RUN composer install --no-dev --optimize-autoloader

# Puerto de Reverb
EXPOSE 9000

# Comando por defecto
CMD ["php", "artisan", "reverb:start", "--host=0.0.0.0", "--port=9000"]
