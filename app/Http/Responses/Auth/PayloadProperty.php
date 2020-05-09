<?php

namespace App\Http\Responses\Auth;

use OpenApi\Annotations\Items;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\Schema;

/**
 *
 * @Schema(
 *     title="Auth Payload Property",
 *     description="Property Payload de Usuário"
 * )
 *
 * @package App\Http\Responses\Auth
 */
class PayloadProperty
{
    /**
     * @Property(type="string",description="iss")
     *
     * @var string
     */
    public $iss;

    /**
     * @Property(type="integer",description="iat")
     *
     * @var int
     */
    public $iat = 0;

    /**
     * @Property(type="integer",description="exp")
     *
     * @var int
     */
    public $exp = 0;

    /**
     * @Property(type="integer",description="nbf")
     *
     * @var int
     */
    public $nbf = 0;

    /**
     * @Property(type="string", description="jti")
     *
     * @var string
     */
    public $jti;

    /**
     * @Property(type="integer",description="sub")
     *
     * @var int
     */
    public $sub = 0;

    /**
     * @Property(type="string", description="prv")
     *
     * @var string
     */
    public $prv;

    /**
     * @Property(type="string", description="email")
     *
     * @var string
     */
    public $email;

}
