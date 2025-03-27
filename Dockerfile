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

# 4. Préparer la structure de fichiers
WORKDIR /var/www/html
COPY . .

# 5. Créer .env si inexistant
RUN cp .env.example .env || echo ".env.example non trouvé, création d'un .env vide" && touch .env

# 6. Configurer les permissions
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && touch storage/database.sqlite \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# 7. Installer les dépendances (sans scripts pour éviter les erreurs)
RUN composer install --optimize-autoloader --no-dev --no-scripts

# 8. Exécuter les commandes artisan
RUN php artisan key:generate --force \
    && php artisan config:clear \
    && php artisan migrate --force \
    && php artisan optimize

EXPOSE 80