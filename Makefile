default: help

run_docker=docker-compose run --rm app

help: ## Show this help
	@echo "Targets:"
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/\(.*\):.*##[ \t]*/    \1 ## /' | sort | column -t -s '##'
	@echo

build: ##Build the docker image
	docker-compose build --no-cache

install: ##Install the project dependencies with composer
	$(run_docker) composer install --no-interaction

update: ##Update the project dependencies with composer
	$(run_docker) composer update --no-interaction

du: ##Regenerate composer autoloader
	$(run_docker) composer dump-autoload

up: ##Starts docker-compose
	docker-compose up --build

upd: ##Starts docker-compose in daemon mode
	docker-compose up -d --build

stop: ##Stops docker-compose
	docker-compose stop

down: ##Destroys service containers
	docker-compose down

sh: ##Starts a bash shell in service container
	$(run_docker) bash

logs: ##Shows logs of service
	docker-compose logs app

logst: ##Tails logs of service
	docker-compose logs -f app

test: ##Run tests (without xdebug)
	$(run_docker) bash -c "rm /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && ./vendor/bin/phpunit"

cs-check: ##Run PHP Code Sniffer (without xdebug)
	$(run_docker) bash -c "rm /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && ./vendor/bin/phpcs --colors"
