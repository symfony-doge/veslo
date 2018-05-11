FROM php:7.2-fpm

ARG HOST_UID
ARG TIMEZONE

WORKDIR /usr/local/etc/php-fpm.d

USER root

# system
RUN apt-get update && apt-get install --no-install-recommends -qy \
        openssl \
        libpq-dev

# php
#RUN docker-php-ext-install \
#        <package>

# docker-php-ext-install available extensions:
# bcmath bz2 calendar ctype curl dba dom enchant exif fileinfo filter ftp gd gettext gmp hash iconv imap interbase intl json ldap mbstring mcrypt mysqli oci8 odbc opcache pcntl pdo pdo_dblib pdo_firebird pdo_mysql pdo_oci pdo_odbc pdo_pgsql pdo_sqlite pgsql phar posix pspell readline recode reflection session shmop simplexml snmp soap sockets spl standard sysvmsg sysvsem sysvshm tidy tokenizer wddx xml xmlreader xmlrpc xmlwriter xsl zip

# xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug
COPY docker-php-ext-xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone && \
    printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini && \
    date

# db
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && \
    docker-php-ext-install pdo pdo_pgsql pgsql

COPY www.conf /usr/local/etc/php-fpm.d/www.conf
COPY php.ini /usr/local/etc/php/conf.d/php.ini

RUN usermod -u ${HOST_UID} www-data

USER www-data

COPY php-fpm-entrypoint.sh /usr/local/bin/php-fpm-entrypoint
ENTRYPOINT ["php-fpm-entrypoint"]

CMD ["php-fpm", "-F"]
