ARG PHP_VERSION=8.0.8

FROM php:${PHP_VERSION}-fpm-alpine as base
ARG APCU_VERSION=5.1.17

WORKDIR /var/www/html
# persistent / runtime deps
RUN apk add --no-cache \
		acl \
		file \
		gettext \
		git \
	;
RUN set -eux; \
	apk add --no-cache --virtual .build-deps \
		$PHPIZE_DEPS \
		coreutils \
		freetype-dev \
		icu-dev \
		libjpeg-turbo-dev \
		libpng-dev \
		libtool \
		libwebp-dev \
		libzip-dev \
		zlib-dev \
	; \
	\
	docker-php-ext-configure gd; \
	docker-php-ext-configure zip; \
	docker-php-ext-install -j$(nproc) \
		exif \
		gd \
		intl \
		pdo_mysql \
		zip \
	; \
	pecl install \
		apcu-${APCU_VERSION} \
	; \
	pecl clear-cache; \
	docker-php-ext-enable \
		apcu \
		opcache \
	;

COPY docker/php/php.ini /usr/local/etc/php/php.ini
COPY docker/php/php-cli.ini /usr/local/etc/php/php-cli.ini

# copy only specifically what we need
COPY app/.env ./
COPY app/bin bin/
COPY app/config config/
COPY app/public public/
COPY app/src src/

COPY docker/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]

FROM base as composer

COPY app/composer.json app/composer.lock ./

ENV COMPOSER_MEMORY_LIMIT=-1

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=2.1.3

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN set -eux; \
	composer clear-cache
ENV PATH="${PATH}:/root/.composer/vendor/bin"

RUN set -eux; \
	composer install --prefer-dist --no-autoloader --no-scripts --no-progress --no-suggest; \
	composer clear-cache; \
	mkdir -p var/cache var/log; \
	composer dump-autoload --classmap-authoritative; \
	APP_SECRET='' composer run-script post-install-cmd; \
	chmod +x bin/console; sync;

FROM composer as development

FROM base as production

COPY --from=composer vendor vendor/
COPY --from=composer var var/

RUN apk del .build-deps
