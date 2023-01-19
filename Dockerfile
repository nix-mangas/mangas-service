FROM php:8.1.3-fpm-alpine3.15

ENV NGINX_VERSION 1.20.2
ENV NJS_VERSION   0.7.0
ENV PKG_RELEASE   1

RUN apk update && apk add --no-cache \
    zip \
    unzip \
    dos2unix \
    supervisor \
    libpng-dev \
    libzip-dev \
    freetype-dev \
    $PHPIZE_DEPS \
    libjpeg-turbo-dev

RUN docker-php-ext-install \
    gd \
    pcntl \
    bcmath \
    mysqli \
    pdo_mysql

RUN docker-php-ext-configure gd --with-freetype --with-jpeg

RUN pecl install zip && docker-php-ext-enable zip \
    && pecl install igbinary && docker-php-ext-enable igbinary \
    && yes | pecl install redis && docker-php-ext-enable redis

RUN set -x \
    && nginxPackages=" \
    nginx=${NGINX_VERSION}-r${PKG_RELEASE} \
    nginx-module-xslt=${NGINX_VERSION}-r${PKG_RELEASE} \
    nginx-module-geoip=${NGINX_VERSION}-r${PKG_RELEASE} \
    nginx-module-image-filter=${NGINX_VERSION}-r${PKG_RELEASE} \
    nginx-module-njs=${NGINX_VERSION}.${NJS_VERSION}-r${PKG_RELEASE} \
    " \
    apk add -X "https://nginx.org/packages/alpine/v$(egrep -o '^[0-9]+\.[0-9]+' /etc/alpine-release)/main" --no-cache $nginxPackages

RUN ln -sf /dev/stdout /var/log/nginx/access.log \
    && ln -sf /dev/stderr /var/log/nginx/error.log

COPY ./docker/supervisord.conf /etc/supervisord.conf

EXPOSE 80

CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisord.conf"]
