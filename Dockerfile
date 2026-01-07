FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    zip unzip git libpng-dev

RUN docker-php-ext-install pdo pdo_mysql

RUN a2enmod rewrite

WORKDIR /var/www/html

COPY . .

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install --no-dev

RUN chown -R www-data:www-data storage bootstrap/cache

RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf
