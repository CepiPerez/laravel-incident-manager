FROM php:8.1.4-apache

RUN a2enmod rewrite

RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN apt-get update && apt-get install -y wkhtmltopdf xvfb


ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf