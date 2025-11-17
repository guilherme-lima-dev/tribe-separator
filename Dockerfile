# Stage 1: Build dos assets
FROM node:20-alpine AS node-builder

WORKDIR /app

# Copiar arquivos de dependências primeiro
COPY package*.json ./
RUN npm ci

# Copiar arquivos de configuração
COPY vite.config.js ./
COPY tailwind.config.js ./
COPY postcss.config.js ./

# Copiar recursos e assets
COPY resources ./resources
COPY public ./public

# Build dos assets
RUN npm run build

# Stage 2: Aplicação PHP
FROM php:8.3-cli-alpine

WORKDIR /var/www/html

# Instalar dependências do sistema
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    oniguruma-dev \
    postgresql-dev \
    mysql-client \
    bash

# Instalar extensões PHP
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    zip \
    exif \
    pcntl \
    bcmath \
    gd \
    mbstring

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar arquivos do projeto
COPY . .

# Copiar assets compilados
COPY --from=node-builder /app/public/build ./public/build

# Instalar dependências do PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Configurar permissões
RUN chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Script de inicialização
RUN printf '#!/bin/sh\n\
set -e\n\
php artisan migrate --force --no-interaction || true\n\
php artisan config:clear\n\
php artisan cache:clear\n\
php artisan view:clear\n\
php artisan route:clear\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}\n' > /usr/local/bin/start.sh && \
    chmod +x /usr/local/bin/start.sh

EXPOSE 8000

CMD ["/usr/local/bin/start.sh"]
