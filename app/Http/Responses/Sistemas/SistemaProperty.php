<?php

namespace App\Http\Responses\Sistemas;

use OpenApi\Annotations\Items;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\Schema;

/**
 * @Schema(title="Sistema", description="Sistema")
 *
 * @package App\Http\Responses\Sistemas
 */
class SistemaProperty
{
    /**
     * @Property(type="integer", description="ID")
     *
     * @var int
     */
    public $id = 0;

    /**
     * @Property(type="string", description= "descricao")
     *
     * @var string
     */
    public $descricao;

    /**
     * @Property(type="string", description= "sigla")
     *
     * @var string
     */
    public $sigla;

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
