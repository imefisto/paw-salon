FROM phpswoole/swoole:6.0.0-php8.3

WORKDIR /var/www

RUN chown www-data:www-data /var/www

COPY ./composer.json .
COPY ./composer.lock .
RUN composer install --no-dev
COPY . .

COPY ./docker/entrypoint.sh /

USER www-data:www-data

ENTRYPOINT ["/entrypoint.sh"]
