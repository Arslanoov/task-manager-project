start: docker-up docker-build

docker-up:
	docker-compose up -d

docker-build:
	docker-compose up -d