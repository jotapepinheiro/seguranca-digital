<?php

namespace App\Http\Responses\Systems;

use OpenApi\Annotations\Items;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\Schema;

/**
 * @Schema(title="Systems", description="Sistema")
 *
 * @package App\Http\Responses\Systems
 */
class SystemProperty
{
    /**
     * @Property(type="integer", description="ID")
     *
     * @var int
     */
    public $id = 0;

    /**
     * @Property(type="string", description= "description")
     *
     * @var string
     */
    public $description;

    /**
     * @Property(type="string", description= "initial")
     *
     * @var string
     */
    public $initial;

    /**
     * @Property(type="string", description= "email")
     *
     * @var string
     */
    public $email;

    /**
     * @Property(type="string", description="url")
     *
     * @var string
     */
    public $url;

    /**
     * @Property(type="string", description="status")
     *
     * @var string
     */
    public $status;

    /**
     * @Property(type="integer", description="created_by")
     *
     * @var int
     */
    public $created_by;

    /**
     * @Property(type="integer", description="updated_by")
     *
     * @var int
     */
    public $updated_by;

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
}
