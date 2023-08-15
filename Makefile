args=$(filter-out $@,$(MAKECMDGOALS))

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


# Use them inside the container
psalm:
	vendor/bin/psalm -c ./tools/psalm/psalm.xml --show-info=true --no-cache

check-cs:
	vendor/bin/php-cs-fixer fix --dry-run --verbose --show-progress=dots --cache-file=tools/php-cs-fixer/.php-cs-fixer.cache --config=tools/php-cs-fixer/config.php
fix-cs:
	vendor/bin/php-cs-fixer fix --verbose --show-progress=dots --cache-file=tools/php-cs-fixer/.php-cs-fixer.cache --config=tools/php-cs-fixer/config.php

test:
	vendor/bin/phpunit --configuration tools/phpunit/phpunit.xml.dist --colors=always --testdox

infection:
	infection --configuration=tools/infection/infection.json5
