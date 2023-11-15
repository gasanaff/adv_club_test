build:
	docker compose -f docker/docker-compose.yml build
	docker compose -f docker/docker-compose.yml up -d
	docker exec -it adv-test-php-fpm composer install
up:
	docker compose -f docker/docker-compose.yml up

down:
	docker compose -f docker/docker-compose.yml down

test:
	docker exec -it adv-test-php-fpm php bin/phpunit