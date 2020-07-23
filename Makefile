start: docker-build docker-up migrate compile
compile: build-sass compile-js-dev
-dev
docker-build:
	docker-compose build

docker-up:
	docker-compose up -d

diff:
	docker-compose run --rm api-php-cli php bin/console cycle:diff

migrate:
	docker-compose run --rm api-php-cli php bin/console cycle:run

build-sass:
	docker-compose exec frontend-nodejs npm rebuild node-sass

compile-js-dev:
	docker-compose exec frontend-nodejs npm run dev
