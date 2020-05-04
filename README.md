# Projeto Sistema de Segurança Digital - Versão 1.0 

[![SQUADRA Tecnologia}](doc/img/logo.svg)](https://www.squadra.com.br) 

## O que este repositório contém?
1. Controle de permissão de usuários ACL.
2. Autenticação com JWT.
3. QueryBuilder Eloquent ORM.

## Qual o objetivo deste repositório?
1. Processo seletivo para a empresa [SQUADRA Tecnologia](https://www.squadra.com.br).
2. Criar um cadastro de "sistemas" e controle de justificativa de alterações.
3. Executar consultas avançadas em uma query.

--- 
## O que é necessário para configurar?
1. PHP >= 7.2 com requisitos de extensão, conforme descrito na documentação do [Lumen](https://lumen.laravel.com/docs/6.x#server-requirements).
3. [Composer](https://getcomposer.org/) em uma Versão estável.
4. Qualquer banco de dados de sua escolha, eu usei o MySQL.

---
## Como instalar?
```shell script
# Instalar todos os pacotes necessários para executar o aplicativo
> composer install;

# Criar o arquivo .env e defina o seu APP_TIMEZONE e banco de dados.
> cp -fR .env.example .env;

# Gerar app secret
> php artisan key:generate;

# Gerar jwt secret
> php artisan jwt:secret;

# Criar as tabelas necessárias no seu banco de dados
# Nota: Lembre-se de criar o banco de dados antes de executar este comando!
> php artisan migrate;

# Alimentar nosso banco de dados com dados necessários
> php artisan db:seed

# Recriar os dados de nosso banco de dados
> php artisan migrate:fresh --seed
```
---
## Importar Endpoits da API para o Insomia
[![Run in Insomnia}](https://insomnia.rest/images/run.svg)](https://insomnia.rest/run/?label=Squadra%20API&uri=https%3A%2F%2Fraw.githubusercontent.com%2Fjotapepinheiro%2Fseguranca-digital%2Fmaster%2Fdoc%2Farquivos%2FInsomnia_export.json)

![](doc/img/insomia.gif)

---
## Como executar o projeto?
```shell script
# Você pode executá-lo no host local ou pode ter a configuração do host virtual
# O servidor fica a sua escolha entre nginx ou apache
# Particularmente prefiro nginx com host de domínio local. 
# Exemplo: http://segurancadigital.test
# Nota: Informe a URL no projeto importado do Insomia para testar os endpoits. 
> php artisan serve
```

## Como você pode ver as rotas da API?
```shell script
# Lista todas as rotas definidas no projeto 
> php artisan route:list
```

## Consultar sistemas cadastrados
```
# Importante: O final da url deve conter os parâmetros api/v1. 
# Exemplo: http://segurancadigital.test/api/v1

# Exibir todos.
> /api/v1/sistemas

# Exibir todos, com a última justicativa.
> /api/v1/sistemas?include=controle

# Exibir todos, com a última justicativa e usuário técnico.
> /api/v1/sistemas?include=controle,controle.user

# Exibir todos, com todas as justicativas.
> /api/v1/sistemas?include=controles

# Exibir todos, com todas as justicativas e usuários técnicos.
> /api/v1/sistemas?include=controles,controles.user

# Filtrar por descrição, com a última justicativa e usuário técnico
> /api/v1/sistemas?include=controle,controle.user&filter=[descricao]=Rodrigues

# Filtrar por descrição e sigla, com a última justicativa
> /api/v1/sistemas?include=controle&filter[descricao]=Rodrigues&filter[sigla]=QUIDEM

# Filtrar por e-mail de usuário técnico
> /api/v1/sistemas?filter[email]=pedro0example.org

# Filtrar por status
> /api/v1/sistemas?filter[status]=cancelado

# Filtrar por nome do usuário todos os sistemas onde ele fez a qualquer alteração
> /api/v1/sistemas?include=controles&filter[controles.user.name]=Pedro

# Filtrar por e-mail do usuário todos os sistemas onde ele fez a qualquer alteração
> /api/v1/sistemas?include=controles&filter[controle.user.email]=pedro0example.org

# Filtrar por nome do usuário que criou o sistema
> /api/v1/sistemas?include=createdBy&filter[createdBy.name]=Pedro

# Filtrar por nome do usuário que alterou o sistema
> /api/v1/sistemas?include=updatedBy&filter[updatedBy.name]=Pedro
```

## Listar sistema por ID
```
> /api/v1/sistemas/20
```

## Listar histórico de lterações de um sistema por ID
```
> /api/v1/sistemas/historico/18
```

## Usuários do sistema
```
# Listar todos usuários cadastrados
> /api/v1/users

# Exibir dados do usuário, perfis e permissões por ID
> /api/v1/users/2

1. Perfil - Super Administrador
> Login: super@super.com 
> Senha: super

2. Perfil - Administrador
> Login: admin@admin.com
> Senha: admin

3. Perfil - Técnico
> Login: tecnico@tecnico.com
> Senha: tecnico

4. 50 usuários ficticios com o perfil técnico 
> Senha Padrão: 123456

```

## Login
```
> /api/v1/auth/login
```
Se o parâmetro "remember" for enviado como "true", o token JWT irá expirar em 1 semana, caso contrário 1 hora.
Este tempo pode se definido no arquivo .env
JWT_TTL e JWT_TTL_REMEMBER_ME
```json
{
	"email": "super@super.com",
	"password": "super",
	"remember": true
}

```

