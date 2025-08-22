FROM php:8.2-cli
RUN apt-get update && apt-get install -y git unzip libicu-dev libzip-dev libonig-dev libpq-dev \
 && docker-php-ext-install intl pdo pdo_mysql pdo_pgsql zip \
 && rm -rf /var/lib/apt/lists/*
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . /app

RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist
RUN php -r "file_exists('.env') || copy('.env.example', '.env');"

# أضف الإنتري بوينت
COPY entrypoint.sh /app/entrypoint.sh
RUN chmod +x /app/entrypoint.sh

ENV PORT=8080
# فعّل هذا فقط لو بدك تعمل seeding عند الإقلاع
# ENV RUN_SEED=true

ENTRYPOINT ["/app/entrypoint.sh"]
