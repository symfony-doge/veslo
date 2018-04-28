FROM php:7.2-fpm

WORKDIR /var/www/veslo.it

RUN apt-get update && apt-get install --no-install-recommends -qy \
        curl \
        git \
        libxml2 \
        libxml2-dev \
        libpng-dev \
        libicu-dev \
        libc-client-dev \
        libkrb5-dev \
        libmcrypt-dev \
        libpq-dev

RUN docker-php-ext-install \
        bcmath \
        dom \
        gd \
        iconv \
        intl \
        json \
        mbstring \
        mcrypt \
        phar \
        xml \
        zip \
        soap

RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl && \
    docker-php-ext-install imap

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && \
    docker-php-ext-install pdo pdo_pgsql pgsql

# docker-php-ext-install available extensions:
# bcmath bz2 calendar ctype curl dba dom enchant exif fileinfo filter ftp gd gettext gmp hash iconv imap interbase intl json ldap mbstring mcrypt mysqli oci8 odbc opcache pcntl pdo pdo_dblib pdo_firebird pdo_mysql pdo_oci pdo_odbc pdo_pgsql pdo_sqlite pgsql phar posix pspell readline recode reflection session shmop simplexml snmp soap sockets spl standard sysvmsg sysvsem sysvshm tidy tokenizer wddx xml xmlreader xmlrpc xmlwriter xsl zip
