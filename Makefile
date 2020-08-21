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
	docker --log-level=debug build --pull --file=gateway/docker/prod/nginx.docker --tag=todo-gateway:${IMAGE_TAG} gateway/docker

build-frontend:
	docker --log-level=debug build --pull --file=frontend/docker/prod/nginx.docker --tag=todo-frontend-nginx:${IMAGE_TAG} frontend

build-api:
	docker --log-level=debug build --pull --file=api/docker/prod/php-fpm.docker --tag=todo-api-php-fpm:${IMAGE_TAG} api
	docker --log-level=debug build --pull --file=api/docker/prod/nginx.docker --tag=todo-api-nginx:${IMAGE_TAG} api

try-build:
	REGISTRY=localhost IMAGE_TAG=0 make up

push-gateway:
	docker push todo-gateway:${IMAGE_TAG}

push-frontend:
	docker push todo-frontend-nginx:${IMAGE_TAG}

push-api:
	docker push todo-api-nginx:${IMAGE_TAG}
	docker push todo-api-php-fpm:${IMAGE_TAG}

deploy:
	ssh ${HOST} -p ${PORT} 'rm -rf todo_${BUILD_NUMBER}'
	ssh ${HOST} -p ${PORT} 'mkdir todo_${BUILD_NUMBER}'
	scp -P ${PORT} docker-compose-production.yml ${HOST}:todo_${BUILD_NUMBER}/docker-compose.yml
	ssh ${HOST} -p ${PORT} 'cd todo_${BUILD_NUMBER} && echo "COMPOSE_PROJECT_NAME=todo" >> .env'
	ssh ${HOST} -p ${PORT} 'cd todo_${BUILD_NUMBER} && echo "REGISTRY=${REGISTRY}" >> .env'
	ssh ${HOST} -p ${PORT} 'cd todo_${BUILD_NUMBER} && echo "IMAGE_TAG=${IMAGE_TAG}" >> .env'
	ssh ${HOST} -p ${PORT} 'cd todo_${BUILD_NUMBER} && docker-compose pull'
	ssh ${HOST} -p ${PORT} 'cd todo_${BUILD_NUMBER} && docker-compose down'
	ssh ${HOST} -p ${PORT} 'cd todo_${BUILD_NUMBER} && docker-compose up --build --remove-orphans -d'
	ssh ${HOST} -p ${PORT} 'rm -f todo'
	ssh ${HOST} -p ${PORT} 'ln -sr todo_${BUILD_NUMBER} todo'

rollback:
	ssh ${HOST} -p ${PORT} 'cd todo_${BUILD_NUMBER} && docker-compose pull'
	ssh ${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && docker-compose up --build --remove-orphans -d'
	ssh ${HOST} -p ${PORT} 'rm -f todo'
	ssh ${HOST} -p ${PORT} 'ln -sr todo_${BUILD_NUMBER} todo'