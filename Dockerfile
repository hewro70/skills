# ====== Stage 1: Build frontend assets with Vite ======
FROM node:20-alpine AS assets
WORKDIR /app

# انسخ فقط ملفات الـ npm أولاً للاستفادة من كاش الطبقات
COPY package.json package-lock.json* yarn.lock* pnpm-lock.yaml* ./
# استخدم npm (بدّل لـ yarn/pnpm إذا مستعملهم)
RUN npm ci

# انسخ بقية السورس اللازمة للبناء
COPY resources ./resources
COPY public ./public
COPY vite.config.* .
COPY tailwind.config.* .  # إن كان عندك
COPY postcss.config.* .   # إن كان عندك

# ابنِ أصول Vite للإنتاج (Laravel يضعها تحت public/build)
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

# انسخ مشروع Laravel كاملاً
COPY . /app

# انسخ الأصول المبنية من المرحلة الأولى إلى public/build
COPY --from=assets /app/public/build /app/public/build

# ثبّت باكج PHP (بدون dev) وحسّن التحميل
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# جهّز .env لو ناقص (بدون caching أثناء الـ build)
RUN php -r "file_exists('.env') || copy('.env.example', '.env');"

# (اختياري) نظّف أي نهايات CRLF في سكربتات الشِل لو عندك entrypoint.sh
# RUN sed -i 's/\r$//' /app/entrypoint.sh && chmod +x /app/entrypoint.sh

ENV PORT=8080

# عند التشغيل: نظّف/ولّد المفتاح → مهاجرات (وسييد إن حبيت) → كاش → شغّل السيرفر
CMD bash -lc "\
  php artisan config:clear || true && \
  php artisan cache:clear || true && \
  php artisan key:generate --force || true && \
  php artisan migrate --force || true && \
  ([ \"${RUN_SEED}\" = \"true\" ] && php artisan db:seed --force || true) && \
  php artisan config:cache || true && \
  php artisan serve --host 0.0.0.0 --port ${PORT} \
"
