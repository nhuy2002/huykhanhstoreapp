# syntax=docker/dockerfile:1

# ---- Stage 1: PHP dependencies (composer) ----
FROM composer:2 AS vendor

ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /app

COPY . .

RUN composer install \
        --no-dev \
        --no-interaction \
        --prefer-dist \
        --optimize-autoloader

# ---- Stage 2: runtime image (Apache + PHP) ----
FROM php:8.3-apache AS app

RUN apt-get update && apt-get install -y --no-install-recommends \
        curl \
        libonig-dev \
        libsqlite3-dev \
    && docker-php-ext-install pdo_sqlite mbstring bcmath \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY --chown=www-data:www-data . .
COPY --from=vendor --chown=www-data:www-data /app/vendor ./vendor

COPY docker/apache-vhost.conf /etc/apache2/sites-available/000-default.conf
COPY docker/entrypoint.sh /usr/local/bin/docker-entrypoint-app.sh
RUN chmod +x /usr/local/bin/docker-entrypoint-app.sh

ENV APP_ENV=production \
    APP_DEBUG=false

EXPOSE 80

ENTRYPOINT ["docker-entrypoint-app.sh"]
CMD ["apache2-foreground"]
