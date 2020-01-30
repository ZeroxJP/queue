FROM php:7.3-fpm

WORKDIR /var/www

COPY . /var/www
RUN chmod -R 755 /var/www/storage
#permisos de usuario (id 1000 por coincidir con el usuario de la maquina host, windows y mac no tienen este "problema")
RUN groupmod -g 1000 www-data
RUN usermod -u 1000 www-data
USER www-data


