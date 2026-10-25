FROM ghcr.io/imefisto/swoole:6.1-php8.3-alpine

WORKDIR /var/www

RUN chown www-data:www-data /var/www

COPY ./composer.json .
COPY ./composer.lock .
RUN composer install --no-dev
COPY . .

COPY ./docker/entrypoint.sh /

USER www-data:www-data

ENTRYPOINT ["/entrypoint.sh"]
