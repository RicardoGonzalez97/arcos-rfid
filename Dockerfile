FROM php:8.2-cli

WORKDIR /app

# instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    zip

RUN docker-php-ext-install pdo pdo_mysql

# instalar composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# copiar proyecto
COPY . .

# instalar dependencias
RUN composer install --no-dev --optimize-autoloader

# puerto de render
EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=10000