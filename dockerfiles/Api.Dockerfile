FROM php:7.2-apache

RUN a2enmod rewrite && service apache2 restart

RUN docker-php-ext-install pdo_mysql
