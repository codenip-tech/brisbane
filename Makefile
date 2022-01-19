#!/bin/bash

UID = $(shell id -u)
DOCKER_BE = brisbane-app

help: ## Show this help message
	@echo 'usage: make [target]'
	@echo
	@echo 'targets:'
	@egrep '^(.+)\:\ ##\ (.+)' ${MAKEFILE_LIST} | column -t -c 2 -s ':#'

start: ## Start the containers
	./make.sh start

stop: ## Stop the containers
	./make.sh stop

restart: ## Restart the containers
	$(MAKE) stop && $(MAKE) start

build: ## Rebuilds all the containers
	./make.sh build

prepare: ## Runs backend commands
	$(MAKE) composer-install

run: ## starts the Symfony development server
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} symfony serve -d

logs: ## Show Symfony logs in real time
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} symfony server:log

# Backend commands
composer-install: ## Installs composer dependencies
	U_ID=${UID} docker exec --user ${UID} ${DOCKER_BE} composer install --no-interaction
# End backend commands

ssh-be: ## bash into the be container
	./make.sh ssh-be

code-style: ## Runs php-cs to fix code styling following Symfony rules
	U_ID=${UID} docker exec --user ${UID} ${DOCKER_BE} php-cs-fixer fix src --rules=@Symfony

.PHONY: tests
tests: ## Run tests
	U_ID=${UID} docker exec --user ${UID} ${DOCKER_BE} vendor/bin/simple-phpunit -c phpunit.xml.dist