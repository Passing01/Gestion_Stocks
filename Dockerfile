FROM php:8.2-apache

# Installer les dépendances
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip \
    && a2enmod rewrite

# Configurer Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier l'application
WORKDIR /var/www/html
COPY . .

# Configurer Laravel
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && touch storage/database.sqlite \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chmod 664 storage/database.sqlite \
    && composer install --optimize-autoloader --no-dev \
    && [ -f .env ] || cp .env.example .env \
    && php artisan key:generate \
    && php artisan migrate --force \
    && php artisan optimize

EXPOSE 80