args = $(filter-out $@,$(MAKECMDGOALS))

.PHONY: start

DOCKER_COMPOSE=docker-compose --file docker/docker-compose.yaml
PHP_CONTAINER=php_ticket

build:
	$(DOCKER_COMPOSE) build --no-cache
start:
	$(DOCKER_COMPOSE) up -d
down:
	$(DOCKER_COMPOSE) down --remove-orphans
enter:
	$(DOCKER_COMPOSE) exec -it $(PHP_CONTAINER) bash
