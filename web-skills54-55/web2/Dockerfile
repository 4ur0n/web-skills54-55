FROM php:8.2.28-apache

RUN apt-get update && apt-get install -y \
        libssl-dev pkg-config git unzip zip curl \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY . /var/www/html

RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --ignore-platform-req=ext-mongodb --no-dev --optimize-autoloader

EXPOSE 80

CMD ["apache2-foreground"]

