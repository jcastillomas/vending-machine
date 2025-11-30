SHELL=/bin/bash

PLATFORM := $(shell uname -s)

COLOR_NC:=\033[0m
COLOR_WHITE:=\033[1;37m
COLOR_BLACK:=\033[0;30m
COLOR_BLUE:=\033[0;34m
COLOR_LIGHT_BLUE:=\033[1;34m
COLOR_GREEN:=\033[0;32m
COLOR_LIGHT_GREEN:=\033[1;32m
COLOR_CYAN:=\033[0;36m
COLOR_LIGHT_CYAN:=\033[1;36m
COLOR_RED:=\033[0;31m
COLOR_LIGHT_RED:=\033[1;31m
COLOR_PURPLE:=\033[0;35m
COLOR_LIGHT_PURPLE:=\033[1;35m
COLOR_BROWN:=\033[0;33m
COLOR_YELLOW:=\033[1;33m
COLOR_GRAY:=\033[0;30m
COLOR_LIGHT_GRAY:=\033[0;37m

define HEADER

⣿⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⠛⣿⡿⠿⢿⣿⠀⠀⠀⠀⠀⠀
⣿⠀⠀⢠⣤⠀⠀⣴⠀⠀⠀⠀⠀⣿⠀⣶⠀⣿⠀⠀⠀⠀⠀⠀
⣿⠀⠰⠾⠿⠶⠾⠿⠶⠶⠶⠶⠀⣿⣀⣉⣀⣿⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
⣿⠀⠀⠀⠀⠀⠀⠀⢀⣤⡀⠀⠀⣿⣏⣉⣹⣿⠀⠀⠀⠀⠀⠀
⣿⠀⠐⠒⠒⠒⠒⠒⠚⠛⠓⠒⠀⣿⣯⣉⣹⣿⠀⠀|⠀__     __             _ _               __  __            _     _             |
⣿⠀⠀⢠⡀⠀⣾⠀⠀⣶⡆⠀⠀⣿⣿⣿⣿⣿⠀⠀|⠀\ \   / /__ _ __   __| (_)_ __   __ _  |  \/  | __ _  ___| |__ (_)_ __   ___  |
⣿⠀⠘⠛⠛⠛⠛⠛⠛⠛⠛⠛⠀⣿⣿⣿⣿⣿⠀⠀|⠀ \ \ / / _ \ '_ \ / _` | | '_ \ / _` | | |\/| |/ _` |/ __| '_ \| | '_ \ / _ \ |
⣿⠀⠀⣶⣦⠀⣶⣶⠀⠀⠀⠀⠀⣿⣿⣿⣿⣿⠀⠀|⠀  \ V /  __/ | | | (_| | | | | | (_| | | |  | | (_| | (__| | | | | | | |  __/ |
⣿⠀⠈⠉⠉⠉⠉⠉⠉⠉⠉⠉⠀⣿⣿⣿⣿⣿⠀⠀|⠀   \_/ \___|_| |_|\__,_|_|_| |_|\__, | |_|  |_|\__,_|\___|_| |_|_|_| |_|\___| |
⣿⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣿⣏⣉⣹⣿⠀⠀|                                |___/                                          |
⣿⠶⠶⠶⠶⠶⠶⠶⠶⠶⠶⠶⠶⣿⣿⣿⣿⣿⠀⠀⠀⠀⠀⠀
⣿⠀⣤⣤⣤⣤⣤⣤⣤⣤⣤⣤⡄⣿⣿⣿⣿⣿⠀⠀⠀⠀⠀⠀
⣿⣤⣤⣤⣤⣤⣤⣤⣤⣤⣤⣤⣤⣿⣿⣿⣿⣿⠀⠀

endef
export HEADER

.PHONY: help
help:
	@echo -e "$(COLOR_LIGHT_GREEN) $$HEADER $(COLOR_NC)"
	@echo ""
	@echo -e "$(COLOR_GREEN)Make commands:$(COLOR_NC)"
	@echo -e "    $(COLOR_YELLOW)help          	        $(COLOR_PURPLE)-> $(COLOR_CYAN) Show this help menu $(COLOR_NC)"
	@echo -e "    $(COLOR_YELLOW)install          	        $(COLOR_PURPLE)-> $(COLOR_CYAN) Up docker containers, composer install $(COLOR_NC)"
	@echo -e "    $(COLOR_YELLOW)start          	        $(COLOR_PURPLE)-> $(COLOR_CYAN) Docker compose Start $(COLOR_NC)"
	@echo -e "    $(COLOR_YELLOW)stop          	       	$(COLOR_PURPLE)-> $(COLOR_CYAN) Docker compose Stop $(COLOR_NC)"
	@echo -e "    $(COLOR_YELLOW)php          	       	$(COLOR_PURPLE)-> $(COLOR_CYAN) Connect to Docker PHP Container $(COLOR_NC)"
	@echo -e "    $(COLOR_YELLOW)cc				$(COLOR_PURPLE)-> $(COLOR_CYAN) Clear the cache $(COLOR_NC)"
	@echo -e "    $(COLOR_YELLOW)test-unit     	       	$(COLOR_PURPLE)-> $(COLOR_CYAN) Execute PHPUnit tests with coverage mode$(COLOR_NC)"
	@echo -e "    $(COLOR_YELLOW)test-integration      	$(COLOR_PURPLE)-> $(COLOR_CYAN) Execute integration tests $(COLOR_NC)"
	@echo -e "    $(COLOR_YELLOW)test-acceptance       	$(COLOR_PURPLE)-> $(COLOR_CYAN) Execute Behat tests $(COLOR_NC)"
	@echo -e "    $(COLOR_YELLOW)test-this-acceptance  	$(COLOR_PURPLE)-> $(COLOR_CYAN) Execute Behat tests with "this" filter $(COLOR_NC)"
	@echo -e "    $(COLOR_YELLOW)test-all			$(COLOR_PURPLE)-> $(COLOR_CYAN) Execute all tests $(COLOR_NC)"
	@echo -e "    $(COLOR_YELLOW)fix-me			$(COLOR_PURPLE)-> $(COLOR_CYAN) Restore database to reload data of the VM$(COLOR_NC)"
	@echo ""

## install: Creates the environment
.PHONY: install
install:
	docker compose build
	docker compose up -d
	docker compose exec php-fpm composer install --no-interaction
	docker compose exec php-fpm /bin/bash -c "XDEBUG_MODE=off bin/console doctrine:migrations:migrate --no-interaction"

## Starts all containers for this project
.PHONY: start
start:
	docker compose start

## Stop containers for this project
.PHONY: stop
stop:
	docker compose stop

## Connects to Docker PHP Container
.PHONY: php
php:
	docker compose exec php-fpm /bin/bash

# Clear cache
.PHONY: cc
cc:
	@docker compose exec php-fpm /bin/bash -c "php bin/console cache:clear"

## Runs unit tests with coverage mode
.PHONY: test-unit
test-unit:
	@docker compose exec php-fpm /bin/bash -c "XDEBUG_MODE=coverage vendor/bin/phpunit -c tests/Unit/phpunit.xml ${parameters}"

## Runs unit integration tests
.PHONY: test-integration
test-integration: __run-test-integration fix-me
__run-test-integration:
	@docker compose exec php-fpm /bin/bash -c "XDEBUG_MODE=debug XDEBUG_CONFIG='idekey=PHPSTORM' vendor/bin/phpunit -c tests/Integration/phpunit.xml ${parameters}"

## Runs all tests starting with unit then integration
.PHONY: test-all
test-all: test-unit test-integration test-acceptance

## Runs acceptance tests
.PHONY: test-acceptance
test-acceptance: __run-test-acceptance fix-me
__run-test-acceptance:
	@docker compose exec php-fpm /bin/bash -c "XDEBUG_MODE=debug XDEBUG_CONFIG='idekey=PHPSTORM' vendor/bin/behat -c tests/Acceptance/behat.yml --colors ${parameters}"

## Runs acceptance behat tests tagged with this
.PHONY: test-this-acceptance
test-this-acceptance:  __run-test-this-acceptance fix-me
__run-test-this-acceptance:
	@docker compose exec php-fpm /bin/bash -c "XDEBUG_MODE=debug XDEBUG_CONFIG='idekey=PHPSTORM' vendor/bin/behat -c tests/Acceptance/behat.yml --tags '@this' --colors ${parameters}"

## Restore DB, this command will help us to restore the DB data after tests, in order to set-up again the VM
.PHONY: fix-me
fix-me:
	@docker compose exec php-fpm /bin/bash -c "XDEBUG_MODE=off bin/console doctrine:database:drop -f || true"
	@docker compose exec php-fpm /bin/bash -c "XDEBUG_MODE=off bin/console doctrine:database:create"
	@docker compose exec php-fpm /bin/bash -c "XDEBUG_MODE=off bin/console doctrine:migrations:migrate --no-interaction"
