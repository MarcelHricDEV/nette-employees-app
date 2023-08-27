FROM php:8.1-fpm

RUN apt update \
        && apt install -y \
            g++ \
            libicu-dev \
            libpq-dev \
            libzip-dev \
            zip \
            zlib1g-dev \
        && docker-php-ext-install \
            opcache 

RUN apt install -y nodejs npm

WORKDIR /var/www/employees-app

COPY . .

RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer install --no-progress --optimize-autoloader

RUN npm ci

EXPOSE 9000
CMD ["php-fpm"]
