#!/bin/sh
set -e

# group write access for local development
umask 0002

exec "$@"
