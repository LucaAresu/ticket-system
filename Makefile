args = $(filter-out $@,$(MAKECMDGOALS))

.PHONY: start

DOCKER_COMPOSE=docker-compose --file docker/docker-compose.yaml
PHP_CONTAINER=php_ticket
EXEC_PHP=$(DOCKER_COMPOSE) exec -it $(PHP_CONTAINER)

build:
	$(DOCKER_COMPOSE) build --no-cache
start:
	$(DOCKER_COMPOSE) up -d
stop:
	$(DOCKER_COMPOSE) down --remove-orphans
enter:
	 $(EXEC_PHP) /bin/zsh
