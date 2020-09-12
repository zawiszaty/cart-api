FROM php:7.4.10-fpm-alpine3.11

COPY . /var/www/html

RUN apk update && \
		apk add --no-cache $PHPIZE_DEPS \
		git \
        libzip-dev \
        unzip \
        zip \
        nginx \
        bash \
        libpng-dev \
        postgresql-dev \
        rabbitmq-c rabbitmq-c-dev \
        && pecl install -o -f xdebug-2.9.0 \
  		&& docker-php-ext-install zip \
  		&& docker-php-ext-install pdo pdo_pgsql \
  		&& docker-php-ext-install bcmath sockets pcntl gd \
  		&& docker-php-ext-enable xdebug

COPY ./.docker/nginx/default.conf /etc/nginx/conf.d/default.conf

WORKDIR /var/www/html

ENTRYPOINT ["sh", "/var/www/html/.docker/entrypoint.sh"]