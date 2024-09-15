# Переменные
COMPOSE_FILE := docker-compose.yml
DOCKER_COMPOSE := docker compose -f $(COMPOSE_FILE)
ENV_FILE := .env
ENVIRONMENT := $(shell grep ENVIRONMENT $(ENV_FILE) | cut -d '=' -f2)

ifeq ($(ENVIRONMENT),development)
    SERVICE := altyysha-local
else
    SERVICE := altyysha
endif

# Цели
.PHONY: help up down restart build logs ps clean update

# Показывать справку по умолчанию
.DEFAULT_GOAL := help

help: ## Показать эту справку
	@echo "Доступные команды:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

up: ## Запустить контейнер
	$(DOCKER_COMPOSE) up -d --remove-orphans $(SERVICE)

down: ## Остановить и удалить контейнер
	$(DOCKER_COMPOSE) down --remove-orphans

restart: down up ## Перезапустить контейнер

build: ## Пересобрать образ
	$(DOCKER_COMPOSE) build --no-cache $(SERVICE)

logs: ## Показать логи контейнера
	$(DOCKER_COMPOSE) logs -f $(SERVICE)

ps: ## Показать статус контейнера
	$(DOCKER_COMPOSE) ps

clean: down ## Остановить контейнер и удалить все Docker ресурсы
	docker system prune -af --volumes

update: down ## Обновить и перезапустить контейнер
	git pull
	$(DOCKER_COMPOSE) pull $(SERVICE)
	$(DOCKER_COMPOSE) up -d --build --remove-orphans $(SERVICE)
