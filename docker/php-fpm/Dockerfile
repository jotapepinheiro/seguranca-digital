FROM php:8.1.0-fpm

# Set Environment Variables
ENV DEBIAN_FRONTEND noninteractive

# Replace shell with bash so we can source files
RUN rm /bin/sh && ln -s /bin/bash /bin/sh

# make sure apt is up to date
RUN apt-get update --fix-missing
RUN apt-get -y install gcc make autoconf libc-dev pkg-config libzip-dev

RUN apt-get install -y --no-install-recommends \
	git \
	libmemcached-dev \
	libz-dev \
	libpq-dev \
	libssl-dev libssl-doc libsasl2-dev \
	libmcrypt-dev \
	libxml2-dev \
	zlib1g-dev libicu-dev g++ \
	libldap2-dev libbz2-dev \
	curl libcurl4-openssl-dev \
	libgmp-dev firebird-dev libib-util \
	re2c libpng++-dev \
	libwebp-dev libjpeg-dev libjpeg62-turbo-dev libpng-dev libxpm-dev libvpx-dev libfreetype6-dev \
	libmagick++-dev \
	libmagickwand-dev \
	zlib1g-dev libgd-dev \
	libtidy-dev libxslt1-dev libmagic-dev libexif-dev file \
	sqlite3 libsqlite3-dev libxslt-dev \
	libmhash2 libmhash-dev libc-client-dev libkrb5-dev libssh2-1-dev \
	unzip libpcre3 libpcre3-dev \
	poppler-utils ghostscript libmagickwand-6.q16-dev libsnmp-dev libedit-dev libreadline6-dev libsodium-dev \
	freetds-bin freetds-dev freetds-common libct4 libsybdb5 tdsodbc libreadline-dev librecode-dev libpspell-dev libonig-dev

# install GD
RUN docker-php-ext-configure gd \
	#	--with-png \
	--with-jpeg \
	--with-xpm \
	--with-webp \
	--with-freetype \
	&& docker-php-ext-install -j$(nproc) gd

RUN docker-php-ext-install pdo_mysql mysqli
RUN docker-php-ext-install mbstring zip soap opcache

###########################################################################
# PHP COMPOSER
###########################################################################

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

###########################################################################
# PHP REDIS EXTENSION
###########################################################################

ARG INSTALL_PHPREDIS=false

RUN if [ ${INSTALL_PHPREDIS} = true ]; then \
    pecl install redis \
      && rm -rf /tmp/pear \
      && docker-php-ext-enable redis \
;fi

###########################################################################
# xDebug:
###########################################################################

ARG INSTALL_XDEBUG=false

RUN if [ ${INSTALL_XDEBUG} = true ]; then \
  pecl install xdebug \
  && rm -rf /tmp/pear \
  && docker-php-ext-enable xdebug \
;fi

ADD ./php-ext-xdebug.ini $PHP_INI_DIR/conf.d/

###########################################################################
# Opcache:
###########################################################################

ARG INSTALL_OPCACHE=false

RUN if [ ${INSTALL_OPCACHE} = true ]; then \
    docker-php-ext-install opcache \
;fi

ADD ./php-ext-opcache.ini $PHP_INI_DIR/conf.d/

###########################################################################
# Apcu:
###########################################################################

ARG INSTALL_APCU=false

RUN if [ ${INSTALL_APCU} = true ]; then \
    pecl install apcu \
    && docker-php-ext-enable apcu \
;fi

ADD ./php-ext-apcu.ini $PHP_INI_DIR/conf.d/

# PHP.ini
COPY ./php.ini $PHP_INI_DIR/conf.d/
COPY ./www.conf /usr/local/etc/php-fpm.d/

USER root

# Configure non-root user.
ARG PUID=1000
ARG PGID=1000

RUN groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

WORKDIR "/var/www"

EXPOSE 9000

CMD ["php-fpm", "-F"]
