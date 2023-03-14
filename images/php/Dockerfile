FROM php:7.0.33-apache-jessie

ARG XDEBUG_ENABLE=false
RUN if [ "$XDEBUG_ENABLE" = "true" ]; then \
    pecl install xdebug-2.7.0 && \
    docker-php-ext-enable xdebug

RUN apt-get update && \
    apt-get install -y libcurl4-openssl-dev pkg-config libssl-dev && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

RUN pecl install mongodb-1.7.5 && \
    docker-php-ext-enable mongodb 
RUN pecl config-set php_ini /usr/local/etc/php/conf.d/docker-php-ext-mongodb.ini

RUN docker-php-ext-install pdo_mysql && \
    docker-php-ext-enable pdo_mysql

RUN a2enmod rewrite
