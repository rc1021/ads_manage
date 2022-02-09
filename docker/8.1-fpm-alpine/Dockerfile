FROM composer:latest
WORKDIR /app
COPY . /app
RUN composer install

FROM php:8.1-fpm-alpine
RUN apk add --no-cache supervisor
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions gd mysqli pdo_mysql zip intl bcmath soap ldap msgpack igbinary redis memcached pcov xdebug

RUN apk add --no-cache nginx yasm ffmpeg
# RUN install-php-extensions swoole
# RUN apk add --no-cache certbot certbot-nginx
# RUN apk add --no-cache nodejs npm

RUN mkdir -p /run/nginx
COPY docker/8.1-fpm-alpine/nginx.conf /etc/nginx/nginx.conf

RUN mkdir -p /var/www/html
WORKDIR /var/www/html
COPY --from=0 /app /var/www/html
RUN chown -R www-data: /var/www/html

COPY docker/8.1-fpm-alpine/startup.sh /usr/local/bin/startup.sh
COPY docker/8.1-fpm-alpine/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
RUN chmod +x /usr/local/bin/startup.sh

EXPOSE 80

ENTRYPOINT ["sh", "/usr/local/bin/startup.sh"]
