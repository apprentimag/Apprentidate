.DEFAULT_GOAL := help

USER = $(shell id -u):$(shell id -g)

.PHONY: up
up: ## Start a development server (use Docker)
	docker-compose -f docker/docker-compose.yml up

.PHONY: down
down: ## Stop and clean Docker server
	docker-compose -f docker/docker-compose.yml down

.PHONY: help
help:
	@grep -h -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'
