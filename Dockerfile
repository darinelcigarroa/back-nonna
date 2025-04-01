FROM php:8.4-fpm-alpine

RUN apk update && apk add --no-cache postgresql-dev

RUN docker-php-ext-install pdo pdo_pgsql

WORKDIR /var/www

COPY . .

RUN composer install --prefer-dist --no-interaction --optimize-autoloader --no-dev

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]