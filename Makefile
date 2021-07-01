.PHONY: help
.DEFAULT_GOAL = help

CONTAINER = segurancadigital

## —— Docker 🐳  ———————————————————————————————————————————————————————————————
start: ## Iniciar Docker-Sync
	docker-sync-stack start;

clean: ## Desligar Docker-Sync
	docker-sync-stack clean;

dshell: ## Acessar container do php
	docker-compose exec php-fpm bash;

rebuild-all: ## Rebuild em todos os containers
	docker-compose down && docker-compose up -d --build;

rebuild-nginx: ## Rebuild no nginx
	docker-compose build --no-cache nginx;

rebuild-php: ## Rebuild no PHP
	docker-compose build --no-cache php-fpm;

reload: ## Reload no nginx
	docker exec nginx nginx -s reload

## —— Lumen 🎶 ———————————————————————————————————————————————————————————————
install: ## Instalar composer
	composer install --ignore-platform-reqs

swagger: ## Gerar documentação do Swagger
	php artisan swagger-lume:generate

migrate: ## Executar Migrate
	docker exec -ti $(CONTAINER)-php sh -c "cd $(CONTAINER) \
	&& php artisan migrate"

migrate-fresh: ## Executar Migrate Refresh
	docker exec -ti $(CONTAINER)-php sh -c "cd $(CONTAINER) \
	&& php artisan migrate:fresh --seed"

dump-autoload: ## Limpar Lumen
	docker exec -ti $(CONTAINER)-php sh -c "cd $(CONTAINER) \
	&& composer dump-autoload \
	&& php artisan clear-compiled \
	&& php artisan cache:clear \
	&& chmod -R 777 storage bootstrap/cache"

composer-validate: ## Validar dependencias do composer
	composer validate

composer-show: ## Exibir pacotes do composer
	composer show -l --direct --outdated

phpstan: ## Analisar código PHP usando PHPSTAN (https://phpstan.org/)
	./vendor/bin/phpstan analyse

phpmd: ## Analisar código PHP usando PHPMD (https://phpmd.org/)
	./vendor/bin/phpmd src text cleancode,codesize,controversial,design,naming,unusedcode

phpcs: ## Executa PhpCs
	./vendor/bin/phpcs

phpcs-fixer: ## Executa PhpCs Fixer
	./vendor/bin/phpcbf

## —— Outros 🛠️️ ———————————————————————————————————————————————————————————————
help: ## Lista de commandos
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) \
	| awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-24s\033[0m %s\n", $$1, $$2}' \
	| sed -e 's/\[32m## /[33m/' && printf "\n"
