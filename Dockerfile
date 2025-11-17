# Stage 1: Build dos assets
FROM node:20-alpine AS node-builder

WORKDIR /app

# Copiar arquivos de dependências primeiro
COPY package*.json ./
COPY package-lock.json* ./
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
# Usar --no-scripts para evitar executar scripts que fazem cache de config durante o build
# (as variáveis de ambiente do Railway só estão disponíveis em runtime, não durante o build)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Configurar permissões
RUN chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Script de inicialização
RUN printf '#!/bin/sh\n\
set -e\n\
# Debug: verificar variáveis de ambiente do banco (remover em produção se necessário)\n\
echo "=== Variáveis de Ambiente do Banco ==="\n\
echo "DB_CONNECTION=${DB_CONNECTION:-não definido}"\n\
echo "DB_HOST=${DB_HOST:-não definido}"\n\
echo "DB_PORT=${DB_PORT:-não definido}"\n\
echo "DB_DATABASE=${DB_DATABASE:-não definido}"\n\
echo "DB_USERNAME=${DB_USERNAME:-não definido}"\n\
echo "DATABASE_URL=${DATABASE_URL:-não definido}"\n\
echo "====================================="\n\
# Limpar todos os caches antes de recriar (garante que variáveis de ambiente sejam lidas)\n\
rm -f bootstrap/cache/config.php\n\
rm -f bootstrap/cache/routes-v7.php\n\
rm -f bootstrap/cache/*.php\n\
php artisan config:clear\n\
php artisan cache:clear\n\
php artisan view:clear\n\
php artisan route:clear\n\
# Executar migrações\n\
php artisan migrate --force --no-interaction || true\n\
# Recriar caches com as variáveis de ambiente corretas (agora disponíveis em runtime)\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
# Iniciar servidor\n\
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}\n' > /usr/local/bin/start.sh && \
    chmod +x /usr/local/bin/start.sh

EXPOSE 8000

CMD ["/usr/local/bin/start.sh"]
