FROM php:8.1-apache


# install the PHP extensions we need
RUN set -ex; \
    \
    savedAptMark="$(apt-mark showmanual)"; \
    \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        libcurl4-openssl-dev \
        libevent-dev \
        libfreetype6-dev \
        libicu-dev \
        libjpeg-dev \
        libldap2-dev \
        libmcrypt-dev \
        libmemcached-dev \
        libpng-dev \
        libpq-dev \
        libxml2-dev \
        libmagickwand-dev \
        libzip-dev \
        libwebp-dev \
        libgmp-dev \
        gcc \
        make \
        #php-pear \ 
        #php-devel \
        openssl \
        libssl-dev \
        libcurl4-openssl-dev \
    ; \
    \
    docker-php-ext-install -j "$(nproc)" \
        bcmath \
        exif \
        gd \
        intl \
        ldap \
        opcache \
        pcntl \
        pdo_mysql \
        pdo_pgsql \
        zip \
        gmp 
	

  

RUN apt-get update; \
    apt-get install -y emacs-nox mlocate less git 
    



RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY *.sh /
VOLUME /var/www/html/
WORKDIR /var/www/html/
EXPOSE 8000
#COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
#CMD bash -c "composer install && php artisan serve

ENTRYPOINT ["/entrypoint.sh"] 

CMD ["apache2-foreground"]
