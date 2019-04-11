#!/bin/bash
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

echo 'Applying environment variables and configs...'
cp .env.dev.dist .env
cp docker-compose.dev.yml.dist docker-compose.yml
cp app/config/parameters.yml.dist app/config/parameters.yml
cp webpack.env.dev.js.dist ./webpack.env.js
source .env

mkdir -p ./var/postgresql/data
mkdir -p ./var/redis/data

echo 'Fixing permissions...'
sudo chown 777 -R .

echo 'Building docker-compose services...'
docker-compose build --force-rm

echo 'Updating composer dependencies...'
# app -d memory_limit=-1 /usr/local/bin/composer
docker-compose run --rm app composer install --no-interaction

echo 'Updating yarn dependencies...'
docker-compose run --rm --no-deps app yarn install --non-interactive
docker-compose run --rm --no-deps app yarn run build:dev

echo 'Applying migrations...'
docker-compose run --rm app ./bin/console doctrine:migrations:migrate --env=dev --no-interaction --allow-no-migration

echo 'Updating build version...'
rm -f VERSION && docker-compose run --rm --no-deps app bin/console app:version:bump --build=`date +"%Y%m%d%H%M%S"`

echo 'Starting docker-compose services...'
docker-compose up -d

docker-compose exec php-fpm php $DEPLOYMENT_PATH/bin/console about
