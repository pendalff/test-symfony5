# Dockerfile
FROM php:7.4-cli-alpine

RUN set -eux; \
    echo "Utils install - start" \
    && apk add --no-cache --update bash \
        git \
        supervisor \
        libzip-dev \
        rabbitmq-c-dev \
    && echo "Utils install - finish" \
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

COPY wait-for-it.sh /usr/bin/wait-for-it
RUN chmod +x /usr/bin/wait-for-it

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

RUN mkdir -p /etc/supervisor.d/conf.d/

COPY supervisor.d/supervisord.conf /etc/supervisor.d/supervisord.conf
COPY supervisor.d/conf.d/* /etc/supervisor.d/conf.d/

RUN mkdir -p /var/log/supervisor

EXPOSE 9001

CMD composer install; wait-for-it database:3310 -- bin/console doctrine:migrations:migrate -n; supervisord -c /etc/supervisor.d/supervisord.conf