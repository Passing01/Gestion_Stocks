FROM php:8.2-apache

# 1. Installer les dépendances
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev sqlite3 \
    && docker-php-ext-install pdo pdo_mysql zip \
    && a2enmod rewrite

# 2. Configurer Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf

# 3. Préparer l'environnement Laravel
WORKDIR /var/www/html
COPY . .

# 4. Configurer SQLite avec permissions
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && touch storage/database.sqlite \
    && sqlite3 storage/database.sqlite "PRAGMA journal_mode=WAL;" \
    && chown -R www-data:www-data storage \
    && chmod -R 775 storage bootstrap/cache \
    && chmod 664 storage/database.sqlite

# 5. Installer Laravel
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --optimize-autoloader --no-dev \
    && php artisan key:generate \
    && php artisan migrate --force \
    && php artisan optimize

EXPOSE 80