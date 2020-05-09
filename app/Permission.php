<?php

namespace App;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    protected $hidden = array('pivot');

    protected $fillable = [
        'name', 'display_name', 'description'
    ];
}
