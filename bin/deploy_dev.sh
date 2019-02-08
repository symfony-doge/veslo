#!/bin/bash

echo 'Stopping docker-compose services...'
docker-compose down
cp .env.dist .env
cp docker-compose.yml.dist docker-compose.yml

mkdir -p ./var/postgresql/data
mkdir -p ./var/redis/data

echo 'Fixing permissions...'
source .env
sudo chown `id -nu $HOST_UID`:www-data -R . && sudo chmod g+w -R .

echo 'Copying latest configs and parameters...'
cp ./app/config/parameters.yml.dist ./app/config/parameters.yml

echo 'Building docker-compose services...'
docker-compose build --force-rm

echo 'Updating composer dependencies...'
docker-compose run app composer install --no-interaction

echo 'Updating yarn dependencies...'
docker-compose run --no-deps app yarn install --non-interactive
docker-compose run --no-deps app yarn run build:dev

echo 'Applying migrations...'
docker-compose run app ./bin/console doctrine:migrations:migrate --env=dev --no-interaction --allow-no-migration

echo 'Clearing up cache...'
docker-compose run app ./bin/console doctrine:cache:clear-metadata --env=dev
docker-compose run app ./bin/console doctrine:cache:clear-result --env=dev
docker-compose run app ./bin/console doctrine:cache:clear-query --env=dev
docker-compose run app ./bin/console cache:clear --env=dev --no-warmup

echo 'Warming up cache...'
docker-compose run app ./bin/console cache:warmup --env=dev

echo 'Starting docker-compose services...'
docker-compose up -d

echo 'Clearing old environment...'
docker rm `docker ps -qa --no-trunc --filter "status=exited"`
docker rmi `docker images -f "dangling=true" -q`

docker-compose run --no-deps php-fpm $DEPLOYMENT_PATH/bin/symfony_requirements
docker-compose run --no-deps app ./bin/console security:check
docker-compose run --no-deps php-fpm $DEPLOYMENT_PATH/bin/console about
