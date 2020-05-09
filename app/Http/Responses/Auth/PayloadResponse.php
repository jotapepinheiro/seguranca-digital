<?php

namespace App\Http\Responses\Auth;

use OpenApi\Annotations\Items;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\Schema;

/**
 * @Schema(title="Auth Payload", description="Response Payload de Usuário")
 *
 * @package App\Http\Responses\Auth
 */
class PayloadResponse
{
    /**
     * @Property(type="array", @Items(ref="#/components/schemas/PayloadProperty"))
     *
     * @var array
     */
    public $payload = [];

    /**
     * @Property(type="array", @Items(ref="#/components/schemas/ExpirePayloadProperty"))
     *
     * @var array
     */
    public $datas = [];
}
