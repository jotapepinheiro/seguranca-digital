<?php

namespace App\Http\Responses\Roles;

use OpenApi\Annotations\Items;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\Schema;

/**
 * @Schema(title="Roles Paginate", description="Lista de Perfis com Paginação")
 *
 * @package App\Http\Responses\Roles
 */
class RolesPaginateResponse
{
    /**
     * @Property(type="integer", description="current_page")
     *
     * @var int
     */
    public $current_page = 0;

    /**
     * @Property(type="string", description= "first_page_url")
     *
     * @var string
     */
    public $first_page_url;

    /**
     * @Property(type="integer", description="from")
     *
     * @var int
     */
    public $from = 0;

    /**
     * @Property(type="integer", description= "last_page")
     *
     * @var int
     */
    public $last_page = 0;

    /**
     * @Property(type="string", description= "last_page_url")
     *
     * @var string
     */
    public $last_page_url;

    /**
     * @Property(type="string", description="next_page_url")
     *
     * @var string
     */
    public $next_page_url;

    /**
     * @Property(type="string", description="path")
     *
     * @var string
     */
    public $path;

    /**
     * @Property(type="integer", description= "per_page")
     *
     * @var int
     */
    public $per_page = 0;

    /**
     * @Property(type="string", description="prev_page_url")
     *
     * @var string
     */
    public $prev_page_url;

    /**
     * @Property(type="integer", description= "to")
     *
     * @var int
     */
    public $to = 0;

    /**
     * @Property(type="integer", description= "total")
     *
     * @var int
     */
    public $total = 0;

    /**
     * @Property(type="array", @Items(ref="#/components/schemas/RoleProperty"))
     *
     * @var array
     */
    public $data = [];
}
