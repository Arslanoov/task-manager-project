up: docker-clear docker-pull docker-build docker-up api-init frontend-init check cucumber-set-permissions test
compile: frontend-install frontend-build-sass frontend-compile-js
generate-keys: generate-private-key generate-public-key
test: api-tests-run frontend-tests-run e2e
check: api-check cucumber-check
build: build-gateway build-frontend build-api
push: push-gateway push-frontend push-api
e2e: cucumber-install e2e-tests-run
api-init: api-set-permissions api-composer-install api-migrations-migrate generate-keys api-set-keys-permissions
frontend-init: compile
testing-build: testing-build-gateway testing-build-cucumber
test-e2e:
	make cucumber-clear
	- make e2e
	make cucumber-report

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
api-composer-update:
	docker-compose run --rm api-php-cli composer update

api-migrations-diff:
	docker-compose run --rm api-php-cli php bin/console.php migrations:diff
api-migrations-migrate:
	docker-compose run --rm api-php-cli php bin/console.php migrations:migrate

api-generate-docs:
	docker-compose run --rm api-php-cli php bin/console.php api:generate:docs

api-tests-run:
	docker-compose run --rm api-php-cli vendor/bin/phpunit --colors=always
api-tests-unit:
	docker-compose run --rm api-php-cli vendor/bin/phpunit --colors=always --testsuite=Unit
api-tests-functional:
	docker-compose run --rm api-php-cli vendor/bin/phpunit --colors=always --testsuite=Functional
api-tests-coverage-unit:
	docker-compose run --rm api-php-cli vendor/bin/phpunit --coverage-clover var/clover.xml --coverage-html var/coverage --testsuite=Unit

api-check:
	docker-compose run --rm api-php-cli composer lint
	docker-compose run --rm api-php-cli composer cs-check
	docker-compose run --rm api-php-cli composer psalm

frontend-install:
	docker-compose exec frontend-nodejs npm install
frontend-build-sass:
	docker-compose exec frontend-nodejs npm rebuild node-sass
frontend-compile-js-dev:
	docker-compose exec frontend-nodejs npm run dev
frontend-compile-js:
	docker-compose exec frontend-nodejs npm run build
frontend-tests-run:
	docker-compose exec frontend-nodejs npm run test

cucumber-install:
	docker-compose run --rm cucumber yarn install
cucumber-set-permissions:
	docker-compose run --rm cucumber chmod 777 /var/www/cucumber/var

cucumber-check:
	docker-compose run --rm cucumber yarn lint
cucumber-check-and-fix:
	docker-compose run --rm cucumber yarn lint-fix
cucumber-report:
	docker-compose run --rm cucumber yarn report
e2e-tests-run:
	docker-compose run --rm cucumber yarn e2e

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
	docker --log-level=debug build --pull --file=api/docker/prod/php-cli.docker --tag=${REGISTRY}/todo-api-php-cli:${IMAGE_TAG} api
	docker --log-level=debug build --pull --file=api/docker/prod/postgres.docker --tag=${REGISTRY}/todo-api-postgres:${IMAGE_TAG} api
	docker --log-level=debug build --pull --file=storage/docker/nginx.docker --tag=${REGISTRY}/todo-api-storage:${IMAGE_TAG} storage

testing-build-gateway:
	docker --log-level=debug build --pull --file=gateway/docker/test/nginx.docker --tag=${REGISTRY}/todo-testing-gateway:${IMAGE_TAG} gateway/docker
testing-build-cucumber:
	docker --log-level=debug build --pull --file=cucumber/docker/test/nginx.docker --tag=${REGISTRY}/todo-cucumber:${IMAGE_TAG} cucumber

try-testing: try-build try-testing-build try-testing-init try-testing-e2e try-testing-down-clear
try-testing-build:
	REGISTRY=localhost IMAGE_TAG=0 make testing-build
testing-init:
	COMPOSE_PROJECT_NAME=testing docker-compose -f docker-compose-testing.yml up -d
	COMPOSE_PROJECT_NAME=testing docker-compose -f docker-compose-testing.yml run --rm api-php-cli wait-for-it api-postgres:5432 -t 60
	COMPOSE_PROJECT_NAME=testing docker-compose -f docker-compose-testing.yml run --rm api-php-cli php bin/console.php migrations:migrate --no-interaction
testing-down-clear:
	COMPOSE_PROJECT_NAME=testing docker-compose -f docker-compose-testing.yml down -v --remove-orphans
testing-e2e:
	COMPOSE_PROJECT_NAME=testing docker-compose -f docker-compose-testing.yml run --rm cucumber-node-cli yarn e2e
try-build:
	REGISTRY=localhost IMAGE_TAG=0 make up
try-testing-init:
	REGISTRY=localhost IMAGE_TAG=0 make testing-init
try-testing-down-clear:
	REGISTRY=localhost IMAGE_TAG=0 make testing-down-clear
try-testing-e2e:
	REGISTRY=localhost IMAGE_TAG=0 make testing-e2e

push-gateway:
	docker push ${REGISTRY}/todo-gateway:${IMAGE_TAG}

push-frontend:
	docker push ${REGISTRY}/todo-frontend-nginx:${IMAGE_TAG}

push-api:
	docker push ${REGISTRY}/todo-api-nginx:${IMAGE_TAG}
	docker push ${REGISTRY}/todo-api-php-fpm:${IMAGE_TAG}
	docker push ${REGISTRY}/todo-api-php-cli:${IMAGE_TAG}
	docker push ${REGISTRY}/todo-api-postgres:${IMAGE_TAG}
	docker push ${REGISTRY}/todo-api-storage:${IMAGE_TAG}

