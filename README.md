# Projeto Sistema de Segurança Digital - Versão 1.0 

![Squadra](doc/img/logo.png)

---
## [Caso de Uso](doc/arquivos/UC_001_Manter_Sistema.doc)

## Banco de Dados
Criar a tabela, usuário e definir as permissoes

```mysql
CREATE DATABASE seguranca_digital;
CREATE USER 'Squadra'@'%' IDENTIFIED BY 'Squadra';
GRANT ALL PRIVILEGES ON seguranca_digital.* TO 'Squadra'@'%';
```

## Contributing
```shell script
composer server
```

## Migratios e Seeders
```shell script
php artisan make:migration create_users_table --create=users
php artisan make:seeder UsersTableSeeder
php artisan make:model User

php artisan make:migration create_sistemas_table --create=sistemas
php artisan make:seeder SistemasTableSeeder
php artisan make:model Sistema

php artisan make:migration create_controles_table --create=controles
php artisan make:seeder ControlesTableSeeder
php artisan make:model Controle


php artisan make:model Role
php artisan make:model Permission 

php artisan migrate
php artisan db:seed

php artisan migrate:fresh --seed

composer dump-autoload
```
