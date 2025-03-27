FROM php:8.2-apache

# Installation des dépendances critiques
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev sqlite3 \
    && docker-php-ext-install pdo pdo_mysql zip \
    && pecl install redis && docker-php-ext-enable redis \
    && a2enmod rewrite

# Configuration Apache critique
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf && \
    echo "ErrorLog /proc/self/fd/2" >> /etc/apache2/apache2.conf && \
    echo "CustomLog /proc/self/fd/1 common" >> /etc/apache2/apache2.conf

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf

# Installation Composer optimisée
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# Script d'initialisation robuste
RUN mkdir -p storage/framework/{sessions,views,cache} && \
    touch storage/database.sqlite && \
    chown -R www-data:www-data /var/www/html && \
    chmod -R 775 storage bootstrap/cache && \
    composer install --optimize-autoloader --no-dev && \
    { [ -f .env ] || cp .env.example .env; } && \
    php artisan key:generate --force && \
    php artisan config:clear && \
    php artisan migrate --force && \
    php artisan optimize

EXPOSE 80