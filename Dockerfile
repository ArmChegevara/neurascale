FROM php:8.2-apache

# Installation des extensions et outils nécessaires
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        unzip \
        git \
        libzip-dev \
    && docker-php-ext-install opcache zip \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Installation de Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Répertoire de travail principal
WORKDIR /var/www

# Copie des fichiers Composer
COPY composer.json composer.lock* /var/www/

# Installation des dépendances PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copie des fichiers publics du site
COPY public/ /var/www/html/

# Copie des fichiers inclus et de configuration hors du dossier public
COPY includes/ /var/www/includes/
COPY config/ /var/www/config/

# Copie de la configuration Apache
COPY docker/apache-vhost.conf /etc/apache2/sites-available/000-default.conf

# Définition des droits sur les fichiers
RUN chown -R www-data:www-data /var/www/html /var/www/includes /var/www/config /var/www/vendor

# Port HTTP exposé
EXPOSE 80