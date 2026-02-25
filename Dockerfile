FROM php:8.3-fpm

# instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nginx \
    supervisor

# limpiar cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# instalar extensiones PHP necesarias para Laravel
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# instalar composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --no-dev --optimize-autoloader

CMD php artisan serve --host=0.0.0.0 --port=8080