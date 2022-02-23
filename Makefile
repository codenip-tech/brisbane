#!/bin/bash

help: ## Show this help message
	@echo 'usage: make [target]'
	@echo
	@echo 'targets:'
	@egrep '^(.+)\:\ ##\ (.+)' ${MAKEFILE_LIST} | column -t -c 2 -s ':#'

ssh-be: ## bash into the be container
	./make.sh ssh-be ${DOCKER_BE}

start: ## Start the containers
	./make.sh start ${DOCKER_BE}

stop: ## Stop the containers
	./make.sh stop ${DOCKER_BE}

restart: ## Restart the containers
	$(MAKE) stop && $(MAKE) start

build: ## Rebuilds all the containers
	./make.sh build ${DOCKER_BE}

code-style: ## Runs php-cs to fix code styling following Symfony rules
	./make.sh code-style ${DOCKER_BE}

code-style-check: ## Runs php-cs with dry run option to check if everything is ok
	./make.sh code-style-check ${DOCKER_BE}

run: ## starts the Symfony development server
	./make.sh run ${DOCKER_BE}

logs: ## Show Symfony logs in real time
	./make.sh logs ${DOCKER_BE}

# Backend commands
composer-install: ## Installs composer dependencies
	./make.sh composer-install ${DOCKER_BE}
# End backend commands

prepare: ## Runs backend commands
	$(MAKE) composer-install

generate-private-key:
	./make.sh generate-private-key

.PHONY: run tests
tests: ## Run tests
	./make.sh tests ${DOCKER_BE}
