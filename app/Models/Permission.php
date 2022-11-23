<?php

namespace App\Models;

use Carbon\Carbon;
use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    protected $hidden = array('pivot');

    protected $fillable = [
        'name', 'display_name', 'description'
    ];

    /**
     * Converte o atributo created_at de string para data
     *
     * @return Carbon|null
     */
    public function getCreatedAtAttribute(): ?Carbon
    {
        return Carbon::make($this->attributes['created_at']);
    }

    /**
     * Converte o atributo created_at de string para data
     *
     * @return Carbon|null
     */
    public function getUpdatedAtAttribute(): ?Carbon
    {
        return Carbon::make($this->attributes['updated_at']);
    }
}
