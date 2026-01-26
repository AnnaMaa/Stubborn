FROM php:8.2-apache

# Extensions PHP nécessaires à Symfony
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    libicu-dev \
    libonig-dev \
    libpq-dev \
    && docker-php-ext-install intl pdo pdo_mysql opcache

# Activer mod_rewrite
RUN a2enmod rewrite

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copier le projet
WORKDIR /var/www/html
COPY . .

# Installer les dépendances Symfony
RUN composer install --no-dev --optimize-autoloader

# Permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
