FROM composer:2.2 AS dependencies

WORKDIR /app
COPY . .
RUN composer install --no-interaction --prefer-dist --no-progress --ignore-platform-req=php --no-scripts

FROM php:7.4-apache

COPY --from=composer:2.2 /usr/bin/composer /usr/local/bin/composer

RUN docker-php-ext-install pdo_mysql \
    && a2enmod rewrite

WORKDIR /var/www/html

COPY .docker/apache-vhost.conf /etc/apache2/sites-available/000-default.conf
COPY . .
COPY --from=dependencies /app/vendor ./vendor

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN php artisan package:discover --ansi

CMD ["apache2-foreground"]
