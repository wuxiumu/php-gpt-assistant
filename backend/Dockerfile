FROM php:7.4-apache
WORKDIR /var/www

RUN apt-get update && apt-get install -y libzip-dev zip unzip git \
    && docker-php-ext-install pdo_mysql \
    && a2enmod rewrite \
    && echo 'Listen 8000' > /etc/apache2/ports.conf \
    && rm -rf /var/lib/apt/lists/*

COPY docker/apache-vhost.conf /etc/apache2/sites-available/000-default.conf
COPY . /var/www
COPY docker/entrypoint.sh /entrypoint.sh

RUN chmod +x /entrypoint.sh \
    && curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer \
    && composer install --no-interaction --prefer-dist --optimize-autoloader --no-scripts \
    && mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 8000
ENTRYPOINT ["/entrypoint.sh"]
CMD ["apache2-foreground"]
