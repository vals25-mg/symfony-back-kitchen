# Utilisez une image PHP avec Apache (pour Symfony)
FROM php:8.2-apache

# Définissez le répertoire de travail
WORKDIR /var/www/html

# Installez les dépendances système nécessaires
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

# Installez Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Créez un utilisateur non-root pour exécuter Composer
RUN useradd -m symfony_user
USER symfony_user

# Copiez les fichiers de votre application Symfony
COPY --chown=symfony_user:symfony_user . .

# Installez les dépendances PHP (en mode production)
RUN composer install --no-dev --optimize-autoloader

# Revenez à l'utilisateur root pour configurer Apache
USER root

# Configurez Apache pour utiliser le répertoire public de Symfony
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Activez le module Apache rewrite (nécessaire pour Symfony)
RUN a2enmod rewrite

# Définissez les permissions pour le cache et les logs
RUN chown -R www-data:www-data /var/www/html/var

# Exposez le port 80 (port par défaut pour Apache)
EXPOSE 80

# Commande pour démarrer Apache
CMD ["apache2-foreground"]
