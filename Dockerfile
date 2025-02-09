# Utiliser une image PHP avec Apache
FROM php:8.2-apache

# Définir le répertoire de travail
WORKDIR /var/www/html

# Installer les dépendances système nécessaires
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install \
    zip \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier les fichiers Symfony
COPY . .

# Vérifier et supprimer les fichiers .env
# RUN rm -f .env .env.local

# Définir les variables d'environnement
ENV APP_ENV=prod
ENV APP_SECRET=980b726dfaf5489b46c341d937b76e72
ENV DATABASE_URL="postgresql://avnadmin:AVNS_fDJfCnVhKRHNpeaM66d@pg-39cad0bd-rvalisoa3-28cc.i.aivencloud.com:14567/defaultdb?sslmode=require"

# Vérifier que les variables sont bien chargées
RUN export APP_ENV=prod && printenv | grep APP_ENV

# Installer les dépendances Symfony
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Corriger les permissions avant le cache
# RUN mkdir -p var/cache var/log && chmod -R 777 var/cache var/log

# Créer les répertoires nécessaires et corriger les permissions
RUN mkdir -p var/cache var/log \
    && chown -R www-data:www-data var/cache var/log \
    && chmod -R 775 var/cache var/log

# Désactiver Dotenv si nécessaire dans config/bootstrap.php
# (voir instructions ci-dessus)

# Générer le cache Symfony
RUN php bin/console cache:clear --env=prod --no-warmup || true

# Configurer Apache pour Symfony
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Activer le module Apache rewrite
RUN a2enmod rewrite

# Exposer le port 80
EXPOSE 80

# Lancer Apache
CMD ["apache2-foreground"]
