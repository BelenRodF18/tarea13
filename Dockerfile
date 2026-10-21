FROM php:8.2-apache

RUN a2enmod rewrite \
    && docker-php-ext-install pdo pdo_mysql \
    && sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

WORKDIR /var/www/html
