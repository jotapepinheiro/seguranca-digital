<?php

namespace App;

use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Model;

class Sistema extends Model
{
    use CreatedUpdatedBy;

    protected $table = 'sistemas';

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:i:s',
        'updated_at' => 'datetime:d/m/Y H:i:s',
    ];

    protected $fillable = [
        'descricao', 'sigla', 'email', 'url', 'status'
    ];

    public function created_by()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function updated_by()
    {
        return $this->hasOne(User::class, 'id', 'updated_by');
    }

    public function controles()
    {
        return $this->hasMany(Controle::class, 'sistema_id')->orderBy('id', 'DESC');
    }

    public function controle()
    {
        return $this->hasOne(Controle::class, 'sistema_id')->orderBy('id', 'DESC');
    }

}
