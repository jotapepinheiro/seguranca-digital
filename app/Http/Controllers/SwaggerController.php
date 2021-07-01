<?php

namespace App\Http\Controllers;


use OpenApi\Annotations\Tag;
use OpenApi\Annotations\Info;
use OpenApi\Annotations\Server;
use OpenApi\Annotations\Schema;
use OpenApi\Annotations\Contact;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\SecurityScheme;
use OpenApi\Annotations\ExternalDocumentation;

/**
 *
 * @Info(
 *     version="1.0.0",
 *     title="API - Segurança Digital",
 *     description="Documentação do Projeto Sistema de Segurança Digital",
 *     @Contact(
 *         email="meu@email.com",
 *         name="João Paulo Pinheiro"
 *     )
 * )
 * @Server(url="http://segurancadigital.local/api/v1", description="Ambiente de Desenvolvimento")
 * @Server(url="http://segurancadigital.prod/api/v1", description="Ambiente de Produção")
 *
 * @Tag(
 *     name="Login",
 *     description="Atenticar com JWT",
 *     @ExternalDocumentation(
 *        description="JWT",
 *        url="https://github.com/tymondesigns/jwt-auth"
 *     )
 * )
 *
 * @Tag(name="Auth", description="Meus Dados")
 * @Tag(name="Users", description="Manter Usuários")
 * @Tag(name="Roles", description="Manter Perfis de Usuários")
 * @Tag(name="Permissions", description="Manter Permissões de Usuários")
 * @Tag(name="Systems", description="Manter Sistemas")
 *
 * @Schema(
 *     schema="ApiResponse",
 *     type="object",
 *     description= "Entidade de resposta, resultado da resposta usa essa estrutura uniformemente",
 *     title="Retorno padrão",
 *     @Property(property="success", type="boolean", description="Status da resposta"),
 *     @Property(property="code", type="integer", description="Código da resposta"),
 *     @Property(property="message", type="string", description="Mensagem da resposta")
 * )
 *
 * @SecurityScheme(
 *     securityScheme="apiAuth",
 *     type="http",
 *     scheme="bearer",
 *     name="JWT bearer",
 *     description= "Informe o token JWT para endpoits seguros",
 *     bearerFormat="JWT",
 *     in="header"
 * )
 *
 * @package App\Http\Controllers
 */

class SwaggerController
{
    //
}
