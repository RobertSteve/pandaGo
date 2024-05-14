# Definir variables
DOCKER := laravel-app
UID := $(shell id -u)

# Comandos para manejar los servicios con docker-compose
up:
	U_ID=$(UID) docker-compose up -d

down:
	U_ID=$(UID) docker-compose down

restart:
	U_ID=$(UID) docker-compose down && docker-compose up -d

build:
	U_ID=$(UID) docker-compose build && docker-compose up -d

rebuild:
	U_ID=$(UID) docker-compose down && docker-compose build && docker-compose up -d

# Comandos para interactuar con el contenedor de PHP
laravel:
	U_ID=$(UID) docker-compose exec $(DOCKER) bash

migrate:
	U_ID=$(UID) docker-compose exec $(DOCKER) php artisan migrate

m-rollback:
	U_ID=$(UID) docker-compose exec $(DOCKER) php artisan migrate:rollback

m-refresh:
	U_ID=$(UID) docker-compose exec $(DOCKER) php artisan migrate:refresh

seed:
	U_ID=$(UID) docker-compose exec $(DOCKER) php artisan db:seed

composer-install:
	U_ID=$(UID) docker-compose exec $(DOCKER) composer install

composer-update:
	U_ID=$(UID) docker-compose exec $(DOCKER) composer update

composer-dump:
	U_ID=$(UID) docker-compose exec $(DOCKER) composer dump-autoload

test:
	U_ID=$(UID) docker-compose exec $(DOCKER) php artisan test

cache-clear:
	U_ID=$(UID) docker-compose exec $(DOCKER) php artisan cache:clear

config-cache:
	U_ID=$(UID) docker-compose exec $(DOCKER) php artisan config:cache

.PHONY: up down restart build rebuild php migrate migrate-rollback composer-install composer-update composer-dump test cache-clear config-cache
