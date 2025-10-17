FROM php:8.3-cli

RUN apt-get update && apt-get install -y --no-install-recommends \
    curl \
    unzip \
    libpq-dev \
    libonig-dev \
    libssl-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    libicu-dev \
    libzip-dev \
    libpng-dev \
    && docker-php-ext-install \
    pdo_pgsql \
    pgsql \
    opcache \
    intl \
    zip \
    bcmath \
    gd \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug\
    && apt-get autoremove -y && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY --from=composer/composer:2-bin /composer /usr/bin/composer
    
WORKDIR /var/www/html/api

COPY ./api/composer.json ./api/composer.lock ./

COPY ./api ./

RUN composer install

RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]