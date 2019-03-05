#!/bin/bash

echo 'Stopping docker-compose services...'
docker-compose down

echo 'Clearing docker volumes...'
docker volume rm veslo_db_data veslo_redis_data veslo_mq_data
docker volume prune --force
mkdir -p ./var/postgresql/data
mkdir -p ./var/redis/data

[ ! -d web/tests ] && mkdir -p web/tests && ln -s ../../tests/_output web/tests/_output

echo 'Applying environment variables and configs...'
cp .env.dev.dist .env
cp docker-compose.dev.yml.dist docker-compose.yml
cp app/config/parameters.yml.dist app/config/parameters.yml
cp webpack.env.dev.js.dist ./webpack.env.js
source .env

echo 'Fixing permissions...'
sudo chown `id -nu $HOST_UID`:www-data -R . && sudo chmod g+w -R .

echo 'Building docker-compose services...'
docker-compose build --force-rm

echo 'Updating composer dependencies...'
docker-compose run app composer install --no-interaction

echo 'Updating yarn dependencies...'
docker-compose run --no-deps app yarn install --non-interactive
docker-compose run --no-deps app yarn run build:dev

echo 'Clearing up cache...'
docker-compose run app ./bin/console doctrine:cache:clear-metadata --env=dev
docker-compose run app ./bin/console doctrine:cache:clear-result --env=dev
docker-compose run app ./bin/console doctrine:cache:clear-query --env=dev
docker-compose run app ./bin/console cache:clear --env=dev --no-warmup

echo 'Warming up cache...'
docker-compose run app ./bin/console cache:warmup --env=dev

echo 'Applying migrations...'
docker-compose run app ./bin/console doctrine:migrations:migrate --env=dev --no-interaction --allow-no-migration

echo 'Starting docker-compose services...'
docker-compose up -d

echo 'Clearing old environment...'
docker rm `docker ps -qa --no-trunc --filter "status=exited"`
docker rmi `docker images -f "dangling=true" -q`

docker-compose exec php-fpm php $DEPLOYMENT_PATH/bin/symfony_requirements
docker-compose run --no-deps app ./bin/console security:check
docker-compose exec php-fpm php $DEPLOYMENT_PATH/bin/console about
