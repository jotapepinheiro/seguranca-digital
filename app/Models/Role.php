<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    use HasFactory;

    protected $hidden = array('pivot');

    protected $fillable = [
        'name', 'display_name', 'description'
    ];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

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
