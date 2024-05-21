FROM php:8.1-fpm-alpine

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html/public

RUN composer require phpmailer/phpmailer