FROM php:8.2-apache

# 1. Installer les dépendances système
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev sqlite3 \
    && docker-php-ext-install pdo pdo_mysql zip \
    && a2enmod rewrite

# 2. Configurer Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf

# 3. Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Copier l'application
WORKDIR /var/www/html
COPY . .

# 5. Préparer l'environnement Laravel
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && touch storage/database.sqlite .env \
    && [ -f .env ] || cp .env.example .env

# 6. Configurer les permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache \
    && chmod 664 storage/database.sqlite

# 7. Installer les dépendances et configurer Laravel
RUN composer install --optimize-autoloader --no-dev \
    && php artisan key:generate \
    && php artisan migrate --force \
    && php artisan optimize

EXPOSE 80