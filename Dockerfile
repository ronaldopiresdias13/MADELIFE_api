FROM php:7.4-apache
RUN docker-php-ext-install pdo pdo_mysql
# Enable apache rewrite
RUN a2enmod rewrite
COPY php.ini /etc/php/7.4/apache2/php.ini