FROM php:8.4-cli

WORKDIR /app

# instalar dependencias necesarias
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    zip \
    ca-certificates

# extensiones PHP necesarias
RUN docker-php-ext-install pdo pdo_mysql sockets

# instalar composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# copiar proyecto
COPY . .

# instalar dependencias Laravel
RUN composer install --no-dev --optimize-autoloader

# limpiar caches de laravel
RUN php artisan config:clear || true
RUN php artisan route:clear || true
RUN php artisan cache:clear || true

# puerto que usa render
EXPOSE 10000

# iniciar app
CMD php artisan config:clear && php artisan migrate --force && php artisan db:seed --force && php artisan serve --host=0.0.0.0 --port=10000