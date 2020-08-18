up: docker-build docker-up api-set-permissions api-composer-install api-migrations-migrate compile generate-keys
compile: frontend-install frontend-build-sass frontend-compile-js
generate-keys: generate-private-key generate-public-key
test: api-load-fixtures api-tests-run

docker-build:
	docker-compose build
docker-up:
	docker-compose up -d

api-set-permissions:
	sudo chmod 777 api/var
	sudo chmod 777 api/var/cache
	sudo chmod 777 storage/public/photos

api-composer-install:
	docker-compose run --rm api-php-cli composer install

api-migrations-diff:
	docker-compose run --rm api-php-cli php bin/console migrations:diff
api-migrations-migrate:
	docker-compose run --rm api-php-cli php bin/console migrations:migrate

api-load-fixtures:
	docker-compose exec api-php-cli composer app fixtures:load
api-tests-run:
	docker-compose run --rm api-php-cli vendor/bin/phpunit

frontend-install:
	docker-compose exec frontend-nodejs npm install
frontend-build-sass:
	docker-compose exec frontend-nodejs npm rebuild node-sass
frontend-compile-js-dev:
	docker-compose exec frontend-nodejs npm run dev
frontend-compile-js:
	docker-compose exec frontend-nodejs npm run build


generate-private-key:
	docker-compose run --rm api-php-cli openssl genrsa -out private.key 2048
generate-public-key:
	docker-compose run --rm api-php-cli openssl rsa -in private.key -pubout -out public.key