FROM php:8.4-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    curl \
    libonig-dev \
    libpng-dev \
    && rm -rf /var/lib/apt/lists/*

# PHP extensions
RUN docker-php-ext-install zip pdo pdo_mysql mbstring exif

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy composer manifests first for better layer caching
COPY apps/api/composer.json apps/api/composer.lock ./
RUN composer install --no-dev --optimize-autoloader || composer install --optimize-autoloader

# Copy application
COPY apps/api ./

# Laravel permissions
RUN mkdir -p storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 8000

# Start php-fpm with nginx handled elsewhere (kept simple for scaffolding)
CMD ["php-fpm"]

