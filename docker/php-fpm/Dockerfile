FROM php:7.4-fpm-alpine

RUN apk update \
    && apk add  --no-cache git curl libmcrypt libmcrypt-dev openssh-client icu-dev \
    libxml2-dev freetype-dev libpng-dev libjpeg-turbo-dev g++ make autoconf \
    && set -ex \
    && apk --no-cache add postgresql-dev \
    && docker-php-source extract \
    && docker-php-source delete \
    && docker-php-ext-install soap intl \
    && docker-php-ext-install pdo pdo_pgsql \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && rm -rf /tmp/* \
    && docker-php-source extract \
    && curl -L -o /tmp/redis.tar.gz https://github.com/phpredis/phpredis/archive/3.0.0.tar.gz \
    && tar xfz /tmp/redis.tar.gz \
    && rm -r /tmp/redis.tar.gz \
    && mv phpredis-3.0.0 /usr/src/php/ext/redis \
    && docker-php-ext-install redis

CMD ["php-fpm", "-F"]

WORKDIR /data/app

EXPOSE 9000