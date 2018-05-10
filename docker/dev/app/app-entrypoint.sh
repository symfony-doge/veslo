#!/bin/sh

# group write access for local development
umask 0002

docker-php-entrypoint
