# DOCKER
> Voltar para [instruções do projeto][l-Doc-Projeto]

---
- PHP v7.3.21
- Mysql v8.0.17
- Nginx
- Redis
- Redis Web-UI

### Executar na raiz do projeto
> cp .env.docker .env 

### Gerar app secret

> php artisan key:generate

### Gerar jwt secret

> php artisan jwt:secret

### Iniciar containers, na raiz do projeto

> docker-compose up -d

### Desligar containers, na raiz do projeto

> docker-compose down

### Rebuild Container

> docker-compose build nginx

### Rebuild Todos Container

> docker-compose down && docker-compose up -d --build

### Rebuild Sem Cache de um Container

> docker-compose build --no-cache nginx

### Reload Nginx

> docker exec -it voxus-nginx nginx -s reload

### Listar Containers

> docker ps -a

### Entrar em um Container

> docker-compose exec nginx bash

### Redis Web-UI

> <http://redis:9987>

### EDITAR ARQUIVO HOSTS
> No Windows: C:\Windows\System32\drivers\etc\hosts

> No Linux/Mac: /etc/hosts

```text
127.0.0.1       voxus.local
127.0.0.1       redis
127.0.0.1       mysql
```

### LIMPAR PROJETO LARAVEL SEM ALIAS

> docker-compose exec php-fpm bash

```shell script
composer dump-autoload
php artisan clear-compiled
php artisan optimize
php artisan cache:clear
chmod -R 777 storage bootstrap/cache
```

### Alias de acesso facil via terminal

```shell script
# Iniciar/Parar Docker do Projeto
alias d-up-vox='cd $HOME/Dev/www/testes/voxus && docker-compose up -d'
alias d-down-vox='cd $HOME/Dev/www/testes/voxus && docker-compose down'

# Executar limpeza do projeto laravel dentro do Docker
limpar-voxus() {
  echo -e "\033[1;32m Limpando Voxus... \033[0m"
  docker exec -ti voxus-php sh -c "cd voxus \
  && composer dump-autoload \
  && php artisan clear-compiled \
  && php artisan cache:clear \
  && php artisan optimize \
  && chmod -R 777 storage bootstrap/cache"

  echo -e "\033[1;36m Limpo! \033[0m"
}
```

[l-Doc-Projeto]: ../README.md
