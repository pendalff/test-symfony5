# Dockerfile
FROM php:7.4-fpm-alpine

RUN set -eux; \
    echo "Utils install - start" \
    && apk add --no-cache --update bash \
        git \
        libzip-dev \
        rabbitmq-c-dev \
    && echo "Extensions install - start" \
    # PDO Zip Sockets Amqp
    && docker-php-ext-install \
        pdo_mysql \
        zip \
        sockets \
    # Amqp
    && apk add --no-cache --update --virtual .phpize-deps $PHPIZE_DEPS \
    && pecl install -o -f amqp \
    && docker-php-ext-enable amqp \
    && apk del .phpize-deps \
    && echo "Extensions install - finish"

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

CMD composer install; php-fpm

EXPOSE 9000
