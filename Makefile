# Выполним по умолчанию, при запуске пустого make
.DEFAULT_GOAL := help

# Подключим файл конфигурации
include app/.env

# И укажем его для docker compose
ENV = --env-file app/.env

# Дата время
BACKUP_DATETIME := $(shell date '+%Y-%m-%d')

# Добавим красоты и чтоб наши команды было видно в теле скрипта
PURPLE = \033[1;35m $(shell date +"%H:%M:%S") --
RESET = --\033[0m

# Считываем файл, всё что содержит двойную решётку # Это описание к командам
help:
	@grep -E '^[a-zA-Z-]+:.*?## .*$$' Makefile | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-17s\033[0m %s\n", $$1, $$2}'
.PHONY: help

# Если это developer окружение, то подключим debug профиль
PROFILE =
ifeq ($(ENVIRONMENT),development)
	PROFILE := --profile dev
else
	PROFILE := --profile prod
endif

update: ## Пересобрать контейнер, обновить композер и миграции
update: docker-down docker-pull docker-build docker-up

restart: ## Restart docker containers
restart: docker-down docker-up

docker-up: ## Поднимем все контейнеры
	@echo "$(PURPLE) Поднимем все контейнеры $(RESET)"
	docker compose $(ENV) $(PROFILE) up -d

docker-build:
	@echo "$(PURPLE) Соберём образы $(RESET)"
	docker compose $(ENV) $(PROFILE) build

docker-pull:
	@echo "$(PURPLE) Поучим все контейнеры $(RESET)"
	docker compose $(ENV) $(PROFILE) pull --include-deps

docker-down: ## Остановим контейнеры
	@echo "$(PURPLE) Остановим контейнеры $(RESET)"
	docker compose $(ENV) $(PROFILE) down --remove-orphans

# Команды для работы с дампами на продакшене ----------------------------------------------------------------------------
backup-db:  ## Снимем дамп с БД
	@echo "$(PURPLE) Снимем дамп с БД $(RESET)"
	@docker compose $(ENV) exec mysql sh -c 'exec mysqldump -u root -p"${MYSQL_ROOT_PASSWORD}" "${WORDPRESS_DB_NAME}"' > "${BACKUPS_FOLDER}/$(BACKUP_DATETIME)_LS.sql"

backup-file:  ## Снимем дамп файлов с папки wordpress
	@echo "$(PURPLE) Создадим архив файлов $(RESET)"
	tar --warning=no-file-changed -czf ${BACKUPS_FOLDER}/${BACKUP_DATETIME}_LS.file.gz \
		--exclude='app/wordpress/wp-content/cache' \
		--exclude='app/wordpress/wp-content/backup' \
		--exclude='app/wordpress/wp-content/upgrade-temp-backup' \
		--exclude='app/wordpress/wp-content/upgrade/' \
		--exclude='app/wordpress/wp-content/plugins/*/cache/' \
		--exclude='app/wordpress/wp-content/backups' -C ./app/wordpress .

import-backup:  ## Импорт БД из сегодняшнего дампа (удобно восстанавливать, если что-то сломал в настройках)
	@echo "$(PURPLE) Импорт БД из дампа $(RESET)"
	@if [ -f "${BACKUPS_FOLDER}/$(BACKUP_DATETIME)_LS.sql" ]; then \
		docker compose $(ENV) exec -T mysql sh -c 'exec mysql -u root -p"$(MYSQL_ROOT_PASSWORD)" "$(WORDPRESS_DB_NAME)"' < "${BACKUPS_FOLDER}/$(BACKUP_DATETIME)_LS.sql"; \
	else \
		echo "Дампа за сегодня нет!"; \
	fi

# Команды для инициализации проекта на локальной машине -----------------------------------------------------------------
init: ## Инициализация проекта для локальной разработки
ifeq ($(ENVIRONMENT), development)
	@echo "Запуск команды init в окружении $(ENVIRONMENT)"
	@$(MAKE) docker-down
	@$(MAKE) docker-pull
	@$(MAKE) docker-build
	@$(MAKE) docker-up-mysql
	@$(MAKE) update-backup
	@$(MAKE) update-dump
	@$(MAKE) update-urls
	@$(MAKE) update
	@$(MAKE) open-url
else
	@echo "Команда init может быть запущена только в окружении development. Текущее окружение: $(ENVIRONMENT)"
endif

fresh-backup:
	@echo "$(PURPLE) Запуск команды 'make backup-db и backup-file' на удаленном сервере $(RESET)"
	sshpass -p"${SSH_PASSWORD}" ssh ${SSH_USER}@${SSH_HOST} "cd ${SSH_FOLDER} && make backup-db && make backup-file"

fetch-backup: fresh-backup docker-up-mysql # Скачаем дамп с удаленного сервера на локальную машинуЗапускаем mysql заранее, чтоб успел развернутся
	@mkdir -p backup
	@echo "$(PURPLE) Скачиваем дамп с удаленного сервера $(RESET)"
	sshpass -p"${SSH_PASSWORD}" scp ${SSH_USER}@${SSH_HOST}:${BACKUPS_FOLDER}/$(BACKUP_DATETIME)_LS.sql ./backup
	@echo "$(PURPLE) Скачиваем архив с удаленного сервера $(RESET)"
	sshpass -p"${SSH_PASSWORD}" scp ${SSH_USER}@${SSH_HOST}:${BACKUPS_FOLDER}/${BACKUP_DATETIME}_LS.file.gz ./backup

update-backup: fetch-backup # Распакуем архив на локальной машине
	@echo "$(PURPLE) Удаляем все лишние файлы в папке перед распаковкой $(RESET)"
	rm -rf ./app/wordpress/*
	@echo "$(PURPLE) Распаковываем архив на локальной машине $(RESET)"
	tar -xzf ./backup/${BACKUP_DATETIME}_LS.file.gz -C ./app/wordpress

docker-up-mysql: ## Поднимем базу данных для разработки
	@echo "$(PURPLE) Поднимем базу данных $(RESET)"
	docker compose $(ENV) $(PROFILE) up -d mysql_dev

update-dump:  ## Импорт БД из дампа
	@echo "$(PURPLE) Импорт БД из дампа $(RESET)"
	@docker compose $(ENV) exec -T mysql_dev sh -c 'exec mysql -u root -p"$(MYSQL_ROOT_PASSWORD)" "$(WORDPRESS_DB_NAME)"' < ./backup/$(BACKUP_DATETIME)_LS.sql;

update-urls:
	@echo "$(PURPLE) Обновление URL-адресов WordPress для окружения $(ENVIRONMENT)... $(RESET)"
	@docker compose $(ENV) exec -T mysql_dev sh -c "mysql -u root -p'$(MYSQL_ROOT_PASSWORD)' $(WORDPRESS_DB_NAME) -e \"UPDATE wp_options SET option_value = '$(LOCAL_URL)' WHERE option_name = 'siteurl';\""
	@docker compose $(ENV) exec -T mysql_dev sh -c "mysql -u root -p'$(MYSQL_ROOT_PASSWORD)' $(WORDPRESS_DB_NAME) -e \"UPDATE wp_options SET option_value = '$(LOCAL_URL)' WHERE option_name = 'home';\""

# Цель для открытия URL в браузере
open-url:
	@echo "Открытие URL $(LOCAL_URL)/ в браузере..."
ifeq ($(shell uname), Darwin)
	@open $(LOCAL_URL)/
else
ifeq ($(shell uname), Linux)
	@xdg-open $(LOCAL_URL)/
endif
endif