FROM php:8.3-apache-bookworm

RUN docker-php-ext-install pdo_mysql

COPY src/ /var/www/html/

EXPOSE 80