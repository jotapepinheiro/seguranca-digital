<?php

namespace App\Models;

use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class System extends Model
{
    use CreatedUpdatedBy;

    protected $table = 'systems';

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:i:s',
        'updated_at' => 'datetime:d/m/Y H:i:s',
    ];

    protected $fillable = [
        'description', 'initial', 'email', 'url', 'status'
    ];

    public function createdBy(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function updatedBy(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'updated_by');
    }

    public function controles(): HasMany
    {
        return $this->hasMany(Controle::class, 'system_id')->orderBy('id', 'DESC');
    }

    public function controle(): HasOne
    {
        return $this->hasOne(Controle::class, 'system_id')->orderBy('id', 'DESC');
    }

}
