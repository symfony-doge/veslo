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

# old version of docker used by scrutinizer can't manage ARGs before FROM, so we need this hack...
docker -v
echo 'Fixing compatibility with legacy docker versions...'
cp -r docker/dev docker/scrutinizer
sed -i 's/${APP_ENV}/scrutinizer/g' docker-compose.yml
declare -a placeholders=("PHP_VERSION" "NGINX_VERSION" "REDIS_VERSION" "RABBITMQ_VERSION")
# removing unsupported ARGs from build context
for placeholder in "${placeholders[@]}"
do
    sed -i '/'"$placeholder"':/ d' docker-compose.yml
done
# removing unsupported ARGs from each dockerfile
for dockerfile in `find ./docker/scrutinizer -name 'Dockerfile'`
do
    for placeholder in "${placeholders[@]}"
    do
        sed -i '/ARG.*'"$placeholder"'/,+1 d' $dockerfile
        sed -i 's/${'"$placeholder"'}/'"${!placeholder}"'/g' $dockerfile
    done
done

mkdir -p ./var/postgresql/data
mkdir -p ./var/redis/data
mkdir -p ./var/rabbitmq

echo 'Fixing permissions...'
sudo chown 775 -R .

echo 'Building docker-compose services...'
docker-compose build --force-rm

echo 'Starting docker-compose services...'
docker volume rm build_db_data build_redis_data build_mq_data
docker-compose up -d db redis mq php-fpm nginx

echo 'Updating composer dependencies...'
docker-compose run --rm --no-deps app composer install --no-interaction --no-scripts

echo 'Updating yarn dependencies...'
docker-compose run --rm --no-deps app yarn install --non-interactive
docker-compose run --rm --no-deps app yarn run build:dev

echo 'Applying migrations...'
docker-compose run --rm --no-deps app ./bin/console doctrine:migrations:migrate --env=dev --no-interaction --allow-no-migration

echo 'Updating build version...'
rm -f VERSION && docker-compose run --rm --no-deps app bin/console app:version:bump --build=`date +"%Y%m%d%H%M%S"`
