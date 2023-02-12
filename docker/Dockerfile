FROM php:7.4-apache


# update/install container (Ubuntu distribution) libraries
# unzip     : for php composer
# libpq-dev : for pgsql php extention
RUN apt-get update -y && apt-get install unzip libpq-dev -y


# installation of php extentions
RUN docker-php-ext-install pdo pdo_pgsql pgsql


# install composer
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN rm composer-setup.php


# installation of dependecies
# NOTE : default workdir of this container is : /var/www/html
COPY ./src/ .
RUN composer install
RUN chmod -R o+w storage/


# change DocumentRoot of default apache VirtualHost (laravel use /path/to/app/public/index.php)
COPY docker/config/apache-app/apache2.conf /etc/apache2/.
COPY docker/config/apache-app/000-default.conf /etc/apache2/sites-available/.
RUN a2enmod rewrite
