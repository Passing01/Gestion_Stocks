# Étape 1 : Builder les dépendances PHP
FROM php:8.2-fpm-alpine AS builder

RUN apk add --no-cache \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN composer install --optimize-autoloader --no-dev \
    && php artisan key:generate \
    && php artisan optimize

# Étape 2 : Image finale avec Apache
FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql

COPY --from=builder /var/www/html /var/www/html
COPY --from=builder /usr/bin/composer /usr/bin/composer

RUN if [ ! -f .env ]; then \
        cp .env.example .env && \
        php artisan key:generate; \
    fi && \
    php artisan optimize

FROM php:8.2-apache

# Configurer Apache
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN a2enmod rewrite

WORKDIR /var/www/html
RUN chown -R www-data:www-data storage bootstrap/cache

RUN echo "APP_KEY=base64:VOTRE_CLE_BASE64_ICI" >> .env