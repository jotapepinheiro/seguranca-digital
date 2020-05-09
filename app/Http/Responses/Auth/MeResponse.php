<?php

namespace App\Http\Responses\Auth;

use OpenApi\Annotations\Items;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\Schema;

/**
 * @Schema(title="Auth Me", description="Retorn dados do usuário")
 *
 * @package App\Http\Responses\Auth
 */
class MeResponse
{
    /**
     * @Property(type="integer", description="ID")
     *
     * @var int
     */
    public $id = 0;

    /**
     * @Property(type="string", description= "name")
     *
     * @var string
     */
    public $name;

    /**
     * @Property(type="string", description= "email")
     *
     * @var string
     */
    public $email;

    /**
     * @Property(type="string", description= "email_verified_at")
     *
     * @var string
     */
    public $email_verified_at;

    /**
     * @Property(type="string", description="created_at")
     *
     * @var string
     */
    public $created_at;

    /**
     * @Property(type="string", description="updated_at")
     *
     * @var string
     */
    public $updated_at;

    /**
     * @Property(type="array", @Items(ref="#/components/schemas/RoleWithPermissionsProperty"))
     *
     * @var array
     */
    public $rules = [];
}
