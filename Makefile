test:
	docker compose -f docker-compose.test.yml run --rm tests

all-test:
	docker compose -f docker-compose.test.yml run --rm tests
	composer analyse
	composer lint:check

