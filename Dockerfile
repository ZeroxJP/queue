FROM php:7.3-fpm

WORKDIR /var/www
# extension para mysql (mariaDb)
RUN docker-php-ext-install pdo_mysql

RUN apt-get update
ENV GEARMAN_VERSION=2.0.6
RUN set -xe \
  ; apt-get -y install libgearman-dev \
  ; TMPDIR=$(mktemp -d) \
  ; cd $TMPDIR \
  ; curl -L https://github.com/wcgallego/pecl-gearman/archive/gearman-${GEARMAN_VERSION}.tar.gz \
  | tar xzv --strip 1 \
  ; phpize \
  ; ./configure \
  ; make -j$(nproc) \
  ; make install \
  ; cd - \
  ; rm -r $TMPDIR \
  ; docker-php-ext-enable gearman

COPY . /var/www
#persmisos usados por laravel en carpeta storage
RUN chmod -R 755 /var/www/storage

#permisos de usuario (id 1000 por coincidir con el usuario de la maquina host (para saber el id del host "id -u"), windows y mac no tienen este problema)
RUN groupmod -g 1000 www-data
RUN usermod -u 1000 www-data
USER www-data


