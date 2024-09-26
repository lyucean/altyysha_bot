# Переменные
ENV_FILE := ./bot/.env
ENVIRONMENT := $(shell grep ENVIRONMENT $(ENV_FILE) | cut -d '=' -f2)

ifeq ($(ENVIRONMENT),development)
    SERVICE := altyysha-local
else
    SERVICE := altyysha
endif

# Цели
.PHONY: help up down restart build logs ps clean update bot

# Показывать справку по умолчанию
.DEFAULT_GOAL := help

help: ## Показать эту справку
	@echo "Доступные команды:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

up: ## Запустить контейнер
	docker compose up -d --remove-orphans $(SERVICE)

down: ## Остановить и удалить контейнер
	docker compose down --remove-orphans

restart: down up ## Перезапустить контейнер

build: ## Пересобрать образ
	docker compose build --no-cache $(SERVICE)

logs: ## Показать логи контейнера
	docker compose logs -f $(SERVICE)

ps: ## Показать статус контейнера
	docker compose ps

clean: down ## Остановить контейнер и удалить все Docker ресурсы
	docker system prune -af --volumes

update: down ## Обновить и перезапустить контейнер
	git pull
	docker compose pull $(SERVICE)
	docker compose up -d --build --remove-orphans $(SERVICE)

bot: ## Запустить PHP-скрипт бота в консоли контейнера
	docker compose exec $(SERVICE) php /var/www/html/bot_script.php