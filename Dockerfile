FROM php:8.2-apache

# Installer les dépendances critiques
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev sqlite3 \
    && docker-php-ext-install pdo pdo_mysql zip \
    && a2enmod rewrite

# Configuration Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf

# Préparer l'environnement
WORKDIR /var/www/html
COPY . .

# Correction spécifique SQLite
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && touch storage/database.sqlite \
    && sqlite3 storage/database.sqlite "PRAGMA journal_mode=WAL; PRAGMA synchronous=OFF;" \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache \
    && chmod 664 storage/database.sqlite

# Installation Laravel
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --optimize-autoloader --no-dev \
    && php artisan key:generate --force \
    && php artisan migrate --force \
    && php artisan optimize

EXPOSE 80