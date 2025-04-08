# Dockerfile
FROM php:8.3-cli

# Instala dependencias necesarias
RUN apt-get update && apt-get install -y \
    git unzip curl nginx supervisor

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copia tu app Laravel (o solo lo necesario para Reverb)
WORKDIR /app
COPY . /app

# Copia configuración de nginx
COPY ./nginx.conf /etc/nginx/nginx.conf

# Copia archivo de supervisord para levantar nginx y Reverb juntos
COPY ./supervisord.conf /etc/supervisord.conf

# Instala dependencias de Laravel si hace falta
RUN composer install --no-dev --optimize-autoloader

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
