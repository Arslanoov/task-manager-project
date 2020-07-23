start: docker-build docker-up migrate

docker-build:
	docker-compose build

docker-up:
	docker-compose up -d

migrate:
	docker-compose run --rm api-php-cli php bin/console cycle:run
