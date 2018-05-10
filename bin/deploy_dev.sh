#!/bin/sh

echo 'Stopping docker-compose services...'
cp .env.dist .env
cp docker-compose.yml.dist docker-compose.yml
docker-compose down

mkdir -p ./var/postgresql/data

echo 'Fixing permissions...'
sudo chown www-data:www-data -R . && sudo chmod g+w -R .

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
docker-compose run app ./bin/console doctrine:migrations:migrate --env=dev --no-interaction

echo 'Clearing and warming up cache...'
docker-compose run --no-deps app ./bin/console cache:clear --env=dev
docker-compose run --no-deps app ./bin/console doctrine:cache:clear-metadata --env=dev
docker-compose run --no-deps app ./bin/console doctrine:cache:clear-result --env=dev
docker-compose run --no-deps app ./bin/console doctrine:cache:clear-query --env=dev

echo 'Starting docker-compose services...'
docker-compose up -d
