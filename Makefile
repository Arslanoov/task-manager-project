up: docker-clear docker-pull docker-build docker-up api-set-permissions api-composer-install api-migrations-migrate compile generate-keys api-set-keys-permissions
compile: frontend-install frontend-build-sass frontend-compile-js
generate-keys: generate-private-key generate-public-key
test: api-load-fixtures api-tests-run
build: build-gateway build-frontend build-api
push: push-gateway push-frontend push-api

docker-build:
	docker-compose build
docker-up:
	docker-compose up -d
docker-down:
	docker-compose down --remove-orphans
docker-clear:
	docker-compose down --remove-orphans -v
docker-pull:
	docker-compose pull
docker-remove:
	docker-compose down --remove-orphans
	sudo rm -rf api/var/docker

api-set-permissions:
	sudo chmod -R 777 api/var
	sudo chmod 777 storage/public/photos
api-set-keys-permissions:
	chmod 755 api/public.key
	chmod 755 api/private.key

api-composer-install:
	docker-compose run --rm api-php-cli composer install

api-migrations-diff:
	docker-compose run --rm api-php-cli php bin/console migrations:diff
api-migrations-migrate:
	docker-compose run --rm api-php-cli php bin/console migrations:migrate

api-generate-docs:
	docker-compose run --rm api-php-cli php bin/console api:generate:docs

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


build-gateway:
	docker --log-level=debug build --pull --file=gateway/docker/prod/nginx.docker --tag=${REGISTRY}/todo-gateway:${IMAGE_TAG} gateway/docker

build-frontend:
	docker --log-level=debug build --pull --file=frontend/docker/prod/nginx.docker --tag=${REGISTRY}/todo-frontend-nginx:${IMAGE_TAG} frontend

build-api:
	docker --log-level=debug build --pull --file=api/docker/prod/php-fpm.docker --tag=${REGISTRY}/todo-api-php-fpm:${IMAGE_TAG} api
	docker --log-level=debug build --pull --file=api/docker/prod/nginx.docker --tag=${REGISTRY}/todo-api-nginx:${IMAGE_TAG} api

try-build:
	REGISTRY=localhost IMAGE_TAG=0 make up

push-gateway:
	docker push ${REGISTRY}/todo-gateway:${IMAGE_TAG}

push-frontend:
	docker push ${REGISTRY}/todo-frontend-nginx:${IMAGE_TAG}

push-api:
	docker push ${REGISTRY}/todo-api-nginx:${IMAGE_TAG}
	docker push ${REGISTRY}/todo-api-php-fpm:${IMAGE_TAG}