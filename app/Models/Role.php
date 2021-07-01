<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    protected $hidden = array('pivot');

    protected $fillable = [
        'name', 'display_name', 'description'
    ];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }
}
