<?php

namespace App\Http\Responses\Auth;

use OpenApi\Annotations\Property;
use OpenApi\Annotations\Schema;

/**
 *
 * @Schema(title="Auth Login", description="Response JWT")
 *
 * @package App\Http\Responses\Auth
 */
class LoginProperty
{
    /**
     * @Property(type="string", description="access_token")
     *
     * @var string
     */
    public $access_token;

    /**
     * @Property(type="string", description="token_type")
     *
     * @var string
     */
    public $token_type;

    /**
     * @Property(type="integer", description="expires_in_minutes")
     *
     * @var integer
     */
    public $expires_in_minutes;

    /**
     * @Property(type="string", description="expires_in_date")
     *
     * @var string
     */
    public $expires_in_date;
}
