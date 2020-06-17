
# Copyright (C) 2019 Pavel Petrov <itnelo@gmail.com>
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program. If not, see <http://www.gnu.org/licenses/>.

ARG PHP_VERSION

FROM php:${PHP_VERSION}-fpm

ARG HOST_UID
ARG TIMEZONE

WORKDIR /usr/local/etc/php-fpm.d

USER root

# system
RUN apt-get update && apt-get install --no-install-recommends -qy \
        openssl \
        libpq-dev \
        # intl
        zlib1g-dev libicu-dev g++

RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone && \
    printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini && \
    date

# php
#RUN docker-php-ext-install \
#        <package>

# docker-php-ext-install available extensions:
# bcmath bz2 calendar ctype curl dba dom enchant exif fileinfo filter ftp gd gettext gmp hash iconv imap interbase
# intl json ldap mbstring mysqli oci8 odbc opcache pcntl pdo pdo_dblib pdo_firebird pdo_mysql pdo_oci pdo_odbc pdo_pgsql
# pdo_sqlite pgsql phar posix pspell readline recode reflection session shmop simplexml snmp soap sockets sodium spl
# standard sysvmsg sysvsem sysvshm tidy tokenizer wddx xml xmlreader xmlrpc xmlwriter xsl zend_test zip

# xdebug
RUN pecl install xdebug-beta && docker-php-ext-enable xdebug
COPY docker-php-ext-xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# igbinary
# https://github.com/igbinary/igbinary
RUN pecl install igbinary && docker-php-ext-enable igbinary
COPY docker-php-ext-igbinary.ini /usr/local/etc/php/conf.d/docker-php-ext-igbinary.ini

# intl
RUN docker-php-ext-configure intl && \
    docker-php-ext-install intl

# db
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && \
    docker-php-ext-install pdo_pgsql pgsql

# redis
RUN pecl bundle -d /usr/src/php/ext redis && \
    docker-php-ext-configure /usr/src/php/ext/redis --enable-redis-igbinary && \
    docker-php-ext-install redis
COPY docker-php-ext-redis.ini /usr/local/etc/php/conf.d/docker-php-ext-redis.ini

COPY www.conf /usr/local/etc/php-fpm.d/www.conf
COPY php.ini /usr/local/etc/php/conf.d/php.ini

RUN usermod -u ${HOST_UID} www-data

USER www-data

COPY php-fpm-entrypoint.sh /usr/local/bin/php-fpm-entrypoint
ENTRYPOINT ["php-fpm-entrypoint"]

CMD ["php-fpm", "-F"]
