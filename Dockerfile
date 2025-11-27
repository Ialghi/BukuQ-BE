# Dockerfile - Laravel (PHP 8.3 + Apache)
FROM php:8.3-apache

# system deps
RUN apt-get update && apt-get install -y \
    git unzip zip libzip-dev libonig-dev libicu-dev libxml2-dev \
    libssl-dev wget ca-certificates \
  && docker-php-ext-install pdo_mysql mbstring intl xml zip

# enable apache mod_rewrite
RUN a2enmod rewrite

# install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# copy source (do not copy vendor/node_modules because .dockerignore excludes them)
COPY . /var/www/html

# copy certs (if any) to a stable path
RUN mkdir -p /opt/certs && cp -R certs/* /opt/certs/ || true

# composer install (production)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# set permissions for Laravel (adjust if needed)
RUN chown -R www-data:www-data /var/www/html \
 && chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

# start apache
CMD ["apache2-foreground"]
