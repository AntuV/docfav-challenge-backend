FROM php:7.4-apache

COPY . .
RUN chown -R www-data:www-data /var/www
RUN chmod -R 755 /var/www
RUN usermod -u 1000 www-data

USER 1000

EXPOSE 80
