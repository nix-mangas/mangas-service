FROM php:8.2-fpm
RUN apt-get update && apt-get install -y nginx libonig-dev
RUN docker-php-ext-configure oniguruma --with-oniguruma=/usr/lib
RUN docker-php-ext-install oniguruma
RUN echo "extension=oniguruma.so" >> /usr/local/etc/php/conf.d/oniguruma.ini
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd sockets
RUN pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis

COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html
RUN composer install

COPY nginx.conf /etc/nginx/conf.d/default.conf

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
