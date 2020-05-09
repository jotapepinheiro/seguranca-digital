<?php

namespace App\Http\Responses\Auth;

use OpenApi\Annotations\Property;
use OpenApi\Annotations\Schema;

/**
 *
 * @Schema(
 *     title="Auth Payload Expire",
 *     description="Datas Payload de Usuário"
 * )
 *
 * @package App\Http\Responses\Auth
 */
class ExpirePayloadProperty
{
    /**
     * @Property(type="string",description="emitido_em")
     *
     * @var string
     */
    public $emitido_em;

    /**
     * @Property(type="string", description="expira_em")
     *
     * @var string
     */
    public $expira_em;

    /**
     * @Property(type="string",description="nao_antes_de")
     *
     * @var string
     */
    public $nao_antes_de;

}
