# ====== Stage 1: Build frontend assets with Vite ======
FROM node:20-alpine AS assets
WORKDIR /app

# Install deps with cache-friendly layers
COPY package.json package-lock.json* ./
RUN npm ci --no-audit --no-fund

# Copy the rest needed for build (rely on .dockerignore to skip heavy stuff)
COPY . .

# Build to public/build (Laravel Vite default)
RUN npm run build

# ====== Stage 2: PHP + Laravel ======
FROM php:8.2-cli

# System deps + PHP extensions
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libzip-dev libonig-dev libpq-dev \
 && docker-php-ext-install intl pdo pdo_mysql pdo_pgsql zip \
 && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy Laravel project
COPY . /app

# Copy built assets from Stage 1
COPY --from=assets /app/public/build /app/public/build

# PHP deps
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Ensure .env exists (no cache/config at build time)
RUN php -r "file_exists('.env') || copy('.env.example', '.env');"

ENV PORT=8080

# Runtime: clear caches, key, migrate (and optional seed), then serve
CMD bash -lc "\
  php artisan config:clear || true && \
  php artisan cache:clear || true && \
  php artisan key:generate --force || true && \
  php artisan migrate --force || true && \
  ([ \"${RUN_SEED}\" = \"true\" ] && php artisan db:seed --force || true) && \
  php artisan config:cache || true && \
  php artisan serve --host 0.0.0.0 --port ${PORT} \
"
