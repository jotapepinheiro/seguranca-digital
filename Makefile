.PHONY: help
.DEFAULT_GOAL = help

CONTAINER = segurancadigital

## —— Docker 🐳  ———————————————————————————————————————————————————————————————
start: ## Iniciar Docker
	docker compose up -d

clean: ## Desligar Docker
	docker compose down

php-shell: ## Acessar container do php
	docker compose exec php-fpm bash;

nginx-shell: ## Acessar container do php
	docker compose exec nginx bash;

rebuild-all: ## Rebuild em todos os containers
	docker compose down && docker compose up -d --build;

rebuild-nginx: ## Rebuild no nginx
	docker compose build --no-cache nginx;

rebuild-php: ## Rebuild no PHP
	docker compose build --no-cache php-fpm;

reload: ## Reload no nginx
	docker exec -ti $(CONTAINER)-nginx nginx -s reload

## —— Lumen 🎶 ———————————————————————————————————————————————————————————————
install: ## Instalar composer
	composer install --ignore-platform-reqs

swagger: ## Gerar documentação do Swagger
	php artisan swagger-lume:generate

migrate: ## Executar Migrate
	docker exec -ti $(CONTAINER)-php sh -c "cd $(CONTAINER) \
	&& php artisan migrate"

seed: ## Executar os Seeds
	docker exec -ti $(CONTAINER)-php sh -c "cd $(CONTAINER) \
	&& php artisan db:seed"

migrate-fresh: ## Executar Migrate Refresh
	docker exec -ti $(CONTAINER)-php sh -c "cd $(CONTAINER) \
	&& php artisan migrate:fresh --seed"

dump-autoload: ## Limpar Lumen
	docker exec -ti $(CONTAINER)-php sh -c "cd $(CONTAINER) \
	&& composer dump-autoload \
	&& php artisan clear-compiled \
	&& php artisan cache:clear \
	&& chmod -R 777 storage/*"

composer-validate: ## Validar dependencias do composer
	composer validate

composer-show: ## Exibir pacotes do composer
	composer show -l --direct --outdated

test: ## Teste PhpUnit
	 php vendor/bin/phpunit

phpstan: ## Analisar código PHP usando PHPSTAN (https://phpstan.org/)
	php -d memory_limit=-1 ./vendor/bin/phpstan analyse --level=0 app public

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
