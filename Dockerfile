FROM php:8.3-cli-alpine

WORKDIR /var/www/html

RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    nodejs \
    bash \
    npm

RUN docker-php-ext-install pdo pdo_mysql zip exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN addgroup -g 1000 laravel && adduser -D -u 1000 -G laravel laravel

USER laravel

EXPOSE 8000
