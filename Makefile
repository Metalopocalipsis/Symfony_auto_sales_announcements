up: docker-up
down: docker-down
restart: docker-down docker-up
init: docker-down-clear docker-pull docker-build docker-up auto-init

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build

cli:
	docker-compose run --rm auto-php-cli php bin/app.php

auto-init: auto-composer-install auto-migrations

auto-migrations:
	docker-compose run --rm auto-php-cli php bin/console doctrine:migrations:migrate --no-interaction
	
auto-composer-install:
	docker-compose run --rm auto-php-cli composer install
