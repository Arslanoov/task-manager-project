start: docker-build docker-up migrate compile generate-keys
compile: build-sass compile-js-dev
generate-keys: generate-private-key generate-public-key

docker-build:
	docker-compose build

docker-up:
	docker-compose up -d

diff:
	docker-compose run --rm api-php-cli php bin/console migrations:diff

migrate:
	docker-compose run --rm api-php-cli php bin/console migrations:migrate

build-sass:
	docker-compose exec frontend-nodejs npm rebuild node-sass

compile-js-dev:
	docker-compose exec frontend-nodejs npm run dev

generate-private-key:
	docker-compose run --rm api-php-cli openssl genrsa -out private.key 2048

generate-public-key:
	docker-compose run --rm api-php-cli openssl rsa -in private.key -pubout -out public.key