deploy:
	ssh ${HOST} -p ${PORT} 'rm -rf todo_${BUILD_NUMBER}'
	ssh ${HOST} -p ${PORT} 'mkdir todo_${BUILD_NUMBER}'
	scp -P ${PORT} docker-compose-production.yml ${HOST}:todo_${BUILD_NUMBER}/docker-compose.yml
	ssh ${HOST} -p ${PORT} 'cd todo_${BUILD_NUMBER} && echo "COMPOSE_PROJECT_NAME=todo" >> .env'
	ssh ${HOST} -p ${PORT} 'cd todo_${BUILD_NUMBER} && echo "REGISTRY=${REGISTRY}" >> .env'
	ssh ${HOST} -p ${PORT} 'cd todo_${BUILD_NUMBER} && echo "IMAGE_TAG=${IMAGE_TAG}" >> .env'
	ssh ${HOST} -p ${PORT} 'cd todo_${BUILD_NUMBER} && echo "SENTRY_DSN=${SENTRY_DSN}" >> .env'
	ssh ${HOST} -p ${PORT} 'cd todo_${BUILD_NUMBER} && echo "MAILER_FROM=${MAILER_FROM}" >> .env'
	ssh ${HOST} -p ${PORT} 'cd todo_${BUILD_NUMBER} && echo "MAILER_HOST=${MAILER_HOST}" >> .env'
	ssh ${HOST} -p ${PORT} 'cd todo_${BUILD_NUMBER} && echo "MAILER_PORT=${MAILER_PORT}" >> .env'
	ssh ${HOST} -p ${PORT} 'cd todo_${BUILD_NUMBER} && echo "MAILER_USER=${MAILER_USER}" >> .env'
	ssh ${HOST} -p ${PORT} 'cd todo_${BUILD_NUMBER} && echo "MAILER_PASSWORD=${MAILER_PASSWORD}" >> .env'
	ssh ${HOST} -p ${PORT} 'cd todo_${BUILD_NUMBER} && echo "MAILER_FROM_EMAIL=${MAILER_FROM_EMAIL}" >> .env'
	ssh ${HOST} -p ${PORT} 'cd todo_${BUILD_NUMBER} && echo "DB_PASSWORD=${DB_PASSWORD}" >> .env'
	ssh ${HOST} -p ${PORT} 'cd todo_${BUILD_NUMBER} && echo "FRONTEND_URL=${FRONTEND_URL}" >> .env'
	ssh ${HOST} -p ${PORT} 'cd todo_${BUILD_NUMBER} && echo "DEBUG=${DEBUG}" >> .env'
	ssh ${HOST} -p ${PORT} 'cd todo_${BUILD_NUMBER} && echo "ENV=${ENV}" >> .env'
	ssh ${HOST} -p ${PORT} 'cd todo_${BUILD_NUMBER} && echo "VUE_APP_API_URL=${VUE_APP_API_URL}" >> .env.production'
	ssh ${HOST} -p ${PORT} 'cd todo_${BUILD_NUMBER} && docker-compose pull'
	ssh ${HOST} -p ${PORT} 'cd todo_${BUILD_NUMBER} && docker-compose up --build -d api-postgres api-php-cli'
	ssh ${HOST} -p ${PORT} 'cd todo_${BUILD_NUMBER} && docker-compose run api-php-cli wait-for-it api-postgres:5432 -t 60'
	ssh ${HOST} -p ${PORT} 'cd todo_${BUILD_NUMBER} && docker-compose run api-php-cli php bin/console.php migrations:migrate --no-interaction'
	scp -P ${PORT} api/public.key ${HOST}:todo_${BUILD_NUMBER}/public.key
	scp -P ${PORT} api/private.key ${HOST}:todo_${BUILD_NUMBER}/private.key
	ssh ${HOST} -p ${PORT} 'cd todo_${BUILD_NUMBER} && chmod 777 public.key'
	ssh ${HOST} -p ${PORT} 'cd todo_${BUILD_NUMBER} && chmod 777 private.key'
	ssh ${HOST} -p ${PORT} 'cd todo_${BUILD_NUMBER} && docker cp public.key todo_api-php-fpm_1:/var/www/api/public.key'
	ssh ${HOST} -p ${PORT} 'cd todo_${BUILD_NUMBER} && docker cp private.key todo_api-php-fpm_1:/var/www/api/private.key'
	ssh ${HOST} -p ${PORT} 'cd todo_${BUILD_NUMBER} && rm public.key'
	ssh ${HOST} -p ${PORT} 'cd todo_${BUILD_NUMBER} && rm private.key'
	ssh ${HOST} -p ${PORT} 'cd todo_${BUILD_NUMBER} && docker-compose up --build --remove-orphans -d'
	ssh ${HOST} -p ${PORT} 'rm -f todo'
	ssh ${HOST} -p ${PORT} 'ln -sr todo_${BUILD_NUMBER} todo'

rollback:
	ssh ${HOST} -p ${PORT} 'cd todo_${BUILD_NUMBER} && docker-compose pull'
	ssh ${HOST} -p ${PORT} 'cd todo_${BUILD_NUMBER} && docker-compose up --build --remove-orphans -d'
	ssh ${HOST} -p ${PORT} 'rm -f todo'
	ssh ${HOST} -p ${PORT} 'ln -sr todo_${BUILD_NUMBER} todo'
