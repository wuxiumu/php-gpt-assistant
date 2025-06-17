FROM php:7.4-apache
WORKDIR /var/www

RUN apt-get update && apt-get install -y libzip-dev zip unzip git \
    && docker-php-ext-install pdo_mysql

COPY . /var/www

RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer \
    && composer install --no-interaction --prefer-dist --optimize-autoloader

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 8000