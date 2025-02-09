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
RUN chown -R www-data:www-data /var/www/html

# Définir les variables d'environnement
ENV APP_ENV=prod
ENV APP_SECRET=your_secret_key
ENV DATABASE_URL=mysql://user:password@database_host/database_name

# Supprimer le fichier .env pour éviter l'erreur en production
RUN rm -f .env

# Installer les dépendances Symfony
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Vérifier si l'environnement est bien défini
RUN printenv | grep APP_ENV

# Corriger les permissions avant le cache
RUN mkdir -p var/cache var/log && chown -R www-data:www-data var

# Générer le cache Symfony avec no-warmup pour éviter les erreurs DB
USER www-data
RUN php bin/console cache:clear --env=prod --no-warmup
USER root

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
