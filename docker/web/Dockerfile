FROM php:7.2.4-apache

MAINTAINER Aleksandar Rakić <info@aleksandarrakic.rs>

COPY ./docker/web/certs/server.crt /etc/apache2/ssl/server.crt
COPY ./docker/web/certs/server.key /etc/apache2/ssl/server.key

COPY ./docker/web/config/dev.conf /etc/apache2/sites-enabled/000-default.conf

RUN apt-get update \
    && apt-get install -y \
        libzip-dev \
        zlib1g-dev \
        unzip \
        libpng-dev \
        zlib1g-dev \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        zip \
        gd \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer \
    && a2enmod rewrite \
        ssl

WORKDIR /var/www/html/

EXPOSE 443