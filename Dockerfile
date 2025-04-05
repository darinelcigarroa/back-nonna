FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libzip-dev \
    zip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install pdo_pgsql zip gd pcntl

WORKDIR /app

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader -vvv

COPY . .

RUN chmod +x artisan
RUN mkdir -p storage/framework/{sessions,views,cache} && chmod -R 777 storage bootstrap/cache

EXPOSE 6001

CMD ["php", "artisan", "reverb:start"]