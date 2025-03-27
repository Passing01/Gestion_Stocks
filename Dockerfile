# Étape de construction
FROM php:8.2-fpm-alpine AS builder

# Installer les dépendances système
RUN apk add --no-cache \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Créer la structure de dossiers
WORKDIR /var/www/html
RUN mkdir -p storage/framework/{sessions,views,cache} \
    storage/logs \
    bootstrap/cache

# Copier TOUS les fichiers nécessaires avant l'installation
COPY . .

# Installer les dépendances (en ignorant les scripts post-install)
RUN composer install --optimize-autoloader --no-dev --no-scripts

# Exécuter les scripts artisan après l'installation complète
RUN composer run-script post-autoload-dump

# Configurer les permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && [ -f .env ] || cp .env.example .env \
    && php artisan key:generate \
    && php artisan optimize

# Étape de production
FROM php:8.2-apache

# Installer les extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_mysql

# Copier depuis le builder avec les bonnes permissions
COPY --from=builder --chown=www-data:www-data /var/www/html /var/www/html

# Configurer Apache
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && a2enmod rewrite

WORKDIR /var/www/html